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
        <?php completeTheOrder($ORDER_NUMBER) ?> // ezycompleteFunctions.php

        <div class="details">
            <h2>Completed Orders:</h2>
        </div>
        <?php
        drawAccountOrderTable($ORDER_NUMBER); // ezycompleteFunctions.php
        ?>
    </div>
    </div>
    </div>
    </div>
    </div>
    <?php get_footer(); ?>