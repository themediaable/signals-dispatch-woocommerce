<?php
/**
 * Phone normalizer interface.
 *
 * @package TMASD\Signals\Dispatch\Contracts
 */

declare(strict_types=1);

namespace TMASD\Signals\Dispatch\Contracts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Interface for phone number normalization services.
 *
 * Provides abstraction for phone number formatting to ensure
 * consistent E.164 format across the plugin.
 */
interface PhoneNormalizerInterface {

	/**
	 * Normalize a phone number to E.164 format.
	 *
	 * @param string $phone Raw phone number input.
	 * @return string Normalized E.164 phone number or empty string on failure.
	 */
	public function normalize( string $phone ): string;

	/**
	 * Validate if a phone number is in valid E.164 format.
	 *
	 * @param string $phone Phone number to validate.
	 * @return bool True if valid E.164 format.
	 */
	public function is_valid( string $phone ): bool;
}
