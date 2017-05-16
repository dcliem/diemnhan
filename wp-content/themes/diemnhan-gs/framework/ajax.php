<?php
/**
 * Điểm Nhấn (GS)
 * This file load data asynchronous for theme.
 *
 * @package Điểm Nhấn (CS)
 * @author  Media (GFS)
 * @license GPL-2.0+
 * @link    http://www.studiopress.com/
 */

add_action('wp_ajax_nopriv_post_ajax', 'post_ajax');
add_action('wp_ajax_post_ajax', 'post_ajax');

if (!function_exists('post_ajax')) {
  function post_ajax(){
    //$out = '123';
      $ppp     = (isset($_POST['ppp'])) ? $_POST['ppp'] : 3;
      $cat     = (isset($_POST['cat'])) ? $_POST['cat'] : 0;

      $args = array(
          'post_type'      => 'post',
          'posts_per_page' => $ppp,
          'category_name'            => $cat
      );

      $loop = new WP_Query($args);

      $out = '';

      if ($loop->have_posts()) :
        while ($loop->have_posts()) :
          $loop->the_post();

        $out .= get_the_title();

        endwhile;
      endif;

      wp_reset_postdata();

      wp_die($out);
  }
}