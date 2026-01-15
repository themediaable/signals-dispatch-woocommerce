<?php
/**
 * Template mapper service.
 *
 * @package TMASD\Signals\Dispatch\Services
 */

declare(strict_types=1);

namespace TMASD\Signals\Dispatch\Services;

use TMASD\Signals\Dispatch\Contracts\PhoneNormalizerInterface;
use TMASD\Signals\Dispatch\Contracts\TemplateMapperInterface;
use TMASD\Signals\Dispatch\Core\AbstractService;
use WC_Order;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Template mapper service.
 *
 * Maps WooCommerce order data to WhatsApp template message variables.
 * Uses encapsulation to hide mapping logic and validation.
 *
 * @final
 */
final class TemplateMapperService extends AbstractService implements TemplateMapperInterface {

	/**
	 * Phone normalizer service.
	 *
	 * @var PhoneNormalizerInterface
	 */
	private PhoneNormalizerInterface $phone_normalizer;

	/**
	 * Constructor.
	 *
	 * @param PhoneNormalizerInterface $phone_normalizer Phone normalizer service.
	 */
	public function __construct( PhoneNormalizerInterface $phone_normalizer ) {
		$this->phone_normalizer = $phone_normalizer;
	}

	/**
	 * Build template payload from an order and mapping configuration.
	 *
	 * @param int                  $order_id Order ID to build from.
	 * @param array<string, mixed> $mapping  Mapping configuration.
	 * @return array<string, mixed> Template payload array.
	 */
	public function build_from_order( int $order_id, array $mapping ): array {
		$order = $this->get_order( $order_id );

		if ( null === $order ) {
			return $this->build_empty_payload();
		}

		$phone = $this->extract_phone( $order );

		if ( '' === $phone ) {
			return $this->build_empty_payload();
		}

		return $this->build_payload( $order, $mapping, $phone );
	}

	/**
	 * Get WooCommerce order by ID.
	 *
	 * @param int $order_id Order ID.
	 * @return WC_Order|null Order object or null.
	 */
	private function get_order( int $order_id ): ?WC_Order {
		if ( ! function_exists( 'wc_get_order' ) ) {
			return null;
		}

		$order = wc_get_order( $order_id );

		return $order instanceof WC_Order ? $order : null;
	}

	/**
	 * Extract and normalize phone from order.
	 *
	 * @param WC_Order $order Order object.
	 * @return string Normalized phone or empty string.
	 */
	private function extract_phone( WC_Order $order ): string {
		$phone = (string) $order->get_billing_phone();
		return $this->phone_normalizer->normalize( $phone );
	}

	/**
	 * Build empty payload for failed lookups.
	 *
	 * @return array<string, mixed> Empty payload structure.
	 */
	private function build_empty_payload(): array {
		return array(
			'phone_e164'    => '',
			'template_name' => '',
			'language'      => 'en_US',
			'variables'     => array(),
		);
	}

	/**
	 * Build complete payload from order and mapping.
	 *
	 * @param WC_Order             $order   Order object.
	 * @param array<string, mixed> $mapping Mapping configuration.
	 * @param string               $phone   Normalized phone.
	 * @return array<string, mixed> Complete payload.
	 */
	private function build_payload( WC_Order $order, array $mapping, string $phone ): array {
		return array(
			'phone_e164'    => $phone,
			'template_name' => $this->get_mapping_value( $mapping, 'template_name', '' ),
			'language'      => $this->get_mapping_value( $mapping, 'language', 'en_US' ),
			'variables'     => $this->resolve_variables( $order, $mapping ),
		);
	}

	/**
	 * Get value from mapping array safely.
	 *
	 * @param array<string, mixed> $mapping Mapping array.
	 * @param string               $key     Key to retrieve.
	 * @param string               $default Default value.
	 * @return string Retrieved or default value.
	 */
	private function get_mapping_value( array $mapping, string $key, string $default ): string {
		return isset( $mapping[ $key ] ) ? (string) $mapping[ $key ] : $default;
	}

	/**
	 * Resolve template variables from order.
	 *
	 * @param WC_Order             $order   Order object.
	 * @param array<string, mixed> $mapping Mapping configuration.
	 * @return array<int, string> Resolved variable values.
	 */
	private function resolve_variables( WC_Order $order, array $mapping ): array {
		$mapping_json = $this->get_mapping_value( $mapping, 'mapping_json', '[]' );
		$resolvers    = $this->decode_mapping_json( $mapping_json );

		$values = array();
		foreach ( $resolvers as $resolver_key ) {
			$values[] = $this->resolve_single_value( $order, (string) $resolver_key );
		}

		return $values;
	}

	/**
	 * Decode mapping JSON to array.
	 *
	 * @param string $json JSON string.
	 * @return array<int, string> Decoded array.
	 */
	private function decode_mapping_json( string $json ): array {
		$decoded = json_decode( $json, true );
		return is_array( $decoded ) ? $decoded : array();
	}

	/**
	 * Resolve a single variable value from order.
	 *
	 * @param WC_Order $order        Order object.
	 * @param string   $resolver_key Variable resolver key.
	 * @return string Resolved value.
	 */
	private function resolve_single_value( WC_Order $order, string $resolver_key ): string {
		$resolvers = $this->get_resolver_map( $order );
		return isset( $resolvers[ $resolver_key ] ) ? $resolvers[ $resolver_key ] : '';
	}

	/**
	 * Get resolver map for order data.
	 *
	 * @param WC_Order $order Order object.
	 * @return array<string, string> Resolver key to value map.
	 */
	private function get_resolver_map( WC_Order $order ): array {
		return array(
			'order_id'            => (string) $order->get_id(),
			'order_number'        => (string) $order->get_order_number(),
			'order_total'         => (string) $order->get_total(),
			'order_currency'      => (string) $order->get_currency(),
			'billing_first_name'  => (string) $order->get_billing_first_name(),
			'billing_last_name'   => (string) $order->get_billing_last_name(),
			'billing_phone'       => (string) $order->get_billing_phone(),
			'billing_email'       => (string) $order->get_billing_email(),
			'shipping_first_name' => (string) $order->get_shipping_first_name(),
			'shipping_last_name'  => (string) $order->get_shipping_last_name(),
			'status'              => (string) $order->get_status(),
			'site_name'           => (string) get_bloginfo( 'name' ),
		);
	}
}
