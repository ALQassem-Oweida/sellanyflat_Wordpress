<?php
/**
 * Post Author Block class
 *
 * @author Jegstudio
 * @since 1.0.0
 * @package gutenverse\block
 */

namespace Gutenverse\Block;

/**
 * Class Post Author Block
 *
 * @package gutenverse\block
 */
class Post_Author extends Block_Abstract {
	/**
	 * Render content
	 *
	 * @param int     $post_id .
	 * @param boolean $backend .
	 *
	 * @return string
	 */
	public function render_content( $post_id, $backend = false ) {
		$type     = esc_html( $this->attributes['authorType'] );
		$html_tag = esc_html( $this->attributes['htmlTag'] );
		$link_to  = esc_html( $this->attributes['linkTo'] );
		$post_id  = $post_id ? $post_id : get_the_ID();

		if ( $backend ) {
			$post_ids = get_posts(
				array(
					'numberposts' => 1,
					'fields'      => 'ids',
				)
			);
			$post_id  = $post_ids[0];
		}

		if ( ! empty( $post_id ) ) {
			$post = get_post( $post_id );

			if ( ! empty( $post ) ) {
				$author = $this->get_author( $post, $type );

				if ( ! empty( $author ) ) {
					switch ( $link_to ) {
						case 'home':
							$home_url = get_home_url();
							$author   = "<a href='{$home_url}'>{$author}</a>";
							break;
						case 'post':
							$post_url = get_post_permalink( $post_id );
							$author   = "<a href='{$post_url}'>{$author}</a>";
							break;
						case 'author':
							$author_url = get_author_posts_url( $post->post_author );
							$author     = "<a href='{$author_url}'>{$author}</a>";
							break;
						case 'custom':
							$custom_url = esc_html( $this->attributes['customURL'] );
							$author     = "<a href='{$custom_url}'>{$author}</a>";
							break;
						default:
							break;
					}

					if ( $backend && 'user_image' !== $type ) {
						$author = $author . esc_html__( ' (example)', 'gutenverse' );
					}

					return "<{$html_tag}>{$author}</{$html_tag}>";
				}
			}
		}

		return $this->empty_content();
	}

	/**
	 * Get author for current post
	 *
	 * @param \WP_Post $post Post object.
	 * @param display  $type string.
	 *
	 * @return string
	 */
	private function get_author( $post, $type = 'display_name' ) {
		$author = '';

		switch ( $type ) {
			case 'first_name':
				$author = get_the_author_meta( 'first_name', $post->post_author );
				break;
			case 'last_name':
				$author = get_the_author_meta( 'last_name', $post->post_author );
				break;
			case 'first_last':
				$author = sprintf( '%s %s', get_the_author_meta( 'first_name', $post->post_author ), get_the_author_meta( 'last_name', $post->post_author ) );
				break;
			case 'last_first':
				$author = sprintf( '%s %s', get_the_author_meta( 'last_name', $post->post_author ), get_the_author_meta( 'first_name', $post->post_author ) );
				break;
			case 'nick_name':
				$author = get_the_author_meta( 'nickname', $post->post_author );
				break;
			case 'display_name':
				$author = get_the_author_meta( 'display_name', $post->post_author );
				break;
			case 'user_name':
				$author = get_the_author_meta( 'user_login', $post->post_author );
				break;
			case 'user_bio':
				$author = get_the_author_meta( 'description', $post->post_author );
				break;
			case 'user_image':
				$author = get_avatar( get_the_author_meta( 'email', $post->post_author ), 256 );
				break;
		}

		return $author;
	}

	/**
	 * Render view in editor
	 */
	public function render_gutenberg() {
		$post_id = ! empty( $this->attributes['backendPostId'] ) ? esc_html( $this->attributes['backendPostId'] ) : false;
		return $this->render_content( $post_id, true );
	}

	/**
	 * Render view in frontend
	 */
	public function render_frontend() {
		$element_id      = $this->attributes['elementId'];
		$post_id         = esc_html( $this->attributes['postId'] );
		$display_classes = $this->set_display_classes();
		$animation_class = $this->set_animation_classes();

		return '<div class="' . $element_id . $display_classes . $animation_class . ' guten-post-author guten-element">' . $this->render_content( $post_id ) . '</div>';
	}
}
