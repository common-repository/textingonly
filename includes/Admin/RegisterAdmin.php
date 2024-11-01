<?php
/**
 * Admin registration class.
 *
 * @package TextingOnly
 */

namespace TextingOnly\Admin;

use \WP_Admin_Bar;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Automattic\Jetpack\Assets;

/**
 * Class RegisterAdmin
 */
class RegisterAdmin {
	/**
	 * Class constructor.
	 */
	public function __construct() {
		// Register Admin Dashboard.
		add_action( 'admin_menu', array( $this, 'register_admin_dashboard' ) );

		// Admin Bar menu setup and actions.
		$this->setup_admin_bar_menu();
	}

	/**
	 * Register admin dashboard.
	 */
	public function register_admin_dashboard() {
		$primary_slug = TEXTINGONLY_SLUG;

		$dashboard_page_suffix = add_menu_page(
			_x( 'TextingOnly Dashboard', 'Page title', 'textingonly' ),
			_x( 'TextingOnly', 'Menu title', 'textingonly' ),
			'manage_options',
			$primary_slug,
			array( $this, 'plugin_dashboard_page' ),
			TEXTINGONLY_URL . '/assets/img/icon.svg',
			30
		);

		// Register dashboard hooks.
		add_action( 'load-' . $dashboard_page_suffix, array( $this, 'dashboard_admin_init' ) );

		// Register dashboard submenu nav item.
		add_submenu_page( $primary_slug, 'TextingOnly Dashboard', 'About', 'manage_options', $primary_slug . '#/about', '__return_null' );

		// Remove duplicate menu page.
		remove_submenu_page( $primary_slug, $primary_slug );

		// Register settings submenu nav items.
		add_submenu_page( $primary_slug, 'TextingOnly Dashboard', 'Settings', 'manage_options', $primary_slug . '#/settings', '__return_null' );
		add_submenu_page( $primary_slug, 'TextingOnly Dashboard', 'User Guide', 'manage_options', 'https://www.textingonly.com/wordpress-plugin' );

		// Register upgrade submenu nav item, if has free plan.
  	$to_options = get_option( 'textingonly' );
		$to__plan   = isset( $to_options['to-plan'] ) ? $to_options['to-plan'] : '';
		if ( 'free' === strtolower( $to__plan ) ) {
			add_submenu_page( $primary_slug, 'TextingOnly Dashboard', 'Upgrade', 'manage_options', 'https://www.textingonly.com/upgrade' );
		}
	}

	/**
	 * Initialize the Dashboard admin resources.
	 */
	public function dashboard_admin_init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_dashboard_admin_scripts' ) );
	}

	/**
	 * Enqueue plugin admin scripts and styles.
	 */
	public function enqueue_dashboard_admin_scripts() {
		$prefix = TEXTINGONLY_SLUG;

		Assets::register_script(
			$prefix . '-dashboard',
			'build/dashboard/index.js',
			TEXTINGONLY_ROOT_FILE,
			array(
				'in_footer'  => true,
				'textdomain' => 'textingonly',
			)
		);

		// Enqueue app script.
		Assets::enqueue_script( $prefix . '-dashboard' );
		// Initial JS state.
		wp_add_inline_script( $prefix . '-dashboard', $this->render_dashboard_initial_state(), 'before' );
	}

	/**
	 * Render the initial state into a JavaScript variable.
	 *
	 * @return string
	 */
	public function render_dashboard_initial_state() {
		return 'var textingonlyPluginState=JSON.parse(decodeURIComponent("' . rawurlencode( wp_json_encode( $this->initial_dashboard_state() ) ) . '"));';
	}

	/**
	 * Get the initial state data for hydrating the React UI.
	 *
	 * @return array
	 */
	public function initial_dashboard_state() {
		return array(
			'apiRoute'     => TEXTINGONLY_SLUG . '/v1',
			'assetsURL'    => TEXTINGONLY_URL . '/assets',
			'changelogURL' => TEXTINGONLY_URL . '/changelog.json?ver=' . filemtime( TEXTINGONLY_DIR . '/changelog.json' ),
			'version'      => TEXTINGONLY_VERSION,
		);
	}

	/**
	 * Plugin Dashboard page.
	 */
	public function plugin_dashboard_page() {
		?>
			<div id="textingonly-dashboard-root"></div>
		<?php
	}

	/**
	 * Sets up admin bar and related functions.
	 *
	 * @return void
	 */
	public function setup_admin_bar_menu() {
		// Register admin bar menu.
		add_action( 'admin_bar_menu', array( $this, 'register_admin_bar_menu' ), 1000 );
		// AJAX action handler for flushing permalinks.
		$action_key = TEXTINGONLY_SLUG . '_flush_rules';
		add_action( 'wp_ajax_' . $action_key, array( $this, 'process_permalinks_flush' ) );
	}

	/**
	 * Register admin bar menu.
	 *
	 * @param WP_Admin_Bar $admin_bar The WP_Admin_Bar instance.
	 *
	 * @return void
	 */
	public function register_admin_bar_menu( WP_Admin_Bar $admin_bar ) {
		$prefix         = TEXTINGONLY_SLUG;
		$parent_menu_id = $prefix . '-dashboard';
		$admin_bar->add_menu(
			array(
				'id'     => $parent_menu_id,
				'parent' => null,
				'title'  => __( 'TextingOnly Tools', 'textingonly' ),
				'href'   => esc_url( admin_url( 'admin.php?page=' . $prefix ) ),
			)
		);
		$nonce = wp_create_nonce( $prefix . '_flush_rules' );
		$admin_bar->add_menu(
			array(
				'parent' => $parent_menu_id,
				'id'     => $prefix . '-flush-rewrite-rules',
				'title'  => __( 'Flush Permalinks', 'textingonly' ),
				'href'   => esc_url( admin_url( 'admin-ajax.php?action=' . $prefix . '_flush_rules&nonce=' . $nonce ) ),
			)
		);
	}

	/**
	 * Handles flush permalinks AJAX action from admin bar menu.
	 *
	 * @return void
	 */
	public function process_permalinks_flush() {
		$prefix    = TEXTINGONLY_SLUG;
		$nonce_key = $prefix . '_flush_rules';
		if ( ! check_ajax_referer( $nonce_key, 'nonce' ) ) {
			exit( 'Unauthorized' );
		}
		flush_rewrite_rules();
    // phpcs:ignore.
		$redirect_uri = wp_unslash( $_SERVER['HTTP_REFERER'] ) && ! empty( $_SERVER['HTTP_REFERER'] ) ? sanitize_url( $_SERVER['HTTP_REFERER'] ) : sanitize_url( admin_url( 'admin.php?page=' . $prefix ) );
		wp_safe_redirect( esc_url_raw( $redirect_uri ) );
		die();
	}
}
