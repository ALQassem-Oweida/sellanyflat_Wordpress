<?php
/**
 * Style Generator class
 *
 * @author Jegstudio
 * @since 1.0.0
 * @package gutenverse
 */

namespace Gutenverse;

use Gutenverse\Style\Accordion;
use Gutenverse\Style\Accordions;
use Gutenverse\Style\Advanced_Heading;
use Gutenverse\Style\Animated_Text;
use Gutenverse\Style\Button;
use Gutenverse\Style\Buttons;
use Gutenverse\Style\Column;
use Gutenverse\Style\Divider;
use Gutenverse\Style\Form_Builder;
use Gutenverse\Style\Form_Input_Checkbox;
use Gutenverse\Style\Form_Input_Date;
use Gutenverse\Style\Form_Input_Email;
use Gutenverse\Style\Form_Input_Multiselect;
use Gutenverse\Style\Form_Input_Number;
use Gutenverse\Style\Form_Input_Radio;
use Gutenverse\Style\Form_Input_Select;
use Gutenverse\Style\Form_Input_Submit;
use Gutenverse\Style\Form_Input_Switch;
use Gutenverse\Style\Form_Input_Telp;
use Gutenverse\Style\Form_Input_Text;
use Gutenverse\Style\Form_Input_Textarea;
use Gutenverse\Style\Fun_Fact;
use Gutenverse\Style\Gallery;
use Gutenverse\Style\Google_Maps;
use Gutenverse\Style\Heading;
use Gutenverse\Style\Icon;
use Gutenverse\Style\Icon_Box;
use Gutenverse\Style\Icon_List;
use Gutenverse\Style\Icon_List_Item;
use Gutenverse\Style\Image;
use Gutenverse\Style\Image_Box;
use Gutenverse\Style\Logo_Slider;
use Gutenverse\Style\Nav_Menu;
use Gutenverse\Style\Post_Author;
use Gutenverse\Style\Post_Block;
use Gutenverse\Style\Post_Comment;
use Gutenverse\Style\Post_Date;
use Gutenverse\Style\Post_Excerpt;
use Gutenverse\Style\Post_Featured_Image;
use Gutenverse\Style\Post_List;
use Gutenverse\Style\Post_Terms;
use Gutenverse\Style\Post_Title;
use Gutenverse\Style\Post_Content;
use Gutenverse\Style\Progress_Bar;
use Gutenverse\Style\Section;
use Gutenverse\Style\Social_Icon;
use Gutenverse\Style\Social_Icons;
use Gutenverse\Style\Social_Share;
use Gutenverse\Style\Social_Share_Item;
use Gutenverse\Style\Spacer;
use Gutenverse\Style\Star_Rating;
use Gutenverse\Style\Style_Abstract;
use Gutenverse\Style\Tab;
use Gutenverse\Style\Tabs;
use Gutenverse\Style\Team;
use Gutenverse\Style\Testimonials;
use Gutenverse\Style\Text_Editor;
use Gutenverse\Style\Video;
use WP_Block_Patterns_Registry;

/**
 * Class Style Generator
 *
 * @package gutenverse
 */
class Style_Generator {
	/**
	 * Font Families
	 *
	 * @var array
	 */
	protected $font_families = array();

	/**
	 * Font Variables
	 *
	 * @var array
	 */
	protected $font_variables = array();

	/**
	 * Init constructor.
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'global_style_generator' ) );
		add_action( 'wp_head', array( $this, 'template_style_generator' ) );
		add_action( 'wp_head', array( $this, 'content_style_generator' ) );
		add_action( 'wp_head', array( $this, 'embeed_font_generator' ) );
	}


	/**
	 * Global Style Generator.
	 */
	public function global_style_generator() {
		$setting  = get_option( 'gutenverse-global-setting-style' );
		$variable = apply_filters( 'gutenverse_global_css', $setting );

		if ( ! empty( trim( $variable ) ) ) {
			gutenverse_print_header_style( 'gutenverse-global-css', $variable );
		}
	}

	/**
	 * Embeed Font on Header.
	 */
	public function embeed_font_generator() {
		$this->load_global_fonts();

		gutenverse_header_font( $this->font_families, $this->font_variables );
	}

	/**
	 * Callback function Flatten Blocks for lower version.
	 *
	 * @param blocks $blocks .
	 *
	 * @return blocks.
	 */
	public function flatten_blocks( $blocks ) {
		if ( is_gutenverse_compatible() ) {
			// use Gutenberg or WP 5.9 & above version.
			return _flatten_blocks( $blocks );
		}

		/**
		 * Below is the native functionality of "_flatten_blocks".
		 * Just to prevent fatal error if somehow user able to install this plugin on WP below 5.9.
		 */

		$all_blocks = array();
		$queue      = array();
		foreach ( $blocks as &$block ) {
			$queue[] = &$block;
		}

		while ( count( $queue ) > 0 ) {
			$block = &$queue[0];
			array_shift( $queue );
			$all_blocks[] = &$block;

			if ( ! empty( $block['innerBlocks'] ) ) {
				foreach ( $block['innerBlocks'] as &$inner_block ) {
					$queue[] = &$inner_block;
				}
			}
		}

		return $all_blocks;
	}

	/**
	 * Callback function for lower version.
	 *
	 * @param blocks $template_content .
	 *
	 * @return blocks.
	 */
	public function inject_theme_attribute_in_block_template_content( $template_content ) {
		if ( is_gutenverse_compatible() ) {
			// use Gutenberg or WP 5.9 & above version.
			return _inject_theme_attribute_in_block_template_content( $template_content );
		}

		/**
		 * Below is the native functionality of "_inject_theme_attribute_in_block_template_content".
		 * Just to prevent fatal error if somehow user able to install this plugin on WP below 5.9.
		 */

		$has_updated_content = false;
		$new_content         = '';
		$template_blocks     = parse_blocks( $template_content );

		$blocks = $this->flatten_blocks( $template_blocks );
		foreach ( $blocks as &$block ) {
			if (
				'core/template-part' === $block['blockName'] &&
				! isset( $block['attrs']['theme'] )
			) {
				$block['attrs']['theme'] = wp_get_theme()->get_stylesheet();
				$has_updated_content     = true;
			}
		}

		if ( $has_updated_content ) {
			foreach ( $template_blocks as &$block ) {
				$new_content .= serialize_block( $block );
			}

			return $new_content;
		}

		return $template_content;
	}

	/**
	 * Generate style for template.
	 */
	public function template_style_generator() {
		global $_wp_current_template_content;
		$style = null;

		if ( ! empty( $_wp_current_template_content ) ) {
			$blocks = $this->parse_blocks( $_wp_current_template_content );
			$blocks = $this->flatten_blocks( $blocks );
			$this->loop_blocks( $blocks, $style );
		}

		if ( ! empty( trim( $style ) ) ) {
			gutenverse_print_header_style( 'gutenverse-template-generator', $style );
		}
	}

	/**
	 * Content Style Generator.
	 */
	public function content_style_generator() {
		global $post;
		$style = null;

		if ( has_blocks( $post ) && isset( $post->post_content ) ) {
			$blocks = $this->parse_blocks( $post->post_content );
			$blocks = $this->flatten_blocks( $blocks );
			$this->loop_blocks( $blocks, $style );
		}

		if ( $style ) {
			gutenverse_print_header_style( 'gutenverse-content-generator', $style );
		}
	}

	/**
	 * Loop Block.
	 *
	 * @param array  $blocks Array of blocks.
	 * @param string $style Style string.
	 */
	public function loop_blocks( $blocks, &$style ) {
		foreach ( $blocks as $block ) {
			$this->generate_block_style( $block, $style );

			if ( 'core/template-part' === $block['blockName'] ) {
				$parts = $this->get_template_part_content( $block['attrs'] );
				$parts = parse_blocks( $parts );
				$parts = $this->flatten_blocks( $parts );
				$this->loop_blocks( $parts, $style );
			}

			if ( 'core/pattern' === $block['blockName'] ) {
				$parts = $this->get_pattern_content( $block['attrs'] );
				$parts = parse_blocks( $parts );
				$parts = $this->flatten_blocks( $parts );
				$this->loop_blocks( $parts, $style );
			}
		}
	}

	/**
	 * Generate Block Style.
	 *
	 * @param array  $block Detail of block.
	 * @param string $style Style string.
	 */
	public function generate_block_style( $block, &$style ) {
		$instance = $this->get_block_style_instance( $block['blockName'], $block['attrs'] );

		if ( ! is_null( $instance ) ) {
			$style    .= $instance->generate_style();
			$fonts     = $instance->get_fonts();
			$fonts_var = $instance->get_fonts_var();

			if ( ! empty( $fonts ) ) {
				$this->font_families = array_merge( $fonts, $this->font_families );
			}

			if ( ! empty( $fonts_var ) ) {
				$this->font_variables = array_merge( $fonts_var, $this->font_variables );

			}
		}
	}

	/**
	 * Get Template Part Content.
	 *
	 * @param array $attributes Attributes.
	 */
	public function get_template_part_content( $attributes ) {
		$template_part_id = null;
		$area             = WP_TEMPLATE_PART_AREA_UNCATEGORIZED;
		return gutenverse_template_part_content( $attributes, $template_part_id, $area );
	}

	/**
	 * Get Pattern Content.
	 *
	 * @param array $attributes Attributes.
	 */
	public function get_pattern_content( $attributes ) {
		$content = '';

		if ( isset( $attributes['slug'] ) ) {
			$block   = WP_Block_Patterns_Registry::get_instance()->get_registered( $attributes['slug'] );
			$content = isset( $block ) ? $block['content'] : $content;
		}

		return $content;
	}

	/**
	 * Get Block Style Instance.
	 *
	 * @param string $name Block Name.
	 * @param array  $attrs Block Attribute.
	 *
	 * @return Style_Abstract
	 */
	public function get_block_style_instance( $name, $attrs ) {
		$instance = null;

		switch ( $name ) {
			case 'gutenverse/accordion':
				$instance = new Accordion( $attrs );
				break;
			case 'gutenverse/accordions':
				$instance = new Accordions( $attrs );
				break;
			case 'gutenverse/advanced-heading':
				$instance = new Advanced_Heading( $attrs );
				break;
			case 'gutenverse/animated-text':
				$instance = new Animated_Text( $attrs );
				break;
			case 'gutenverse/logo-slider':
				$instance = new Logo_Slider( $attrs );
				break;
			case 'gutenverse/fun-fact':
				$instance = new Fun_Fact( $attrs );
				break;
			case 'gutenverse/section':
				$instance = new Section( $attrs );
				break;
			case 'gutenverse/column':
				$instance = new Column( $attrs );
				break;
			case 'gutenverse/heading':
				$instance = new Heading( $attrs );
				break;
			case 'gutenverse/divider':
				$instance = new Divider( $attrs );
				break;
			case 'gutenverse/tab':
				$instance = new Tab( $attrs );
				break;
			case 'gutenverse/tabs':
				$instance = new Tabs( $attrs );
				break;
			case 'gutenverse/video':
				$instance = new Video( $attrs );
				break;
			case 'gutenverse/button':
				$instance = new Button( $attrs );
				break;
			case 'gutenverse/buttons':
				$instance = new Buttons( $attrs );
				break;
			case 'gutenverse/google-maps':
				$instance = new Google_Maps( $attrs );
				break;
			case 'gutenverse/icon':
				$instance = new Icon( $attrs );
				break;
			case 'gutenverse/gallery':
				$instance = new Gallery( $attrs );
				break;
			case 'gutenverse/icon-box':
				$instance = new Icon_Box( $attrs );
				break;
			case 'gutenverse/icon-list':
				$instance = new Icon_List( $attrs );
				break;
			case 'gutenverse/icon-list-item':
				$instance = new Icon_List_Item( $attrs );
				break;
			case 'gutenverse/post-author':
				$instance = new Post_Author( $attrs );
				break;
			case 'gutenverse/post-comment':
				$instance = new Post_Comment( $attrs );
				break;
			case 'gutenverse/post-date':
				$instance = new Post_Date( $attrs );
				break;
			case 'gutenverse/post-excerpt':
				$instance = new Post_Excerpt( $attrs );
				break;
			case 'gutenverse/post-featured-image':
				$instance = new Post_Featured_Image( $attrs );
				break;
			case 'gutenverse/post-terms':
				$instance = new Post_Terms( $attrs );
				break;
			case 'gutenverse/post-title':
				$instance = new Post_Title( $attrs );
				break;
			case 'gutenverse/post-content':
				$instance = new Post_Content( $attrs );
				break;
			case 'gutenverse/post-block':
				$instance = new Post_Block( $attrs );
				break;
			case 'gutenverse/post-list':
				$instance = new Post_List( $attrs );
				break;
			case 'gutenverse/image':
				$instance = new Image( $attrs );
				break;
			case 'gutenverse/image-box':
				$instance = new Image_Box( $attrs );
				break;
			case 'gutenverse/testimonials':
				$instance = new Testimonials( $attrs );
				break;
			case 'gutenverse/nav-menu':
				$instance = new Nav_Menu( $attrs );
				break;
			case 'gutenverse/progress-bar':
				$instance = new Progress_Bar( $attrs );
				break;
			case 'gutenverse/social-icon':
				$instance = new Social_Icon( $attrs );
				break;
			case 'gutenverse/social-icons':
				$instance = new Social_Icons( $attrs );
				break;
			case 'gutenverse/spacer':
				$instance = new Spacer( $attrs );
				break;
			case 'gutenverse/star-rating':
				$instance = new Star_Rating( $attrs );
				break;
			case 'gutenverse/text-editor':
				$instance = new Text_Editor( $attrs );
				break;
			case 'gutenverse/team':
				$instance = new Team( $attrs );
				break;
			case 'gutenverse/social-share':
				$instance = new Social_Share( $attrs );
				break;
			case 'gutenverse/social-share-facebook':
			case 'gutenverse/social-share-twitter':
			case 'gutenverse/social-share-pinterest':
			case 'gutenverse/social-share-stumbleupon':
			case 'gutenverse/social-share-linkedin':
			case 'gutenverse/social-share-reddit':
			case 'gutenverse/social-share-tumblr':
			case 'gutenverse/social-share-vk':
			case 'gutenverse/social-share-whatsapp':
			case 'gutenverse/social-share-telegram':
			case 'gutenverse/social-share-wechat':
			case 'gutenverse/social-share-line':
			case 'gutenverse/social-share-email':
				$instance = new Social_Share_Item( $attrs );
				break;
			case 'gutenverse/form-builder':
				$instance = new Form_Builder( $attrs );
				break;
			case 'gutenverse/form-input-checkbox':
				$instance = new Form_Input_Checkbox( $attrs );
				break;
			case 'gutenverse/form-input-date':
				$instance = new Form_Input_Date( $attrs );
				break;
			case 'gutenverse/form-input-email':
				$instance = new Form_Input_Email( $attrs );
				break;
			case 'gutenverse/form-input-multiselect':
				$instance = new Form_Input_Multiselect( $attrs );
				break;
			case 'gutenverse/form-input-number':
				$instance = new Form_Input_Number( $attrs );
				break;
			case 'gutenverse/form-input-radio':
				$instance = new Form_Input_Radio( $attrs );
				break;
			case 'gutenverse/form-input-select':
				$instance = new Form_Input_Select( $attrs );
				break;
			case 'gutenverse/form-input-submit':
				$instance = new Form_Input_Submit( $attrs );
				break;
			case 'gutenverse/form-input-switch':
				$instance = new Form_Input_Switch( $attrs );
				break;
			case 'gutenverse/form-input-telp':
				$instance = new Form_Input_Telp( $attrs );
				break;
			case 'gutenverse/form-input-text':
				$instance = new Form_Input_Text( $attrs );
				break;
			case 'gutenverse/form-input-textarea':
				$instance = new Form_Input_Textarea( $attrs );
				break;
			default:
				$instance = null;
		}

		return $instance;
	}

	/**
	 * Loading fonts from global styles and variable
	 */
	public function load_global_fonts() {
		$variable_fonts = get_option( 'gutenverse-global-variable-font' );

		if ( ! empty( $variable_fonts ) ) {
			$this->font_families = array_merge( $variable_fonts, $this->font_families );
		}
	}

	/**
	 * Parse Guten Block.
	 *
	 * @param string $content the content string.
	 * @since 1.1.0
	 */
	public function parse_blocks( $content ) {
		global $wp_version;

		return ( version_compare( $wp_version, '5', '>=' ) ) ? parse_blocks( $content ) : parse_blocks( $content );
	}
}
