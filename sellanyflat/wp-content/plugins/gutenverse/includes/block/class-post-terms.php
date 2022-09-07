<?php
/**
 * Post Terms Block class
 *
 * @author Jegstudio
 * @since 1.0.0
 * @package gutenverse\block
 */

namespace Gutenverse\Block;

/**
 * Class Post Terms Block
 *
 * @package gutenverse\block
 */
class Post_Terms extends Block_Abstract {
	/**
	 * Render content
	 *
	 * @param int     $post_id .
	 * @param boolean $backend .
	 *
	 * @return string
	 */
	public function render_content( $post_id, $backend = false ) {
		$element_id = esc_html( $this->attributes['elementId'] );
		$html_tag   = esc_html( $this->attributes['htmlTag'] );
		$taxonomy   = esc_html( $this->attributes['taxonomy'] );
		$separator  = esc_html( $this->attributes['separator'] );
		$link_to    = $this->attributes['linkTo'];
		$post_id    = $post_id ? $post_id : get_the_ID();

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
			$term_list = get_the_terms( $post_id, $taxonomy );
			$content   = '';

			if ( ! empty( $term_list ) ) {
				$count = count( $term_list );

				$term = $term_list[0]->name;

				if ( 'term' === $link_to ) {
					$term = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_term_link( $term_list[0] ) ), $term );
				}

				$content .= sprintf( '<%1$s class="term-list">%2$s</%1$s>', $html_tag, $term );

				for ( $i = 1; $i < $count; $i++ ) {
					$term = $term_list[ $i ]->name;

					if ( 'term' === $link_to ) {
						$term = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_term_link( $term_list[ $i ] ) ), $term );
					}

					$content .= sprintf( '%1$s  <%2$s class="term-list">%3$s</%2$s>', $separator, $html_tag, $term );
				}

				if ( $backend ) {
					$content = $content . esc_html__( ' (example)', 'gutenverse' );
				}

				$content = sprintf( '<span class="%2$s guten-post-terms">%1$s</span>', $content, $element_id );

				return $content;
			}

			return "<span class='guten-post-terms'>example, category, and, terms</span>";
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

		return '<div class="' . $element_id . $display_classes . $animation_class . ' guten-post-terms guten-element">' . $this->render_content( $post_id ) . '</div>';
	}
}
