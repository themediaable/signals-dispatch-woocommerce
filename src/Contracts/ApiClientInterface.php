<?php
/**
 * API client interface.
 *
 * @package TMASD\Signals\Dispatch\Contracts
 */

declare(strict_types=1);

namespace TMASD\Signals\Dispatch\Contracts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Interface for WhatsApp API client.
 *
 * Abstracts the WhatsApp Cloud API communication layer
 * to allow for testing and alternative implementations.
 */
interface ApiClientInterface {

	/**
	 * Send a template message via WhatsApp Cloud API.
	 *
	 * @param string             $phone         Phone number in E.164 format.
	 * @param string             $template_name Template name.
	 * @param string             $language      Language code (e.g., en_US).
	 * @param array<int, string> $variables     Template variable values.
	 * @return array{success: bool, error?: string, payload: array<string, mixed>, response: array<string, mixed>}
	 */
	public function send_template_message(
		string $phone,
		string $template_name,
		string $language,
		array $variables
	): array;
}
