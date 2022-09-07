<?php
/**
 * Theme List class
 *
 * @author Jegstudio
 * @since 1.0.0
 * @package gutenverse
 */

namespace Gutenverse;

/**
 * Class Theme List
 *
 * @package gutenverse
 */
class Theme_List {
	/**
	 * Type
	 *
	 * @var string
	 */
	const TYPE = 'gutenverse-theme-list';

	/**
	 * Init constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'child_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue scripts
	 *
	 * @param string $hook .
	 */
	public function enqueue_scripts( $hook ) {
		$register_location = array(
			'gutenverse_page_gutenverse-theme-list',
		);

		if ( in_array( $hook, $register_location, true ) ) {
			$include = include_once GUTENVERSE_DIR . '/lib/dependencies/themelist.asset.php';

			if ( gutenverse_pro_activated() ) {
				$include['dependencies'][] = 'gutenverse-pro';
			}

			wp_enqueue_script(
				'gutenverse-theme-list',
				GUTENVERSE_URL . '/assets/js/themelist.js',
				$include['dependencies'],
				GUTENVERSE_VERSION,
				true
			);

			wp_localize_script( 'gutenverse-theme-list', 'GutenverseThemeList', $this->gutenverse_theme_list_config() );

			wp_set_script_translations( 'gutenverse-theme-list', 'gutenverse', GUTENVERSE_LANG_DIR );

			wp_register_style(
				'fontawesome-gutenverse',
				GUTENVERSE_URL . '/assets/fontawesome/css/all.css',
				null,
				GUTENVERSE_VERSION
			);

			wp_enqueue_style(
				'gutenverse-theme-list',
				GUTENVERSE_URL . '/assets/css/themelist.css',
				array( 'fontawesome-gutenverse' ),
				GUTENVERSE_VERSION
			);
		}
	}

	/**
	 * Gutenverse Config
	 *
	 * @return array
	 */
	public function gutenverse_theme_list_config() {
		$config = array();

		$config['serverUrl']      = GUTENVERSE_LIBRARY_URL;
		$config['serverEndpoint'] = 'wp-json/gutenverse-server/v1';
		$config['imgDir']         = GUTENVERSE_URL . '/assets/img';
		$config['freeImg']        = GUTENVERSE_URL . '/assets/img/asset_21.webp';
		$config['getPro']         = GUTENVERSE_LIBRARY_URL;
		$config['apiUrl']         = 'https://gutenverse.com/wp-json/gutenverse-server/v1';
		$config['url']            = home_url();
		$config['fseUrl']         = is_gutenverse_compatible() ? admin_url( 'site-editor.php' ) : admin_url( 'edit.php?post_type=page' );
		$config['subscribed']     = get_option( 'gutenverse-subscribed', false );
		$config['rating']         = 'https://wordpress.org/support/plugin/gutenverse/reviews/#new-post';
		$config['support']        = 'https://wordpress.org/support/plugin/gutenverse/';
		$config['installNonce']   = wp_create_nonce( 'updates' );
		$config['themeUrl']       = admin_url( 'themes.php?page=' );

		return $config;
	}

	/**
	 * Child Menu
	 */
	public function child_menu() {
		add_submenu_page(
			Dashboard::TYPE,
			esc_html__( 'Theme List', 'gutenverse' ),
			esc_html__( 'Theme List', 'gutenverse' ),
			'manage_options',
			self::TYPE,
			array( $this, 'load_gutenverse_theme_list' ),
			1
		);
	}

	/**
	 * Load Gutenverse Theme List Page
	 */
	public function load_gutenverse_theme_list() {
		?>
		<div id="gutenverse-theme-list"></div>
		<?php
	}
}
