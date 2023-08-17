<?php

function drawOrderTable($ORDER_NUMBER)
{
  global $wpdb;
  $wpdb->show_errors();
  $totalPrice = 0;
  $items =  array();

  $orders = $wpdb->get_results(
    $wpdb->prepare(
      "SELECT ID,order_number,userID,uploads
    FROM wp_ezyUploads
    WHERE order_number = %s",
      $ORDER_NUMBER
    )
  );

  echo '<div class="myDiv1">';
  echo '<h5>This order: ', $ORDER_NUMBER . '</h5>';
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


  // DOM Parser Object
  // echo '<p>' . "fred" . '</p>';
  // $dom = new DOMDocument();
  // $dom->validateOnParse = true;
  // $dom->loadHTML($totalPrice);

  // $dom->preserveWhiteSpace = false;
  // $totalPrice - $dom->getElementById("totalPrintPrice2");
  // echo '<p>' . $totalPrice->nodeValue . '</p>';
  // echo '<p>' . $totalPrice . '</p>';
}


function drawAccountOrderTable($ORDER_NUMBER)
{
  echo "completedTable ++++++++++++++++++++++++++++++++++++++++++++";
  echo '<div class="completedTable">';
  $USER = get_current_user_id();
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
  // foreach ($orderExists as $key => $order) {
  //   if ($ORDER_NUMBER == $order) {
  //     echo $key . " : " . $order . "<br>";
  foreach ($users as $key => $user) :


    $totalPrice = 0;

    $thisUser = $user->user_details;
    $thisUser = json_decode($user->user_details);

    $costs = $user->costs;
    // echo '<h5>Order#: ', $user->order_number . ' ' . $key . '</h5>';
    // $items = json_decode($user->items);


    // $items = json_decode($items);


    // echo '<pre> User:' . $thisUser  . '</pre>';
    // $thisUser = json_encode($thisUser, JSON_PRETTY_PRINT);


    echo '<table>';
    echo '<thead>';
    echo '<tr class="wfu_browser_tr wfu_included wfu_visible wfu_row-1 wfu_browser-2">';
    echo '<th class="wfu_browser_td wfu_col-1 wfu_browser-2">' . 'ID:' . '</th>';
    echo '<th class="wfu_browser_td wfu_col-2 wfu_browser-2">' . 'Order:' . '</th>';
    echo '<th class="wfu_browser_td wfu_col-3 wfu_browser-2">' . 'Date:' . '</th>';
    echo '<th class="wfu_browser_td wfu_col-4 wfu_browser-2">' . 'Order status:' . '</th>';
    echo '<th class="wfu_browser_td wfu_col-5 wfu_browser-2">' . 'User details:' . '</th>';
    echo '<th class="wfu_browser_td wfu_col-6 wfu_browser-2">' . 'delivery:' . '</th>';
    echo '<th class="wfu_browser_td wfu_col-7 wfu_browser-2">' . 'items' . '</th>';
    echo '<th class="wfu_browser_td wfu_col-4 wfu_browser-2">' . 'Costs:' . '</th>';
    echo '<th class="wfu_browser_td wfu_col-4 wfu_browser-2">' . 'Edit:' . '</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    echo '<tr class="wfu_browser_tr wfu_included wfu_visible wfu_row-1 wfu_browser-2">';
    echo '<td class="wfu_browser_td wfu_col-1 wfu_browser-2">' . $user->ID . '</td>';
    echo '<td class="wfu_browser_td wfu_col-2 wfu_browser-2">' . $user->order_number . '</td>';
    echo '<td class="wfu_browser_td wfu_col-3 wfu_browser-2">' . $user->date . '</td>';
    echo '<td class="wfu_browser_td wfu_col-4 wfu_browser-2">' . $user->order_status . '</td>';

    // * user details
    echo '<td class="wfu_browser_td wfu_col-5 wfu_browser-2">' .
      'User: <strong>' . $thisUser->first_name . " " . $thisUser->last_name . '</strong><br/>' .
      'email: <strong>' . $thisUser->email . '</strong><br/>' .
      'Address: <strong>' . $thisUser->address . '</strong><br/>' .
      'Suburb: <strong>' . $thisUser->suburb . '</strong><br/>' .
      'City: <strong>' . $thisUser->city . ' ' . $thisUser->postcode . '</strong><br/>' .
      'Phone: <strong>' . $thisUser->phone . '<br/>' .

      '</td>';

    // * Delivery
    echo '<td class="wfu_browser_td wfu_col-6 wfu_browser-2">' .
      'Delivery: <strong>' . $thisUser->delivery_details . '</strong><br/>' .
      'Rural: <strong>' . $thisUser->rural_delivery . '</strong><br/>' .
      'Saturday: <strong>' . $thisUser->saturday_delivery . '</strong><br/>' .
      'Postal: <strong>' . $thisUser->deliver_to_postal_address . '</strong><br/>' .
      'Postal address: <strong>' . '<br/>' . $thisUser->postal_address . '</strong><br/>' .
      'Additional Instructions:  <strong>' . '<br/>' . $thisUser->additional_instructions . '</strong><br/>' .
      '</td>';

    // * items
    echo '<td class="wfu_browser_td wfu_col-7 wfu_browser-2">';
    $items = json_decode($user->items);
    foreach ($items as $item) :
      echo 'File Name: <strong>' . $item->file_name . '</strong><br/>' .
        'Qty: <strong>' . $item->qty . '</strong><br/>' .
        'Size: <strong>' . $item->size . '</strong><br/>' .
        'Finish: <strong>' . $item->finish . '</strong><br/>' .
        'Cost: <strong>' . $item->print_cost . '</strong><br/><hr>';

    endforeach;
    '<br/>' . '</td>';

    // * Costs
    echo '<td class="wfu_browser_td wfu_col-8 wfu_browser-2">' .

      'Print Cost: $<strong>' . $costs->print_cost . '</strong><br/>' .
      'Delivery Cost: $<strong>'  . $costs->delivery_cost . '</strong><br/>' .
      'Subtotal: $<strong>'  . $costs->subtotal . '</strong><br/>' .
      '+ GST:  $<strong>'  .  $costs->gst . '</strong><br/>' .
      'Total:  $<strong>'  .  $costs->total . '</strong><br/>' .
      '<br/>' . '</td>';

    // todo - update to yes/no
    echo '<td class="wfu_browser_td wfu_col-9 wfu_browser-2">' . '<a href="#">' . 'edit' . '</a>' . '</td>';
    // echo '<td class="wfu_browser_td wfu_col-4 wfu_browser-1">' . '</td>';
    echo '</tr>';
    $totalPrice = $totalPrice + $costs->total;

    echo '</tbody>';
    echo '<tfoot>';
    echo '<tr class="wfu_browser_tr wfu_included wfu_visible wfu_row-1 wfu_browser-2">';
    echo '<td class="wfu_browser_td wfu_col-1 wfu_browser-2">' . '</td>';
    echo '<td class="wfu_browser_td wfu_col-2 wfu_browser-2">' . '</td>';
    echo '<td class="wfu_browser_td wfu_col-3 wfu_browser-2">' . '</td>';
    echo '<td class="wfu_browser_td wfu_col-4 wfu_browser-2">' . '</td>';
    echo '<td class="wfu_browser_td wfu_col-5 wfu_browser-2">' . '</td>';
    echo '<td class="wfu_browser_td wfu_col-6 wfu_browser-2">' . '</td>';
    echo '<td class="wfu_browser_td wfu_col-7 wfu_browser-2">' . '</td>';
    echo '<td id="totalPrintPrice2" class="wfu_browser_td wfu_col-8 wfu_browser-2">' . number_format((float)$totalPrice, 2, '.', '') . '</td>';
    echo '<td class="wfu_browser_td wfu_col-9 wfu_browser-2">' . '</td>';
    // echo '<td class="wfu_browser_td wfu_col-10 wfu_browser-1">' . '</td>';
    // echo '<td class="wfu_browser_td wfu_col-11 wfu_browser-1">' . '</td>';
    // echo '<td class="wfu_browser_td wfu_col-12 wfu_browser-1">' . '</td>';
    echo '</tr>';
    echo '</tfoot>';
    echo '</table';
    echo '</div>';
  // echo '</div>';
  // echo '</div>';

  endforeach;
  echo '</div>';
}
