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


    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script type="text/javascript">
      jQuery(document).ready(function($) {
        var test = '667'
        //console.log(test)

        $.ajax({
          url: '/wp-admin/admin-ajax.php',
          data: {
            'action': 'php_tutorial',
            'php_test': test
          },
          success: function(data) {
            console.log("Happy")
          }
        });

      });
    </script>







    <?php get_footer(); ?>
</main>
</div>