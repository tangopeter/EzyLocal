<?php

/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 *
 * @package HelloElementor
 * Template Name: My Account template
 * Template Post Type: page
 */
?>
<?php
$ORDER_NUMBER = get_option('ORDER_NUMBER');

acf_form_head();
get_header();
?>
<main id="content" <?php post_class('site-main'); ?> role="main">
    <?php if (apply_filters('hello_elementor_page_title', true)) : ?>
        <header class="page-header">
            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
        </header>

    <?php endif; ?>

    <div class="page-content">
        <div class="details">
            <?php // https://www.advancedcustomfields.com/resources/acf_form/  
            ?>
            <div class="completeTheOrder">
                <h3>My Details:</h3>
                <?php showUserDetails(); ?>

            </div>
        </div>

        <!-- <?php existingOrder($ORDER_NUMBER) ?> -->
        <?php completeTheOrder($ORDER_NUMBER) ?>


        <div class="details">
            <h2>Completed Orders:</h2>
        </div>
        <?php
        drawAccountOrderTable($ORDER_NUMBER); // ezycompleteFunctions.php
        ?>

        <!-- <div class="myDiv2">
            <input type="button" name="btn-newOrder" id="btn-newOrder" value='New order'>
        </div> -->

        <?php echo get_option('ORDER_NUMBER'); ?>

        <?php

        if (isset($_POST['button1'])) {
            increaseOrderNumber();
            // wp_redirect('https://ezylocal:8890/?page_id=54');
            echo get_option('ORDER_NUMBER');
            exit;
        }
        if (isset($_POST['button2'])) {
            decreaseOrderNumber();
            echo get_option('ORDER_NUMBER');
        }
        ?>
        <div class="myDiv2">
            <form method="post">
                <input type="submit" name="button1" value="New Order" />

                <input type="submit" name="button2" value="Previous Order">
        </div>
        <div class=" myFooter">
            <?php get_footer(); ?>
        </div>