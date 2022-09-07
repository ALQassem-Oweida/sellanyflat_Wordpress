<?php
/**
 * Post Content Block class
 *
 * @author Jegstudio
 * @since 1.0.0
 * @package gutenverse\block
 */

namespace Gutenverse\Block;

/**
 * Class Post Content Block
 *
 * @package gutenverse\block
 */
class Post_Content extends Block_Abstract {
	/**
	 * Render content
	 *
	 * @param int     $post_id .
	 * @param boolean $backend .
	 *
	 * @return string
	 */
	public function render_content( $post_id, $backend = false ) {
		$post_content = $post_id ? get_post( $post_id ) : get_post( get_the_ID() );
		$content      = ! empty( $post_content->post_content ) ? $post_content->post_content : '';

		if ( $backend ) {
			return esc_html__( 'This will be your post\'s content block, it will display all the blocks in any single post or page.', 'gutenverse' );
		}

		$blocks  = parse_blocks( $content );
		$content = '';

		if ( ! empty( $blocks ) ) {
			foreach ( $blocks as $block ) {
				$content = $content . render_block( $block );
			}
		}

		return $content;
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

		return '<div class="' . $element_id . $display_classes . $animation_class . ' guten-post-content guten-element">' . $this->render_content( $post_id ) . '</div>';
	}
}
