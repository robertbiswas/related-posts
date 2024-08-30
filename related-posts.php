<?php
/*
* Plugin Name:       Related-Posts
* Plugin URI:        https://robertbiswas.com/related-posts-plugin
* Description:       It shows related posts based on current post's category.
* Version:           1.0.0
* Requires at least: 6.0.0
* Requires PHP:      7.4
* Author:            Robert Biswas
* Author URI:        https://robertbiswas.com
* License:           GPL v2 or later
* License URI:       https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain:       rbrp
*/

if ( ! defined( 'ABSPATH' ) ) {
exit;
}

class RBRP_Related_Posts {
	static $instance;
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct(){
		add_action( 'init', array( $this, 'init') );
		add_action( 'the_content', array( $this, 'display_related_posts' ) );
		add_action('admin_menu', array( $this, 'related_posts_setting_page' ) );
		add_action( 'admin_post_related_posts_settings_action', array( $this, 'save_plugin_setting' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'related_post_setting_link' ) );
	}

	/**
	 * Manages Constants and initialization of this plugins.
	 */
	public function init(){
		// Determine plugin version and setting up Constants
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		$plugin_data = get_plugin_data( __FILE__ );
		if ( ! defined( 'PLUGIN_VERSION' ) ) {
			define( 'PLUGIN_VERSION', $plugin_data['Version'] );
		}
		
		// Set plugin assets path
		define( 'PLUGIN_ASSETS', plugins_url( 'assets/', __FILE__ ) );

		// Enqueue styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue_management' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_management' ) );
	}

	/**
	 * The Callback method of the "the_content" Hook
	 * It query related posts of the current loop post based on it's categories
	 * 
	 * It receive the content passed by hook.
	 * Return original content with related post list if any
	 */
	public function display_related_posts($content) {
		if ( is_single() ){
			$max_post_number = get_option('related_posts_max_posts') ? get_option('related_posts_max_posts') : 5;
			$template_style = get_option('related_posts_style') ? get_option('related_posts_style') : 'style_vertical_list';

			$post_id = get_the_ID();
			$categories = get_the_terms( $post_id, 'category' );
			
			if ( $categories ) {
				$category_ids_array = wp_list_pluck( $categories, 'term_id' );
				$args = array(
					'post_type'      => 'post',
					'post_status'    => 'publish',
					'post__not_in'   => array($post_id),
					'category__in'   => $category_ids_array,
					'posts_per_page' => $max_post_number,
					'orderby'        => 'rand',
				);
			
				$query = new WP_Query($args);
				if ( $query->have_posts() ){
					$content .= '<div class="related-posts-wrapper">';
					$content .= '<h3>'. esc_html( __( 'Related Posts:', 'rb-related-posts' ) ) . '</h3>';
					
					if( 'style_vertical_list' === $template_style ) :
						$content .= '<div class="related-posts-list">';
					endif;

					if( 'style_carousel' === $template_style ) :
						$content .= '<div class="related-posts-carousel splide" aria-label="Related Post Carousel"><div class="splide__track"><ul class="splide__list">';
					endif;

					ob_start();
					while ( $query->have_posts() ){
						$query->the_post();
						if ( 'style_vertical_list' === $template_style ){
							include __DIR__ . '/includes/templates/related-posts-template.php';
						} else {
							include __DIR__ . '/includes/templates/related-posts-carousel-template.php';
						}
					}
					$related_list = ob_get_contents();
					ob_end_clean();
					$content .= $related_list;

					// Adding closing tags.
					if( 'style_vertical_list' === $template_style ) :
						$content .= '</div></div>';
					endif;
					if( 'style_carousel' === $template_style ) :
						$content .= '</div></div></ul></div>';
					endif;
				}
				return $content;
			}
		}
	}

	/**
	 * Manage all CSS and Script's registration and Enqueue
	 */
	public function frontend_enqueue_management(){
		if ( is_single() ){
			if ( 'style_carousel' == get_option('related_posts_style')){
				wp_enqueue_style( 'splide-style', plugins_url( 'assets/css/splide-core.min.css', __FILE__ ), array(), PLUGIN_VERSION );
				wp_enqueue_style( 'related-post-carousel-style', plugins_url( 'assets/css/related-post-carousel.css', __FILE__ ), array(), PLUGIN_VERSION );
				wp_enqueue_script('splide-js', PLUGIN_ASSETS . 'js/splide.min.js', [], PLUGIN_VERSION, true);
				wp_enqueue_script('related-posts-carousel-js', PLUGIN_ASSETS . 'js/related-post-carousel.js', ['splide-js'], PLUGIN_VERSION, true);
			} else {
				wp_enqueue_style( 'related-posts-style', plugins_url( 'assets/css/related-post.css', __FILE__ ), array(), PLUGIN_VERSION );
			}
		}
	}

	/**
	 * Enqueue Admin Styles & Scripts only for 
	 * this plugin's admin page.
	 */
	public function admin_enqueue_management( $hook ){
		if ( 'toplevel_page_related-posts-settings' == $hook ){

			// Styles enqueue
			wp_enqueue_style( 'rangeslider-css', PLUGIN_ASSETS . 'css/rangeslider.css', [], PLUGIN_VERSION );
			wp_enqueue_style( 'rbrp-admin-related-posts', PLUGIN_ASSETS . 'css/admin-related-post.css', [], PLUGIN_VERSION );

			// Scripts enqueue
			wp_enqueue_script('rangeslider-js', PLUGIN_ASSETS . 'js/rangeslider.min.js', ['jquery'], PLUGIN_VERSION, true);
			wp_enqueue_script('rbrp-admin-js', PLUGIN_ASSETS . 'js/rbrp-admin.js', ['rangeslider-js'], PLUGIN_VERSION, true);
		}
	}

	/**
	 * Create the post categories list.
	 * Receive a Post's ID
	 * Return a html markup of category 
	 */
	public function get_category_list($post_id){
		$categories = get_the_terms( $post_id, 'category');
		$cats = '';
		foreach( $categories as $single_cat ){
			$cats .= '<span>'. $single_cat->name .'</span>';
		}
		return $cats;
	}

	/**
	 * Creating Setting Page for Related Posts
	 */
	public function related_posts_setting_page(){
		add_menu_page(
			'Settings for Related Posts',
			'Related Posts',
			'manage_options',
			'related-posts-settings',
			array( $this, 'related_posts_settings' ),
			'dashicons-grid-view'
		);
	}

	/**
	 * Adding "Setting" link to the Plugin page.
	 */
	public function related_post_setting_link($links){
		$setting_link = sprintf( "<a href='%s'>%s</a>", 'admin.php?page=related-posts-settings', 'Settings' );
		$links[] = $setting_link;
		return $links;
	}

	/**
	 * Setup of Plugin Setting page's 
	 * form and fields.
	 */
	public function related_posts_settings(){
		include_once __DIR__ . '/includes/settings-form.php';
	}

	/**
	 * Saving Plugin Setting Options.
	 */
	public function save_plugin_setting(){
		check_admin_referer('rbrp_form');
		if( isset( $_POST['max-posts'])){
			update_option( 'related_posts_max_posts', (int) $_POST['max-posts'] );
		}
		if( isset( $_POST['rp-template'])){
			update_option( 'related_posts_style', sanitize_text_field($_POST['rp-template'])  );
		}
		wp_redirect('admin.php?page=related-posts-settings');
	}
}

RBRP_Related_Posts::get_instance();