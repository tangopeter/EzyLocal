// console.log("order page 2nd");

// const thisJobNumber = document.getElementById("userdata_2_field_1");

const printQty = document.getElementById("userdata_1_field_2");
const sizefinish = document.getElementById("userdata_1_field_3");
const reSize = document.getElementById("userdata_1_field_4");
const eachPrice = document.getElementById("userdata_1_field_5");
const printPrice = document.getElementById("userdata_1_field_6");
const size = document.getElementById("userdata_1_field_7");
const finish = document.getElementById("userdata_1_field_8");

   //  const printCost = document.getElementById("acf-field_6385721dce4e6-field_6385711932dde");
   //  const deliveryCost = document.getElementById("uacf-field_6385721dce4e6-field_6385713232ddf");

   //  const totalCost = document.getElementById("acf-field_6385721dce4e6-field_6385716f32de1");


export function orderPage2() {

      // Get the image settings changes
      sizefinish.addEventListener('change', getOrderSize);
      reSize.addEventListener('change', getOrderSize);
      printQty.addEventListener('input', getOrderSize);
    }
    // *******************************
    // Print order finish and cost details
    // ******************************* 
    // Get the image size cost and Quanitity

    
    export function getOrderSize(event) {
      // Array for the prices
      var printPrices = new Array();
      var i0 = ["dummy", 0.00, 0.50, 1.00, 1.50, "NA", "NA"];
      var i1 = ["5x7_Lustre", 1.20, 0.95, 0.80, "5x7", "Lustre"];
      var i2 = ["5x7.5_Lustre", 1.20, 0.95, 0.80, "5x7.5", "Lustre"];
      var i3 = ["5x11_Lustre", 3.00, 3.00, 3.00, "5x11", "Lustre"];
      var i4 = ["5x11_Laminated", 3.00, 3.00, 3.00, "5x11", "Laminated"];
      var i5 = ["6x4_Lustre", 0.25, 0.25, 0.25, "6x4", "Lustre"];
      var i6 = ["6x4.5_Lustre", 0.30, 0.30, 0.30, "6x4.5", "Lustre"];
      var i7 = ["6x8_Lustre", 2.80, 2.50, 2.00, "6x8", "Lustre"];
      var i8 = ["6x9_Lustre", 2.80, 2.50, 2.00, "6x9", "Lustre"];
      var i9 = ["8x6_Supreme", 3.00, 3.00, 3.00, "8x6", "Supreme"];
      var i10 = ["8x12_Supreme", 5.50, 5.50, 5.50, "8x12", "Supreme"];
      var i11 = ["10x8_Gloss", 5.90, 5.00, 4.50, "10x8", "Gloss"];
      var i12 = ["10x8_Lustre", 5.90, 5.00, 4.50, "10x8", "Lustre"];
      var i13 = ["10x8_Laminated", 8.90, 7.00, 4.20, "10x8", "Laminated"];
      var i14 = ["10x12_Gloss", 10.50, 9.00, 7.50, "10x12", "Gloss"];
      var i15 = ["10x12_Lustre", 10.50, 9.00, 7.50, "10x12", "Lustre"];
      var i16 = ["10x12.5_Gloss", 11.90, 9.50, 9.50, "10x12.5", "Gloss"];
      var i17 = ["10x12.5_Lustre", 11.90, 9.50, 9.50, "10x12.5", "Lustre"];
      var i18 = ["10x12.5_Laminated", 12.40, 10.00, 10.00, "10x12.5", "Laminated"];
      var i19 = ["10x15_Gloss", 14.50, 12.00, 10.75, "10x15", "Gloss"];
      var i20 = ["10x15_Lustre", 14.50, 12.00, 10.75, "10x15", "Lustre"];
      var i21 = ["12x8_Lustre", 6.00, 5.50, 4.50, "12x8", "Lustre"];
      var i21 = ["12x18_Lustre", 21.00, 21.00, 21.00, "12x8", "Lustre"];
      var i22 = ["6x20_Lustre", 18.00, 18.00, 18.00, "6x20", "Lustre"];
      var i23 = ["6x25_Lustre", 22.00, 22.00, 22.00, "6x25", "Lustre"];
      var i24 = ["10x20_Lustre", 23.00, 23.00, 23.00, "10x20", "Lustre"];
      var i25 = ["10x25_Lustre", 26.00, 26.00, 26.00, "10x25", "Lustre"];
      var i26 = ["12x20_Lustre", 25.00, 25.00, 25.00, "12x20", "Lustre"];
      var i27 = ["12x25_Lustre", 30.00, 30.00, 30.00, "12x25", "Lustre"];
      printPrices.push(i0);
      printPrices.push(i1);
      printPrices.push(i2);
      printPrices.push(i3);
      printPrices.push(i4);
      printPrices.push(i5);
      printPrices.push(i6);
      printPrices.push(i7);
      printPrices.push(i8);
      printPrices.push(i9);
      printPrices.push(i10);
      printPrices.push(i11);
      printPrices.push(i12);
      printPrices.push(i13);
      printPrices.push(i14);
      printPrices.push(i15);
      printPrices.push(i16);
      printPrices.push(i17);
      printPrices.push(i18);
      printPrices.push(i19);
      printPrices.push(i20);
      printPrices.push(i21);
      printPrices.push(i22);
      printPrices.push(i23);
      printPrices.push(i24);
      printPrices.push(i25);
      printPrices.push(i26);
      printPrices.push(i27);
    //

      // console.log("----------------------------");
      // console.log("onChange" + event.target);
      //get the size
      const p = (event.target.value);
      console.log(p);
      // get index of the size
      const i = sizefinish.selectedIndex;
      console.log(i);
      let pr = "0";
      // get the quantity
      const qty = printQty.value;
      // get the unit price
      if (qty < 11) {
        pr = printPrices[i][1];
      } else if (qty > 10 && qty < 31) {
        pr = printPrices[i][2];
      } else if (qty > 30) {
        pr = printPrices[i][3];
      }
      eachPrice.textContent = pr.toFixed(2);
      eachPrice.value = pr.toFixed(2);
      // get quantity x price & update the print price
      let price = Number(qty) * Number(pr);
      printPrice.value = price.toFixed(2);
      // UpdateOrderTotals2(event, price.toFixed(2));
      size.value = printPrices[i][4];
      finish.value = printPrices[i][5];

      console.log( 'Qty: ' + qty);
      console.log( 'pr: ' + pr);
      let totalPrintsCost2 = qty*pr;

    };


    export function UpdateOrderTotals2() {
       console.log("start update ******************************");

      console.log("end update *****************************");
    }

    function updateWFUField(fieldName, newValue) {
      fieldName.value = newValue;
      // console.log("updateWFUField: ", fieldName.className, ", ", newValue);
    }

    function updateWFUText(fieldName, newValue) {
      fieldName.textContent = newValue.toFixed(2);
      // console.log("updateWFUText: ", fieldName.className, ", ", fieldName.textContent);
    }

    function updateNumberWFUField(fieldName, newValue) {
     // console.log("updateNumberWFUField: ", fieldName, ", ", newValue);
      let nn = Number(newValue).toFixed(2);
      fieldName.value = nn;
      // console.log("updateNumberWFUField: ", fieldName,  newValue, fieldName.value);
    }

    function updateNumberWFUField2(fieldName, newValue) {
      console.log("updateNumberWFUField2: ", fieldName.className, ", ", newValue);
      let nn = Number(newValue).toFixed(2);
      fieldName.value = nn;
      console.log("updateNumberWFUField: ", fieldName,  newValue, fieldName.textContent);
    }

    function getWFUField(fieldName) {
      let fn = document.getElementById(fieldName);
      // console.log("get field name: ", fieldName, " ", fn);
      return fn;
    }