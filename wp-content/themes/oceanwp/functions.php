<?php
/**
 * Theme functions and definitions.
 *
 * Sets up the theme and provides some helper functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 *
 * For more information on hooks, actions, and filters,
 * see http://codex.wordpress.org/Plugin_API
 *
 * @package OceanWP WordPress theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Core Constants
define( 'OCEANWP_THEME_DIR', get_template_directory() );
define( 'OCEANWP_THEME_URI', get_template_directory_uri() );

final class OCEANWP_Theme_Class {

	/**
	 * Main Theme Class Constructor
	 *
	 * @since   1.0.0
	 */
	public function __construct() {

		// Define constants
		add_action( 'after_setup_theme', array( 'OCEANWP_Theme_Class', 'constants' ), 0 );

		// Load all core theme function files
		add_action( 'after_setup_theme', array( 'OCEANWP_Theme_Class', 'include_functions' ), 1 );

		// Load configuration classes
		add_action( 'after_setup_theme', array( 'OCEANWP_Theme_Class', 'configs' ), 3 );

		// Load framework classes
		add_action( 'after_setup_theme', array( 'OCEANWP_Theme_Class', 'classes' ), 4 );

		// Setup theme => add_theme_support, register_nav_menus, load_theme_textdomain, etc
		add_action( 'after_setup_theme', array( 'OCEANWP_Theme_Class', 'theme_setup' ), 10 );

		// Setup theme => Generate the custom CSS file
		add_action( 'admin_bar_init', array( 'OCEANWP_Theme_Class', 'save_customizer_css_in_file' ), 9999 );

		// register sidebar widget areas
		add_action( 'widgets_init', array( 'OCEANWP_Theme_Class', 'register_sidebars' ) );

		// Registers theme_mod strings into Polylang
		if ( class_exists( 'Polylang' ) ) {
			add_action( 'after_setup_theme', array( 'OCEANWP_Theme_Class', 'polylang_register_string' ) );
		}

		/** Admin only actions **/
		if ( is_admin() ) {

			// Load scripts in the WP admin
			add_action( 'admin_enqueue_scripts', array( 'OCEANWP_Theme_Class', 'admin_scripts' ) );

			// Outputs custom CSS for the admin
			add_action( 'admin_head', array( 'OCEANWP_Theme_Class', 'admin_inline_css' ) );

		/** Non Admin actions **/
		} else {

			// Load theme CSS
			add_action( 'wp_enqueue_scripts', array( 'OCEANWP_Theme_Class', 'theme_css' ) );

			// Load his file in last
			add_action( 'wp_enqueue_scripts', array( 'OCEANWP_Theme_Class', 'custom_style_css' ), 9999 );

			// Remove Customizer CSS script from Front-end
			add_action( 'init', array( 'OCEANWP_Theme_Class', 'remove_customizer_custom_css' ) );

			// Load theme js
			add_action( 'wp_enqueue_scripts', array( 'OCEANWP_Theme_Class', 'theme_js' ) );

			// Add a pingback url auto-discovery header for singularly identifiable articles
			add_action( 'wp_head', array( 'OCEANWP_Theme_Class', 'pingback_header' ), 1 );

			// Add meta viewport tag to header
			add_action( 'wp_head', array( 'OCEANWP_Theme_Class', 'meta_viewport' ), 1 );

			// Add an X-UA-Compatible header
			add_filter( 'wp_headers', array( 'OCEANWP_Theme_Class', 'x_ua_compatible_headers' ) );

			// Loads html5 shiv script
			add_action( 'wp_head', array( 'OCEANWP_Theme_Class', 'html5_shiv' ) );

			// Outputs custom CSS to the head
			add_action( 'wp_head', array( 'OCEANWP_Theme_Class', 'custom_css' ), 9999 );

			// Minify the WP custom CSS because WordPress doesn't do it by default
			add_filter( 'wp_get_custom_css', array( 'OCEANWP_Theme_Class', 'minify_custom_css' ) );

			// Alter the search posts per page
			add_action( 'pre_get_posts', array( 'OCEANWP_Theme_Class', 'search_posts_per_page' ) );

			// Alter WP categories widget to display count inside a span
			add_filter( 'wp_list_categories', array( 'OCEANWP_Theme_Class', 'wp_list_categories_args' ) );

			// Add a responsive wrapper to the WordPress oembed output
			add_filter( 'embed_oembed_html', array( 'OCEANWP_Theme_Class', 'add_responsive_wrap_to_oembeds' ), 99, 4 );

			// Adds classes the post class
			add_filter( 'post_class', array( 'OCEANWP_Theme_Class', 'post_class' ) );

			// Add schema markup to the authors post link
			add_filter( 'the_author_posts_link', array( 'OCEANWP_Theme_Class', 'the_author_posts_link' ) );

			// Add support for Elementor Pro locations
			add_action( 'elementor/theme/register_locations', array( 'OCEANWP_Theme_Class', 'register_elementor_locations' ) );

			// Remove the default lightbox script for the beaver builder plugin
			add_filter( 'fl_builder_override_lightbox', array( 'OCEANWP_Theme_Class', 'remove_bb_lightbox' ) );

		}

	}

	/**
	 * Define Constants
	 *
	 * @since   1.0.0
	 */
	public static function constants() {

		$version = self::theme_version();

		// Theme version
		define( 'OCEANWP_THEME_VERSION', $version );

		// Javascript and CSS Paths
		define( 'OCEANWP_JS_DIR_URI', OCEANWP_THEME_URI .'/assets/js/' );
		define( 'OCEANWP_CSS_DIR_URI', OCEANWP_THEME_URI .'/assets/css/' );

		// Include Paths
		define( 'OCEANWP_INC_DIR', OCEANWP_THEME_DIR .'/inc/' );
		define( 'OCEANWP_INC_DIR_URI', OCEANWP_THEME_URI .'/inc/' );

		// Check if plugins are active
		define( 'OCEAN_EXTRA_ACTIVE', class_exists( 'Ocean_Extra' ) );
		define( 'OCEANWP_ELEMENTOR_ACTIVE', class_exists( 'Elementor\Plugin' ) );
		define( 'OCEANWP_BEAVER_BUILDER_ACTIVE', class_exists( 'FLBuilder' ) );
		define( 'OCEANWP_WOOCOMMERCE_ACTIVE', class_exists( 'WooCommerce' ) );
		define( 'OCEANWP_EDD_ACTIVE', class_exists( 'Easy_Digital_Downloads' ) );
		define( 'OCEANWP_LIFTERLMS_ACTIVE', class_exists( 'LifterLMS' ) );
		define( 'OCEANWP_ALNP_ACTIVE', class_exists( 'Auto_Load_Next_Post' ) );
		define( 'OCEANWP_LEARNDASH_ACTIVE', class_exists( 'SFWD_LMS' ) );
	}

	/**
	 * Load all core theme function files
	 *
	 * @since 1.0.0
	 */
	public static function include_functions() {
		$dir = OCEANWP_INC_DIR;
		require_once ( $dir .'helpers.php' );
		require_once ( $dir .'header-content.php' );
		require_once ( $dir .'customizer/controls/typography/webfonts.php' );
		require_once ( $dir .'walker/init.php' );
		require_once ( $dir .'walker/menu-walker.php' );
		require_once ( $dir .'third/class-gutenberg.php' );
		require_once ( $dir .'third/class-elementor.php' );
		require_once ( $dir .'third/class-beaver-themer.php' );
		require_once ( $dir .'third/class-bbpress.php' );
		require_once ( $dir .'third/class-buddypress.php' );
		require_once ( $dir .'third/class-lifterlms.php' );
		require_once ( $dir .'third/class-learndash.php' );
		require_once ( $dir .'third/class-sensei.php' );
		require_once ( $dir .'third/class-social-login.php' );
	}

	/**
	 * Configs for 3rd party plugins.
	 *
	 * @since 1.0.0
	 */
	public static function configs() {

		$dir = OCEANWP_INC_DIR;

		// WooCommerce
		if ( OCEANWP_WOOCOMMERCE_ACTIVE ) {
			require_once ( $dir .'woocommerce/woocommerce-config.php' );
		}

		// Easy Digital Downloads
		if ( OCEANWP_EDD_ACTIVE ) {
			require_once ( $dir .'edd/edd-config.php' );
		}
	}

	/**
	 * Returns current theme version
	 *
	 * @since   1.0.0
	 */
	public static function theme_version() {

		// Get theme data
		$theme = wp_get_theme();

		// Return theme version
		return $theme->get( 'Version' );

	}

	/**
	 * Load theme classes
	 *
	 * @since   1.0.0
	 */
	public static function classes() {

		// Admin only classes
		if ( is_admin() ) {

			// Recommend plugins
			require_once( OCEANWP_INC_DIR .'plugins/class-tgm-plugin-activation.php' );
			require_once( OCEANWP_INC_DIR .'plugins/tgm-plugin-activation.php' );

		}

		// Front-end classes
		else {

			// Breadcrumbs class
			require_once( OCEANWP_INC_DIR .'breadcrumbs.php' );

		}

		// Customizer class
		require_once( OCEANWP_INC_DIR .'customizer/customizer.php' );

	}

	/**
	 * Theme Setup
	 *
	 * @since   1.0.0
	 */
	public static function theme_setup() {

		// Load text domain
		load_theme_textdomain( 'oceanwp', OCEANWP_THEME_DIR .'/languages' );

		// Get globals
		global $content_width;

		// Set content width based on theme's default design
		if ( ! isset( $content_width ) ) {
			$content_width = 1200;
		}

		// Register navigation menus
		register_nav_menus( array(
			'topbar_menu'     => esc_html__( 'Top Bar', 'oceanwp' ),
			'main_menu'       => esc_html__( 'Main', 'oceanwp' ),
			'footer_menu'     => esc_html__( 'Footer', 'oceanwp' ),
			'mobile_menu'     => esc_html__( 'Mobile (optional)', 'oceanwp' )
		) );

		// Enable support for Post Formats
		add_theme_support( 'post-formats', array( 'video', 'gallery', 'audio', 'quote', 'link' ) );

		// Enable support for <title> tag
		add_theme_support( 'title-tag' );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Enable support for Post Thumbnails on posts and pages
		add_theme_support( 'post-thumbnails' );

		/**
		 * Enable support for header image
		 */
		add_theme_support( 'custom-header', apply_filters( 'ocean_custom_header_args', array(
			'width'              => 2000,
			'height'             => 1200,
			'flex-height'        => true,
			'video'              => true,
		) ) );

		/**
		 * Enable support for site logo
		 */
		add_theme_support( 'custom-logo', apply_filters( 'ocean_custom_logo_args', array(
			'height'      => 45,
			'width'       => 164,
			'flex-height' => true,
			'flex-width'  => true,
		) ) );

		/*
		 * Switch default core markup for search form, comment form, comments, galleries, captions and widgets
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'widgets',
		) );

		// Declare WooCommerce support.
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		// Add editor style
		add_editor_style( 'assets/css/editor-style.min.css' );

		// Declare support for selective refreshing of widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

	}

	/**
	 * Adds the meta tag to the site header
	 *
	 * @since 1.1.0
	 */
	public static function pingback_header() {

		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
		}

	}

	/**
	 * Adds the meta tag to the site header
	 *
	 * @since 1.0.0
	 */
	public static function meta_viewport() {

		// Meta viewport
		$viewport = '<meta name="viewport" content="width=device-width, initial-scale=1">';

		// Apply filters for child theme tweaking
		echo apply_filters( 'ocean_meta_viewport', $viewport );

	}

	/**
	 * Load scripts in the WP admin
	 *
	 * @since 1.0.0
	 */
	public static function admin_scripts() {
		global $pagenow;
		if ( 'nav-menus.php' == $pagenow ) {
			wp_enqueue_style( 'oceanwp-menus', OCEANWP_INC_DIR_URI .'walker/assets/menus.css' );
		}
	}

	/**
	 * Load front-end scripts
	 *
	 * @since   1.0.0
	 */
	public static function theme_css() {

		// Define dir
		$dir = OCEANWP_CSS_DIR_URI;
		$theme_version = OCEANWP_THEME_VERSION;

		// Remove font awesome style from plugins
		wp_deregister_style( 'font-awesome' );
		wp_deregister_style( 'fontawesome' );

		// Load font awesome style
		wp_enqueue_style( 'font-awesome', $dir .'third/font-awesome.min.css', false, '4.7.0' );

		// Register simple line icons style
		wp_enqueue_style( 'simple-line-icons', $dir .'third/simple-line-icons.min.css', false, '2.4.0' );

		// Register the lightbox style
		wp_enqueue_style( 'magnific-popup', $dir .'third/magnific-popup.min.css', false, '1.0.0' );

		// Register the slick style
		wp_enqueue_style( 'slick', $dir .'third/slick.min.css', false, '1.6.0' );

		// Main Style.css File
		wp_enqueue_style( 'oceanwp-style', $dir .'style.min.css', false, $theme_version );

		// Register hamburgers buttons to easily use them
		wp_register_style( 'oceanwp-hamburgers', $dir .'third/hamburgers/hamburgers.min.css', false, $theme_version );

		// Register hamburgers buttons styles
		$hamburgers = oceanwp_hamburgers_styles();
		foreach ( $hamburgers as $class => $name ) {
			wp_register_style( 'oceanwp-'. $class .'', $dir .'third/hamburgers/types/'. $class .'.css', false, $theme_version );
		}

		// Get mobile menu icon style
		$mobileMenu = get_theme_mod( 'ocean_mobile_menu_open_hamburger', 'default' );

		// Enqueue mobile menu icon style
		if ( ! empty( $mobileMenu ) && 'default' != $mobileMenu ) {
			wp_enqueue_style( 'oceanwp-hamburgers' );
			wp_enqueue_style( 'oceanwp-'. $mobileMenu .'' );
		}

		// If Vertical header style
		if ( 'vertical' == oceanwp_header_style() ) {
			wp_enqueue_style( 'oceanwp-hamburgers' );
			wp_enqueue_style( 'oceanwp-spin' );
		}

	}

	/**
	 * Returns all js needed for the front-end
	 *
	 * @since 1.0.0
	 */
	public static function theme_js() {

		// Get js directory uri
		$dir = OCEANWP_JS_DIR_URI;

		// Get current theme version
		$theme_version = OCEANWP_THEME_VERSION;

		// Get localized array
		$localize_array = self::localize_array();

		// Comment reply
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Add images loaded
		wp_enqueue_script( 'imagesloaded' );

		// Register nicescroll script to use it in some extensions
		wp_register_script( 'nicescroll', $dir .'third/nicescroll.min.js', array( 'jquery' ), $theme_version, true );

		// Enqueue nicescroll script if vertical header style
		if ( 'vertical' == oceanwp_header_style() ) {
			wp_enqueue_script( 'nicescroll' );
		}

		// Register Infinite Scroll script
		wp_register_script( 'infinitescroll', $dir .'third/infinitescroll.min.js', array( 'jquery' ), $theme_version, true );

		// WooCommerce scripts
		if ( OCEANWP_WOOCOMMERCE_ACTIVE
			&& 'yes' != get_theme_mod( 'ocean_woo_remove_custom_features', 'no' ) ) {
			wp_enqueue_script( 'oceanwp-woocommerce', $dir .'third/woo/woo-scripts.min.js', array( 'jquery' ), $theme_version, true );
		}

		// Load the lightbox scripts
		wp_enqueue_script( 'magnific-popup', $dir .'third/magnific-popup.min.js', array( 'jquery' ), $theme_version, true );
		wp_enqueue_script( 'oceanwp-lightbox', $dir .'third/lightbox.min.js', array( 'jquery' ), $theme_version, true );

		// Load minified js
		wp_enqueue_script( 'oceanwp-main', $dir .'main.min.js', array( 'jquery' ), $theme_version, true );
		
		// Localize array
		wp_localize_script( 'oceanwp-main', 'oceanwpLocalize', $localize_array );

	}

	/**
	 * Functions.js localize array
	 *
	 * @since 1.0.0
	 */
	public static function localize_array() {

		// Create array
		$sidr_side 		= get_theme_mod( 'ocean_mobile_menu_sidr_direction', 'left' );
		$sidr_side 		= $sidr_side ? $sidr_side : 'left';
		$sidr_target 	= get_theme_mod( 'ocean_mobile_menu_sidr_dropdown_target', 'icon' );
		$sidr_target 	= $sidr_target ? $sidr_target : 'icon';
		$vh_target 		= get_theme_mod( 'ocean_vertical_header_dropdown_target', 'icon' );
		$vh_target 		= $vh_target ? $vh_target : 'icon';
		$array = array(
			'isRTL'                 => is_rtl(),
			'menuSearchStyle'       => oceanwp_menu_search_style(),
			'sidrSource'       		=> oceanwp_sidr_menu_source(),
			'sidrDisplace'       	=> get_theme_mod( 'ocean_mobile_menu_sidr_displace', true ) ? true : false,
			'sidrSide'       		=> $sidr_side,
			'sidrDropdownTarget'    => $sidr_target,
			'verticalHeaderTarget'  => $vh_target,
			'customSelects'         => '.woocommerce-ordering .orderby, #dropdown_product_cat, .widget_categories select, .widget_archive select, .single-product .variations_form .variations select',
		);

		// WooCart
		if ( OCEANWP_WOOCOMMERCE_ACTIVE ) {
			$array['wooCartStyle'] 	= oceanwp_menu_cart_style();
		}

		// Apply filters and return array
		return apply_filters( 'ocean_localize_array', $array );
	}

	/**
	 * Add headers for IE to override IE's Compatibility View Settings
	 *
	 * @since 1.0.0
	 */
	public static function x_ua_compatible_headers( $headers ) {
		$headers['X-UA-Compatible'] = 'IE=edge';
		return $headers;
	}

	/**
	 * Load HTML5 dependencies for IE8
	 *
	 * @since 1.0.0
	 */
	public static function html5_shiv() {
		wp_register_script( 'html5shiv', OCEANWP_JS_DIR_URI . '/third/html5.min.js', array(), OCEANWP_THEME_VERSION, false );
		wp_enqueue_script( 'html5shiv' );
		wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );
	}

	/**
	 * Registers sidebars
	 *
	 * @since   1.0.0
	 */
	public static function register_sidebars() {

		$heading = 'h4';
		$heading = apply_filters( 'ocean_sidebar_heading', $heading );

		// Default Sidebar
		register_sidebar( array(
			'name'			=> esc_html__( 'Default Sidebar', 'oceanwp' ),
			'id'			=> 'sidebar',
			'description'	=> esc_html__( 'Widgets in this area will be displayed in the left or right sidebar area if you choose the Left or Right Sidebar layout.', 'oceanwp' ),
			'before_widget'	=> '<div id="%1$s" class="sidebar-box %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

		// Left Sidebar
		register_sidebar( array(
			'name'			=> esc_html__( 'Left Sidebar', 'oceanwp' ),
			'id'			=> 'sidebar-2',
			'description'	=> esc_html__( 'Widgets in this area are used in the left sidebar region if you use the Both Sidebars layout.', 'oceanwp' ),
			'before_widget'	=> '<div id="%1$s" class="sidebar-box %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

		// Search Results Sidebar
		if ( get_theme_mod( 'ocean_search_custom_sidebar', true ) ) {
			register_sidebar( array(
				'name'			=> esc_html__( 'Search Results Sidebar', 'oceanwp' ),
				'id'			=> 'search_sidebar',
				'description'	=> esc_html__( 'Widgets in this area are used in the search result page.', 'oceanwp' ),
				'before_widget'	=> '<div id="%1$s" class="sidebar-box %2$s clr">',
				'after_widget'	=> '</div>',
				'before_title'	=> '<'. $heading .' class="widget-title">',
				'after_title'	=> '</'. $heading .'>',
			) );
		}

		// Footer 1
		register_sidebar( array(
			'name'			=> esc_html__( 'Footer 1', 'oceanwp' ),
			'id'			=> 'footer-one',
			'description'	=> esc_html__( 'Widgets in this area are used in the first footer region.', 'oceanwp' ),
			'before_widget'	=> '<div id="%1$s" class="footer-widget %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

		// Footer 2
		register_sidebar( array(
			'name'			=> esc_html__( 'Footer 2', 'oceanwp' ),
			'id'			=> 'footer-two',
			'description'	=> esc_html__( 'Widgets in this area are used in the second footer region.', 'oceanwp' ),
			'before_widget'	=> '<div id="%1$s" class="footer-widget %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

		// Footer 3
		register_sidebar( array(
			'name'			=> esc_html__( 'Footer 3', 'oceanwp' ),
			'id'			=> 'footer-three',
			'description'	=> esc_html__( 'Widgets in this area are used in the third footer region.', 'oceanwp' ),
			'before_widget'	=> '<div id="%1$s" class="footer-widget %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

		// Footer 4
		register_sidebar( array(
			'name'			=> esc_html__( 'Footer 4', 'oceanwp' ),
			'id'			=> 'footer-four',
			'description'	=> esc_html__( 'Widgets in this area are used in the fourth footer region.', 'oceanwp' ),
			'before_widget'	=> '<div id="%1$s" class="footer-widget %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

	}

	/**
	 * Registers theme_mod strings into Polylang.
	 *
	 * @since 1.1.4
	 */
	public static function polylang_register_string() {

		if ( function_exists( 'pll_register_string' ) && $strings = oceanwp_register_tm_strings() ) {
			foreach( $strings as $string => $default ) {
				pll_register_string( $string, get_theme_mod( $string, $default ), 'Theme Mod', true );
			}
		}

	}

	/**
	 * All theme functions hook into the oceanwp_head_css filter for this function.
	 *
	 * @since 1.0.0
	 */
	public static function custom_css( $output = NULL ) {
			    
	    // Add filter for adding custom css via other functions
		$output = apply_filters( 'ocean_head_css', $output );

		// If Custom File is selected
		if ( 'file' == get_theme_mod( 'ocean_customzer_styling', 'head' ) ) {

			global $wp_customize;
			$upload_dir = wp_upload_dir();

			// Render CSS in the head
			if ( isset( $wp_customize ) || ! file_exists( $upload_dir['basedir'] .'/oceanwp/custom-style.css' ) ) {

				 // Minify and output CSS in the wp_head
				if ( ! empty( $output ) ) {
					echo "<!-- OceanWP CSS -->\n<style type=\"text/css\">\n" . wp_strip_all_tags( oceanwp_minify_css( $output ) ) . "\n</style>";
				}
			}

		} else {

			// Minify and output CSS in the wp_head
			if ( ! empty( $output ) ) {
				echo "<!-- OceanWP CSS -->\n<style type=\"text/css\">\n" . wp_strip_all_tags( oceanwp_minify_css( $output ) ) . "\n</style>";
			}

		}

	}

	/**
	 * Minify the WP custom CSS because WordPress doesn't do it by default.
	 *
	 * @since 1.1.9
	 */
	public static function minify_custom_css( $css ) {

		return oceanwp_minify_css( $css );

	}

	/**
	 * Save Customizer CSS in a file
	 *
	 * @since 1.4.12
	 */
	public static function save_customizer_css_in_file( $output = NULL ) {

		// If Custom File is not selected
		if ( 'file' != get_theme_mod( 'ocean_customzer_styling', 'head' ) ) {
			return;
		}

		// Get all the customier css
	    $output = apply_filters( 'ocean_head_css', $output );

	    // Get Custom Panel CSS
	    $output_custom_css = wp_get_custom_css();

	    // Minified the Custom CSS
		$output .= oceanwp_minify_css( $output_custom_css );
			
		// We will probably need to load this file
		require_once( ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'file.php' );
		
		global $wp_filesystem;
		$upload_dir = wp_upload_dir(); // Grab uploads folder array
		$dir = trailingslashit( $upload_dir['basedir'] ) . 'oceanwp'. DIRECTORY_SEPARATOR; // Set storage directory path

		WP_Filesystem(); // Initial WP file system
		$wp_filesystem->mkdir( $dir ); // Make a new folder 'oceanwp' for storing our file if not created already.
		$wp_filesystem->put_contents( $dir . 'custom-style.css', $output, 0644 ); // Store in the file.

	}

	/**
	 * Include Custom CSS file if present.
	 *
	 * @since 1.4.12
	 */
	public static function custom_style_css( $output = NULL ) {

		// If Custom File is not selected
		if ( 'file' != get_theme_mod( 'ocean_customzer_styling', 'head' ) ) {
			return;
		}

		global $wp_customize;
		$upload_dir = wp_upload_dir();

		// Get all the customier css
	    $output = apply_filters( 'ocean_head_css', $output );

	    // Get Custom Panel CSS
	    $output_custom_css = wp_get_custom_css();

	    // Minified the Custom CSS
		$output .= oceanwp_minify_css( $output_custom_css );

		// Render CSS from the custom file
		if ( ! isset( $wp_customize ) && file_exists( $upload_dir['basedir'] .'/oceanwp/custom-style.css' ) && ! empty( $output ) ) { 
		    wp_enqueue_style( 'oceanwp-custom', trailingslashit( $upload_dir['baseurl'] ) . 'oceanwp/custom-style.css', false, null );	    			
		}		
	}

	/**
	 * Remove Customizer style script from front-end
	 *
	 * @since 1.4.12
	 */
	public static function remove_customizer_custom_css() {

		// If Custom File is not selected
		if ( 'file' != get_theme_mod( 'ocean_customzer_styling', 'head' ) ) {
			return;
		}
		
		global $wp_customize;

		// Disable Custom CSS in the frontend head
		remove_action( 'wp_head', 'wp_custom_css_cb', 11 );
		remove_action( 'wp_head', 'wp_custom_css_cb', 101 );

		// If custom CSS file exists and NOT in customizer screen
		if ( isset( $wp_customize ) ) {
			add_action( 'wp_footer', 'wp_custom_css_cb', 9999 );
		}
	}

	/**
	 * Adds inline CSS for the admin
	 *
	 * @since 1.0.0
	 */
	public static function admin_inline_css() {
		echo '<style>div#setting-error-tgmpa{display:block;}</style>';
	}


	/**
	 * Alter the search posts per page
	 *
	 * @since 1.3.7
	 */
	public static function search_posts_per_page( $query ) {
		$posts_per_page = get_theme_mod( 'ocean_search_post_per_page', '8' );
		$posts_per_page = $posts_per_page ? $posts_per_page : '8';

		if ( $query->is_main_query() && is_search() ) {
			$query->set( 'posts_per_page', $posts_per_page );
		}
	}

	/**
	 * Alter wp list categories arguments.
	 * Adds a span around the counter for easier styling.
	 *
	 * @since 1.0.0
	 */
	public static function wp_list_categories_args( $links ) {
		$links = str_replace( '</a> (', '</a> <span class="cat-count-span">(', $links );
		$links = str_replace( ' )', ' )</span>', $links );
		return $links;
	}

	/**
	 * Alters the default oembed output.
	 * Adds special classes for responsive oembeds via CSS.
	 *
	 * @since 1.0.0
	 */
	public static function add_responsive_wrap_to_oembeds( $cache, $url, $attr, $post_ID ) {

		// Supported video embeds
		$hosts = apply_filters( 'ocean_oembed_responsive_hosts', array(
			'vimeo.com',
			'youtube.com',
			'blip.tv',
			'money.cnn.com',
			'dailymotion.com',
			'flickr.com',
			'hulu.com',
			'kickstarter.com',
			'vine.co',
			'soundcloud.com',
			'#http://((m|www)\.)?youtube\.com/watch.*#i',
	        '#https://((m|www)\.)?youtube\.com/watch.*#i',
	        '#http://((m|www)\.)?youtube\.com/playlist.*#i',
	        '#https://((m|www)\.)?youtube\.com/playlist.*#i',
	        '#http://youtu\.be/.*#i',
	        '#https://youtu\.be/.*#i',
	        '#https?://(.+\.)?vimeo\.com/.*#i',
	        '#https?://(www\.)?dailymotion\.com/.*#i',
	        '#https?://dai\.ly/*#i',
	        '#https?://(www\.)?hulu\.com/watch/.*#i',
	        '#https?://wordpress\.tv/.*#i',
	        '#https?://(www\.)?funnyordie\.com/videos/.*#i',
	        '#https?://vine\.co/v/.*#i',
	        '#https?://(www\.)?collegehumor\.com/video/.*#i',
	        '#https?://(www\.|embed\.)?ted\.com/talks/.*#i'
		) );

		// Supports responsive
		$supports_responsive = false;

		// Check if responsive wrap should be added
		foreach( $hosts as $host ) {
			if ( strpos( $url, $host ) !== false ) {
				$supports_responsive = true;
				break; // no need to loop further
			}
		}

		// Output code
		if ( $supports_responsive ) {
			return '<p class="responsive-video-wrap clr">' . $cache . '</p>';
		} else {
			return '<div class="oceanwp-oembed-wrap clr">' . $cache . '</div>';
		}

	}

	/**
	 * Adds extra classes to the post_class() output
	 *
	 * @since 1.0.0
	 */
	public static function post_class( $classes ) {

		// Get post
		global $post;

		// Add entry class
		$classes[] = 'entry';

		// Add has media class
		if ( has_post_thumbnail()
			|| get_post_meta( $post->ID, 'ocean_post_oembed', true )
			|| get_post_meta( $post->ID, 'ocean_post_self_hosted_media', true )
			|| get_post_meta( $post->ID, 'ocean_post_video_embed', true )
		) {
			$classes[] = 'has-media';
		}

		// Return classes
		return $classes;

	}

	/**
	 * Add schema markup to the authors post link
	 *
	 * @since 1.0.0
	 */
	public static function the_author_posts_link( $link ) {

		// Add schema markup
		$schema = oceanwp_get_schema_markup( 'author_link' );
		if ( $schema ) {
			$link = str_replace( 'rel="author"', 'rel="author" '. $schema, $link );
		}

		// Return link
		return $link;

	}

	/**
	 * Add support for Elementor Pro locations
	 *
	 * @since 1.5.6
	 */
	public static function register_elementor_locations( $elementor_theme_manager ) {
		$elementor_theme_manager->register_all_core_location();
	}

	/**
	 * Add schema markup to the authors post link
	 *
	 * @since 1.1.5
	 */
	public static function remove_bb_lightbox() {
		return true;
	}

}
new OCEANWP_Theme_Class;



/**********************************************************************************************************************************************************************************************************/


function psp_add_styles(){
	
	wp_enqueue_style('bootstrap-css',get_template_directory_uri().'/css/bootstrap.css');
	wp_enqueue_style('hover.css',get_template_directory_uri().'/css/hover.css');
	wp_enqueue_style('animate.css',get_template_directory_uri().'/css/animate.css');


    if ( is_front_page() ) {

			wp_enqueue_style('skitter.css',get_template_directory_uri().'/css/skitter.css');
		}
	

    wp_enqueue_style('bootstrap-dropdownhover.min.css',get_template_directory_uri().'/css/bootstrap-dropdownhover.min.css');

	wp_enqueue_style('slick.css',get_template_directory_uri().'/css/slick.css');

	wp_enqueue_style('slick-theme.css',get_template_directory_uri().'/css/slick-theme.css');

	wp_enqueue_style('ubislider.min.css',get_template_directory_uri().'/css/ubislider.min.css');	

	wp_enqueue_style('main',get_template_directory_uri().'/css/main.css');
}



/*
*function to add my custom script
*wp_enqueue_script()
*/
function psp_add_scripts(){
	
	// this is dropdown 
	wp_enqueue_script('bootstrap-dropdownhover.min.js',get_template_directory_uri().'/js/bootstrap-dropdownhover.min.js',array(),false,true);

	//add bootstrap script file
	wp_enqueue_script('bootstrap.min.js',get_template_directory_uri().'/js/bootstrap.min.js',array('jquery'),false,true);
	// this is skitter
	wp_enqueue_script('jquery.easing.1.3.js',get_template_directory_uri().'/js/jquery.easing.1.3.js',array(),false,true);
	wp_enqueue_script('jquery.skitter.min.js',get_template_directory_uri().'/js/jquery.skitter.min.js',array(),false,true);
	// this is slick
	wp_enqueue_script('slick.min.js',get_template_directory_uri().'/js/slick.min.js',array(),false,
		true);
	//this is ubislider
	wp_enqueue_script('jqueryElevateZoom.js',get_template_directory_uri().'/js/jqueryElevateZoom.js',array(),false,true);
	wp_enqueue_script('ubislider.min.js',get_template_directory_uri().'/js/ubislider.min.js',array(),false,true);
	
	//add my file script
	wp_enqueue_script('main.js',get_template_directory_uri().'/js/main.js',array(),false,true);
	//add wow file script
	wp_enqueue_script('wow.min.js',get_template_directory_uri().'/js/wow.min.js',array(),false,true);
	
	//add html5shiv for old browsers
	wp_enqueue_script('html5shiv',get_template_directory_uri().'/js/html5shiv.min.js');
	//add conditional comment for html5shiv
	wp_script_add_data('html5shiv','conditional','lt IE 9');
	//add respond script for old browser
	wp_enqueue_script('respond',get_template_directory_uri().'/js/respond.min.js');
	//add conditional comment for respond
	wp_script_add_data('respond','conditional','lt IE 9');
}


//add css styles
add_action('wp_enqueue_scripts','psp_add_styles');
//add js script
add_action('wp_enqueue_scripts','psp_add_scripts');



function custom_add_google_fonts() {
 wp_enqueue_style( 'custom-google-fonts', 'https://fonts.googleapis.com/css?family=Bitter', false );
 }
 add_action( 'wp_enqueue_scripts', 'custom_add_google_fonts' );


function custom_add_google_fonts_ar() {
 wp_enqueue_style( 'custom-google-fonts', 'https://fonts.googleapis.com/css?family=Markazi+Text&display=swap', false );
 }
 add_action( 'wp_enqueue_scripts', 'custom_add_google_fonts_ar' );


function fontawesome_dashboard() {
   wp_enqueue_style('fontawesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', '', '4.7.0', 'all'); 
}

add_action('admin_init', 'fontawesome_dashboard');



//register Cat sidebar
function cat_sidebar(){

	register_sidebar(array(
		'name'=>'Cat Sidebar',
		'id'  =>'cat-sidebar',
		'description'=>'cat sidebar appear every where',
		'class'=>'cat-sidebar',
		'before_widget'=>'<div class="widget-content">',
		'after_widget'=>'</div>',
		'before_title'=>'<h3 class="widget-title">',
		'after_title'=>'</h3>'
	));
}
add_action('widgets_init','cat_sidebar');

//register Timer sidebar 
function timer_sidebar(){

	register_sidebar(array(
		'name'=>'Timer Sidebar',
		'id'  =>'timer-sidebar',
		'description'=>'timer sidebar appear every where',
		'class'=>'timer-sidebar',
		'before_widget'=>'<div class="widget-content">',
		'after_widget'=>'</div>',
		'before_title'=>'<h3 class="widget-title">',
		'after_title'=>'</h3>'
	));
}
add_action('widgets_init','timer_sidebar');


// add main slider 

	function create_main_slider(){
		
		$values = array(
			
			'public' => true, 
			'labels' => array('name'=>'Main Slider',
							  'add_new'=>'Add MSlider',
							  'singular_name'=>'MSlider',
							  'add_new_item'=>'Add New MSlider',
							  'edit_item'=>'Edit MSlider',
							  'new_item'=>'New MSlider',
							  'view_item'=>'View MSlider',
							  'view_items'=>'View MSlider',
							  'search_items'=>'Search MSlider',
							   ), 
			  'menu_icon' => '',
			'supports'=> array(''),
		     'capabilities'    => array(
		                       
		                       ),); 
		
		register_post_type('mslider',$values); 
		
		
		
	}


    add_action('init','create_main_slider');


// Add Suport JSON 

add_action( 'init', 'my_custom_post_type_rest_support', 25 );

function my_custom_post_type_rest_support() {

global $wp_post_types;

$post_type_name = 'mslider';

if( isset( $wp_post_types[ $post_type_name ] ) ) {

$wp_post_types[$post_type_name]->show_in_rest = true;
$wp_post_types[$post_type_name]->rest_base = $post_type_name;
$wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';

}

}

	
function fontawesome_icon_dashboard() {
   echo "<style type='text/css' media='screen'>
   		icon16.icon-media:before, #adminmenu .menu-icon-mslider div.wp-menu-image:before {
   		font-family: Fontawesome !important;
   		content: '\\f1de';
     }
     	</style>";
 }
add_action('admin_head', 'fontawesome_icon_dashboard');


function my_custom_admin_styles() {
?>
    <style type="text/css">
      .post-type-inhoud form #delete-action{
           display:none;
       }
     </style>
<?php
}
add_action('admin_head', 'my_custom_admin_styles');


// add sidebar photo 


	function create_sidebar_photo(){
		
		$values = array(
			
			'public' => true, 
			'labels' => array('name'=>'Sidebar Photo',
							  'add_new'=>'Add Sidebar Photo',
							  'singular_name'=>'Sidebar Photo',
							  'add_new_item'=>'Add New Sidebar Photo',
							  'edit_item'=>'Edit Sidebar Photo',
							  'new_item'=>'New Sidebar Photo',
							  'view_item'=>'View Sidebar Photo',
							  'view_items'=>'View Sidebar Photo',
							  'search_items'=>'Search Sidebar Photo',
							   ), 
			  'menu_icon' => '','supports'=> array('')
		); 
		
		register_post_type('sidebarphoto',$values); 
		
	}


     add_action('init','create_sidebar_photo');


// Add Suport JSON two 

add_action( 'init', 'my_custom_post_type_rest_supportt', 25 );

function my_custom_post_type_rest_supportt() {

global $wp_post_types;

$post_type_name = 'sidebarphoto';

if( isset( $wp_post_types[ $post_type_name ] ) ) {

$wp_post_types[$post_type_name]->show_in_rest = true;
$wp_post_types[$post_type_name]->rest_base = $post_type_name;
$wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';

}

}

function fontawesome_icon_dashboard_three() {
   echo "<style type='text/css' media='screen'>
   		icon16.icon-media:before, #adminmenu .menu-icon-sidebarphoto div.wp-menu-image:before {
   		font-family: Fontawesome !important;
   		content: '\\f0c9';
     }
     	</style>";
 }
add_action('admin_head', 'fontawesome_icon_dashboard_three');



// add main slider footer


	function create_main_slider_footer(){
		
		$values = array(
			
			'public' => true, 
			'labels' => array('name'=>'Main Slider Footer',
							  'add_new'=>'Add MSlider Footer',
							  'singular_name'=>'MSlider Footer',
							  'add_new_item'=>'Add New MSlider Footer',
							  'edit_item'=>'Edit MSlider Footer',
							  'new_item'=>'New MSlider Footer',
							  'view_item'=>'View MSlider Footer',
							  'view_items'=>'View MSlider Footer',
							  'search_items'=>'Search MSlider Footer',
							   ), 
			  'menu_icon' => '','supports'=> array('')
		); 
		
		register_post_type('msliderfooter',$values); 
		
		
		
	}


     add_action('init','create_main_slider_footer');
	
function fontawesome_icon_dashboard_two() {
   echo "<style type='text/css' media='screen'>
   		icon16.icon-media:before, #adminmenu .menu-icon-msliderfooter div.wp-menu-image:before {
   		font-family: Fontawesome !important;
   		content: '\\f1de';
     }
     	</style>";
 }
add_action('admin_head', 'fontawesome_icon_dashboard_two');


/**
* Replaces "Post" in the update messages for custom post types on the "Edit"post screen.
* For example, for a "Product" custom post type, "Post updated. View Post." becomes "Product updated. View Product".
*
* @param array $messages The default WordPress messages.
*/

function pico_custom_update_messages( $messages ) {
global $post, $post_ID;

$post_types = get_post_types( array( 'show_ui' => true, '_builtin' => false ), 'objects' );

foreach( $post_types as $post_type => $post_object ) {

    $messages[$post_type] = array(
        0  => '', // Unused. Messages start at index 1.
        1  => sprintf( __( '%s updated. <a href="%s">View %s</a>' ), $post_object->labels->singular_name, esc_url( get_permalink( $post_ID ) ), $post_object->labels->singular_name ),
        2  => __( 'Custom field updated.' ),
        3  => __( 'Custom field deleted.' ),
        4  => sprintf( __( '%s updated.' ), $post_object->labels->singular_name ),
        5  => isset( $_GET['revision']) ? sprintf( __( '%s restored to revision from %s' ), $post_object->labels->singular_name, wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6  => sprintf( __( '%s published. <a href="%s">View %s</a>' ), $post_object->labels->singular_name, esc_url( get_permalink( $post_ID ) ), $post_object->labels->singular_name ),
        7  => sprintf( __( '%s saved.' ), $post_object->labels->singular_name ),
        8  => sprintf( __( '%s submitted. <a target="_blank" href="%s">Preview %s</a>'), $post_object->labels->singular_name, esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ), $post_object->labels->singular_name ),
        9  => sprintf( __( '%s scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview %s</a>'), $post_object->labels->singular_name, date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ), $post_object->labels->singular_name ),
        10 => sprintf( __( '%s draft updated. <a target="_blank" href="%s">Preview %s</a>'), $post_object->labels->singular_name, esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ), $post_object->labels->singular_name ),
        );
}

return $messages;
}
add_filter( 'post_updated_messages', 'pico_custom_update_messages');


/*
 * Remove a link from the Yoast SEO breadcrumbs
 * Credit: https://timersys.com/remove-link-yoast-breadcrumbs/
 * Last Tested: Mar 12 2017 using Yoast SEO 4.4 on WordPress 4.7.3
 */
add_filter( 'wpseo_breadcrumb_single_link' ,'wpseo_remove_breadcrumb_link', 10 ,2);
function wpseo_remove_breadcrumb_link( $link_output , $link ){
    $text_to_remove = 'Products';
  
    if( $link['text'] == $text_to_remove ) {
      $link_output = '';
    }
 
    return $link_output;
}


/**
 * Change number of related products output
 */ 
function woo_related_products_limit() {
  global $product;
	
	$args['posts_per_page'] = 6;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args', 20 );
  function jk_related_products_args( $args ) {
	$args['posts_per_page'] = 4; // 4 related products
	$args['columns'] = 4; // arranged in 4 columns
	return $args;
}


add_filter( 'woocommerce_ajax_loader_url', 'custom_loader_icon', 10, 1 );
function custom_loader_icon() {
    return __( get_home_path() . 'wp-content/uploads/custom-loader.svg', 'woocommerce' );
}


add_filter( 'woocommerce_rest_prepare_shop_order_object', 'custom_change_shop_order_response', 10, 3 );
function custom_change_shop_order_response( $response, $object, $request ) {
 $order = wc_get_order( $response->data['id'] );
    $used_coupons = $request->get_param( 'coupon_lines' );
    $coupon_amount = 0;
    if( !empty( $used_coupons ) ):
        foreach ($used_coupons as $coupon ){
            $coupon_id = $coupon['id'];
            $coupon_amount = $coupon['amount'];
        }
    endif;

    $order_coupons = $reponse->data['coupon_lines'];
    if( !empty( $order_coupons ) ) :
        foreach ( $order_coupons as $coupon ) {
            wc_update_order_item_meta( $coupon['id'], 'discount_amount', $coupon['amount'] );
        }
    endif;
  $order_total = get_post_meta( $response->data['id'], '_order_total', true );
    $order_total = $order_total - $coupon_amount;
    update_post_meta( $order->ID, '_order_total', $order_total );
    $response->data['total']  = $order_total;

    return $response;
}



function my_admin_styles() {
  echo '<style>
    #permalink-manager-col {
      display:none;
    }
    .permalink-manager-col {
      display:none;
    }
    .column-author{
        display:none;
    }
    .column-permalink-manager-col{
        display:none;
    }
  </style>';
}
 
add_action('admin_head', 'my_admin_styles');


/**
* @snippet Remove the Postcode Field on the WooCommerce Checkout
*/
 
add_filter( 'woocommerce_checkout_fields' , 'bbloomer_remove_billing_postcode_checkout' );
 
function bbloomer_remove_billing_postcode_checkout( $fields ) {
  unset($fields['billing']['billing_postcode']);
  return $fields;
}


