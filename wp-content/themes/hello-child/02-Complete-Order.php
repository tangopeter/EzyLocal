<?php

/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 *
 * @package HelloElementor
 * Template Name: 02 Complete-Order
 * Template Post Type: page
 */

?>
<?php acf_form_head(); ?>
<?php get_header(); ?>
<?php
$ORDER_NUMBER = get_option('ORDER_NUMBER');
?>

<main id=" content" <?php post_class('site-main'); ?> role="main">
  <?php if (apply_filters('hello_elementor_page_title', true)) : ?>
    <header class="page-header">
      <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
    </header>

  <?php endif; ?>


  <div class="page-content">
    <?php echo '<p> Order number: ' . get_option('ORDER_NUMBER') . '</p>' ?>

    <div class="details">
      <div class="completeTheOrder completeTheOrder1">
        <h3>Costs:</h3>
        <?php showCostDetails(); ?>
      </div>

      <div class="completeTheOrder completeTheOrder2">
        <h3>Delivery:</h3>
        <div id="deliveryCosts">
          <?php showDeliveryDetails(); ?>
        </div>

      </div>
      <div class="completeTheOrder completeTheOrder3">
        <h3>My Details:</h3>
        <?php showUserDetails(); ?>
      </div>
    </div>
  </div>
  <div class="page-content">
    <?php drawOrderTable($ORDER_NUMBER); ?>
  </div>
  <?php
  echo '<div class="myDiv2">';
  echo "<input type='button' class='completeOrder' value='Finalize the Order' onclick='finalizeOrder()'>";

  echo "<script>
      function finalizeOrder() {
        window.location.href = 'https://ezylocal:8890/?page_id=2572';
      }
    </script>";
  echo '</div>';
  ?>
  <div class="myFooter">
    <?php get_footer(); ?>
  </div>