<?php
/*
Plugin Name: MP Show Child Pages
Plugin URI:  https://petrov.net.ua/
Author: Mykhaylo Petrov
Author URI: https://petrov.net.ua/
Text Domain: mp-show-child-pages
*/

if ( ! defined( 'ABSPATH' ) ) {
    die; // or exit;
}

define( 'MP_SHOW_CHILD_PAGES', plugin_dir_path( __FILE__ ) );
define( 'MP_SHOW_CHILD_PAGES_URL', plugin_dir_url( __FILE__ ) );

add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 'mp-show-child-pages-main-style',  MP_SHOW_CHILD_PAGES_URL . 'assets/css/style.css' );
    wp_enqueue_script( 'mp-show-child-pages-main-script',  MP_SHOW_CHILD_PAGES_URL . 'assets/js/script.js', array(), '', true );
});

/**
 * Pages with Tags
 * 
 * https://wordpress.org/plugins/pages-with-category-and-tag/
 */
add_action( 'init', function() {
	/* Add categories and tags to pages */
	// register_taxonomy_for_object_type( 'category', 'page' );
	register_taxonomy_for_object_type( 'post_tag', 'page' );
});

add_action( 'pre_get_posts', function( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	/* View categories and tags archive pages */
	// if ($query->is_category && $query->is_main_query()) {
	// 	$query->set('post_type', array('post', 'page'));
	// }

	if ( $query->is_tag && $query->is_main_query() ) {
		$query->set( 'post_type', array( 'post', 'page' ) );
	}
});

/**
 * Під'єднання ACF не як окремого плагіну, а з підпапки свого плагіну 
 * 
 * ++ https://support.advancedcustomfields.com/forums/topic/include-acf-to-my-plugin/
 * -- https://www.advancedcustomfields.com/resources/including-acf-within-a-plugin-or-theme/
 * 
 */
if ( ! class_exists( 'acf' ) ) {
    // Customize ACF path
    if ( ! function_exists( 'mp_acf_settings_path' ) ) {
        function mp_acf_settings_path( $path ) {
            $path = MP_SHOW_CHILD_PAGES . 'includes/acf/';
            return $path;
        }
    }
    add_filter( 'acf/settings/path', 'mp_acf_settings_path' );

    // Customize ACF dir
    if ( ! function_exists( 'mp_acf_settings_dir' ) ) {
        function mp_acf_settings_dir( $dir ) {
            $dir = MP_SHOW_CHILD_PAGES_URL . 'includes/acf/';
            return $dir;
        }
    }
    add_filter( 'acf/settings/dir', 'mp_acf_settings_dir' );

    // Hide ACF field group admin menu item
    add_filter( 'acf/settings/show_admin', '__return_false' );

    // Include ACF
    include_once( MP_SHOW_CHILD_PAGES . 'includes/acf/acf.php' );

    // Create ACF fields
    require_once MP_SHOW_CHILD_PAGES . 'includes/acf-fields.php';
    // Get child pages
    require_once MP_SHOW_CHILD_PAGES . 'includes/get-child-pages.php';
} else {
    // Якщо ACF вже активований як оермий плагін 
    // або під'єднаний як бібліотека в якомусь іншому плагіні чи темі

    // Customize ACF path
    if ( ! function_exists( 'mp_acf_settings_path' ) ) {
        function mp_acf_settings_path( $path ) {
            $path = MP_SHOW_CHILD_PAGES . 'includes/acf/';
            return $path;
        }
    }
    add_filter( 'acf/settings/path', 'mp_acf_settings_path' );

    // Customize ACF dir
    if ( ! function_exists( 'mp_acf_settings_dir' ) ) {
        function mp_acf_settings_dir( $dir ) {
            $dir = MP_SHOW_CHILD_PAGES_URL . 'includes/acf/';
            return $dir;
        }
    }
    add_filter( 'acf/settings/dir', 'mp_acf_settings_dir' );

    // Create ACF fields
    require_once MP_SHOW_CHILD_PAGES . 'includes/acf-fields.php';
    
    // Get child pages
    require_once MP_SHOW_CHILD_PAGES . 'includes/get-child-pages.php';
}