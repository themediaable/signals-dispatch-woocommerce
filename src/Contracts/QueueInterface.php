<?php
/**
 * Queue interface.
 *
 * @package TMASD\Signals\Dispatch\Contracts
 */

declare(strict_types=1);

namespace TMASD\Signals\Dispatch\Contracts;

use WC_Order;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Interface for message queue handling.
 *
 * Abstracts the queue system for scheduling and
 * processing template message jobs.
 */
interface QueueInterface {

	/**
	 * Handle WooCommerce order status change.
	 *
	 * @param int      $order_id   Order ID.
	 * @param string   $old_status Previous status.
	 * @param string   $new_status New status.
	 * @param WC_Order $order      Order object.
	 * @return void
	 */
	public function handle_order_status_changed(
		int $order_id,
		string $old_status,
		string $new_status,
		WC_Order $order
	): void;

	/**
	 * Schedule a template message to be sent.
	 *
	 * @param int    $order_id  Order ID.
	 * @param string $event_key Event key.
	 * @param int    $attempts  Retry attempt count.
	 * @return void
	 */
	public function schedule_send( int $order_id, string $event_key, int $attempts = 0 ): void;

	/**
	 * Process the send template message action.
	 *
	 * @param int    $order_id  Order ID.
	 * @param string $event_key Event key.
	 * @param int    $attempts  Retry attempt count.
	 * @return void
	 */
	public function handle_send_template_message( int $order_id, string $event_key, int $attempts = 0 ): void;
}
