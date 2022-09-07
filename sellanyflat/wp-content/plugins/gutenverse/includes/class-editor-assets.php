<?php
/**
 * Editor Assets class
 *
 * @author Jegstudio
 * @since 1.0.0
 * @package gutenverse
 */

namespace Gutenverse;

/**
 * Class Editor Assets
 *
 * @package gutenverse
 */
class Editor_Assets {
	/**
	 * Init constructor.
	 */
	public function __construct() {
		add_action( 'admin_footer', array( $this, 'register_root' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'register_script' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_backend' ) );
	}

	/**
	 * Register Javascript Script
	 */
	public function register_script() {
		// Register & Enqueue Style.
		wp_register_style(
			'fontawesome-gutenverse',
			GUTENVERSE_URL . '/assets/fontawesome/css/all.css',
			null,
			GUTENVERSE_VERSION
		);

		wp_enqueue_style(
			'gutenverse-iconlist',
			GUTENVERSE_URL . '/assets/gtnicon/gtnicon.css',
			array(),
			GUTENVERSE_VERSION
		);

		wp_enqueue_style(
			'gutenverse-editor-style',
			GUTENVERSE_URL . '/assets/css/editor-block.css',
			array( 'wp-edit-blocks', 'fontawesome-gutenverse' ),
			GUTENVERSE_VERSION
		);

		wp_enqueue_style(
			'gutenverse-frontend-style',
			GUTENVERSE_URL . '/assets/css/frontend-block.css',
			array( 'gutenverse-iconlist', 'fontawesome-gutenverse' ),
			GUTENVERSE_VERSION
		);

		// Register & Enqueue Script.
		$include = include_once GUTENVERSE_DIR . '/lib/dependencies/block.asset.php';

		if ( gutenverse_pro_activated() ) {
			$include['dependencies'][] = 'gutenverse-pro';
		}

		wp_enqueue_script(
			'gutenverse-block',
			GUTENVERSE_URL . '/assets/js/block.js',
			$include['dependencies'],
			GUTENVERSE_VERSION,
			true
		);

		if ( ! gutenverse_pro_activated() ) {
			wp_localize_script( 'gutenverse-block', 'GutenverseConfig', $this->gutenverse_config() );
		}

		wp_set_script_translations( 'gutenverse-block', 'gutenverse', GUTENVERSE_LANG_DIR );
	}

	/**
	 * Gutenverse Config
	 *
	 * @return array
	 */
	public function gutenverse_config() {
		$template        = get_user_meta( get_current_user_id(), 'gutense_templates_viewed', true );
		$global_setting  = get_option( 'gutenverse-global-setting' );
		$global_variable = get_option( 'gutenverse-global-variable' );
		$settings_data   = get_option( 'gutenverse-settings' );

		$config                     = array();
		$config['fonts']            = ( new Fonts() )->get_font_settings();
		$config['imagePlaceholder'] = GUTENVERSE_URL . '/assets/img/img-placeholder.jpg';
		$config['imgDir']           = GUTENVERSE_URL . '/assets/img';
		$config['serverUrl']        = GUTENVERSE_LIBRARY_URL;
		$config['serverEndpoint']   = 'wp-json/gutenverse-server/v1';
		$config['proUrl']           = GUTENVERSE_STORE_URL;
		$config['openedTemplate']   = $template ? $template : array();
		$config['globalSetting']    = ! empty( $global_setting ) ? $global_setting : array();
		$config['userId']           = get_current_user_id();
		$config['freeImg']          = GUTENVERSE_URL . '/assets/img/asset_21_small.webp';
		$config['isTools']          = ! ! defined( 'GUTENVERSE_TOOLS' );
		$config['settingsData']     = ! empty( $settings_data ) ? $settings_data : array();
		$config['themeListUrl']     = admin_url( 'admin.php?page=gutenverse-theme-list' );
		$config['globalVariable']   = ! empty( $global_variable ) ? $global_variable : array(
			'colors' => array(),
			'fonts'  => array(),
		);

		return $config;
	}

	/**
	 * Add root div
	 */
	public function register_root() {
		?>
		<div id='gutenverse-root'></div>
		<?php
	}

	/**
	 * Enqueue Backend Font
	 */
	public function enqueue_backend() {
		$include = include_once GUTENVERSE_DIR . '/lib/dependencies/shared.asset.php';

		wp_enqueue_script(
			'gutenverse-shared',
			GUTENVERSE_URL . '/assets/js/shared.js',
			$include['dependencies'],
			GUTENVERSE_VERSION,
			true
		);

		wp_set_script_translations( 'gutenverse-shared', 'gutenverse', GUTENVERSE_LANG_DIR );

		wp_enqueue_style(
			'gutenverse-backend-font',
			'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&display=swap',
			array(),
			GUTENVERSE_VERSION
		);
	}
}
