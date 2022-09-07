<?php
/**
 * Post Date Block class
 *
 * @author Jegstudio
 * @since 1.0.0
 * @package gutenverse\block
 */

namespace Gutenverse\Block;

/**
 * Class Post Date Block
 *
 * @package gutenverse\block
 */
class Post_Date extends Block_Abstract {
	/**
	 * Render content
	 *
	 * @param int     $post_id .
	 * @param boolean $backend .
	 *
	 * @return string
	 */
	public function render_content( $post_id, $backend = false ) {
		$type          = esc_html( $this->attributes['dateType'] );
		$format        = esc_html( $this->attributes['dateFormat'] );
		$custom_format = esc_html( $this->attributes['customFormat'] );
		$html_tag      = esc_html( $this->attributes['htmlTag'] );
		$link_to       = esc_html( $this->attributes['linkTo'] );
		$post_id       = $post_id ? $post_id : get_the_ID();

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
			if ( 'both' === $type ) {
				$date = gutenverse_get_post_date( $post_id, $format, 'published', $custom_format );
				$date = $date . esc_html__( ' - Updated on ', 'gutenverse' );
				$date = $date . gutenverse_get_post_date( $post_id, $format, 'modified', $custom_format );
			} else {
				$date = gutenverse_get_post_date( $post_id, $format, $type, $custom_format );
			}

			if ( ! empty( $date ) ) {
				switch ( $link_to ) {
					case 'home':
						$home_url = get_home_url();
						$date     = "<a href='{$home_url}'>{$date}</a>";
						break;
					case 'post':
						$post_url = get_post_permalink( $post_id );
						$date     = "<a href='{$post_url}'>{$date}</a>";
						break;
					case 'custom':
						$custom_url = esc_html( $this->attributes['customURL'] );
						$date       = "<a href='{$custom_url}'>{$date}</a>";
						break;
					default:
						break;
				}

				if ( $backend ) {
					$date = $date . esc_html__( ' (example)', 'gutenverse' );
				}

				return "<{$html_tag}>{$date}</{$html_tag}>";
			}
		}

		return $this->empty_content();
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

		return '<div class="' . $element_id . $display_classes . $animation_class . ' guten-post-date guten-element">' . $this->render_content( $post_id ) . '</div>';
	}
}
