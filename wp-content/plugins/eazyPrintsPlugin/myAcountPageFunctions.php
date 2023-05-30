<?php

function drawAccountOrderTable($ORDER_NUMBER)
{
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
  foreach ($users as $user) :
    $items = json_decode($user->items);
    $totalPrice = 0;

    $costs = $user->costs;
    $costs = json_decode($user->costs);
    $thisUser = $user->user_details;
    $thisUser = json_decode($user->user_details);
    $items = $user->items;
    $items = json_decode($user->items);


    // echo '<pre> Userss:' . $thisUser  . '</pre>';
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
    echo '<h5>Order#: ', $user->order_number . '</h5>';
    echo '<tr class="wfu_browser_tr wfu_included wfu_visible wfu_row-1 wfu_browser-2">';
    echo '<td class="wfu_browser_td wfu_col-1 wfu_browser-2">' . $user->ID . '</td>';
    echo '<td class="wfu_browser_td wfu_col-2 wfu_browser-2">' . $user->order_number . '</td>';
    echo '<td class="wfu_browser_td wfu_col-3 wfu_browser-2">' . $user->date . '</td>';
    echo '<td class="wfu_browser_td wfu_col-4 wfu_browser-2">' .

      $user->order_status . '</td>';

    echo '<td class="wfu_browser_td wfu_col-5 wfu_browser-2">' .
      'User: <strong>' . $thisUser->first_name . " " . $thisUser->last_name . '</strong><br />' .
      'email: <strong>' . $thisUser->email . '</strong><br />' .
      'Address: <strong>' . $thisUser->address . '</strong><br />' .
      'Suburb: <strong>' . $thisUser->suburb . '</strong><br />' .
      'City: <strong>' . $thisUser->city . ' ' . $thisUser->postcode . '</strong><br />' .
      'Phone: <strong>' . $thisUser->phone . '<br />' .

      '</td>';
    echo '<td class="wfu_browser_td wfu_col-6 wfu_browser-2">' .
      'Delivery: <strong>' . $thisUser->delivery_details . '</strong><br />' .
      'Rural: <strong>' . $thisUser->rural_delivery . '</strong><br />' .
      'Saturday: <strong>' . $thisUser->saturday_delivery . '</strong><br />' .
      // 'Postal: <strong>' . $thisUser->deliver_to_postal_address . '</strong><br />' .
      'Postal address: <strong>' . '<br />' . $thisUser->postal_address . '</strong><br />' .
      'Additional Instructions: <strong>' . '<br />' . $thisUser->additional_instructions . '</strong><br />' .
      '</td>';

    echo '<td class="wfu_browser_td wfu_col-7 wfu_browser-2">' .
      'File: <strong>' . $items->file_name . '</strong><br />' .
      'Qty: <strong>' . $items->qty . '</strong><br />' .
      'Size: <strong>' . $items->size . '</strong><br />' .
      'Finish: <strong> ' . $items->finish . '</strong><br />' .
      '<br />' . '</td>';

    echo '<td class="wfu_browser_td wfu_col-8 wfu_browser-2">' .
      'Print Cost: $<strong>' . $costs->print_cost . '</strong><br />' .
      'Delivery Cost: $<strong>' . $costs->delivery_cost . '</strong><br />' .
      'Subtotal: $<strong>' . $costs->subtotal . '</strong><br />' .
      '+ GST: $<strong>' . $costs->gst . '</strong><br />' .
      'Total: $<strong>' . $costs->total . '</strong><br />' .
      '<br />' . '</td>';
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
    echo '</div>';
    echo '</div>';
  endforeach;
  echo '</div>';
  echo '</div>';
  echo '</div>';
}