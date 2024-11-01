<?php
/**
 * TextingOnly plugin.
 *
 * @package TextingOnly
 */

namespace TextingOnly;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use TextingOnly\Core\Options;
use TextingOnly\API\OptionsAPI;
use TextingOnly\Admin\RegisterAdmin;

/**
 * Class Plugin
 */
class Plugin {
	/**
	 * Options manager.
	 *
	 * @var Options
	 */
	public $options_manager;

	/**
	 * Options API manager.
	 *
	 * @var OptionsAPI
	 */
	public $options_api_manager;

	/**
	 * Admin Manager.
	 *
	 * @var RegisterAdmin;
	 */
	public $admin_manager;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Get options manager instance.
		$this->options_manager = Options::get_instance();

		// Register APIs.
		$this->options_api_manager = new OptionsAPI();

		// Register Admin.
		$this->admin_manager = new RegisterAdmin();

		$this->register_hooks();
	}

	/**
	 * Register core hooks.
	 */
	public function register_hooks() {
		add_filter(
			'plugin_action_links_' . TEXTINGONLY_FOLDER . '/textingonly.php',
			array( $this, 'action_links' )
		);
		add_action( 'wp_enqueue_scripts', array(&$this, 'textingonly_public_scripts') );
		add_action( 'admin_enqueue_scripts', array(&$this, 'textingonly_admin_scripts') );
    add_action( 'wp_ajax_textingonly_qq', array(&$this, 'textingonly_qq') );
    add_shortcode( 'textingonly', array(&$this, 'textingonly_shortcode') );
    add_action('wp_footer', array(&$this, 'textingonly__float') );
	}

	/**
	 * Enqueue public assets.
	 */
  public function textingonly_public_scripts() {
  	global $post;
    if ( $this->textingonly_sitewide_display() || is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'textingonly') ) {
    	$to_css__ver = '1.0.0';
    	wp_enqueue_style( 'textingonly-css', plugins_url('/assets/css/textingonly.css', dirname(__FILE__) ), array(), $to_css__ver );
    }
  }

	/**
	 * Enqueue wp-admin assets.
	 */
  public function textingonly_admin_scripts() {
  	$to_js__ver = '1.0.0';
		wp_register_script( 'to-textingonly-script', plugins_url('/assets/js/textingonly-admin.js', dirname(__FILE__)), array(), $to_js__ver, TRUE );
		$ajax__arr  = array(
		  'ajaxurl'  => esc_url( admin_url( 'admin-ajax.php' ) ),
		  'security' => wp_create_nonce( 'textingonly-ajx' )
		);
		wp_localize_script( 'to-textingonly-script', 'forajax', $ajax__arr );
		wp_enqueue_script( 'to-textingonly-script' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
  }

	/**
	 * TextingOnly account details.
	 *
	 * @return json
	 */
  public function textingonly_qq() {
    check_ajax_referer( 'textingonly-ajx', 'security' );
  	$to_options = get_option( 'textingonly' );
		$to_api_key = isset( $to_options['to-api-key'] ) ? base64_decode( $to_options['to-api-key'] ) : null;
		$to_a_email = isset( $to_options['to-account-email'] ) ? $to_options['to-account-email'] : null;
		$url___this = ( ! empty( home_url() ) ) ? sanitize_url( home_url() ) : '';
		if ( empty( $to_api_key ) || empty( $to_a_email ) ) {
			$to_options[ 'to-qrcids' ]     = array();
			$to_options[ 'default-qrcid' ] = '';
			$to_options[ 'to-plan' ]       = '';
			update_option( 'textingonly', $to_options );
			$missing  = '';
			$missing .= ( empty( $to_a_email ) ) ? 'Account email missing. ' : '';
			$missing .= ( empty( $to_api_key ) ) ? 'API key missing. ' : '';
    	echo wp_json_encode( array( 
    		"status" => 'fail',
    		"reason" => $missing,
    	) );
		} else {
	    $api_url = TEXTINGONLY_API;
	    $args = array(
	      'headers' => array( 
	        'Authorization' => $url___this
	      ),
	      'body' => array(
	        'apikey' => $to_api_key,
	        'aemail' => $to_a_email,
	        'admurl' => $url___this
	      )
	    );
	    $response = wp_remote_get( $api_url, $args );
	    if ($response) {
	      $textingonly_qq = json_decode( wp_remote_retrieve_body( $response ), true ) ?: null;
	      if ( $textingonly_qq ) {
      		if ( ! isset( $textingonly_qq[ 'qrcs' ] ) ) {
      			$textingonly_qq[ 'qrcs' ] = array();
      		}
    			$to_options[ 'to-qrcids' ] = json_decode( wp_json_encode( $textingonly_qq[ 'qrcs' ] ) );
      		if ( ! isset( $textingonly_qq[ 'plan' ] ) ) {
      			$textingonly_qq[ 'plan' ] = 'unknown';
      		}
      		$to_options[ 'to-plan' ] = ucwords( $textingonly_qq[ 'plan' ] );
    			update_option( 'textingonly', $to_options );
      	}
      	echo wp_json_encode( array( 
      		"status" => isset( $textingonly_qq[ 'status' ] ) ? sanitize_text_field( $textingonly_qq[ 'status' ] ) : 'fail',
      		"reason" => isset( $textingonly_qq[ 'reason' ] ) ? sanitize_text_field( $textingonly_qq[ 'reason' ] ) : '',
      		"plan"   => sanitize_text_field( $textingonly_qq[ 'plan' ] ),
      		"cached" => isset( $textingonly_qq[ 'cached' ] ) ? rest_sanitize_boolean( $textingonly_qq[ 'cached' ] ) : false,
      	) );
      }
	  }
    wp_die();
  }

	/**
	 * The Button/Link Shortcode.
	 *
	 * @access public
	 * @param array $atts Shortode attributes.
	 * @return mixed
	 */
  public function textingonly_shortcode( $atts ) {
    $a = shortcode_atts( array(
      'code'  => '',
      'style' => '',
      'text'  => '',
      'icon'  => '',
      'size'  => '',
      'color' => '',
      'bgcolor' => '',
    ), $atts );
    $to_option = get_option( 'textingonly' ) ?? array();
    $code = $a['code'];
    if ('' === $code) {
      $code = $to_option['default-qrcid'] ?? '010101';
      if ( empty( trim( $code ) ) ) {
      	$code = '010101';
      }
    }
    $tolink = 'https://c.txtng.co/' . $code;
    $link_text = $a['text'];
    if ('' === $link_text) {
      $link_text = $to_option['default-btn-txt'] ?? 'Text Us';
      if ( empty( trim( $link_text ) ) ) {
      	$link_text = 'Text Us';
      }
    }
    $bg_color = ( $this->check_valid_hex_code( $a['bgcolor'] ) ) ? $a['bgcolor'] : '';
    if ('' === $bg_color) {
    	$bg_color = $to_option['default-bg-color'] ?? '#003366';
      if ( empty( trim( $bg_color ) ) ) {
      	$bg_color = '#003366';
      }
    }
    $txt_color = ( $this->check_valid_hex_code( $a['color'] ) ) ? $a['color'] : '';
    if ('' === $txt_color) {
    	$txt_color = $to_option['default-txt-color'] ?? '#e7e8e9';
      if ( empty( trim( $txt_color ) ) ) {
      	$txt_color = '#e7e8e9';
      }
    }
    $colors = "background-color:" . $bg_color . ";color:" . $txt_color . ";";
    $style = $a['style'];
    if ('' === $style) {
      $style = $to_option['default-style'] ?? 'default';
      if ( empty( trim( $style ) ) ) {
      	$style = 'default';
      }
    }
    $link_clss = ( empty( $style ) ) ? 'to--btn to-btn-custom' : 'to--btn to-btn-custom ' . $style;
    $size = $a['size'];
    if ('' === $size) {
      $size = $to_option['default-font-size'] ?? 'fs-reg';
      if ( empty( trim( $size ) ) ) {
      	$size = 'fs-reg';
      }
    }
    $link_clss = $link_clss . ' ' . $size;
    $icon = $a['icon'];
    if ('' === $icon) {
      $icon = $to_option['default-icon'] ?? 'no';
      if ( 'light' === $icon ) {
      	$icon = 'has-icon';
      }
      if ( 'dark' === $icon ) {
      	$icon = 'has-icon icon-dark';
      }
    }
    $link_clss = $link_clss . ' ' . $icon;
    $button = '<a href="' . esc_url( $tolink ) . '" class="' . esc_attr( $link_clss ) . '" style="' . esc_attr( $colors ) . '" target="_blank" rel="noopener">' . esc_html( $link_text ) . '</a>';
    return $button;
  }

	/**
	 * Validate a hex color code.
	 *
	 * @access protected
	 * @param string $str String to check.
	 * @return bool
	 */
	protected function check_valid_hex_code( $str = '' ) {
		$answer = false;
		if ( ! empty( $str ) && 7 === strlen( $str ) ) {
			$firstchar = mb_substr( $str, 0, 1 );
			if ( '#' === $firstchar ) {
				$theval = substr( $str, 1 );
				if ( ctype_xdigit( $theval ) ) {
					$answer = true;
				}
			}
		}
		return $answer;
	}

	/**
	 * Check if sitewide display is turned on.
	 *
	 * @access protected
	 * @return bool
	 */
	protected function textingonly_sitewide_display() {
		$sitewide   = false;
	  $to_options = get_option('textingonly') ?? array();
	  if ( isset( $to_options['sitewide-display'] ) && isset( $to_options['default-qrcid'] ) && ! empty( $to_options['default-qrcid'] ) ) {
	  	$swdisplay = $to_options['sitewide-display'] ?? 'no';
	  	if ( false !== strpos( $swdisplay, 'yes--' ) ) {
	  		$sitewide = true;
	  	}
	  }
	  return $sitewide;
	}

	/**
	 * Add sitewide button, or not.
	 *
	 * @access public
	 * @return mixed
	 */
	public function textingonly__float() {
		if ( $this->textingonly_sitewide_display() ) {
			$to_options = get_option('textingonly') ?? array();
			$swdisplay  = $to_options['sitewide-display'] ?? 'no';
  		$swdiswhere = $to_options['sitewide-where'] ?? 'all';
  		$swdivclass = str_replace( 'yes--', '', $swdisplay );
  		$float_btn  = '<div id="to-flt-btn" class="' . esc_attr( $swdivclass . ' ' . $swdiswhere ) . '">';
  		$float_btn .= do_shortcode( '[textingonly]' );
  		$float_btn .= '</div>';
  		echo wp_kses_post( $float_btn );
		}
	}

	/**
	 * Register plugin action links.
	 *
	 * @access public
	 * @param array $actions A list of actions for the plugin.
	 * @return array
	 */
	public function action_links( $actions ) {
		$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=textingonly#/settings' ) ) . '">' . __( 'Settings', 'textingonly' ) . '</a>';
		array_unshift( $actions, $settings_link );
		return $actions;
	}

	/**
	 * Plugin activation hook.
	 *
	 * @access public
	 * @static
	 */
	public static function plugin_activation() {
		flush_rewrite_rules();
	}

	/**
	 * Plugin deactivation hook.
	 *
	 * @access public
	 * @static
	 */
	public static function plugin_deactivation() {
		flush_rewrite_rules();
	}
}
