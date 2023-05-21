<?php

function finalizeButton()
{
?>
  <div class="myDiv2">
    <input type=button id="completeOrder" class="button1" name="btn-comp" value='Finalize the Order'>
  </div>
<?php
}

function existingOrder($ORDER_NUMBER)
{
  global $wpdb;
  $wpdb->show_errors();
  // echo $ORDER_NUMBER;
  $orderExists = $wpdb->get_col(
    "SELECT order_number
      FROM wp_ezy_orders
      WHERE order_number = $ORDER_NUMBER"
  );
  // echo   '<br>' . count($orderExists);
  // echo   '<br>' . $orderExists[0];
  if ($ORDER_NUMBER != $orderExists[0]) {
    completeTheOrder($ORDER_NUMBER);
  } else {
    echo
    $orderExists[0] . " exists";
  }
}

function completeTheOrder($ORDER_NUMBER)
{
  $current_user = wp_get_current_user();

  global $wpdb;

  $orders = $wpdb->get_results(
    $wpdb->prepare(
      "SELECT ID,order_number,userID,uploads
    FROM wp_ezyUploads
    WHERE order_number = %s",
      $ORDER_NUMBER
    )
  );






  $items = [];

  foreach ($orders as $order) {
    $uppy = json_decode($order->uploads);
    $items[] = $uppy;
    // echo '<pre>' . $order->uploads . '</pre>';
  }

  $upload3 = json_encode($items, JSON_PRETTY_PRINT);

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
  $upload = json_encode($thisUser, JSON_PRETTY_PRINT);
  $costs = array(
    'print_cost' => get_field('print_cost', $current_user),
    'delivery_cost' => get_field('delivery_cost', $current_user),
    'subtotal' => get_field('subtotal', $current_user),
    'gst' => get_field('gst', $current_user),
    'total' => get_field('total', $current_user)
  );
  $upload1 = json_encode($costs, JSON_PRETTY_PRINT);

  $wpdb->insert(
    $wpdb->prefix . 'ezy_orders', // name of the table
    array( // 'key' => 'value'
      'order_number' => $ORDER_NUMBER,
      'user' => get_current_user_id(),
      'date' => date('Y-m-d H:i:s'),
      'order_status' => "tba2",
      'costs' => $upload1,
      'user_details' => $upload,
      'items' => $upload3
    ),
    array(
      "%d", // $ORDER_NUMBER,
      '%d', // 'userID',
      '%s', // 'status'
      '%s', // 'costs'
      '%s', // 'user details'
      '%s' // 'items'
    )
  );
  // $ORDER_NUMBER = get_option('ORDER_NUMBER');
  // $ORDER_NUMBER++;
  // update_option('ORDER_NUMBER', $ORDER_NUMBER);
}


function drawAccountOrderTable($ORDER_NUMBER)
{
  echo '<div class="completedTable">';

  $USER = get_current_user_id();
  echo '<h5>User#: ', $USER . '</h5>';
  global $wpdb;
  $wpdb->show_errors();
  $totalPrice = 0;

  $users = $wpdb->get_results(
    $wpdb->prepare(
      "SELECT ID,order_number,user, date, order_status, costs, user_details, items
  FROM wp_ezy_orders
  WHERE user = %s",
      $USER
    )
  );
  echo '<div class="myData3">';
  $i = 0;
  $len = count($users);
  echo $i . " ", $len;

  foreach ($users as $user) :

    // echo "users: ", $user;
    $uppy = json_decode($user->items);

    $costs = $user->costs;
    $costs = json_decode($user->costs);
    // echo $users;

    // echo '<pre>' . var_dump($user) . '</pre>';
    // echo 'Print Cost: ' . $costs->print_cost . '<br/>';
    // echo 'Delivery Cost: ' . $costs->delivery_cost . '<br/>';
    // echo 'Subtotal: ' . $costs->subtotal . '<br/>';
    // echo '+ GST: ' .  $costs->gst . '<br/>';
    // echo 'Total: ' .  $costs->total . '<br/>';



    echo '<div class="mydata2">';

    echo '<table>';
    echo '<thead>';
    echo '<tr class="wfu_browser_tr wfu_included wfu_visible wfu_row-1 wfu_browser-1">';
    echo '<th class="wfu_browser_td wfu_col-1 wfu_browser-1">' . 'ID:' . '</th>';
    echo '<th class="wfu_browser_td wfu_col-1 wfu_browser-1">' . 'Order:' . '</th>';
    echo '<th class="wfu_browser_td wfu_col-2 wfu_browser-1">' . 'Date:' . '</th>';
    echo '<th class="wfu_browser_td wfu_col-3 wfu_browser-1">' . 'Order status:' . '</th>';
    echo '<th class="wfu_browser_td wfu_col-5 wfu_browser-1">' . 'User details:' . '</th>';
    echo '<th class="wfu_browser_td wfu_col-6 wfu_browser-1">' . 'delivery:' . '</th>';
    echo '<th class="wfu_browser_td wfu_col-7 wfu_browser-1">' . 'items' . '</th>';
    echo '<th class="wfu_browser_td wfu_col-4 wfu_browser-1">' . 'Costs:' . '</th>';
    echo '<th class="wfu_browser_td wfu_col-4 wfu_browser-1">' . 'Edit:' . '</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    echo '<h5>Order#: ', $user->order_number . '</h5>';
    echo '<tr class="wfu_browser_tr wfu_included wfu_visible wfu_row-1 wfu_browser-1">';
    echo '<td class="wfu_browser_td wfu_col-2 wfu_browser-1">' . $user->ID . '</td>';
    echo '<td class="wfu_browser_td wfu_col-2 wfu_browser-1">' . $user->order_number . '</td>';
    echo '<td class="wfu_browser_td wfu_col-3 wfu_browser-1">' . $user->date . '</td>';
    echo '<td class="wfu_browser_td wfu_col-3 wfu_browser-1">' . $user->order_status . '</td>';


    echo '<td class="wfu_browser_td wfu_col-5 wfu_browser-1">' . $user->user_details . '</td>';
    // echo '<td class="wfu_browser_td wfu_col-6 wfu_browser-1">' . $user->delivery_details . '</td>';
    echo '<td class="wfu_browser_td wfu_col-7 wfu_browser-1">' . $user->items . '</td>';
    echo '<td class="wfu_browser_td wfu_col-4 wfu_browser-1">' .
      'Print Cost: $<strong>' . $costs->print_cost . '</strong><br/>' .
      'Delivery Cost: $<strong>'  . $costs->delivery_cost . '</strong><br/>' .
      'Subtotal: $<strong>'  . $costs->subtotal . '</strong><br/>' .
      '+ GST:  $<strong>'  .  $costs->gst . '</strong><br/>' .
      'Total:  $<strong>'  .  $costs->total . '</strong><br/>' .
      '<br/>' . '</td>';
    echo '<td class="wfu_browser_td wfu_col-4 wfu_browser-1">' . '<a href="#">' . 'edit' . '</a>' . '</td>';
    // echo '<td class="wfu_browser_td wfu_col-4 wfu_browser-1">' . '</td>';
    echo '</tr>';
    // $totalPrice = $totalPrice + $uppy->total_price;

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
    echo '<td id="totalPrintPrice" class="wfu_browser_td wfu_col-9 wfu_browser-1">' . number_format((float)$totalPrice, 2, '.', '') . '</td>';
    echo '<td class="wfu_browser_td wfu_col-8 wfu_browser-1">' . '</td>';
    // echo '<td class="wfu_browser_td wfu_col-10 wfu_browser-1">' . '</td>';
    // echo '<td class="wfu_browser_td wfu_col-11 wfu_browser-1">' . '</td>';
    // echo '<td class="wfu_browser_td wfu_col-12 wfu_browser-1">' . '</td>';
    echo '</tr>';
    echo '</tfoot>';
    echo '</table';
    echo '</div>';
    echo '</div>';
    echo '</div>';
  endforeach;
}
