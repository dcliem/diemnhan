<?php
//* Begin theme filter
// Remove id from stylesheet
add_filter( 'style_loader_tag', function( $html ) {
    return preg_replace( '~\s+id=["\'][^"\']++["\']\s~', '', $html );
}, 10 );

// Function remove version
function remove_loader_src( $html ) {
    if( strpos( $html, 'ver=' ) )
        return remove_query_arg( 'ver', $html );
}
// Remove version from stylesheet
add_filter( 'style_loader_src', 'remove_loader_src', 10 );
// Remove version from script
add_filter( 'script_loader_src', 'remove_loader_src', 10 );

// Remove thumbnail width and height
function remove_image_size_attributes( $html ) {
    return preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
}
// Remove image size attributes from post thumbnails
add_filter( 'post_thumbnail_html', 'remove_image_size_attributes', 10 );
// Remove image size attributes from images added to a WordPress post
add_filter( 'image_send_to_editor', 'remove_image_size_attributes', 10 );

// Remove notification update
function remove_core_updates(){ global $wp_version;
    $checked = array(
        'last_checked'=> time(),
        'version_checked'=> $wp_version
    );
    return (object)$checked;
}
// Remove notification update core
add_filter('pre_site_transient_update_core','remove_core_updates');
// Remove notification update plugins
add_filter('pre_site_transient_update_plugins','remove_core_updates');
// Remove notification update themes
add_filter('pre_site_transient_update_themes','remove_core_updates');

// Display footer text
add_filter( 'admin_footer_text', function( $text ) {
    return base64_decode( 'PGEgaHJlZj0iaHR0cDovL3d3dy5uaWNld2Viei5jb20iIHRpdGxlPSJUaGnhur90IGvhur8gV2Vic2l0ZSBjaHV5w6puIG5naGnhu4dwIj5OaWNlIFdlYlo8L2E+IFRlY2hub2xvZ3kgZm9yIGxpZmUsIEFsbCBSaWdodHMgUmVzZXJ2ZWQ=', true );
}, 10 );

// Display site favicon
add_filter( 'genesis_pre_load_favicon', function( $favicon_url ) { global $NZ;
    $favicon = $NZ['info']['favicon'];
    return $favicon ? $favicon : NZ_URI . '/favicon.png';
}, 10 );

// Default post thumbnail
add_filter( 'post_thumbnail_html', function( $html ) {
    if ( empty( $html ) )
        $html = '<img src="' . get_theme_file_uri( '/assets/images/thumbnail.jpg' ) . '" class="wp-post-image" alt="Default thumbnail" />';
    return str_replace( 'class="', 'class="nz-images ', $html );
}, 10 );

// PageNavi bootstrap style
add_filter( 'wp_pagenavi', function( $html ) {
    $out = str_replace("<div","",$html);
    $out = str_replace("class='wp-pagenavi'>","",$out);
    $out = str_replace("<a","<li><a",$out);
    $out = str_replace("</a>","</a></li>",$out);
    $out = str_replace("<span","<li><span",$out);
    $out = str_replace("</span>","</span></li>",$out);
    $out = str_replace("</div>","",$out);
    return '<ul class="pagination pagination-centered">'.$out.'</ul>';
}, 10 );

//* Begin them action
// Remove WordPress generator meta
remove_action( 'wp_head', 'wp_generator' );

// Remove Visual Composer generator meta
if ( class_exists( 'Vc_Manager' ) ) {
    add_action('init', function() {
        remove_action('wp_head', array(visual_composer(), 'addMetaData'));
    }, 10);
}

// Remeve Genesis style
remove_action( 'genesis_meta', 'genesis_load_stylesheet' );

// Remeve Genesis footer
remove_action('genesis_footer', 'genesis_do_footer');

// Remove text
add_action('get_header', function() {
    ob_start('remove_text');
}, 10 );
add_action('wp_head', function() {
    ob_end_flush();
}, 100);
function remove_text( $output ) {
    $targets = array( "<!-- Admin only notice: this page doesn't show a meta description because it doesn't have one, either write it for this page specifically or go into the SEO -> Titles menu and set up a template. -->" );
    if ( defined('WPSEO_VERSION') ) {
        $targets = array_merge($targets, array(
            '<!-- This site is optimized with the Yoast SEO Premium plugin v'.WPSEO_VERSION.' - https://yoast.com/wordpress/plugins/seo/ -->',
            '<!-- / Yoast SEO Premium plugin. -->',
            '<!-- This site is optimized with the Yoast SEO plugin v'.WPSEO_VERSION.' - https://yoast.com/wordpress/plugins/seo/ -->',
            '<!-- / Yoast SEO plugin. -->'
        ));
    }
    $output = str_ireplace($targets, '', $output);
    $output = trim($output);
    $output = preg_replace('/^[ \t]*[\r\n]+/m', '', $output);
    return $output;
}

// Add admin style
add_action('admin_enqueue_scripts', function() {
    if ( is_admin() )
        wp_enqueue_style('nz-admin-styles', get_theme_file_uri( '/assets/css/nz-admin.css' ) );
        wp_enqueue_style( 'font-face', get_theme_file_uri( '/assets/css/nz-fonts.css' ) );
}, 10);

//* Remove Emoji from WordPress
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

//* Clean WordPress header
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head, 10, 0');

