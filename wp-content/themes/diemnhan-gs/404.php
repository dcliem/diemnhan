<?php
//* Loop Content
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'page_content_main' );
function page_content_main() { ?>
    Nội dung truy cập không tồn tại
    <?php
}
genesis();