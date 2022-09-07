<?php
/**
 * Post Excerpt Block class
 *
 * @author Jegstudio
 * @since 1.0.0
 * @package gutenverse\block
 */

namespace Gutenverse\Block;

/**
 * Class Post Excerpt Block
 *
 * @package gutenverse\block
 */
class Post_Excerpt extends Block_Abstract {
	/**
	 * Render content
	 *
	 * @param int     $post_id .
	 * @param boolean $backend .
	 *
	 * @return string
	 */
	public function render_content( $post_id, $backend = false ) {
		$html_tag = esc_html( $this->attributes['htmlTag'] );
		$link_to  = esc_html( $this->attributes['linkTo'] );
		$post_id  = $post_id ? $post_id : get_the_ID();

		if ( $backend ) {
			$post_excerpt = esc_html__( 'This will be your post\'s excerpt, it will display the text summary of any single post or page.', 'gutenverse' );
			return "<{$html_tag}>{$post_excerpt}</{$html_tag}>";
		}

		if ( ! empty( $post_id ) ) {
			$post_excerpt = get_the_excerpt( $post_id );

			if ( ! empty( $post_excerpt ) ) {
				switch ( $link_to ) {
					case 'home':
						$home_url     = get_home_url();
						$post_excerpt = "<a href='{$home_url}'>{$post_excerpt}</a>";
						break;
					case 'post':
						$post_url     = get_post_permalink( $post_id );
						$post_excerpt = "<a href='{$post_url}'>{$post_excerpt}</a>";
						break;
					case 'custom':
						$custom_url   = esc_html( $this->attributes['customURL'] );
						$post_excerpt = "<a href='{$custom_url}'>{$post_excerpt}</a>";
						break;
					default:
						break;
				}

				return "<{$html_tag}>{$post_excerpt}</{$html_tag}>";
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

		return '<div class="' . $element_id . $display_classes . $animation_class . ' guten-post-excerpt guten-element">' . $this->render_content( $post_id ) . '</div>';
	}
}
