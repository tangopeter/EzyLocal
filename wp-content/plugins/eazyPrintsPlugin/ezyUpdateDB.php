<?php

function addNewRowToUploads($orderArray)
{
  global $wpdb;
  $orderNumber = $orderArray[0];

  $thisOrder = array(
    'userID' => $orderArray[1],
    'file_name' => $orderArray[2],
    'file_path' => $orderArray[3],
    'qty' => $orderArray[4],
    'size' => $orderArray[5],
    'finish' => $orderArray[6],
    'resize' => $orderArray[7],
    'price' => $orderArray[8],
    'total_price' => $orderArray[9],
    'date_time' => $orderArray[10]
  );
  $upload = json_encode($thisOrder, JSON_PRETTY_PRINT);

  $wpdb->insert(
    $wpdb->prefix . 'ezyUploads', // name of the table
    array( // 'key' => 'value'
      'order_number' => $orderArray[0],
      'userID' => $orderArray[1],
      'uploads' => $upload
    ),
    array(
      '%d', //'order_number' => $orderArray[0],
      '%d', // 'user' => $orderArray[1]
      '%s'
    )
  );
}
