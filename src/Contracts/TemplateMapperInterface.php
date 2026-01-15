<?php
/**
 * Template mapper interface.
 *
 * @package TMASD\Signals\Dispatch\Contracts
 */

declare(strict_types=1);

namespace TMASD\Signals\Dispatch\Contracts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Interface for template variable mapping.
 *
 * Abstracts the logic for building template payloads
 * from WooCommerce order data.
 */
interface TemplateMapperInterface {

	/**
	 * Build template payload from an order.
	 *
	 * @param int                  $order_id Order ID.
	 * @param array<string, mixed> $mapping  Mapping configuration.
	 * @return array{phone_e164: string, template_name: string, language: string, variables: array<int, string>}
	 */
	public function build_from_order( int $order_id, array $mapping ): array;
}
