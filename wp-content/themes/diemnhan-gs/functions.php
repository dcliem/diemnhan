<?php
/**
 * Điểm Nhấn (GS)
 * This file adds functions to the theme.
 *
 * @package Điểm Nhấn (CS)
 * @author  Media (GFS)
 * @license GPL-2.0+
 * @link    http://www.studiopress.com/
 */

//* Child theme
define( 'CHILD_THEME_NAME', 'Điểm Nhấn (GS)' );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/' );
define( 'CHILD_THEME_VERSION', '1.5.2' );

//* Begin theme constants
define ( 'NZ_URI' , get_theme_file_uri() );
define ( 'NZ_DIR' , get_theme_file_path() );
define ( 'NZ_JS'  , get_theme_file_uri( '/assets/js' ) );
define ( 'NZ_CSS' , get_theme_file_uri( '/assets/css' ) );
define ( 'NZ_IMG' , get_theme_file_uri( '/assets/images' ) );

//* Start the engine
include_once( get_parent_theme_file_path('/lib/init.php') );

//* Setup Theme
foreach ( glob( dirname( __FILE__ ) . '/framework/*.php' ) as $file ) { include_once $file; }
//* Load bootstrap markup
foreach ( glob( dirname( __FILE__ ) . '/bootstrap/*.php' ) as $file ) { include_once $file; }

//* Custom Image Sizes
add_image_size( 'preview-image', 150, 120, false );

//* Set Localization
load_child_theme_textdomain( 'diemnhan', apply_filters( 'child_theme_textdomain', get_theme_file_path( '/languages' ), 'diemnhan' ) );

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* This theme styles the visual editor to resemble the theme style, specifically font, colors, and column width.
add_editor_style( get_theme_file_uri( '/assets/css/editor-style.css') );

//* Add Custome Menu
add_action( 'init', 'register_additional_menu' );
function register_additional_menu() {
    register_nav_menu( 'nav_menu' , 'Nav Menu');
}

//* Snippet Name: Add custom RSS Feed to dashboard
add_action( 'wp_dashboard_setup', 'nz_dashboard_widgets' );
function nz_dashboard_widgets() {
    add_meta_box( 'nz_dashboard_widget', __( 'Cập nhật WordPress', 'diemnhan' ), 'nz_dashboard_feed_output', 'dashboard', 'side', 'high' );
}
function nz_dashboard_feed_output() {
    echo '<div class="rss-widget">';
    wp_widget_rss_output(array(
        'url' => 'http://www.nicewebz.com/wordpress/feed/',
        'items' => 3,
        'show_summary' => 1,
        'show_author' => 0,
        'show_date' => 1
    ));
    echo "</div>"; ?>
    <div class="rss-widget" style="border-top: 1px solid #eee;padding: 8px 0 0;margin-top: 8px;"><ul><li class="dashboard-news-plugin"><span>Gói Bổ Xung Tính Năng Phổ Biến:</span> Limit Login Attempts&nbsp;<a href="plugin-install.php?tab=plugin-information&amp;plugin=limit-login-attempts&amp;_wpnonce=44a18e31c8&amp;TB_iframe=true&amp;width=772&amp;height=338" class="thickbox open-plugin-details-modal" aria-label="Cài đặt Limit Login Attempts">(Cài đặt)</a></li></ul></div>
    <?php
}

//* Begin enqueue scripts
add_action( 'wp_enqueue_scripts', function() {
    // jQuery default
    wp_enqueue_script( 'jquery' );

    // Normalize
    wp_enqueue_style( 'normalize', get_theme_file_uri( '/assets/css/normalize.css' ), array(), '6.0.0' );

    // Boostrap
    wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/bootstrap/css/bootstrap.min.css' ), array(), '3.3.7' );
    wp_enqueue_style( 'bootstrap-theme', get_theme_file_uri( '/bootstrap/css/bootstrap-theme.min.css' ), array(), '3.3.7' );
    wp_enqueue_script( 'bootstrap', get_theme_file_uri( '/bootstrap/js/bootstrap.min.js' ), array( 'jquery' ), '3.3.7', true );

    // Font-awesome
    wp_enqueue_style( 'font-awesome', get_theme_file_uri( '/font-awesome/css/font-awesome.min.css' ), array(), '4.7.0' );

    // Owl-carousel
    wp_enqueue_style( 'owl-carousel', get_theme_file_uri( '/assets/owl-carousel/dist/assets/owl.carousel.min.css' ), array(), '2.2.1' );
    wp_enqueue_style( 'owl-carousel-theme', get_theme_file_uri( '/assets/owl-carousel/dist/assets/owl.theme.default.min.css' ), array(), '2.2.1' );
    wp_enqueue_script( 'owl-carousel', get_theme_file_uri( '/assets/owl-carousel/dist/owl.carousel.js' ), array( 'jquery' ), '2.2.1', true );

    // MatchHeight
    wp_enqueue_script( 'matchHeight', get_theme_file_uri( '/assets/match-height/dist/jquery.matchHeight-min.js' ), array( 'jquery' ), '0.7.2', true );

    // Site scripts

    // End Site script

    // Diem nhan
    wp_enqueue_style( 'style', get_stylesheet_uri() );
    wp_enqueue_style( 'diemnhan', get_theme_file_uri( '/assets/css/diemnhan.css' ), array(), CHILD_THEME_VERSION );
    wp_enqueue_script( 'diemnhan', get_theme_file_uri( '/assets/js/diemnhan.js' ), array( 'jquery' ), CHILD_THEME_VERSION, true );
    wp_enqueue_script( 'nice', get_theme_file_uri( '/assets/js/nice.js' ), array( 'jquery' ), CHILD_THEME_VERSION, false );

    // Localize script
    $l10n_args = apply_filters( 'DN_VAR_JS', array(
        'ADMIN_URL'     => admin_url(),
        'SITE_URL'      => site_url(),
        'AJAX_URL'      => admin_url( 'admin-ajax.php'),
        'ASSETS_URL'    => get_theme_file_uri( '/assets' )
    ) );
    wp_localize_script( 'diemnhan', 'DN_VAR', $l10n_args );
}, 18 );


