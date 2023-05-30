<?php

function drawOrderTable($ORDER_NUMBER)
{
  global $wpdb;
  $wpdb->show_errors();
  $totalPrice = 0;

  $orders = $wpdb->get_results(
    $wpdb->prepare(
      "SELECT ID,order_number,userID,uploads
    FROM wp_ezyUploads
    WHERE order_number = %s",
      $ORDER_NUMBER
    )
  );

  echo '<div class="myDiv1">';
  echo '<h5>Order#: ', $ORDER_NUMBER . '</h5>';
  echo '<table>';
  echo '<thead>';
  echo '<tr class="wfu_browser_tr wfu_included wfu_visible wfu_row-1 wfu_browser-1">';
  echo '<th class="wfu_browser_td wfu_col-1 wfu_browser-1">' . 'User ID:' . '</th>';
  echo '<th class="wfu_browser_td wfu_col-1 wfu_browser-1">' . 'Order:' . '</th>';
  echo '<th class="wfu_browser_td wfu_col-2 wfu_browser-1">' . 'File Name:' . '</th>';
  echo '<th class="wfu_browser_td wfu_col-3 wfu_browser-1">' . 'File path:' . '</th>';
  echo '<th class="wfu_browser_td wfu_col-4 wfu_browser-1">' . 'Qty:' . '</th>';
  echo '<th class="wfu_browser_td wfu_col-5 wfu_browser-1">' . 'Size:' . '</th>';
  echo '<th class="wfu_browser_td wfu_col-6 wfu_browser-1">' . 'Resize:' . '</th>';
  echo '<th class="wfu_browser_td wfu_col-7 wfu_browser-1">' . 'Finish:' . '</th>';
  echo '<th class="wfu_browser_td wfu_col-8 wfu_browser-1">' . 'Price:' . '</th>';
  echo '<th class="wfu_browser_td wfu_col-9 wfu_browser-1">' . 'Total Price:' . '</th>';
  echo '<th class="wfu_browser_td wfu_col-10 wfu_browser-1">' . 'Date/time:' . '</th>';
  echo '<th class="wfu_browser_td wfu_col-4 wfu_browser-1">' . 'Status:' . '</th>';
  echo '<th class="wfu_browser_td wfu_col-4 wfu_browser-1">' . 'Edit:' . '</th>';
  echo '</tr>';
  echo '</thead>';
  echo '<tbody>';
  foreach ($orders as $order) {
    $uppy = json_decode($order->uploads);

    echo '<tr class="wfu_browser_tr wfu_included wfu_visible wfu_row-1 wfu_browser-1">';
    echo '<td class="wfu_browser_td wfu_col-2 wfu_browser-1">' . $order->userID . '</td>';
    echo '<td class="wfu_browser_td wfu_col-2 wfu_browser-1">' . $order->order_number . '</td>';
    echo '<td class="wfu_browser_td wfu_col-3 wfu_browser-1">' . $uppy->file_name . '</td>';
    echo '<td class="wfu_browser_td wfu_col-3 wfu_browser-1">' . $uppy->file_path . '</td>';
    echo '<td class="wfu_browser_td wfu_col-4 wfu_browser-1">' . $uppy->qty . '</td>';
    echo '<td class="wfu_browser_td wfu_col-5 wfu_browser-1">' . $uppy->size . '</td>';
    echo '<td class="wfu_browser_td wfu_col-6 wfu_browser-1">' . $uppy->resize . '</td>';
    echo '<td class="wfu_browser_td wfu_col-7 wfu_browser-1">' . $uppy->finish . '</td>';
    echo '<td class="wfu_browser_td wfu_col-8 wfu_browser-1">' . $uppy->price . '</td>';
    echo '<td class="wfu_browser_td wfu_col-9 wfu_browser-1">' . $uppy->total_price . '</td>';
    echo '<td class="wfu_browser_td wfu_col-10 wfu_browser-1">' . $uppy->date_time . '</td>';
    echo '<td class="wfu_browser_td wfu_col-4 wfu_browser-1">' . '</td>';
    echo '<td class="wfu_browser_td wfu_col-4 wfu_browser-1">' . '<a href="#">' . 'edit' . '</a>' . '</td>';
    echo '</tr>';
    $totalPrice = $totalPrice + $uppy->total_price;
  }
  echo '</tbody>';
  echo '<tfoot>';
  echo '<tr class="wfu_browser_tr wfu_included wfu_visible wfu_row-1 wfu_browser-1">';
  echo '<td class="wfu_browser_td wfu_col-1 wfu_browser-1">' . '</td>';
  echo '<td class="wfu_browser_td wfu_col-2 wfu_browser-1">' . '</td>';
  echo '<td class="wfu_browser_td wfu_col-3 wfu_browser-1">' . '</td>';
  echo '<td class="wfu_browser_td wfu_col-4 wfu_browser-1">' . '</td>';
  echo '<td class="wfu_browser_td wfu_col-5 wfu_browser-1">' . '</td>';
  echo '<td class="wfu_browser_td wfu_col-6 wfu_browser-1">' . '</td>';
  echo '<td class="wfu_browser_td wfu_col-7 wfu_browser-1">' . '</td>';
  echo '<td class="wfu_browser_td wfu_col-8 wfu_browser-1">' . '</td>';
  echo '<td class="wfu_browser_td wfu_col-8 wfu_browser-1">' . '</td>';
  echo '<td id="totalPrintPrice" class="wfu_browser_td wfu_col-9 wfu_browser-1">' . number_format((float)$totalPrice, 2, '.', '') . '</td>';
  echo '<td class="wfu_browser_td wfu_col-10 wfu_browser-1">' . '</td>';
  echo '<td class="wfu_browser_td wfu_col-11 wfu_browser-1">' . '</td>';
  echo '<td class="wfu_browser_td wfu_col-12 wfu_browser-1">' . '</td>';
  echo '</tr>';
  echo '</tfoot>';
  echo '</table';
}

function existingOrder($ORDER_NUMBER)
{
  global $wpdb;
  $wpdb->show_errors();
  $orderExists = $wpdb->get_col(
    "SELECT order_number
  FROM wp_ezy_orders"
  );
  $existing = false;
  foreach ($orderExists as $key => $order) {
    if ($ORDER_NUMBER == $order) {
      echo $key . " : " . $order . "<br>";
      $existing = true;
    } else {
      echo '<p>' . 'Order number ' . $order . ' already exists' . '</p>';
    }
    if (!$existing) {
      completeTheOrder($ORDER_NUMBER);
    }
  }
}


function completeTheOrder($ORDER_NUMBER)
{
  $current_user = wp_get_current_user();
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
    // Items
    $uploads = json_decode($order->uploads);

    $thisItem = array(
      'file_name' => $uploads->file_name,
      'qty' => $uploads->qty,
      'size' => $uploads->size,
      'finish' => $uploads->finish,
      'print_cost' => $uploads->total_price
    );
    // $thisItem = json_encode($thisItem, JSON_PRETTY_PRINT);
    array_push($thisItem1, $thisItem);

    if ($order === end($orders)) {
      // echo '<pre> This Item1: ' . $thisItem1 . '</pre>';
      // User and Delivery details
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
        'delivery_cost' => get_field('delivery_cost', $current_user),
        'subtotal' => get_field('subtotal', $current_user),
        'gst' => get_field('gst', $current_user),
        'total' => get_field('total', $current_user)
      );
      $upload1 = json_encode($costs, JSON_PRETTY_PRINT);
      // echo '<pre> Costs:' . $upload1 . '</pre>';
    }
    if ($order === end($orders)) {
      // $thisItem1 = json_encode($thisItem1, JSON_PRETTY_PRINT);
      break;
    }
  };
  // Add all to DB
  $wpdb->insert(
    $wpdb->prefix . 'ezy_orders', // name of the table
    array( // 'key' => 'value'
      'order_number' => $ORDER_NUMBER,
      'user' => get_current_user_id(),
      'date' => date('Y-m-d H:i:s'),
      'order_status' => "tbp",
      'user_details' => $thisUser,
      'costs' => $upload1,
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
