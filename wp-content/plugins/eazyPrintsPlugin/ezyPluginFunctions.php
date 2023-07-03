<?php

function doSomethingElse()
{
  // echo '<div class="completeTheOrder">';
  // echo '<h3>' . "not complete" . '</h3>';
  // echo '</div>';
}

function function_alert($message)
{
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
    'submit_value'  => __('Update your profile')
  ));
}

function showCostDetails()
{ ?>
  <p>Print Cost:
    <span id="printCostTotalPriceID">0.00</span>
  </p>
  <p>Delivery Price:
    <span id="deliveryCostPriceID">0.00</span>
  </p>
  <p>Subtotal:
    <span id="subtotalCostPriceID">0.00</span>
  </p>
  <p>+GST:
    <span id="gstCostPriceID">0.00</span>
  </p>
  <p>Total Cost:
    <span id="costsTotalPriceID">0.00</span>
  </p>
<?php
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
    'submit_value'  => __('Update your delivery details')
  ));
}
