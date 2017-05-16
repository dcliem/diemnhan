<?php
if ( !class_exists( 'WooCommerce' ) )
    return;
//Number products per page
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'dn_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'dn_wrapper_end', 10);

function dn_wrapper_start() {
   echo '<div class="content-sidebar-wrap">';
}

function dn_wrapper_end() {
   echo '</div>';
}

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
   add_theme_support( 'woocommerce' );
}

