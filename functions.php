<?php
/**
 * DIVart Gazette functions and definitions
 *
 * @package DIVart Gazette
 */


if ( ! function_exists( 'divart_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function divart_setup() {
	
	
	remove_action( 'wp_enqueue_scripts', 'gazette_scripts' );
	remove_action( 'after_setup_theme', 'gazette_jetpack_setup', 11 );

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Gazette, use a find and replace
	 * to change 'gazette' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'gazette', get_stylesheet_directory() . '/languages' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'top' => __( 'Top Location', 'gazette' ),
		'footer'  => __( 'Footer Location', 'gazette' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'image', 'video', 'link', 'gallery',
	) );
	
		/**
	 * Add theme support for Infinite Scroll.
	 * See: http://jetpack.me/support/infinite-scroll/
	 */
	//remove_theme_support( 'infinite-scroll', 11 );

	add_theme_support( 'infinite-scroll', array(
		'container'      => 'main',
		'footer'         => 'main',
		'footer_widgets' => array( 'sidebar-2' ),
		'wrapper'        => false,
		'type'           => 'click',
	) );

	
		/**
	 * Add theme support for Featured Content.
	 * See: http://jetpack.me/support/featured-content/
	 */
	add_theme_support( 'featured-content', array(
		'filter'      => 'gazette_get_featured_posts',
		'description' => __( 'The featured content section displays on the front page above the header.', 'gazette' ),
		'max_posts'   => 6,
	) );

	/**
	 * Add theme support for Responsive Videos.
	 */
	add_theme_support( 'jetpack-responsive-videos' );

	/**
	 * Add theme support for Logo upload.
	 */
	add_image_size( 'gazette-logo', 270, 60 );
	add_theme_support( 'site-logo', array( 'size' => 'gazette-logo' ) );
	
	
}
endif; // divart_setup


add_action( 'after_setup_theme', 'divart_setup', 11 );

	/*
	 * Enable support for child theme style.css & parent theme style.css.
	 *
	 * @link https://codex.wordpress.org/Child_Themes#Creating_a_Child_Theme_from_a_Modified_Existing_Theme
	 */
	 


function divart_styles_script() {


wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.3' );

    $parent_style = 'divart-parent-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'divart-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );

	wp_enqueue_style( 'gazette-lato-inconsolata', gazette_lato_inconsolata_fonts_url() );

	wp_enqueue_style( 'gazette-style', get_stylesheet_uri() );

	wp_enqueue_script( 'gazette-navigation', get_template_directory_uri() . '/js/navigation.js', array( 'jquery' ), '20150507', true );

	wp_enqueue_script( 'divart-featured-content', get_stylesheet_directory_uri() . '/js/divart-featured-content.js', array( 'jquery' ), '20150507', true );

	wp_enqueue_script( 'gazette-header', get_template_directory_uri() . '/js/header.js', array( 'jquery' ), '20150507', true );

	wp_enqueue_script( 'gazette-search', get_template_directory_uri() . '/js/search.js', array( 'jquery' ), '20150507', true );

	if ( ( is_single() && ( has_post_thumbnail() && ( ! has_post_format() || has_post_format( 'aside' ) || has_post_format( 'image' ) || has_post_format( 'gallery' ) ) ) ) || ( is_page() && has_post_thumbnail() ) ) {
		wp_enqueue_script( 'gazette-single-thumbnail', get_template_directory_uri() . '/js/single-thumbnail.js', array( 'jquery' ), '20150416', true );
	}

	if ( is_singular() ) {
		wp_enqueue_script( 'gazette-single', get_template_directory_uri() . '/js/single.js', array( 'jquery' ), '20150507', true );
	}

	if ( is_singular() && is_active_sidebar( 'sidebar-1' ) ) {
		wp_enqueue_script( 'gazette-sidebar', get_template_directory_uri() . '/js/sidebar.js', array(), '20150429', true );
	}

	if ( is_home() || is_archive() || is_search() ) {
		wp_enqueue_script( 'gazette-posts', get_template_directory_uri() . '/js/posts.js', array( 'jquery' ), '20150507', true );
	}

	wp_enqueue_script( 'gazette-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
}

add_action( 'wp_enqueue_scripts', 'divart_styles_script' );


/**
 * Filter the except length to 12 characters.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function divart_excerpt_length( $length ) {
    return 12;
}
add_filter( 'excerpt_length', 'divart_excerpt_length', 999 );
