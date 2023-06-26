<?php

function doSomethingElse()
{
  // echo '<div class="completeTheOrder">';
  // echo '<h3>' . "not complete" . '</h3>';
  // echo '</div>';
}

function function_alert($message)
{
  // Display the alert box 
  echo  '<script>
    swal(  title: $message,
 
    );
  </script>';
}

function showUserDetails()
{
  $current_user = wp_get_current_user();
  acf_form(array(
    'post_id'       => $current_user,
    'form' => true,
    'form_attributes' => array(),
    'post_title'    => false,
    'field_groups' => array(2786),
    'fields' => array('first_name', 'last_name', 'email', 'address', 'suburb', 'city', 'country', 'postcode', 'phone'),
    'return' => add_query_arg('updated', 'true', get_permalink()),
    'submit_value'  => __('Update Profile')
  ));
}

function showCostDetails()
{
  $current_user = wp_get_current_user();
  acf_form(array(
    'post_id' => $current_user,
    'form' => true,
    'form_attributes' => array(),
    'post_title'    => false,
    'field_groups' => array(2816),
    'fields' => array('print_cost', 'delivery_cost', 'subtotal', 'gst', 'total', 'complete_order',),
    // 'submit_value'  => __('Update meta')
  ));
}

function showDeliveryDetails()
{
  $current_user = wp_get_current_user();
  acf_form(array(
    'post_id'       => $current_user,
    'form' => true,
    'form_attributes' => array(),
    'post_title'    => false,
    'field_groups' => array(2823),
    'fields' => array('delivery_method_and_details', 'rural_delivery', 'saturday_delivery', 'deliver_to_postal_address', 'postal_address', 'additional_instructions'),
    'return' => add_query_arg('updated', 'true', get_permalink()),
    'submit_value'  => __('Update Delivery Details')
  ));
}
