<?php

/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 *
 * @package HelloElementor
 * Template Name: testy2222
 * Template Post Type: page
 */
?>

<?php get_header(); ?>

<main id="content" <?php post_class('site-main'); ?> role="main">
  <?php if (apply_filters('hello_elementor_page_title', true)) : ?>
    <header class="page-header">
      <!-- <?php the_title('<h1 class="entry-title">', '</h1>'); ?> -->
    </header>
  <?php endif; ?>


  <div class="page-content">
    <?php
    echo 'testing';
    function our_tutorial()
    {

      if (isset($_REQUEST)) {
        $testing = $_REQUEST['php_test'];

        echo 'This is our JS var: ' . $testing;
      }
      die();
    }
    add_action('wp_ajax_php_tutorial', 'our_tutorial');
    ?>

    <script type="text/javascript">
      jQuery(document).ready(function($) {
        var test = '75'
        console.log(test)

        $.ajax({
          url: '/wp-admin/admin-ajax.php',
          data: {
            'action': 'php_test',
            'php_test': test
          },
          success: function(data) {
            console.log("happy");
          },
        });
      });
    </script>





    <?php

    ?>
    <?php get_footer(); ?>
</main>
</div>