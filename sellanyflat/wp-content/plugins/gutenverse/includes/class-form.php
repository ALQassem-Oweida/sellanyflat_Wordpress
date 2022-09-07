<?php
/**
 * Form class
 *
 * @author Jegstudio
 * @since 1.0.0
 * @package gutenverse
 */

namespace Gutenverse;

use WP_Post;

/**
 * Class Form
 *
 * @package gutenverse
 */
class Form {
	/**
	 * Post type
	 *
	 * @var string
	 */
	const POST_TYPE = 'gutenverse-form';

	/**
	 * Init constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'post_type' ), 9 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ) );
		add_action( 'admin_footer', array( $this, 'admin_footer' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( $this, 'custom_column' ), 10, 2 );
		add_action( 'admin_menu', array( $this, 'parent_menu' ) );
		add_filter( 'post_row_actions', array( $this, 'action_row' ), 10, 2 );
		add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', array( $this, 'set_custom_column' ) );
	}

	/**
	 * Parent Menu
	 */
	public function parent_menu() {
		add_menu_page(
			esc_html__( 'Form', 'gutenverse' ),
			esc_html__( 'Form', 'gutenverse' ),
			'manage_options',
			self::POST_TYPE,
			null,
			GUTENVERSE_URL . '/assets/icon/icon-form-dashboard.svg',
			31
		);
	}

	/**
	 * Set custom columns.
	 *
	 * @return array
	 */
	public function set_custom_column() {
		$columns['cb']           = '<input type=\'checkbox\' />';
		$columns['title']        = __( 'Title', 'gutenverse' );
		$columns['form_entries'] = __( 'Entries', 'gutenverse' );
		$columns['author']       = __( 'Author', 'gutenverse' );
		$columns['date']         = __( 'Date', 'gutenverse' );

		return $columns;
	}

	/**
	 * Row Actions
	 *
	 * @param array    $actions .
	 * @param \WP_Post $post .
	 *
	 * @return string
	 */
	public function action_row( $actions, $post ) {
		if ( self::POST_TYPE === $post->post_type ) {
			unset( $actions['view'] );
		}
		return $actions;

	}

	/**
	 * Custom column.
	 *
	 * @param array $column .
	 * @param int   $post_id .
	 */
	public function custom_column( $column, $post_id ) {
		if ( 'form_entries' === $column ) {
			$total_entries = Entries::get_total_entries( $post_id );
			$form_link     = admin_url( 'edit.php?post_type=' . Entries::POST_TYPE ) . '&form_id=' . $post_id;
			$export_link   = rest_url( '/gutenverse-client/v1/form-action/export/' . $post_id . '?_wpnonce=' . wp_create_nonce( 'wp_rest' ) );
			$form_ref      = '<a class="total-entries" href="' . $form_link . '">' . $total_entries . '</a>';

			if ( $total_entries > 0 ) {
				$form_ref .= '<a class="export-entries" href="' . $export_link . '">' . esc_html__( 'Export CVS', 'gutenverse' ) . '</a>';
			}

			gutenverse_print_html( $form_ref );
		}
	}

	/**
	 * Admin footer
	 */
	public function admin_footer() {
		$screen = get_current_screen();

		if ( self::POST_TYPE === $screen->post_type ) {
			?>
			<div id='gutenverse-form-action'></div>
			<?php
		}
	}

	/**
	 * Enqueue Script
	 */
	public function enqueue_script() {
		$screen = get_current_screen();

		if ( self::POST_TYPE === $screen->post_type ) {
			$include = include_once GUTENVERSE_DIR . '/lib/dependencies/backend.asset.php';

			wp_register_script(
				'gutenverse-backend',
				GUTENVERSE_URL . '/assets/js/backend.js',
				$include['dependencies'],
				GUTENVERSE_VERSION,
				true
			);

			if ( gutenverse_pro_activated() ) {
				$include['dependencies'][] = 'gutenverse-pro';
			} else {
				wp_localize_script( 'gutenverse-backend', 'GutenverseConfig', $this->js_config() );
			}

			wp_enqueue_script( 'gutenverse-backend' );

			wp_set_script_translations( 'gutenverse-backend', 'gutenverse', GUTENVERSE_LANG_DIR );

			wp_enqueue_style(
				'gutenverse-backend',
				GUTENVERSE_URL . '/assets/css/backend.css',
				null,
				GUTENVERSE_VERSION
			);
		}
	}

	/**
	 * JS Config.
	 */
	public function js_config() {
		$config = Gutenverse::instance()->editor_assets->gutenverse_config();

		return $config;
	}

	/**
	 * Register Post Type
	 */
	public function post_type() {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'          =>
					array(
						'name'               => esc_html__( 'Form Action', 'gutenverse' ),
						'singular_name'      => esc_html__( 'Form Action', 'gutenverse' ),
						'menu_name'          => esc_html__( 'Form Action', 'gutenverse' ),
						'add_new'            => esc_html__( 'New Form Action', 'gutenverse' ),
						'add_new_item'       => esc_html__( 'Create Form', 'gutenverse' ),
						'edit_item'          => esc_html__( 'Edit Form Option', 'gutenverse' ),
						'new_item'           => esc_html__( 'New Form Entry', 'gutenverse' ),
						'view_item'          => esc_html__( 'View Form', 'gutenverse' ),
						'search_items'       => esc_html__( 'Search Form', 'gutenverse' ),
						'not_found'          => esc_html__( 'No entry found', 'gutenverse' ),
						'not_found_in_trash' => esc_html__( 'No Form in Trash', 'gutenverse' ),
						'parent_item_colon'  => '',
					),
				'description'     => esc_html__( 'Gutenverse Form Action', 'gutenverse' ),
				'public'          => true,
				'show_ui'         => true,
				'menu_position'   => 6,
				'capability_type' => 'post',
				'hierarchical'    => false,
				'supports'        => array( 'title', 'revisions', 'page-attributes' ),
				'map_meta_cap'    => true,
				'show_in_menu'    => self::POST_TYPE,
				'rewrite'         => array(
					'slug' => self::POST_TYPE,
				),
			)
		);
	}



	/**
	 * Create Form Action
	 *
	 * @param integer $id Post ID.
	 *
	 * @return array
	 */
	public static function get_form_action_data( $id ) {
		if ( $id ) {
			$meta = get_post_meta( $id, 'form-data', true );
			$data = array(
				'title' => get_the_title( $id ),
			);

			return array_merge( $data, $meta );
		}

		return false;
	}

	/**
	 * Create Form Action
	 *
	 * @param array $params Form Action Parameter.
	 *
	 * @return array
	 */
	public static function create_form_action( $params ) {
		$form_data = array(
			'post_title'  => $params['title'],
			'post_status' => 'publish',
			'post_type'   => self::POST_TYPE,
			'meta_input'  => array(
				'form-data' => $params,
			),
		);

		return wp_insert_post( $form_data );
	}

	/**
	 * Edit Form Action
	 *
	 * @param array $params Form Action Parameter.
	 *
	 * @return array
	 */
	public static function edit_form_action( $params ) {
		update_post_meta(
			$params['id'],
			'form-data',
			array(
				'require_login'                  => $params['require_login'],
				'user_browser'                   => $params['user_browser'],
				'form_success_notice'            => $params['form_success_notice'],
				'form_error_notice'              => $params['form_error_notice'],
				'user_confirm'                   => $params['user_confirm'],
				'auto_select_email'              => $params['auto_select_email'],
				'email_input_name'               => $params['email_input_name'],
				'user_email_subject'             => $params['user_email_subject'],
				'user_email_form'                => $params['user_email_form'],
				'user_email_reply_to'            => $params['user_email_reply_to'],
				'user_email_body'                => $params['user_email_body'],
				'admin_confirm'                  => $params['admin_confirm'],
				'admin_email_subject'            => $params['admin_email_subject'],
				'admin_email_to'                 => $params['admin_email_to'],
				'admin_email_from'               => $params['admin_email_from'],
				'admin_email_reply_to'           => $params['admin_email_reply_to'],
				'admin_note'                     => $params['admin_note'],
				'overwrite_default_confirmation' => $params['overwrite_default_confirmation'],
				'overwrite_default_notification' => $params['overwrite_default_notification'],
			)
		);

		return wp_update_post(
			array(
				'ID'         => $params['id'],
				'post_title' => $params['title'],
			)
		);
	}

	/**
	 * Delete Form Action
	 *
	 * @param integer $id Delete Form Action.
	 *
	 * @return mixed
	 */
	public static function delete_form_action( $id ) {
		return wp_delete_post( $id, true );
	}

	/**
	 * Clone Form Action
	 *
	 * @param integer $id Delete Form Action.
	 *
	 * @return mixed
	 */
	public static function clone_form_action( $id ) {
		$title     = get_the_title( $id );
		$meta      = get_post_meta( $id, 'form-data', true );
		$new_title = $title . esc_html__( ' Clone', 'gutenverse' );

		return self::create_form_action(
			array_merge(
				$meta,
				array(
					'title' => $new_title,
				)
			)
		);
	}

}
