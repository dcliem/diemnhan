<?php
/**
 * Điểm Nhấn (CS)
 * This file adds the landing page template to the theme.
 *
 * @package Điểm Nhấn (CS)
 * @author  Media (GFS)
 * @license GPL-2.0+
 * @link    http://www.studiopress.com/
 */

//* Template Name: Full Width

//* Loop Content
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'page_content_main' );
function page_content_main() { global $NZ;
    while ( have_posts() ) : the_post();
        the_content();
    endwhile;
}
genesis();