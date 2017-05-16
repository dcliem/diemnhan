<?php
//* Loop Content
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'archive_content_main' );
function archive_content_main() { global $NZ;
    if (have_posts()): while (have_posts()) : the_post(); ?>
    <?php
    endwhile;
    endif;
}
genesis();