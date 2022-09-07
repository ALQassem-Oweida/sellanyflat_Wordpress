<?php
/**
 * REST APIs class
 *
 * @author Jegstudio
 * @since 1.0.0
 * @package gutenverse
 */

namespace Gutenverse;

use Gutenverse\Form;
use Gutenverse\Style\Global_Style;
use WP_Query;

/**
 * Class Api
 *
 * @package gutenverse
 */
class Api {
	/**
	 * Endpoint Path
	 *
	 * @var string
	 */
	const ENDPOINT = 'gutenverse-client/v1';

	/**
	 * Blocks constructor.
	 */
	public function __construct() {
		$this->register_routes();
	}

	/**
	 * Register Gutenverse APIs
	 */
	private function register_routes() {
		register_rest_route(
			self::ENDPOINT,
			'menu',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'menu' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'subscribed',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'subscribed' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'form/search',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'search_form' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'form/init',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'form_init' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'form/submit',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'submit_form' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'form-action/create',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'create_form_action' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'form-action/edit',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'edit_form_action' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'form-action/(?P<id>\d+)',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_form_action' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'form-action/(?P<id>\d+)',
			array(
				'methods'             => 'DELETE',
				'callback'            => array( $this, 'delete_form_action' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'form-action/clone',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'clone_form_action' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'form-action/export/(?P<id>\d+)',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'export_form_action' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'taxonomies',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_taxonomies' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'singles',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_singles' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'globalstyle/modify',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'modify_global_style' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'globalvariable/modify',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'modify_global_variable' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'settings/modify',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'modify_settings' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'layout/like-list',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'liked_layout' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'layout/set-like',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'site_like_layout' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'section/like-list',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'liked_section' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'section/set-like',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'site_like_section' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'import/images',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'import_images' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'themes/activate',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'activate_theme' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::ENDPOINT,
			'themes/install',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'install_theme' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Install Themes from Gutenverse.com repository.
	 *
	 * @param object $request .
	 */
	public function install_theme( $request ) {
		$theme = $request->get_param( 'slug' );

		$request = wp_remote_post(
			GUTENVERSE_LIBRARY_URL . '/wp-json/gutenverse-server/v1/theme/information',
			array(
				'body' => array(
					'slug' => $theme,
				),
			)
		);

		$res = json_decode( wp_remote_retrieve_body( $request ), true );
		$api = (object) $res;

		require_once ABSPATH . 'wp-admin/includes/misc.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		$upgrader = new \Theme_Upgrader( new \WP_Ajax_Upgrader_Skin() );
		$upgrader->install( $api->download_link );

		return $api;
	}


	/**
	 * Modify Settings
	 *
	 * @param object $request .
	 */
	public function modify_settings( $request ) {
		$result = $this->save_option( $request, 'setting', 'gutenverse-settings' );

		return $result;
	}

	/**
	 * Modify Global Variable.
	 *
	 * @param object $request .
	 */
	public function modify_global_variable( $request ) {
		$result = $this->save_option( $request, 'variable', 'gutenverse-global-variable' );
		$result = $this->save_option( $request, 'style', 'gutenverse-global-variable-style' );
		$result = $this->save_option( $request, 'font', 'gutenverse-global-variable-font' );

		return $result;
	}

	/**
	 * Modify Global Style.
	 *
	 * @param object $request .
	 */
	public function modify_global_style( $request ) {
		$result = $this->save_option( $request, 'setting', 'gutenverse-global-setting' );
		$result = $this->save_option( $request, 'font', 'gutenverse-global-setting-font' );

		if ( $request->get_param( 'setting' ) ) {
			$setting      = $request->get_param( 'setting' );
			$global_style = new Global_Style( $setting );
			$option_name  = 'gutenverse-global-setting-style';
			$options      = get_option( $option_name );

			if ( ! isset( $options ) ) {
				add_option( $option_name, $global_style->generate_style() );
			} else {
				update_option( $option_name, $global_style->generate_style() );
			}
		}

		return $result;
	}

	/**
	 * Flag true if already subscribed.
	 *
	 * @param object $request .
	 */
	public function subscribed( $request ) {
		$this->save_option( $request, 'subscribed', 'gutenverse-subscribed' );
		$this->save_option( $request, 'email', 'gutenverse-subscribed-email' );

		return true;
	}

	/**
	 * Save / Update Option
	 *
	 * @param object $request Request Object.
	 * @param object $param_name Request Parameter Name.
	 * @param object $option_name Option .
	 */
	public function save_option( $request, $param_name, $option_name ) {
		$data    = $request->get_param( $param_name );
		$options = get_option( $option_name );

		if ( ! isset( $options ) ) {
			$result = add_option( $option_name, $data );
		} else {
			$result = update_option( $option_name, $data );
		}

		return $result;
	}

	/**
	 * Get Taxonomies
	 *
	 * @param object $request object.
	 */
	public function get_taxonomies( $request ) {
		$include = $request->get_param( 'include' );
		$search  = $request->get_param( 'search' );

		$taxonomies = get_terms(
			array(
				'name__like' => $search,
				'include'    => $include,
			)
		);

		$result = array();

		foreach ( $taxonomies as $key => $term ) {
			$taxonomy = get_taxonomy( $term->taxonomy );
			$result[] = array(
				'id'   => $term->term_id,
				'name' => $term->name . ' - ' . $taxonomy->label,
			);
		}

		return $result;
	}

	/**
	 * Get Singles
	 *
	 * @param object $request object.
	 */
	public function get_singles( $request ) {
		$include   = $request->get_param( 'include' );
		$search    = $request->get_param( 'search' );
		$post_type = $request->get_param( 'post_type' );
		$post_type = ! empty( $post_type ) && 'all_types' !== $post_type ? $post_type : array( 'page', 'post' );

		$args  = array(
			's'         => $search,
			'include'   => $include,
			'post_type' => $post_type,
		);
		$posts = get_posts( $args );

		$result = array();

		foreach ( $posts as $key => $post ) {
			$result[] = array(
				'id'   => $post->ID,
				'name' => $post->post_title,
			);
		}

		return $result;
	}

	/**
	 * Liked Layout
	 *
	 * @param object $request object.
	 */
	public function template_notification( $request ) {
		$user_id   = $request->get_param( 'id' );
		$templates = $request->get_param( 'templates' );

		return update_user_meta( $user_id, 'gutense_templates_viewed', $templates );
	}


	/**
	 * Liked Layout
	 */
	public function liked_section() {
		$liked = get_option( 'gutenverse-liked-section', array() );
		return $liked;
	}

	/**
	 * Like Layout
	 *
	 * @param object $request object.
	 */
	public function site_like_section( $request ) {
		$likes = $request->get_param( 'likes' );
		return update_option( 'gutenverse-liked-section', $likes );
	}

	/**
	 * Import Images
	 *
	 * @param object $request images.
	 */
	public function import_images( $request ) {
		$images   = $request->get_param( 'images' );
		$contents = $request->get_param( 'contents' );
		$array    = array();

		/**
		 * Temporarily increase time limit for import.
		 * Default 30s is not enough for importing long content.
		 */
		set_time_limit( 300 );

		foreach ( $images as $image ) {
			$data = $this->check_image_exist( $image );
			if ( ! $data ) {
				$data = $this->handle_file( $image );
			}
			$array[] = $data;
		}

		return array(
			'images'   => $array,
			'contents' => $contents,
		);
	}

	/**
	 * Return image
	 *
	 * @param string $url Image attachment url.
	 *
	 * @return array|null
	 */
	public function check_image_exist( $url ) {
		$attachments = new WP_Query(
			array(
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'meta_query'  => array(
					array(
						'key'     => '_import_source',
						'value'   => $url,
						'compare' => 'LIKE',
					),
				),
			)
		);

		foreach ( $attachments->posts as $post ) {
			$attachment_url = wp_get_attachment_url( $post->ID );
			return array(
				'id'  => $post->ID,
				'url' => $attachment_url,
			);
		}

		return $attachments->posts;
	}

	/**
	 * Handle Import file, and return File ID when process complete
	 *
	 * @param string $url URL of file.
	 *
	 * @return int|null
	 */
	public function handle_file( $url ) {
		$file_name = basename( $url );
		$upload    = wp_upload_bits( $file_name, null, '' );
		$this->fetch_file( $url, $upload['file'] );

		if ( $upload['file'] ) {
			$file_loc  = $upload['file'];
			$file_name = basename( $upload['file'] );
			$file_type = wp_check_filetype( $file_name );

			$attachment = array(
				'post_mime_type' => $file_type['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file_name ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);

			include_once ABSPATH . 'wp-admin/includes/image.php';
			$attach_id = wp_insert_attachment( $attachment, $file_loc );
			update_post_meta( $attach_id, '_import_source', $url );

			try {
				$attach_data = wp_generate_attachment_metadata( $attach_id, $file_loc );
				wp_update_attachment_metadata( $attach_id, $attach_data );
			} catch ( \Exception $e ) {
				$this->handle_exception( $e );
			} catch ( \Throwable $t ) {
				$this->handle_exception( $e );
			}

			return array(
				'id'  => $attach_id,
				'url' => $upload['url'],
			);
		} else {
			return null;
		}
	}

	/**
	 * Handle Exception.
	 *
	 * @param \Exception $e Exception.
	 */
	public function handle_exception( $e ) {
		// Empty Exception.
	}

	/**
	 * Download file and save to file system
	 *
	 * @param string $url File URL.
	 * @param string $file_path file path.
	 *
	 * @return array|bool
	 */
	public function fetch_file( $url, $file_path ) {
		$http     = new \WP_Http();
		$response = $http->get( $url );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$headers             = wp_remote_retrieve_headers( $response );
		$headers['response'] = wp_remote_retrieve_response_code( $response );

		if ( false === $file_path ) {
			return $headers;
		}

		// GET request - write it to the supplied filename.
		require_once ABSPATH . 'wp-admin/includes/file.php';
		WP_Filesystem();
		global $wp_filesystem;
		$wp_filesystem->put_contents( $file_path, wp_remote_retrieve_body( $response ), FS_CHMOD_FILE );

		return $headers;
	}

	/**
	 * Liked Layout
	 */
	public function liked_layout() {
		$liked = get_option( 'gutenverse-liked-layout', array() );
		return $liked;
	}

	/**
	 * Like Layout
	 *
	 * @param object $request object.
	 */
	public function site_like_layout( $request ) {
		$likes = $request->get_param( 'likes' );
		update_option( 'gutenverse-liked-layout', $likes );
		return true;
	}

	/**
	 * Export Form Action
	 *
	 * @param object $request object.
	 */
	public function export_form_action( $request ) {
		$form_id    = $request->get_param( 'id' );
		$file_title = get_the_title( $form_id ) . '-' . time();
		$posts      = get_posts(
			array(
				'post_type'  => Entries::POST_TYPE,
				'meta_query' => array(
					array(
						'key'     => 'form-id',
						'value'   => $form_id,
						'compare' => '===',
					),
				),
			)
		);

		header( 'Content-type: text/csv' );
		header( 'Content-Disposition: attachment; filename=' . $file_title . '.csv' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		foreach ( $posts as $id => $post ) {
			$form = get_post_meta( $post->ID, 'form-data', true );
			if ( 0 === $id ) {
				foreach ( $form as $id => $data ) {
					echo esc_html( $data['id'] );

					if ( count( $data ) > $id ) {
						echo ',';
					}
				}
				echo "\n";
			}

			$content = array();
			foreach ( $form as $id => $data ) {
				if ( is_array( $data['value'] ) ) {
					echo '"';
					echo esc_html( implode( ',', $data['value'] ) );
					echo '"';
				} else {
					echo esc_html( $data['value'] );
				}

				if ( count( $data ) > $id ) {
					echo ',';
				}
			}
			echo "\n";
		}

		exit;
	}

	/**
	 * Get Form Action
	 *
	 * @param object $request object.
	 *
	 * @return boolean
	 */
	public function get_form_action( $request ) {
		$id = $request->get_param( 'id' );
		return Form::get_form_action_data( $id );
	}

	/**
	 * Delete Form Action
	 *
	 * @param object $request object.
	 *
	 * @return boolean
	 */
	public function delete_form_action( $request ) {
		$id          = $request->get_param( 'id' );
		$form_action = Form::delete_form_action( $id );
		return rest_ensure_response( $form_action );
	}

	/**
	 * Clone form action.
	 *
	 * @param object $request object.
	 *
	 * @return boolean
	 */
	public function clone_form_action( $request ) {
		$id          = $request->get_param( 'id' );
		$form_action = Form::clone_form_action( $id );
		return rest_ensure_response( $form_action );
	}

	/**
	 * Create Form Action
	 *
	 * @param object $request object.
	 *
	 * @return boolean
	 */
	public function edit_form_action( $request ) {
		$form = $request->get_param( 'form' );

		$params = wp_parse_args(
			$form,
			array(
				'id'            => '',
				'title'         => '',
				'require_login' => '',
				'user_browser'  => '',
			)
		);

		$form_action = Form::edit_form_action( $params );
		return rest_ensure_response( $form_action );
	}

	/**
	 * Create Form Action
	 *
	 * @param object $request object.
	 *
	 * @return boolean
	 */
	public function create_form_action( $request ) {
		$form = $request->get_param( 'form' );

		$params = wp_parse_args(
			$form,
			array(
				'title'                          => '',
				'require_login'                  => '',
				'user_browser'                   => '',
				'user_confirm'                   => '',
				'user_confirm'                   => '',
				'auto_select_email'              => '',
				'email_input_name'               => '',
				'user_email_subject'             => '',
				'user_email_form'                => '',
				'user_email_reply_to'            => '',
				'user_email_body'                => '',
				'admin_confirm'                  => '',
				'admin_email_subject'            => '',
				'admin_email_to'                 => '',
				'admin_email_from'               => '',
				'admin_email_reply_to'           => '',
				'admin_note'                     => '',
				'overwrite_default_confirmation' => '',
				'overwrite_default_notification' => '',
			)
		);

		$form_action = Form::create_form_action( $params );
		return rest_ensure_response( $form_action );
	}

	/**
	 * Get User IP Address
	 *
	 * @param array $data .
	 *
	 * @return array|false
	 */
	public function get_browser_data( $data ) {
		if ( empty( $data['user_browser'] ) ) {
			return false;
		}

		$ip = 'unknown';

		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
		} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		}

		$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : 'unknown';

		return array(
			'ip'         => $ip,
			'user_agent' => $user_agent,
		);
	}

	/**
	 * Submit Form
	 *
	 * @param object $request .
	 *
	 * @return WP_Response
	 */
	public function submit_form( $request ) {
		$form_entry = $request['form-entry'];

		if ( isset( $form_entry['data'] ) ) {
			$form_id    = (int) $form_entry['formId'];
			$form_entry = array(
				'form-id'      => $form_id,
				'post-id'      => $form_entry['postId'],
				'entry-data'   => $form_entry['data'],
				'browser-data' => $this->get_browser_data( $form_entry ),
			);

			$params = wp_parse_args(
				$form_entry,
				array(
					'form-id'      => 0,
					'post-id'      => 0,
					'entry-data'   => array(),
					'browser-data' => array(),
				)
			);

			$result = Entries::submit_form_data( $params );

			if ( (int) $result > 0 ) {
				$settings_data = get_option( 'gutenverse-settings' );
				$form_data     = get_post_meta( $form_id, 'form-data', true );
				$entry_id      = $result;

				if ( isset( $settings_data['form'] ) ) {
					if ( isset( $settings['form']['confirmation'] ) && true !== $form_data['overwrite_default_confirmation'] ) {
						$form_data = array_merge( $form_data, $settings['form']['confirmation'] );
					}

					if ( isset( $settings['form']['notification'] ) && true !== $form_data['overwrite_default_notification'] ) {
						$form_data = array_merge( $form_data, $settings['form']['notification'] );
					}
				}

				$mail_list = $this->mail_list( $form_entry['entry-data'], $form_data );

				if ( ! empty( $mail_list ) ) {
					$result = ( new Mail() )->send_user_email( $form_id, $form_data, $entry_id, $form_entry, $mail_list );
				}

				if ( ! empty( $form_data['admin_confirm'] ) ) {
					$result = ( new Mail() )->send_admin_email( $form_id, $form_data, $entry_id, $form_entry );
				}
			}
		}

		return rest_ensure_response( $result );
	}

	/**
	 * Check mail list
	 *
	 * @param array $entry_data .
	 * @param array $form_data .
	 *
	 * @return array.
	 */
	private function mail_list( $entry_data, $form_data ) {
		if ( ! isset( $form_data['user_confirm'] ) ) {
			return false;
		}

		$mail_rgx   = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';
		$input_name = false;
		$mail_list  = array();

		if ( ! $form_data['auto_select_email'] && isset( $form_data['email_input_name'] ) ) {
			$input_name = $form_data['email_input_name'];
		}

		foreach ( $entry_data as $data ) {
			if ( $input_name ) {
				if ( $input_name === $data['id'] && preg_match( $mail_rgx, $data['value'] ) ) {
					$mail_list[] = $data['value'];
				}
			} elseif ( preg_match( $mail_rgx, $data['value'] ) ) {
				$mail_list[] = $data['value'];
			}
		}

		return $mail_list;
	}

	/**
	 * Search Form
	 *
	 * @param object $request .
	 *
	 * @return WP_Rest.
	 */
	public function search_form( $request ) {
		$search = $request->get_param( 'search' );

		$args = array(
			'post_type' => Form::POST_TYPE,
			's'         => $search,
		);

		$query  = new \WP_Query( $args );
		$result = array();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$result[] = array(
					'label' => get_the_title(),
					'value' => get_the_ID(),
				);
			}
		}

		wp_reset_postdata();

		return $result;
	}

	/**
	 * Fetch Menu API
	 */
	public function menu() {
		$menus = wp_get_nav_menus();
		$data  = array();

		foreach ( $menus as $menu ) {
			$data[] = array(
				'label' => $menu->name,
				'value' => $menu->term_id,
			);
		}

		return $data;
	}

	/**
	 * Search Meta
	 *
	 * @param object $request .
	 *
	 * @return WP_Rest.
	 */
	public function form_init( $request ) {
		$form_id   = $request->get_param( 'form_id' );
		$post_type = get_post_type( (int) $form_id );
		$result    = array(
			'require_login' => false,
			'logged_in'     => is_user_logged_in(),
		);

		if ( Form::POST_TYPE === $post_type ) {
			$data                          = get_post_meta( (int) $form_id, 'form-data', true );
			$result['require_login']       = $data['require_login'];
			$result['form_success_notice'] = $data['form_success_notice'];
			$result['form_error_notice']   = $data['form_error_notice'];
		}

		return $result;
	}

	/**
	 * Activate Theme
	 *
	 * @param object $request .
	 *
	 * @return WP_Rest.
	 */
	public function activate_theme( $request ) {
		$stylesheet = strtolower( $request->get_param( 'stylesheet' ) );

		$check_theme = wp_get_theme( $stylesheet );

		if ( $check_theme->exists() ) {
			switch_theme( $stylesheet );

			return array(
				'status' => 200,
			);
		}

		return null;
	}
}
