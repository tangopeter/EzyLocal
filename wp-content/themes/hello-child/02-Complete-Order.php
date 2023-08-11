<?php

/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 *
 * @package HelloElementor
 * Template Name: 02 Complete-Order
 * Template Post Type: page
 */


?>

<head>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js">
  </script>
</head>

<?php acf_form_head(); ?>
<?php get_header(); ?>
<?php
$ORDER_NUMBER = get_option('ORDER_NUMBER');
$current_user_ID = get_current_user_id();

?>

<main id=" content" <?php post_class('site-main'); ?> role="main">
  <?php if (apply_filters('hello_elementor_page_title', true)) : ?>
    <header class="page-header">
      <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
    </header>

  <?php endif; ?>


  <div class="page-content">
    <?php echo '<p> Order number: ' . get_option('ORDER_NUMBER') . '</p>' ?>
    <div class="completeOrderPage">

      <div class="completeTheOrder completeTheOrder1">
        <h3>Delivery:</h3>
        <div id="deliveryCosts">
          <?php showDeliveryDetails(); ?>
        </div>
      </div>


      <div class="completeTheOrder completeTheOrder2">
        <h3>Costs:</h3>
        <p>Print Cost:
          <span id="printCostTotalPriceID">$0.00</span>
        </p>
        <p>Delivery Price:
          <span id="deliveryCostPriceID">0</span>
        </p>
        <p>Subtotal:
          <span id="subtotalCostPriceID">0</span>
        </p>
        <p>+GST:
          <span id="gstCostPriceID">0</span>
        </p>
        <p>Total Cost:
          <span id="costsTotalPriceID">0</span>
        </p>
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

  <div class="myDiv2">

    <form method="post">
      <input type="submit" name="btn-complete2" value="Finalize this order">
    </form>
    <?php
    if (isset($_POST['btn-complete2'])) {
    ?>
      <script>
        window.location.href = 'https://ezylocal:8890/?page_id=2572';
      </script>
    <?php  }
    ?>
    <div class="myFooter">
      <?php get_footer(); ?>
    </div>
  </div>
</main>