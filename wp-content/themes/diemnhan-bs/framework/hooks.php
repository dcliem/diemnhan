<?php
/**
 * Điểm Nhấn (GS)
 * This file adds hock content to the theme.
 *
 * @package Điểm Nhấn (CS)
 * @author  Media (GFS)
 * @license GPL-2.0+
 * @link    http://www.studiopress.com/
 */

//* Header Content
remove_action( 'genesis_header', 'genesis_do_header' );
add_action( 'genesis_header', 'nz_header_main' );
add_action( 'genesis_before_header', 'nz_header_before' );
add_action( 'genesis_after_header', 'nz_header_after' );
//* Main Content
add_action( 'genesis_before_content', 'nz_main_before' );
add_action( 'genesis_after_content', 'nz_main_after' );
//* Loop Content
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
//* Footer Content
add_action( 'genesis_footer', 'nz_footer_main' );
add_action( 'genesis_before_footer', 'nz_footer_before' );
add_action( 'genesis_after_footer', 'nz_footer_after' );

//* Function Contents
function nz_header_main(){ get_template_part( 'templates/tplheader' ); }
function nz_header_before(){ get_template_part( 'templates/tplheader', 'before' ); }
function nz_header_after(){ get_template_part( 'templates/tplheader', 'after' ); }

function nz_main_before(){ get_template_part( 'templates/tplmain', 'before' ); }
function nz_main_after(){ get_template_part( 'templates/tplmain', 'after' ); }

function nz_footer_main(){ get_template_part( 'templates/tplfooter' ); }
function nz_footer_before(){ get_template_part( 'templates/tplfooter', 'before' ); }
function nz_footer_after(){ get_template_part( 'templates/tplfooter', 'after' ); }
