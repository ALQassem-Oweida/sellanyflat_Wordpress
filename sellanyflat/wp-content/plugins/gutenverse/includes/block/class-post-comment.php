<?php
/**
 * Post Comment Block class
 *
 * @author Jegstudio
 * @since 1.0.0
 * @package gutenverse\block
 */

namespace Gutenverse\Block;

/**
 * Class Post Comment Block
 *
 * @package gutenverse\block
 */
class Post_Comment extends Block_Abstract {
	/**
	 * $attributes, $content
	 *
	 * @param int     $post_id .
	 * @param boolean $backend .
	 *
	 * @return string
	 */
	public function render_content( $post_id, $backend = false ) {
		$post_id = $post_id ? $post_id : get_the_ID();

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
			$comments = get_comments(
				array(
					'post_id' => $post_id,
					'status'  => 'approve',
				)
			);

			$comment_list = wp_list_comments(
				array(
					'per_page'          => 10,
					'reverse_top_level' => false,
					'echo'              => false,
				),
				$comments
			);

			if ( ! empty( $comment_list ) ) {
				$comment_list = '<ol class="commentlist">' . $comment_list . '</ol>';
			}

			ob_start();
			comment_form( array(), $post_id );
			$content = ob_get_clean();

			return $comment_list . '<div class="comment-form">' . $content . '</div>';
		}

		return $this->empty_content();
	}

	/**
	 * Render view in editor
	 */
	public function render_gutenberg() {
		return '<div><span>Below is an example view of the post comment if user is not logged in.</span></div>
		<ol class="commentlist">
			<li id="comment-1" class="comment byuser comment-author-admin bypostauthor even thread-even depth-1">
				<article id="div-comment-2" class="comment-body">
					<footer class="comment-meta">
						<div class="comment-author vcard">
						<img alt="" src="http://2.gravatar.com/avatar/89bf62eef95ad7f15f6026f781b35dfa?s=32&amp;d=mm&amp;r=g" srcset="http://2.gravatar.com/avatar/89bf62eef95ad7f15f6026f781b35dfa?s=64&amp;d=mm&amp;r=g 2x" class="avatar avatar-32 photo" height="32" width="32" loading="lazy"/>
							<b class="fn"><a href="javascript:void(0);" rel="external nofollow ugc" class="url">User</a></b>
							<span class="says">says:</span>
						</div>
						<div class="comment-metadata">
							<a href="javascript:void(0);">
								<time datetime="2022-07-11T05:42:20+00:00">01 January 2022 at 9:00 am</time>
							</a>
						</div>
					</footer>
					<div class="comment-content">
						<p>This is my first comment</p>
					</div>
					<div class="reply">
						<a rel="nofollow" class="comment-reply-link" href="javascript:void(0);" data-commentid="2" data-postid="1" data-belowelement="div-comment-2" data-respondelement="respond" data-replyto="Reply to admin" aria-label="Reply to admin">Reply</a>
					</div>
				</article>
			</li>
		</ol>
		<div class="comment-form">
			<div id="respond" class="comment-respond">
				<h3 id="reply-title" class="comment-reply-title">Leave a Reply <small><a rel="nofollow" id="cancel-comment-reply-link" href="javascript:void(0);" style="display:none;">Cancel reply</a></small></h3>
				<form>
					<p class="comment-notes">
						<span id="email-notes">Your email address will not be published.</span>
						<span class="required-field-message" aria-hidden="true">Required fields are marked
							<span class="required" aria-hidden="true">*</span>
						</span>
					</p>
					<p class="comment-form-comment">
						<label for="comment">Comment 
							<span class="required" aria-hidden="true">*</span>
						</label>
						<textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required=""></textarea>
					</p>
					<p class="comment-form-author">
						<label for="author">Name
							<span class="required" aria-hidden="true">*</span>
						</label>
						<input id="author" name="author" type="text" value="" size="30" maxlength="245" required=""/>
					</p>
					<p class="comment-form-email">
						<label for="email">Email
							<span class="required" aria-hidden="true">*</span>
						</label>
						<input id="email" name="email" type="email" value="" size="30" maxlength="100" aria-describedby="email-notes" required=""/>
					</p>
					<p class="comment-form-url">
						<label for="url">Website</label>
						<input id="url" name="url" type="url" value="" size="30" maxlength="200"/>
					</p>
					<p class="comment-form-cookies-consent">
						<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"/>
						<label for="wp-comment-cookies-consent">Save my name, email, and website in this browser for the next time I comment.</label>
					</p>
					<p class="form-submit wp-block-button">
						<input name="submit" type="submit" id="submit" value="Post Comment"/>
						<input type="hidden" name="comment_post_ID" value="1" id="comment_post_ID"/>
						<input type="hidden" name="comment_parent" id="comment_parent" value="0"/>
					</p>
				</form>
			</div>
		</div>';
	}

	/**
	 * Render view in frontend
	 */
	public function render_frontend() {
		$element_id      = $this->attributes['elementId'];
		$post_id         = esc_html( $this->attributes['postId'] );
		$display_classes = $this->set_display_classes();
		$animation_class = $this->set_animation_classes();

		return '<div class="' . $element_id . $display_classes . $animation_class . ' guten-post-comment guten-element">' . $this->render_content( $post_id ) . '</div>';
	}
}
