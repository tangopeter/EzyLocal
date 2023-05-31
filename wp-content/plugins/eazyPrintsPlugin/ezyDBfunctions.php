<?php
function showStuff($ORDER_NUMBER)
{
  // todo echo '<!-- MYDATA -->';
  // ! echo '<div class="myData">';
  // ? echo '
  echo '<pre> dump' . var_dump($ORDER_NUMBER) . '</pre>';
  // * echo '
  // \\ </div>';
}

function increaseOrderNumber()
{
  $orderNumber = get_option('ORDER_NUMBER');
  $orderNumber++;
  update_option('ORDER_NUMBER', $orderNumber);
}
function decreaseOrderNumber()
{
  $orderNumber = get_option('ORDER_NUMBER');
  $orderNumber--;
  update_option('ORDER_NUMBER', $orderNumber);
}
