<?php
/**
 * Settings class
 *
 * @author Jegstudio
 * @since 1.0.0
 * @package gutenverse
 */

namespace Gutenverse;

/**
 * Class Settings
 *
 * @package gutenverse
 */
class Settings {
	/**
	 * Type
	 *
	 * @var string
	 */
	const TYPE = 'gutenverse-settings';

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
			'gutenverse_page_gutenverse-settings',
		);

		if ( in_array( $hook, $register_location, true ) ) {
			$include = include_once GUTENVERSE_DIR . '/lib/dependencies/settings.asset.php';

			wp_enqueue_script(
				'gutenverse-settings',
				GUTENVERSE_URL . '/assets/js/settings.js',
				$include['dependencies'],
				GUTENVERSE_VERSION,
				true
			);

			wp_set_script_translations( 'gutenverse-settings', 'gutenverse', GUTENVERSE_LANG_DIR );

			wp_localize_script( 'gutenverse-settings', 'GutenverseSettings', $this->gutenverse_scripts() );

			wp_enqueue_style(
				'gutenverse-settings',
				GUTENVERSE_URL . '/assets/css/settings.css',
				null,
				GUTENVERSE_VERSION
			);
		}
	}

	/**
	 * Gutenverse Settings
	 *
	 * @return array
	 */
	public function gutenverse_scripts() {
		$settings_data = get_option( 'gutenverse-settings' );

		$config                 = array();
		$config['settingsData'] = ! empty( $settings_data ) ? $settings_data : array();
		$config['getPro']       = GUTENVERSE_LIBRARY_URL;
		$config['freeImg']      = GUTENVERSE_URL . '/assets/img/asset_21.webp';

		return $config;
	}

	/**
	 * Child Menu
	 */
	public function child_menu() {
		add_submenu_page(
			Dashboard::TYPE,
			esc_html__( 'Settings', 'gutenverse' ),
			esc_html__( 'Settings', 'gutenverse' ),
			'manage_options',
			self::TYPE,
			array( $this, 'gutenverse_settings' ),
			2
		);
	}

	/**
	 * Gutenverse Settings
	 */
	public function gutenverse_settings() {
		?>
		<div id="gutenverse-settings"></div>
		<?php
	}
}
