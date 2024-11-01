<?php
/**
 * TextingOnly
 *
 * @package           TextingOnly
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       TextingOnly
 * Plugin URI:        https://www.textingonly.com/wordpress-plugin/
 * Description:       Easily integrate business SMS texting products and tools into your website.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            TextingOnly
 * Author URI:        https://www.textingonly.com
 * Text Domain:       textingonly
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TEXTINGONLY_VERSION', '1.0.0' );
define( 'TEXTINGONLY_DIR', plugin_dir_path( __FILE__ ) );
define( 'TEXTINGONLY_ROOT_FILE', __FILE__ );
define( 'TEXTINGONLY_ROOT_FILE_RELATIVE_PATH', plugin_basename( __FILE__ ) );
define( 'TEXTINGONLY_SLUG', 'textingonly' );
define( 'TEXTINGONLY_FOLDER', dirname( plugin_basename( __FILE__ ) ) );
define( 'TEXTINGONLY_URL', plugins_url( '', __FILE__ ) );
define( 'TEXTINGONLY_API', 'https://wp.textingonly.com/v1/qrcs/' );

// TextingOnly Autoloader.
$textingonly_autoloader = TEXTINGONLY_DIR . 'vendor/autoload_packages.php';
if ( is_readable( $textingonly_autoloader ) ) {
	require_once $textingonly_autoloader;
} else { 
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			__( 'Error loading autoloader file for TextingOnly plugin', 'textingonly' )
		);
	}
	add_action(
		'admin_notices',
		function () {
			?>
		<div class="notice notice-error is-dismissible">
			<p>
				<?php
				printf(
					wp_kses(
						/* translators: Placeholder is a link to the plugin user guide. */
						__( 'Your installation of TextingOnly is incomplete. Please refer to <a href="%1$s" target="_blank" rel="noopener noreferrer">the plugin user guide</a> for more information.', 'textingonly' ),
						array(
							'a' => array(
								'href'   => array(),
								'target' => array(),
								'rel'    => array(),
							),
						)
					),
					'https://www.textingonly.com/wordpress-plugin/'
				);
				?>
			</p>
		</div>
			<?php
		}
	);
	return;
}

// Redirect to plugin dashboard when the plugin is activated.
add_action( 'activated_plugin', 'textingonly_activation' );

/**
 * Redirects to plugin onboarding when the plugin is activated.
 *
 * @param string $plugin Path to the plugin file relative to the plugins directory.
 */
function textingonly_activation( $plugin ) {
	flush_rewrite_rules();
	if (
		TEXTINGONLY_ROOT_FILE_RELATIVE_PATH === $plugin &&
		\Automattic\Jetpack\Plugins_Installer::is_current_request_activating_plugin_from_plugins_screen( TEXTINGONLY_ROOT_FILE_RELATIVE_PATH )
	) {
		wp_safe_redirect( esc_url_raw( admin_url( 'admin.php?page=textingonly#/about' ) ) );
		exit;
	}
}

register_activation_hook( __FILE__, array( '\TextingOnly\Plugin', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( '\TextingOnly\Plugin', 'plugin_deactivation' ) );

// TextingOnly.
if ( class_exists( \TextingOnly\Plugin::class ) ) {
	new \TextingOnly\Plugin();
}
