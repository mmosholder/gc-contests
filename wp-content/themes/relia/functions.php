<?php
/**
 * relia functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package relia
 */

if ( ! function_exists( 'relia_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function relia_setup() {

        if( !defined( 'RELIA_VERSION' ) ) :
            define('RELIA_VERSION', '1.1.2');
        endif;

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on relia, use a find and replace
	 * to change 'relia' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'relia', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'relia' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

        add_action( 'after_setup_theme', 'woocommerce_support' );

        /**
         * Enable support for WooCommerce plugin.
         */
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
        add_editor_style('');


}
endif; // relia_setup
add_action( 'after_setup_theme', 'relia_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function relia_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'relia_content_width', 640 );
}
add_action( 'after_setup_theme', 'relia_content_width', 0 );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load the theme functions
 */
require get_template_directory() . '/inc/relia/relia.php';

require get_template_directory() . '/inc/tgm.php';
require_once get_template_directory() . '/inc/scripts.php';

function jsonToProp($data)
{
    return htmlentities(json_encode($data, JSON_HEX_QUOT), ENT_QUOTES);
}

// Custom entry endpoints
add_action('rest_api_init', function () {
	register_rest_route('contests/v1', '/all', array(
		'methods' => 'GET',
		'callback' => 'handle_get_all'
	));

	register_rest_route('contests/v1', '/contest/(?P<contest_id>\d+)/users/(?P<user_id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'handle_get_user_entries'
	));

	register_rest_route('contests/v1', '/entries/(?P<entry_id>\d+)', array(
		'methods' => 'PATCH',
		'callback' => 'update_entry',
		'args' => array(
			'tier1' => array(
				'required' => true,
				'type' => 'string'
			),
			'tier2' => array(
				'required' => true,
				'type' => 'string'
			),
			'tier3' => array(
				'required' => true,
				'type' => 'string'
			),
			'tier4' => array(
				'required' => true,
				'type' => 'string'
			),
			'tier5' => array(
				'required' => true,
				'type' => 'string'
			),
			'tier6' => array(
				'required' => true,
				'type' => 'string'
			)
		)
	));
});


function handle_get_all($data)
{
	global $wpdb;
	$query = "SELECT * FROM `wp_440kyaxs8z_contests`";
	$list = $wpdb->get_results($query);
	return $list;
}

function handle_get_user_entries($data)
{
	global $wpdb;
	$query = "SELECT * FROM `wp_440kyaxs8z_entries` WHERE user_id = $data[user_id] AND contest_id = $data[contest_id]";
	$list = $wpdb->get_results($query);
	return $list;
}

function update_entry($data)
{
	global $wpdb;
	$params = $data->get_params();
	$entry_id = $data['entry_id'];
	$tier1 = $params['tier1'];
	$tier2 = $params['tier2'];
	$tier3 = $params['tier3'];
	$tier4 = $params['tier4'];
	$tier5 = $params['tier5'];
	$tier6 = $params['tier6'];

	$wpdb->update(
    'wp_440kyaxs8z_entries',
		array(
			'tier1' => $tier1,
			'tier2' => $tier2,
			'tier3' => $tier3,
			'tier4' => $tier4,
			'tier5' => $tier5,
			'tier6' => $tier6
		),
		array('entry_id' => $entry_id),
		array(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
		),
		array('%d')
	);
}
