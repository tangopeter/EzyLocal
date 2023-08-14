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
    echo '<script>
        swal({title: "This order allready exists",icon: "error" });
        </script>';

    die();
  } else {
    return true;
  }
}


function completeTheOrder($ORDER_NUMBER)
{
  echo "completeTheOrder($ORDER_NUMBER)";
?>

<?php
  $current_user = wp_get_current_user();
  // // does this order number exist?
  // $existing = doesThisOrderExist($ORDER_NUMBER);

  // if (!$existing) {
  //   return;
  // }
  // $post_id = get_field('field_640d5fdec5c14', false, false);
  // $values = get_fields($post_id);

  $header_selection_id = get_field("field_640d5fdec5c14");
  $post = get_post($header_selection_id);
  global $post;
  echo $post->ID;

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
  $items = array();

  foreach ($orders as $key => $order) {
    // Items
    $uploads = json_decode($order->uploads);

    array_push($items, array(
      'file_name' => $uploads->file_name,
      'qty' => $uploads->qty,
      'size' => $uploads->size,
      'finish' => $uploads->finish,
      'print_cost' => $uploads->total_price
    ));

    if ($order === end($orders)) {
      $thisUser = array(
        'first_name' => get_field('first_name', $current_user),
        'last_name' => get_field('last_name', $current_user),
        'email' => get_field('email', $current_user),
        'address' => get_field('address', $current_user),
        'suburb' => get_field('suburb', $current_user),
        'city' => get_field('city', $current_user),
        'postcode' => get_field('postcode', $current_user),
        'phone' => get_field('phone', $current_user),

        'delivery_details' => get_field('delivery_method_and_details', $current_user),
        'rural_delivery' => get_field('rural_delivery', $current_user),
        'saturday_delivery' => get_field('saturday_delivery', $current_user),
        'deliver_to_postal_address' => get_field('deliver_to_postal_address', $current_user),
        'postal_address' => get_field('postal_address', $current_user),
        'additional_instructions' => get_field('additional_instructions', $current_user)
      );
      $thisUser = json_encode($thisUser, JSON_PRETTY_PRINT);
      // echo '<pre> User:' . $thisUser  . '</pre>';

      // Costs
      $costs = array(
        'print_cost' => get_field('print_cost', $current_user),
        'delivery_cost' => get_field('delivery_cost', $current_user),
        'subtotal' => get_field('subtotal', $current_user),
        'gst' => get_field('gst', $current_user),
        'total' => get_field('total', $current_user)
      );
      $costs = json_encode($costs, JSON_PRETTY_PRINT);
      // echo '<pre> upload1:' . $upload1 . '</pre>';
    }
  };

  $items = json_encode($items, JSON_PRETTY_PRINT);
  // echo '<pre> $thisItem1:' . $thisItem1 . '</pre>';

  // Add all to DB
  $wpdb->insert(
    $wpdb->prefix . 'ezy_orders', // name of the table
    array( // 'key' => 'value'
      'order_number' => $ORDER_NUMBER,
      'user' => get_current_user_id(),
      'date' => date('Y-m-d H:i:s'),
      'order_status' => $post->ID,
      'user_details' => $thisUser,
      'costs' => $costs,
      'items' => $items
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
