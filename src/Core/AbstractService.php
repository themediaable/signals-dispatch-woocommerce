<?php
/**
 * Abstract base service.
 *
 * @package TMASD\Signals\Dispatch\Core
 */

declare(strict_types=1);

namespace TMASD\Signals\Dispatch\Core;

use TMASD\Signals\Dispatch\Contracts\ServiceInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract base class for services.
 *
 * Provides common functionality for all services including
 * configuration access and WordPress option management.
 *
 * @abstract
 */
abstract class AbstractService implements ServiceInterface {

	/**
	 * Boot the service.
	 *
	 * Override in child classes to register hooks.
	 *
	 * @return void
	 */
	public function boot(): void {
		// Default implementation - override in child classes.
	}

	/**
	 * Get a plugin option value safely.
	 *
	 * @param string $option_name Option name constant.
	 * @param string $default     Default value if not set.
	 * @return string Option value.
	 */
	protected function get_option( string $option_name, string $default = '' ): string {
		$value = get_option( $option_name, $default );

		return is_string( $value ) ? trim( $value ) : $default;
	}

	/**
	 * Update a plugin option.
	 *
	 * @param string $option_name Option name.
	 * @param mixed  $value       Option value.
	 * @return bool True on success.
	 */
	protected function update_option( string $option_name, $value ): bool {
		return update_option( $option_name, $value );
	}

	/**
	 * Check if plugin is fully configured.
	 *
	 * @return bool True if all required options are set.
	 */
	protected function is_configured(): bool {
		return '' !== $this->get_option( \TMASD_OPTION_PHONE_NUMBER_ID )
			&& '' !== $this->get_option( \TMASD_OPTION_WABA_ID )
			&& '' !== $this->get_option( \TMASD_OPTION_ACCESS_TOKEN );
	}
}
