<?php
/**
 * Base service interface.
 *
 * @package TMASD\Signals\Dispatch\Contracts
 */

declare(strict_types=1);

namespace TMASD\Signals\Dispatch\Contracts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Interface for all plugin services.
 *
 * Defines the contract that all services must implement
 * to ensure consistent initialization across the plugin.
 */
interface ServiceInterface {

	/**
	 * Boot the service.
	 *
	 * Called during plugin initialization to set up any hooks
	 * or perform service-specific setup tasks.
	 *
	 * @return void
	 */
	public function boot(): void;
}
