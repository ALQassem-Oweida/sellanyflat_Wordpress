<?php
/**
 * Post Featured Image Block class
 *
 * @author Jegstudio
 * @since 1.0.0
 * @package gutenverse\block
 */

namespace Gutenverse\Block;

/**
 * Class Post Featured Image Block
 *
 * @package gutenverse\block
 */
class Post_Featured_Image extends Block_Abstract {
	/**
	 * Render content
	 *
	 * @param int     $post_id .
	 * @param boolean $backend .
	 *
	 * @return string
	 */
	public function render_content( $post_id, $backend = false ) {
		$element_id      = esc_html( $this->attributes['elementId'] );
		$link_to         = esc_html( $this->attributes['linkTo'] );
		$post_id         = $post_id ? $post_id : get_the_ID();
		$display_classes = $this->set_display_classes();
		$animation_class = $this->set_animation_classes();

		if ( ! empty( $post_id ) ) {
			$post_featured = get_the_post_thumbnail_url( $post_id, 'full' );

			if ( ! empty( $post_featured ) ) {
				switch ( $link_to ) {
					case 'home':
						$home_url = get_home_url();
						$content  = '<a href="' . $home_url . '" class="' . $element_id . $display_classes . $animation_class . ' guten-post-featured-image"><img src="' . $post_featured . '"/></a>';
						break;
					case 'post':
						$post_url = get_post_permalink( $post_id );
						$content  = '<a href="' . $post_url . '" class="' . $element_id . $display_classes . $animation_class . ' guten-post-featured-image"><img src="' . $post_featured . '"/></a>';
						break;
					case 'custom':
						$custom_url = esc_html( $this->attributes['customURL'] );
						$content    = '<a href="' . $custom_url . '" class="' . $element_id . $display_classes . $animation_class . ' guten-post-featured-image"><img src="' . $post_featured . '"/></a>';
						break;
					case 'media':
						$content = '<a href="' . $post_featured . '" class="' . $element_id . $display_classes . $animation_class . ' guten-post-featured-image"><img src="' . $post_featured . '"/></a>';
						break;
					default:
						$content = '<div class="' . $element_id . $display_classes . $animation_class . ' guten-post-featured-image"><img src="' . $post_featured . '"/></div>';
						break;
				}

				return $content;
			}
		}

		return $this->empty_content();
	}

	/**
	 * Render view in editor
	 */
	public function render_gutenberg() {
		$element_id = esc_html( $this->attributes['elementId'] );
		$post_id    = ! empty( $this->attributes['backendPostId'] ) ? esc_html( $this->attributes['backendPostId'] ) : false;

		if ( ! empty( $post_id ) ) {
			$post_featured = get_the_post_thumbnail_url( $post_id, 'full' );

			if ( ! empty( $post_featured ) ) {

				return '<div class="' . $element_id . ' guten-post-featured-image guten-element"><img src="' . $post_featured . '"/></div>';
			}
		}

		return '<div class="' . $element_id . ' guten-post-featured-image guten-element"><img src="' . esc_url( GUTENVERSE_URL . '/assets/img/img-placeholder.jpg' ) . '"/></div>';
	}

	/**
	 * Render view in frontend
	 */
	public function render_frontend() {
		$post_id = esc_html( $this->attributes['postId'] );

		return $this->render_content( $post_id );
	}
}
