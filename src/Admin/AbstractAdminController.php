<?php
/**
 * Abstract admin controller.
 *
 * @package TMASD\Signals\Dispatch\Admin
 */

declare(strict_types=1);

namespace TMASD\Signals\Dispatch\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract base class for admin controllers.
 *
 * Provides common functionality for admin page controllers
 * including capability checks and rendering utilities.
 *
 * @abstract
 */
abstract class AbstractAdminController {

	/**
	 * Page slug for this controller.
	 *
	 * @var string
	 */
	protected string $page_slug = '';

	/**
	 * Page title.
	 *
	 * @var string
	 */
	protected string $page_title = '';

	/**
	 * Menu title.
	 *
	 * @var string
	 */
	protected string $menu_title = '';

	/**
	 * Get the required capability for this controller.
	 *
	 * @return string Capability name.
	 */
	protected function get_capability(): string {
		return current_user_can( \TMASD_CAPABILITY ) ? \TMASD_CAPABILITY : 'manage_options';
	}

	/**
	 * Assert user has permission to access.
	 *
	 * @return void
	 */
	protected function assert_access(): void {
		if ( ! current_user_can( $this->get_capability() ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'signals-dispatch-woocommerce' ) );
		}
	}

	/**
	 * Verify nonce from POST request.
	 *
	 * @param string $action Nonce action name.
	 * @return void
	 */
	protected function verify_nonce( string $action ): void {
		check_admin_referer( $action );
	}

	/**
	 * Get a sanitized GET parameter.
	 *
	 * @param string $key     Parameter key.
	 * @param string $default Default value.
	 * @return string Sanitized value.
	 */
	protected function get_query_param( string $key, string $default = '' ): string {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Used for display only.
		if ( ! isset( $_GET[ $key ] ) ) {
			return $default;
		}
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Used for display only.
		return sanitize_text_field( wp_unslash( $_GET[ $key ] ) );
	}

	/**
	 * Get a sanitized POST parameter.
	 *
	 * @param string $key     Parameter key.
	 * @param string $default Default value.
	 * @return string Sanitized value.
	 */
	protected function get_post_param( string $key, string $default = '' ): string {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified in calling method.
		if ( ! isset( $_POST[ $key ] ) ) {
			return $default;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified in calling method.
		return sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
	}

	/**
	 * Redirect with a status message.
	 *
	 * @param string $page   Page slug.
	 * @param string $status Status key (e.g., 'updated', 'deleted').
	 * @return void
	 */
	protected function redirect_with_status( string $page, string $status ): void {
		wp_safe_redirect( admin_url( "admin.php?page={$page}&{$status}=1" ) );
		exit;
	}

	/**
	 * Render a success notice.
	 *
	 * @param string $message Notice message.
	 * @return void
	 */
	protected function render_notice_success( string $message ): void {
		echo '<div class="notice notice-success"><p>' . esc_html( $message ) . '</p></div>';
	}

	/**
	 * Render an error notice.
	 *
	 * @param string $message Notice message.
	 * @return void
	 */
	protected function render_notice_error( string $message ): void {
		echo '<div class="notice notice-error"><p>' . esc_html( $message ) . '</p></div>';
	}

	/**
	 * Render the page.
	 *
	 * @return void
	 */
	abstract public function render(): void;
}
