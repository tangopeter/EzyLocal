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

  // let printCostTotal = (Number(0.00)).toFixed(2);
  // let deliveryCost = (Number(0.00)).toFixed(2);
  // let subtotalCost = (Number(0.00)).toFixed(2);
  // let gstCost = (Number(0.00)).toFixed(2);
  // let totalCost = (Number(0.00)).toFixed(2);

  const deliveryMethod = document.getElementById("acf-field_640d61d5084e4");
  const rural = document.getElementById("acf-field_640d61d5084ec");
  const saturday = document.getElementById("acf-field_640d61d508503");
  const toPostal = document.getElementById("acf-field_640d61d50850d");

  const printPrice = document.getElementById("totalPrintPrice");


  // const deliveryCostPrice = document.getElementsByClassName("deliveryCostPrice");
  // const subtotalCostPrice = document.getElementsByClassName("subtotalCostPrice");
  // const gstCostPrice = document.getElementsByClassName("gstCostPrice");
  // const costsTotalPrice = document.getElementsByClassName("costsTotalPrice");



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

  // deliveryCostPrice.textContent = deliveryCost;
  // subtotalCostPrice.textContent = subtotalCost;
  // gstCostPrice.textContent = gstCost;
  // costsTotalPrice.textContent = totalCost;


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
// todo ************************************************
function updateOrderValue() {
  // !Print Cost:

  const printPrice = document.getElementById("totalPrintPrice").textContent;
  console.log("print Price: ", printPrice);

  const printCostTotalPrice = document.getElementById("printCostTotalPriceID");
  // console.log(printCostTotalPrice);
  updateNumberWFUField1(printCostTotalPrice, printPrice);

  const deliveryMethodPrice = document.getElementsByClassName("delPriceMethod")[0].textContent;
  console.log('deliveryMethodPrice: ', deliveryMethodPrice);

  const ruralPrice = document.getElementsByClassName("ruralPrice")[0].textContent;
  // console.log('Rural Cost: ', ruralPrice);

  const satPrice = document.getElementsByClassName("satPrice")[0].textContent;
  // console.log('Sat Cost: ', satPrice);

  var deliveryTotal =
    Number(deliveryMethodPrice) +
    Number(ruralPrice) +
    Number(satPrice);

  const totalDeliveryCost = document.getElementById("deliveryCostPriceID");
  // console.log('totalDeliveryCost: ', totalDeliveryCost);
  deliveryTotal = deliveryTotal.toFixed(2);
  updateNumberWFUField1(deliveryCostPriceID, deliveryTotal)

  var pp = Number(printPrice);
  var dt = Number(deliveryTotal);
  var rp = Number(ruralPrice);
  var sp = Number(satPrice);

  // console.log('printCost: ', typeof pp, pp);
  // console.log('deliveryTotal: ', typeof dt, dt);
  // console.log('ruralPrice: ', typeof rp, rp);
  // console.log('satPrice: ', typeof sp, sp);

  var orderSubTotal = 
    pp + dt;
  console.log('subTotal: ', Number(orderSubTotal).toFixed(2) );
  const orderSubTotalCostID = document.getElementById("subtotalCostPriceID");

  updateNumberWFUField1(orderSubTotalCostID, orderSubTotal)
  console.log('__________________________________');
  
  var gstCost = (Number(orderSubTotal) * Number(0.15));
  console.log('+ GST: ', gstCost.toFixed(2));

  var gst = document.getElementById("gstCostPriceID");
  updateNumberWFUField1(gst, gstCost)

  var finalCost = Number(orderSubTotal.toFixed(2)) + Number(gstCost.toFixed(2));
  const totalCost = document.getElementById("costsTotalPriceID");
  updateNumberWFUField1(totalCost, finalCost);

  console.log('----------------------------------');
  console.log('finalCost: ', finalCost.toFixed(2));
  console.log('>=================================');


  
}

function updateNumberWFUField1(fieldName, newValue) {
  // console.log("Id_updateNumberWFUField: ", fieldName, ", ", newValue);
  let nn = Number(newValue).toFixed(2);
  fieldName.textContent = nn;
  // console.log(">> updateNumberWFUField: ", fieldName, newValue, fieldName.textContent);
}
function updateNumberWFUField2(fieldName, newValue) {
  // console.log("class_updateNumberWFUField: ", fieldName.className, ", ", newValue);
  let nn = Number(newValue).toFixed(2);
  fieldName.textContent = nn;
  // console.log(">> updateNumberWFUField: ", fieldName,  newValue, fieldName.textContent);
}
