<?php

function doesThisOrderExist($ORDER_NUMBER)
{
  global $wpdb;
  $existing = false;
  $wpdb->show_errors();

  $existingOrders = $wpdb->get_results(
    $wpdb->prepare(
      "SELECT order_number
        FROM wp_ezy_orders
        WHERE order_number = %s",
      $ORDER_NUMBER
    )
  );
  if ($existingOrders) {
    echo "order " . $ORDER_NUMBER . " Exists";
    die();
  } else {
    return true;
  }
}


function completeTheOrder($ORDER_NUMBER)
{
  $current_user_ID = get_current_user_id();
  // does this order exist allready?
  $existing = doesThisOrderExist($ORDER_NUMBER);

  if (!$existing) {
    return;
  }



  $thisItem1 = array();

  global $wpdb;
  $wpdb->show_errors();

  $orders = $wpdb->get_results(
    $wpdb->prepare(
      "SELECT ID,order_number,userID,uploads
        FROM wp_ezyUploads
        WHERE order_number = %s",
      $ORDER_NUMBER
    )

  );

  foreach ($orders as $key => $order) {

    if ($order === end($orders)) {
      // echo '<pre> This Item1: ' . $thisItem1 . '</pre>';
      // User and Delivery details
      $thisUser = array(
        'first_name' => get_field('first_name', $current_user_ID),
        'last_name' => get_field('last_name', $current_user_ID),
        'email' => get_field('email', $current_user_ID),
        'address' => get_field('address', $current_user_ID),
        'suburb' => get_field('suburb', $current_user_ID),
        'city' => get_field('city', $current_user_ID),
        'postcode' => get_field('postcode', $current_user_ID),
        'phone' => get_field('phone', $current_user_ID),

        'delivery_details' => get_field('delivery_method_and_details', $current_user_ID),
        'rural_delivery' => get_field('rural_delivery', $current_user_ID),
        'saturday_delivery' => get_field('saturday_delivery', $current_user_ID),
        'deliver_to_postal_address' => get_field('deliver_to_postal_address', $current_user_ID),
        'postal_address' => get_field('postal_address', $current_user_ID),
        'additional_instructions' => get_field('additional_instructions', $current_user_ID)
      );
      $thisUser = json_encode($thisUser, JSON_PRETTY_PRINT);
      // echo '<pre> User:' . $thisUser  . '</pre>';

      // Costs
      $costs = array(
        'delivery_cost' => get_field('delivery_cost', $current_user_ID),
        'subtotal' => get_field('subtotal', $current_user_ID),
        'gst' => get_field('gst', $current_user_ID),
        'total' => get_field('total', $current_user_ID)
      );
      $costs = json_encode($costs, JSON_PRETTY_PRINT);
      // echo '<pre> upload1:' . $upload1 . '</pre>';
    }
    // Items
    $uploads = json_decode($order->uploads);

    $thisItem = array(
      'file_name' => $uploads->file_name,
      'qty' => $uploads->qty,
      'size' => $uploads->size,
      'finish' => $uploads->finish,
      'print_cost' => $uploads->total_price
    );

    array_push($thisItem1, $uploads->file_name);
    array_push($thisItem1, $uploads->qty,);
    array_push($thisItem1, $uploads->size,);
    array_push($thisItem1, $uploads->finish,);
    array_push($thisItem1, $uploads->total_price,);
  };

  $thisItem1 = json_encode($thisItem1, JSON_PRETTY_PRINT);
  // echo '<pre> $thisItem1:' . $thisItem1 . '</pre>';

  // Add all to DB
  $wpdb->insert(
    $wpdb->prefix . 'ezy_orders', // name of the table
    array( // 'key' => 'value'
      'order_number' => $ORDER_NUMBER,
      'user' => get_current_user_id(),
      'date' => date('Y-m-d H:i:s'),
      'order_status' => "tbp",
      'user_details' => $thisUser,
      'costs' => $costs,
      'items' => $thisItem1
    ),
    array(
      "%d", // $ORDER_NUMBER,
      '%d', // 'user',
      '%s', // 'date',
      '%s', // 'status'
      '%s', // 'costs'
      '%s', // 'user details'
      '%s', // 'this item'
    )
  );
}
