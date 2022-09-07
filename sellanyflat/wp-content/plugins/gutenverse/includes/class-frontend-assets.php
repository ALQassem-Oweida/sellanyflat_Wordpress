<?php
/**
 * Frontend Assets class
 *
 * @author Jegstudio
 * @since 1.0.0
 * @package gutenverse
 */

namespace Gutenverse;

/**
 * Class Frontend Assets
 *
 * @package gutenverse
 */
class Frontend_Assets {
	/**
	 * Init constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 99 );
		add_filter( 'gutenverse_global_css', array( $this, 'global_variable_css' ) );
	}

	/**
	 * Global Variable CSS
	 *
	 * @param string $result Global Variable CSS.
	 *
	 * @return string
	 */
	public function global_variable_css( $result ) {
		$setting = get_option( 'gutenverse-global-variable-style' );

		return $result . ' ' . $setting;
	}

	/**
	 * Frontend Script
	 */
	public function frontend_scripts() {
		// Load standalone package for ReactPlayer ref : https://github.com/CookPete/react-player.
		wp_enqueue_script(
			'react-player-dep',
			GUTENVERSE_URL . '/assets/frontend/react-player/ReactPlayer.standalone.js',
			array(),
			GUTENVERSE_VERSION,
			true
		);

		$include = include_once GUTENVERSE_DIR . '/lib/dependencies/frontend.asset.php';

		wp_enqueue_script(
			'gutenverse-frontend-event',
			GUTENVERSE_URL . '/assets/js/frontend.js',
			$include['dependencies'],
			GUTENVERSE_VERSION,
			true
		);

		wp_set_script_translations( 'gutenverse-frontend-event', 'gutenverse', GUTENVERSE_LANG_DIR );

		// Register font awesome.
		wp_enqueue_style(
			'gutenverse-frontend-font-awesome',
			GUTENVERSE_URL . '/assets/fontawesome/css/all.min.css',
			array(),
			GUTENVERSE_VERSION
		);

		wp_register_style(
			'gutenverse-frontend-icon-gutenverse',
			GUTENVERSE_URL . '/assets/gtnicon/gtnicon.css',
			array(),
			GUTENVERSE_VERSION
		);

		wp_enqueue_style(
			'gutenverse-frontend-style',
			GUTENVERSE_URL . '/assets/css/frontend-block.css',
			array( 'gutenverse-frontend-icon-gutenverse' ),
			GUTENVERSE_VERSION
		);
	}

}
