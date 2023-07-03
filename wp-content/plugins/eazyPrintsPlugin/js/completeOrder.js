// console.log("complete order pages");

// jQuery(document).ready(function ($) {
//   if (typeof acf !== 'undefined') {
//     console.log('ACF is defined', acf);
//   }
// });
// const newOrder = document.getElementById("btn-newOrder");
// newOrder.addEventListener('click', createNewOrder);


export function completeMyOrder() {
  let dm = (Number(4.50)).toFixed(2);
  let rp = (Number(0.00)).toFixed(2);
  let sd = (Number(0.00)).toFixed(2);
  let total = (Number(0.00)).toFixed(2);

  let printCostTotal = (Number(0.00)).toFixed(2);
  let deliveryCost = (Number(0.00)).toFixed(2);
  let subtotalCost = (Number(0.00)).toFixed(2);
  let gstCost = (Number(0.00)).toFixed(2);
  let totalCost = (Number(0.00)).toFixed(2);




  const deliveryMethod = document.getElementById("acf-field_640d61d5084e4");
  const rural = document.getElementById("acf-field_640d61d5084ec");
  const saturday = document.getElementById("acf-field_640d61d508503");
  const toPostal = document.getElementById("acf-field_640d61d50850d");

  const printPriceCost = document.getElementsByClassName("printCostTotalPrice");
  const deliveryCostPrice = document.getElementsByClassName("deliveryCostPrice");
  const subtotalCostPrice = document.getElementsByClassName("subtotalCostPrice");
  const gstCostPrice = document.getElementsByClassName("gstCostPrice");
  const costsTotalPrice = document.getElementsByClassName("costsTotalPrice");
  
  printPriceCost.textContent = printCostTotal;
  deliveryCostPrice.textContent = deliveryCost;
  subtotalCostPrice.textContent = subtotalCost;
  gstCostPrice.textContent = gstCost;
  costsTotalPrice.textContent = totalCost;

  deliveryMethod.addEventListener('change', getDeliveryMethod);
  rural.addEventListener('change', checkRuralDelivery);
  saturday.addEventListener('change', checkSatDelivery);
  toPostal.addEventListener('click', toPostalClick)

  const deliveryMethodPrice = document.createElement("p");
  const ruralPrice = document.createElement("p");
  const saturdayPrice = document.createElement("p");



    // Add a Delivery method Cost field
  deliveryMethodPrice.textContent = dm;
  deliveryMethodPrice.classList.add('delPriceMethod');
  deliveryMethod.insertAdjacentElement('afterEnd', deliveryMethodPrice);

  // Add a Rural Delivery cost field
  ruralPrice.textContent = rp;
  ruralPrice.classList.add('ruralPrice');
  rural.insertAdjacentElement('afterEnd', ruralPrice);
  // Add a Saturday Delivery cost field
  saturdayPrice.textContent = sd;
  saturdayPrice.classList.add('satPrice');
  saturday.insertAdjacentElement('afterEnd', saturdayPrice);

  // get the print cost total and update 
  const printPrice = document.getElementById("totalPrintPrice");
  var pp = printPrice.textContent;
  console.log(pp);
  // pp = (Number(pp).toFixed(2));
  // printPriceCost.textContent = pp;
  // const printCost = document.getElementById("printCostTotalPrice");
  printPriceCost.value = (Number(printPrice).toFixed(2));
  updateOrderValue();

  // get the delivery method cost and update 
  function getDeliveryMethod(event) {
    var dm = event.target.value;
    deliveryMethodPrice.textContent = dm;
    updateOrderValue();
  }
  // Get the Rural delivery and cost
  function checkRuralDelivery(event) {
    var rp = 0;
    // console.log(event)
    
    let checkBox = acf.getField('field_640d61d5084ec');
    if (checkBox.val() == 1) {
      rp = 4.50;
    } else {
      rp = 0.00;
    }
    // console.log("rp = ", rp);
    let rp1 = (Number(rp)).toFixed(2)
    updateNumberWFUField2(ruralPrice, rp1);
    updateOrderValue();
  }

  // Get the Saturday delivery and cost
  function checkSatDelivery(event) {
    var sd = 0;
    let checkBox = acf.getField('field_640d61d508503');
    if (checkBox.val() == 1) {
      sd = 5.00;
    } else {
      sd = 0.00;
    }
    // console.log("sd = ", sd);
    let sd1 = (Number(sd)).toFixed(2)
    updateNumberWFUField2(saturdayPrice, sd1);
    updateOrderValue();
  }
  // Send to postal address
  function toPostalClick(event) {
    const mailToAddress = document.getElementById("mailAddress");
    mailToAddress.classList.toggle("showField");
  }
}
function updateOrderValue() {
  // Print Cost:
  const printPrice = document.getElementsByClassName("totalPrintPrice");
  var pp = printPrice.textContent;
  pp = (Number(pp).toFixed(2));
  console.log("pp: ", pp)
  const printCostTotalPrice = document.getElementsByClassName("printCostTotalPrice");
  // printCost.value = pp;
  printCostTotalPrice.value = pp;
  // console.log('Print Cost: ', printCost.value);

  const parentDOM = document.getElementsByClassName("deliveryCosts");
  const deliveryMethodPrice = document.getElementsByClassName("delPriceMethod")[0].innerText;
  console.log('deliveryMethodPrice: ', deliveryMethodPrice);

  const ruralPrice = parentDOM.getElementsByClassName("ruralPrice")[0].innerText;
  // console.log('Rural Cost: ', ruralPrice);

  const satPrice = parentDOM.getElementsByClassName("satPrice")[0].innerText;
  // console.log('Sat Cost: ', satPrice);

  var deliveryTotal =
    Number(deliveryMethodPrice) +
    Number(ruralPrice) +
    Number(satPrice);

  const totalDeliveryCost = document.getElementById("deliveryCostPrice");
  console.log('totalDeliveryCost: ', totalDeliveryCost);
  totalDeliveryCost.value(deliveryTotal.toFixed(2));
  
  // console.log('printCost: ', typeof printCost.value);
  console.log('deliveryTotal: ', deliveryTotal);
  // console.log('ruralPrice: ', ruralPrice);
  // console.log('satPrice: ', satPrice);

  var pp = Number(pp);
  var dt = Number(deliveryTotal);
  var rp = Number(ruralPrice);
  var sp = Number(satPrice);
  console.log('printCost: ', typeof pp, pp);
  console.log('deliveryTotal: ', typeof dt, dt);
  console.log('ruralPrice: ', typeof rp, rp);
  console.log('satPrice: ', typeof sp, sp);

  var orderSubTotal = 
    pp + dt;
  console.log('subTotal: ', Number(orderSubTotal).toFixed(2) );
  const orderSubTotalCost = acf.getField('field_641d71ff02321');
  orderSubTotalCost.val(orderSubTotal.toFixed(2));
  console.log('__________________________________');
  
  var subTotal = acf.getField('field_641d71ff02321');
  // subTotal.val(orderSubTotal.toFixed(2));
  // console.log('subTotal: ', orderSubTotal.toFixed(2));

  var gstCost1 = (Number(orderSubTotal) * Number(0.15));
  console.log('+GST: ', gstCost1.toFixed(2));

  var gst = acf.getField('field_640d6074c5c16');
  gst.val(gstCost1.toFixed(2));

  var finalCost = Number(orderSubTotal.toFixed(2)) + Number(gstCost1);
  var totalCost = acf.getField('field_640d609fc5c17');
  totalCost.val(finalCost.toFixed(2));

  console.log('----------------------------------');
  console.log('finalCost: ', finalCost.toFixed(2));
  console.log('>=================================');
}

function updateNumberWFUField2(fieldName, newValue) {
  // console.log("updateNumberWFUField: ", fieldName.className, ", ", newValue);
  let nn = Number(newValue).toFixed(2);
  fieldName.textContent = nn;
  // console.log("updateNumberWFUField: ", fieldName,  newValue, fieldName.textContent);
}

// function completeThisOrder() {
//   console.log("completeThisOrder");


//   console.log("1/ update all the uploaded files ");

//   console.log("2/ collect all the user & delivery data");

//   console.log("3/ Write to new entry in completed orders table");




// }

// function createNewOrder() {
//   console.log("newOrder");

// }