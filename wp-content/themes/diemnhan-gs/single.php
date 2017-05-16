<?php
//* Loop Content
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'single_content_main' );
function single_content_main() { global $NZ;
    while ( have_posts() ) : the_post(); ?>
    <?php
    endwhile;
}
genesis();