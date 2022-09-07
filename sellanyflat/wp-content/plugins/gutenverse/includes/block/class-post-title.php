<?php
/**
 * Post Title Block class
 *
 * @author Jegstudio
 * @since 1.0.0
 * @package gutenverse\block
 */

namespace Gutenverse\Block;

/**
 * Class Post Title Block
 *
 * @package gutenverse\block
 */
class Post_Title extends Block_Abstract {
	/**
	 * Render content
	 *
	 * @param int     $post_id .
	 * @param boolean $backend .
	 *
	 * @return string
	 */
	public function render_content( $post_id, $backend = false ) {
		$html_tag   = esc_html( $this->attributes['htmlTag'] );
		$link_to    = $this->attributes['linkTo'];
		$post_title = $post_id ? get_the_title( $post_id ) : get_the_title();

		switch ( $link_to ) {
			case 'home':
				$home_url   = get_home_url();
				$post_title = "<a href='{$home_url}'>{$post_title}</a>";
				break;
			case 'post':
				$post_url   = get_post_permalink( $post_id );
				$post_title = "<a href='{$post_url}'>{$post_title}</a>";
				break;
			case 'custom':
				$custom_url = esc_html( $this->attributes['customURL'] );
				$post_title = "<a href='{$custom_url}'>{$post_title}</a>";
				break;
			default:
				break;
		}

		if ( $backend ) {
			$post_title = esc_html__( 'Post Title', 'gutenverse' );
		}

		return "<{$html_tag}>{$post_title}</{$html_tag}>";
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

		return '<div class="' . $element_id . $display_classes . $animation_class . ' guten-post-title guten-element">' . $this->render_content( $post_id ) . '</div>';
	}
}
