<?php
//* Loop Content
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'front_content_main' );
function front_content_main() { global $NZ;
}
genesis();