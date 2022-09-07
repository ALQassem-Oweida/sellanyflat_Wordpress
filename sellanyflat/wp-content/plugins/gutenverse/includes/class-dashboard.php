<?php
/**
 * Dashboard class
 *
 * @author Jegstudio
 * @since 1.0.0
 * @package gutenverse
 */

namespace Gutenverse;

/**
 * Class Dashboard
 *
 * @package gutenverse
 */
class Dashboard {
	/**
	 * Type
	 *
	 * @var string
	 */
	const TYPE = 'gutenverse-dashboard';

	/**
	 * Init constructor.
	 */
	public function __construct() {
		$this->id = 'tabbed-template';

		add_action( 'admin_menu', array( $this, 'parent_menu' ) );
		add_action( 'admin_menu', array( $this, 'child_menu' ) );
		add_filter( 'admin_footer_text', '__return_empty_string', 11 );
		add_filter( 'update_footer', '__return_empty_string', 11 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue scripts
	 *
	 * @param string $hook .
	 */
	public function enqueue_scripts( $hook ) {
		$register_location = array(
			'post.php',
			'toplevel_page_gutenverse-dashboard',
		);

		if ( in_array( $hook, $register_location, true ) ) {
			$include = include_once GUTENVERSE_DIR . '/lib/dependencies/dashboard.asset.php';

			if ( gutenverse_pro_activated() ) {
				$include['dependencies'][] = 'gutenverse-pro';
			}

			wp_enqueue_script(
				'gutenverse-dashboard',
				GUTENVERSE_URL . '/assets/js/dashboard.js',
				$include['dependencies'],
				GUTENVERSE_VERSION,
				true
			);

			wp_localize_script( 'gutenverse-dashboard', 'GutenverseDashboard', $this->gutenverse_dashboard() );

			wp_set_script_translations( 'gutenverse-dashboard', 'gutenverse', GUTENVERSE_LANG_DIR );

			wp_register_style(
				'fontawesome-gutenverse',
				GUTENVERSE_URL . '/assets/fontawesome/css/all.css',
				null,
				GUTENVERSE_VERSION
			);
			wp_enqueue_style(
				'gutenverse-dashboard-bg',
				GUTENVERSE_URL . '/assets/css/dashboard-bg.css',
				array(),
				GUTENVERSE_VERSION
			);

			wp_enqueue_style(
				'gutenverse-dashboard',
				GUTENVERSE_URL . '/assets/css/dashboard.css',
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
	public function gutenverse_dashboard() {
		$config               = array();
		$config['imgDir']     = GUTENVERSE_URL . '/assets/img';
		$config['freeImg']    = GUTENVERSE_URL . '/assets/img/asset_21.webp';
		$config['getPro']     = GUTENVERSE_LIBRARY_URL;
		$config['apiUrl']     = 'https://gutenverse.com/wp-json/gutenverse-server/v1';
		$config['url']        = home_url();
		$config['fseUrl']     = is_gutenverse_compatible() ? admin_url( 'site-editor.php' ) : admin_url( 'edit.php?post_type=page' );
		$config['subscribed'] = get_option( 'gutenverse-subscribed', false );
		$config['rating']     = 'https://wordpress.org/support/plugin/gutenverse/reviews/#new-post';
		$config['support']    = 'https://wordpress.org/support/plugin/gutenverse/';
		$config['docs']       = 'https://gutenverse.com/docs/';
		$config['themelist']  = admin_url( 'admin.php?page=gutenverse-theme-list' );

		return $config;
	}

	/**
	 * Parent Menu
	 */
	public function parent_menu() {
		add_menu_page(
			esc_html__( 'Gutenverse', 'gutenverse' ),
			esc_html__( 'Gutenverse', 'gutenverse' ),
			'manage_options',
			self::TYPE,
			null,
			GUTENVERSE_URL . '/assets/icon/icon-logo-dashboard.svg',
			30
		);
	}

	/**
	 * Child Menu
	 */
	public function child_menu() {
		add_submenu_page(
			self::TYPE,
			esc_html__( 'Dashboard', 'gutenverse' ),
			esc_html__( 'Dashboard', 'gutenverse' ),
			'manage_options',
			self::TYPE,
			array( $this, 'load_gutenverse_dashboard' ),
			0
		);
	}

	/**
	 * Load Gutenverse Pro Activation Page
	 */
	public function load_gutenverse_dashboard() {
		?>
		<div id="gutenverse-dashboard"></div>
		<?php
	}
}
