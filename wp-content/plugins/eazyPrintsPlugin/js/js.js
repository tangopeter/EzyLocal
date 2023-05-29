import { orderPage, getDeliveryMethod , checkRuralDelivery , checkSatDelivery , getOrderSize , UpdateOrderTotals } from './orderPage.js';
import { orderPage2 } from './orderPage2.js';
import { completeMyOrder } from './completeOrder.js';
import { completeThisOrder } from './accountPage.js';
// import { goToCompleteOrder } from './ezyFunctions.js';


// console.log("js  +", document.title  );

// console.log("js " + document.title);
// jQuery(document).ready(function ($) {
//   if (typeof acf !== 'undefined') {
//     console.log('ACF is defined', acf);
//   }
//   else {
//     console.log('ACF not defined', acf);
//   }
// });

switch (document.title)
{
  case 'Order Prints – Ezy Prints':
    // console.log("4 " + document.title);
    orderPage2();
    break;
  case 'testy – Ezy Prints':
    console.log("5 " + document.title);
    break;
  case 'Complete Order – Ezy Prints':
    console.log("6 " + document.title);
    completeMyOrder();
    break;
  case 'My account – Ezy Prints':
    console.log("7 " + document.title);
    completeThisOrder();
    break;
  default:
    console.log("10 "+ document.title);

  }

