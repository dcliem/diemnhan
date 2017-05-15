<?php
/*
Plugin Name: Diemnhan framework Guide 
Plugin URI: http://diemnhan.com
Description: Find diem nhan framework hooks (action and filter hooks) quick and easily by seeing their actual locations inside your theme.
Version: 1.0.0
Author: Huu Ha
Author URI: http://diemnhan.com
License: GPLv2
*/


register_activation_hook(__FILE__, 'wplb_activation_check');
function wplb_activation_check() {

	    $theme_info = wp_get_theme();

		$genesis_flavors = array(
			'genesis',
			'genesis-trunk',
		);

        if ( ! in_array( $theme_info->Template, $genesis_flavors ) ) {
            deactivate_plugins( plugin_basename(__FILE__) ); // Deactivate ourself
	        wp_die('Sorry, you can\'t activate unless you have installed <a href="http://www.diemnhan.com">Diem Nhan</a>');
        }
}

add_action( 'admin_bar_menu', 'wplb_admin_bar_links', 100 );
function wplb_admin_bar_links() {
global $wp_admin_bar;

	if ( is_admin() )
		return;

	$wp_admin_bar->add_menu(
		array(
			'id' => 'ghooks',
			'title' => __( 'G Hook Guide', 'gvisualhookguide' ),
			'href' => '',
			'position' => 0,
		)
	);
	$wp_admin_bar->add_menu(
		array(
			'id'	   => 'ghooks_action',
			'parent'   => 'ghooks',
			'title'    => __( 'Action Hooks', 'gvisualhookguide' ),
			'href'     => add_query_arg( 'g_hooks', 'show' ),
			'position' => 10,
		)
	);
	// $wp_admin_bar->add_menu(
	// 	array(
	// 		'id'	   => 'ghooks_action_and_functions',
	// 		'parent'   => 'ghooks',
	// 		'title'    => __( 'Action Hooks And Functions', 'gvisualhookguide' ),
	// 		'href'     => add_query_arg( 'g_hooks_and_function', 'show' ),
	// 		'position' => 10,
	// 	)
	// );
	$wp_admin_bar->add_menu(
		array(
			'id'	   => 'ghooks_filter',
			'parent'   => 'ghooks',
			'title'    => __( 'Filter Hooks', 'gvisualhookguide' ),
			'href'     => add_query_arg( 'g_filters', 'show' ),
			'position' => 10,
		)
	);
	$wp_admin_bar->add_menu(
		array(
			'id'	   => 'ghooks_markup',
			'parent'   => 'ghooks',
			'title'    => __( 'Markup', 'gvisualhookguide' ),
			'href'     => add_query_arg( 'g_markup', 'show' ),
			'position' => 10,
		)
	);
	$wp_admin_bar->add_menu(
		array(
			'id'	   => 'ghooks_clear',
			'parent'   => 'ghooks',
			'title'    => __( 'Clear', 'gvisualhookguide' ),
			'href'     => remove_query_arg(
				array(
					'g_hooks',
					'g_hooks_and_function',
					'g_filters',
					'g_markup',
				)
			),
			'position' => 10,
		)
	);

}

add_action('wp_enqueue_scripts', 'wplb_hooks_stylesheet');
function wplb_hooks_stylesheet() {

	 $wplb_plugin_url = plugins_url() . '/dnfw-guide/';

	 if ( 'show' == isset( $_GET['g_hooks'] ) || 'show' == isset( $_GET['g_hooks_and_function'] ) )
	 	wp_enqueue_style( 'wplb_styles', $wplb_plugin_url . 'styles.css' );

	 if ( 'show' == isset( $_GET['g_filters'] ) )
	 	wp_enqueue_style( 'wplb_styles', $wplb_plugin_url . 'styles.css' );

     if ( 'show' == isset( $_GET['g_markup'] ) )
     	wp_enqueue_style( 'wplb_markup_styles', $wplb_plugin_url . 'markup.css' );

}


add_action('get_header', 'wplb_genesis_hooker' );
function wplb_genesis_hooker() {
global $wplb_genesis_action_hooks;

	 if ( !('show' == isset( $_GET['g_hooks_and_function'] ) ) && !('show' == isset( $_GET['g_hooks'] ) ) && !('show' == isset( $_GET['g_filters'] ) ) && !('show' == isset( $_GET['g_markup'] ) ) ) {
		 return;  // BAIL without hooking into anyhting if not displaying anything
	 }

	$wplb_genesis_action_hooks = array(

			'genesis_doctype' => array(
				'hook' => 'genesis_doctype',
				'area' => 'Document Head',
				'description' => '',
				'functions' => array(),
				),
			'genesis_title' => array(
				'hook' => 'genesis_title',
				'area' => 'Document Head',
				'description' => 'This hook executes between tags and outputs the doctitle. You can find all doctitle related code in /lib/structure/header.php.',
				'functions' => array(),
				),
			'genesis_meta' => array(
				'hook' => 'genesis_meta',
				'area' => 'Document Head',
				'description' => 'This hook executes in the section of the document source. By default, things like META descriptions and keywords are output using this hook, along with the default stylesheet and the reference to the favicon.',
				'functions' => array(),
				),
			'genesis_home' => array(
				'hook' => 'genesis_home',
				'area' => 'Structural',
				'description' => '',
				'functions' => array(),
				),
			'genesis_before' => array(
				'hook' => 'genesis_before',
				'area' => 'Structural',
				'description' => 'This hook executes immediately after the opening tag in the document source.',
				'functions' => array(),
				),
			'genesis_before_header' => array(
				'hook' => 'genesis_before_header',
				'area' => 'Structural',
				'description' => 'This hook executes immediately before the header (outside the #header div).',
				'functions' => array(),
				),
			'genesis_header' => array(
				'hook' => 'genesis_header',
				'area' => 'Structural',
				'description' => 'By default, this hook outputs the header code, including the title, description, and widget area (if necessary).',
				'functions' => array(),
				),
			'genesis_header_right' => array(
				'hook' => 'genesis_header_right',
				'area' => 'Structural',
				'description' => 'This hook executes immediately before the Header Right widget area inside div.widget-area.',
				),
			'genesis_site_title' => array(
				'hook' => 'genesis_site_title',
				'area' => 'Structural',
				'description' => 'By default, this hook outputs the site title, within the header area. It uses the user-specified SEO settings to build the site title markup appropriately.',
				'functions' => array(),
				),
			'genesis_site_description' => array(
				'hook' => 'genesis_site_description',
				'area' => 'Structural',
				'description' => 'By default, this hook outputs the site description, within the header area. It uses the user-specified SEO settings to build the site description markup appropriately.',
				'functions' => array(),
				),
			'genesis_after_header' => array(
				'hook' => 'genesis_after_header',
				'area' => 'Structural',
				'description' => 'This hook executes immediately after the header (outside the #header div).',
				'functions' => array(),
				),
			'genesis_before_content_sidebar_wrap' => array(
				'hook' => 'genesis_before_content_sidebar_wrap',
				'area' => 'Structural',
				'description' => 'This hook executes immediately before the div block that wraps the content and the primary sidebar (outside the #content-sidebar-wrap div).',
				'functions' => array(),
				),
			'genesis_before_content' => array(
				'hook' => 'genesis_before_content',
				'area' => 'Structural',
				'description' => 'This hook executes immediately before the content column (outside the #content div).',
				'functions' => array(),
				),
			'genesis_before_loop' => array(
				'hook' => 'genesis_before_loop',
				'area' => 'Loop',
				'description' => 'This hook executes immediately before all loop blocks. Therefore, this hook falls outside the loop, and cannot execute functions that require loop template tags or variables.',
				'functions' => array(),
				),
			'genesis_loop' => array(
				'hook' => 'genesis_loop',
				'area' => 'Loop',
				'description' => 'This hook outputs the actual loop. See lib/structure/loop.php and lib/structure/post.php for more details.',
				'functions' => array(),
				),
			'genesis_before_entry' => array(
				'hook' => 'genesis_before_entry',
				'area' => 'Loop',
				'description' => 'This hook executes before each post in all loop blocks (outside the post_class() article).',
				'functions' => array(),
				),
			'genesis_entry_header' => array(
				'hook' => 'genesis_entry_header',
				'area' => 'Loop',
				'description' => 'This hook executes immediately inside the article element for each post within the loop.',
				'functions' => array(),
				),
			'genesis_before_entry_content' => array(
				'hook' => 'genesis_entry_content',
				'area' => 'Loop',
				'description' => 'This hook executes immediately before the post/page content is output, outside the .entry-content div.',
				'functions' => array(),
				),
			'genesis_entry_content' => array(
				'hook' => 'genesis_entry_content',
				'area' => 'Loop',
				'description' => 'This hook outputs the actual post content and if chosen, the post image (inside the #content div).',
				'functions' => array(),
				),
			'genesis_after_entry_content' => array(
				'hook' => 'genesis_entry_content',
				'area' => 'Loop',
				'description' => 'This hook executes immediately after the post/page content is output, outside the .entry-content div.',
				'functions' => array(),
				),
			'genesis_entry_footer' => array(
				'hook' => 'genesis_entry_footer',
				'area' => 'Loop',
				'description' => 'This hook executes immediately before the close of the article element for each post within the loop.',
				'functions' => array(),
				),
			'genesis_after_entry' => array(
				'hook' => 'genesis_after_entry',
				'area' => 'Loop',
				'description' => 'This hook executes after each post in all loop blocks (outside the post_class() article).',
				'functions' => array(),
				),
			'genesis_before_post' => array(
				'hook' => 'genesis_before_post',
				'area' => 'Loop',
				'description' => 'This hook executes before each post in all loop blocks (outside the post_class() div).',
				'functions' => array(),
				),
			'genesis_before_post_title' => array(
				'hook' => 'genesis_before_post_title',
				'area' => 'Loop',
				'description' => 'This hook executes immediately before each post title for each post within the loop.',
				'functions' => array(),
				),
			'genesis_post_title' => array(
				'hook' => 'genesis_post_title',
				'area' => 'Loop',
				'description' => 'This hook outputs the actual post title, contextually, based on what type of page you are viewing.',
				'functions' => array(),
				),
			'genesis_after_post_title' => array(
				'hook' => 'genesis_after_post_title',
				'area' => 'Loop',
				'description' => 'This hook executes immediately after each post title for each post within the loop.',
				'functions' => array(),
				),
			'genesis_before_post_content' => array(
				'hook' => 'genesis_before_post_content',
				'area' => 'Loop',
				'description' => 'This hook executes immediately before the post/page content is output, outside the .entry-content div.',
				'functions' => array(),
				),
			'genesis_post_content' => array(
				'hook' => 'genesis_post_content',
				'area' => 'Loop',
				'description' => 'This hook outputs the actual post content and if chosen, the post image (inside the #content div).',
				'functions' => array(),
				),
			'genesis_after_post_content' => array(
				'hook' => 'genesis_after_post_content',
				'area' => 'Loop',
				'description' => 'This hook executes immediately after the post/page content is output, outside the .entry-content div.',
				'functions' => array(),
				),
			'genesis_after_post' => array(
				'hook' => 'genesis_after_post',
				'area' => 'Loop',
				'description' => 'This hook executes after each post in all loop blocks (outside the post_class() div).',
				'functions' => array(),
				),
			'genesis_before_comments' => array(
				'hook' => 'genesis_before_comments',
				'area' => 'Comment',
				'description' => 'This hook executes immediately before the comments block (outside the #comments div).',
				'functions' => array(),
				),
			'genesis_list_comments' => array(
				'hook' => 'genesis_list_comments',
				'area' => 'Comment',
				'description' => 'This hook executes inside the comments block, inside the .comment-list OL. By default, it outputs a list of comments associated with a post via the genesis_default_list_comments() function.',
				'functions' => array(),
				),
			'genesis_before_comment' => array(
				'hook' => 'genesis_before_comment',
				'area' => 'Comment',
				'description' => 'This hook executes before the output of each individual comment (author, meta, comment text).',
				'functions' => array(),
				),
			'genesis_comment' => array(
				'hook' => 'genesis_comment',
				'area' => 'Comment',
				'description' => '',
				'functions' => array(),
				),
			'genesis_after_comment' => array(
				'hook' => 'genesis_after_comment',
				'area' => 'Comment',
				'description' => 'This hook executes after the output of each individual comment (author, meta, comment text).',
				'functions' => array(),
				),
			'genesis_after_comments' => array(
				'hook' => 'genesis_after_comments',
				'area' => 'Comment',
				'description' => 'This hook executes immediately after the comments block (outside the #comments div).',
				'functions' => array(),
				),
			'genesis_before_pings' => array(
				'hook' => 'genesis_before_pings',
				'area' => 'Comment',
				'description' => 'This hook executes immediately before the pings block (outside the #pings div).',
				'functions' => array(),
				),
			'genesis_list_pings' => array(
				'hook' => 'genesis_list_pings',
				'area' => 'Comment',
				'description' => 'This hook executes inside the pings block, inside the .ping-list OL. By default, it outputs a list of pings associated with a post via the genesis_default_list_pings() function.',
				'functions' => array(),
				),
			'genesis_after_pings' => array(
				'hook' => 'genesis_after_pings',
				'area' => 'Comment',
				'description' => 'This hook executes immediately after the pings block (outside the #pings div).',
				'functions' => array(),
				),
			'genesis_before_respond' => array(
				'hook' => 'genesis_before_respond',
				'area' => 'Comment',
				'description' => '',
				'functions' => array(),
				),
			'genesis_before_comment_form' => array(
				'hook' => 'genesis_before_comment_form',
				'area' => 'Comment',
				'description' => 'This hook executes immediately before the comment form, outside the #respond div.',
				'functions' => array(),
				),
			'genesis_comment_form' => array(
				'hook' => 'genesis_comment_form',
				'area' => 'Comment',
				'description' => 'This hook outputs the actual comment form, including the #respond div wrapper.',
				'functions' => array(),
				),
			'genesis_after_comment_form' => array(
				'hook' => 'genesis_after_comment_form',
				'area' => 'Comment',
				'description' => 'This hook executes immediately after the comment form, outside the #respond div.',
				'functions' => array(),
				),
			'genesis_after_respond' => array(
				'hook' => 'genesis_after_respond',
				'area' => 'Comment',
				'description' => '',
				'functions' => array(),
				),
			'genesis_after_endwhile' => array(
				'hook' => 'genesis_after_endwhile',
				'area' => 'Loop',
				'description' => 'This hook executes after the endwhile; statement in all loop blocks.',
				'functions' => array(),
				),
			'genesis_loop_else' => array(
				'hook' => 'genesis_loop_else',
				'area' => 'Loop',
				'description' => 'This hook executes after the else : statement in all loop blocks.',
				'functions' => array(),
				),
			'genesis_after_loop' => array(
				'hook' => 'genesis_after_loop',
				'area' => 'Loop',
				'description' => 'This hook executes immediately after all loop blocks. Therefore, this hook falls outside the loop, and cannot execute functions that require loop template tags or variables.',
				'functions' => array(),
				),
			'genesis_after_content' => array(
				'hook' => 'genesis_after_content',
				'area' => 'Structural',
				'description' => 'This hook executes immediately after the content column (outside the #content div).',
				'functions' => array(),
				),
			'genesis_before_sidebar_widget_area' => array(
				'hook' => 'genesis_before_sidebar_widget_area',
				'area' => 'Structural',
				'description' => 'This hook executes immediately before the primary sidebar widget area (inside the #sidebar div).',
				'functions' => array(),
				),
			'genesis_after_sidebar_widget_area' => array(
				'hook' => 'genesis_after_sidebar_widget_area',
				'area' => 'Structural',
				'description' => 'This hook executes immediately after the primary sidebar widget area (inside the #sidebar div).',
				'functions' => array(),
				),
			'genesis_after_content_sidebar_wrap' => array(
				'hook' => 'genesis_after_content_sidebar_wrap',
				'area' => 'Structural',
				'description' => 'This hook executes immediately after the div block that wraps the content and the primary sidebar (outside the #content-sidebar-wrap div).',
				'functions' => array(),
				),
			'genesis_before_sidebar_alt_widget_area' => array(
				'hook' => 'genesis_before_sidebar_alt_widget_area',
				'area' => 'Structural',
				'description' => 'This hook executes immediately before the alternate sidebar widget area (inside the #sidebar-alt div).',
				'functions' => array(),
				),
			'genesis_after_sidebar_alt_widget_area' => array(
				'hook' => 'genesis_after_sidebar_alt_widget_area',
				'area' => 'Structural',
				'description' => 'This hook executes immediately after the alternate sidebar widget area (inside the #sidebar-alt div).',
				'functions' => array(),
				),
			'genesis_before_footer' => array(
				'hook' => 'genesis_before_footer',
				'area' => 'Structural',
				'description' => 'This hook executes immediately before the footer, outside the #footer div.',
				'functions' => array(),
				),
			'genesis_footer' => array(
				'hook' => 'genesis_footer',
				'area' => 'Structural',
				'description' => 'This hook, by default, outputs the content of the footer, including the #footer div wrapper.',
				'functions' => array(),
				),
			'genesis_after_footer' => array(
				'hook' => 'genesis_after_footer',
				'area' => 'Structural',
				'description' => 'This hook executes immediately after the footer, outside the #footer div.',
				'functions' => array(),
				),
			'genesis_after' => array(
				'hook' => 'genesis_after',
				'area' => 'Structural',
				'description' => 'This hook executes immediately before the closing tag in the document source.',
				'functions' => array(),
				)
		);

	foreach ( $wplb_genesis_action_hooks as $action )
		add_action( $action['hook'] , 'wplb_genesis_action_hook' , 1 );
}

function wplb_genesis_action_hook () {
global $wplb_genesis_action_hooks;

	$current_action = current_filter();

	if ( 'show' == isset( $_GET['g_hooks'] )) {

		if ( 'Document Head' == $wplb_genesis_action_hooks[$current_action]['area'] ) :

			echo "<!-- ";
				echo $current_action;
			echo " -->\n";

		else :

			echo '<div class="genesis_hook">' . $current_action . wplb_list_hooked_functions($current_action).'</div>';

		endif;
	}

}

function wplb_list_hooked_functions($tag=false, $class=''){

	global $wp_filter;

	$html = '';

	if($tag){
		$hook[$tag] = $wp_filter[$tag];

		$html .= '<div class="'.$class.' functions ">';
		foreach($hook as $tag => $priority){
		  // ksort($priority);
			  foreach($priority as $priority => $function){
			  	$html .= $priority.': ';
				  	foreach($function as $name => $properties){ 
				  		$html .= "$name ";
				  	}
				$html .= '<br />';
			  }
		 }
		 $html .= '</div>';
	}

	return $html;
	


}

function wplb_hook_name( $function, $element = null, $description = null ) {
    $function_name = str_replace( 'gvhg', 'genesis', $function );
    if ( $element ) {

        return '<' . $element . ' class="filter" >' . $function_name . wplb_list_hooked_functions($function_name,"filter").'</' . $element . '>';
    }
    return $function;
}

add_action( 'wp', 'wplb_filter_logic' );
function wplb_filter_logic() {

	if ( 'show' == isset( $_GET['g_filters'] ) ) {

		add_filter( 'genesis_seo_title', 'wplb_genesis_seo_title', 10, 3 );
		add_filter( 'genesis_seo_description', 'wplb_genesis_seo_description', 10, 3 );
		add_filter( 'genesis_title_comments', 'wplb_title_comments');
		add_filter( 'genesis_comment_form_args', 'wplb_comment_form_args');
		add_filter( 'genesis_comments_closed_text', 'wplb_comments_closed_text');
		add_filter( 'comment_author_says_text', 'wplb_comment_author_says_text');
		add_filter( 'genesis_no_comments_text', 'wplb_no_comments_text');
		add_filter( 'genesis_title_pings', 'wplb_title_pings');
		add_filter( 'ping_author_says_text', 'wplb_ping_author_says_text');
		add_filter( 'genesis_no_pings_text', 'wplb_no_pings_text');
		add_filter( 'genesis_breadcrumb_args', 'wplb_breadcrumb_args');
		add_filter( 'genesis_footer_backtotop_text', 'wplb_footer_backtotop_text', 100);
		add_filter( 'genesis_footer_creds_text', 'wplb_footer_creds_text', 100);
		//add_filter( 'genesis_footer_output', 'wplb_footer_output', 100, 3);
		add_filter( 'genesis_author_box_title', 'wplb_author_box_title' );
		add_filter( 'genesis_post_info', 'wplb_post_info' );
		add_filter( 'genesis_post_meta', 'wplb_post_meta' );
		add_filter( 'genesis_post_title_text', 'wplb_post_title_text');
		add_filter( 'genesis_noposts_text', 'wplb_noposts_text');
		add_filter( 'genesis_search_text', 'wplb_search_text');
		add_filter( 'genesis_search_button_text', 'wplb_search_button_text');
		add_filter( 'genesis_nav_home_text', 'wplb_nav_home_text');
		add_filter( 'genesis_favicon_url', 'wplb_favicon_url');
		add_filter( 'genesis_footer_credits', 'wplb_footer_creds_text');

	}

}

function wplb_genesis_seo_title( $title, $inside, $wrap ) {
	return sprintf('<%s id="title">' . wplb_hook_name( __FUNCTION__, 'span', 'Applied to the output of the genesis_seo_site_title function which depending on the SEO option set by the user will either wrap the title in <h1> or <p> tags. Default value: $title, $inside, $wrap' ) . '</%s>', $wrap, $wrap);
}

function wplb_genesis_seo_description( $description, $inside, $wrap ) {
	return sprintf('<%s id="title">' . wplb_hook_name( __FUNCTION__, 'span', 'Applied to the output of the genesis_seo_site_description function which depending on the SEO option set by the user will either wrap the description in <h1> or <p> tags. Default value: $description, $inside, $wrap' ) . '</%s>', $wrap, $wrap);
}

function wplb_author_box_title() {
	return '<strong>' . wplb_hook_name( __FUNCTION__, 'span' ) . '</strong>';
}

function wplb_comment_author_says_text() {
	return wplb_hook_name( __FUNCTION__, 'span' );
}

function wplb_ping_author_says_text() {
	return wplb_hook_name( __FUNCTION__, 'span' );
}

function wplb_footer_backtotop_text() {
    return wplb_hook_name( __FUNCTION__, 'div' );
}

function wplb_footer_creds_text() {
    return wplb_hook_name( __FUNCTION__, 'div' );
}

function wplb_footer_output($output, $backtotop_text, $creds) {
    return wplb_hook_name( __FUNCTION__, 'div' ) . $backtotop_text . $creds;
}

function wplb_breadcrumb_args($args) {
	$args['prefix'] = '<div class="breadcrumb"><span class="filter">genesis_breadcrumb_args</span> ';
    $args['suffix'] = '</div>';
	$args['home'] = __('<span class="filter">[\'home\']</span>', 'genesis');
    $args['sep'] = '<span class="filter">[\'sep\']</span>';
    $args['labels']['prefix'] = __('<span class="filter">[\'labels\'][\'prefix\']</span> ', 'genesis');
	return $args;
}

function wplb_title_pings() {
    echo wplb_hook_name( __FUNCTION__, 'h3' );
}

function wplb_no_pings_text() {
    echo wplb_hook_name( __FUNCTION__, 'p' );
}

function wplb_title_comments() {
    echo wplb_hook_name( __FUNCTION__, 'h3' );
}

function wplb_comments_closed_text() {
    echo wplb_hook_name( __FUNCTION__, 'p' );
}

function wplb_no_comments_text() {
    echo wplb_hook_name( __FUNCTION__, 'p' );
}

function wplb_comment_form_args($args) {
    $args['title_reply'] = '<span class="filter">genesis_comment_form_args [\'title_reply\']</span>';
    $args['comment_notes_before'] = '<span class="filter">genesis_comment_form_args [\'comment_notes_before\']</span>';
    $args['comment_notes_after'] = '<span class="filter">genesis_comment_form_args [\'comment_notes_after\']</span>';

    return $args;
}

function wplb_favicon_url() {
    return 'genesis_favicon_url';
}

function wplb_post_info() {
    return wplb_hook_name( __FUNCTION__, 'span' );
}

function wplb_post_meta() {
    return wplb_hook_name( __FUNCTION__, 'span' );
}

function wplb_post_title_text() {
	return wplb_hook_name( __FUNCTION__, 'span' );
}

function wplb_noposts_text() {
	return wplb_hook_name( __FUNCTION__, 'span' );
}

function wplb_search_text() {
	return esc_attr('genesis_search_text');
}

function wplb_search_button_text() {
	return esc_attr('genesis_search_button_text');
}

function wplb_nav_home_text() {
	return wplb_hook_name( __FUNCTION__, 'span' );
}
