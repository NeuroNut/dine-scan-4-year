
let count = {};
let countTotal = 0;
let totalAmount = 0;
let paymentMode = "postpaid";
let p_amount = 0;
let pay_id = "not available for postpaid";

$.getJSON("../admin/menushow.php", function (data) {
  // Loop through the food items and add them to the menu.
  for (let foodItem of data) {
    //console.log(foodItem);
    count[foodItem.name] = {};
    for( let size of foodItem.sizesNcosts){
    count[foodItem.name][size.size_name] = 0;
    }
    addFoodItem(foodItem);
  }
});

// Function to add a food item to the menu.
function addFoodItem(foodItem) {
  // Create a new <div> element for the food item.
  const foodItemDiv = document.createElement("div");
  foodItemDiv.classList.add("food-item");

  // Add the food item's image to the <div> element.
  const foodItemImage = document.createElement("img");
  foodItemImage.src = 'data:image/jpeg;base64,' + foodItem.image;
  foodItemDiv.appendChild(foodItemImage);

  // Add the food item's name to the <div> element.
  const foodItemName = document.createElement("h3");
  foodItemName.textContent = foodItem.name;
  foodItemDiv.appendChild(foodItemName);

  // Add the cost of the food item to the <div> element.
    for (let sizeNcost of foodItem.sizesNcosts) {

const foodItemCost = document.createElement("span");
foodItemCost.textContent = sizeNcost.size_name + ": Rs. " + sizeNcost.cost;
foodItemDiv.appendChild(foodItemCost);

// Create a new line break element
const lineBreak = document.createElement("br");
// Append the line break to the foodItemDiv
foodItemDiv.appendChild(lineBreak);



const counter = document.createElement("div");
  counter.classList.add("counter");

  // Add the decrement button to the counter.
  const decrementBtn = document.createElement("button");
  decrementBtn.classList.add("decrement");
  decrementBtn.textContent = "-";
  decrementBtn.addEventListener("click", function () {
    if (count[foodItem.name][sizeNcost.size_name] > 0) {
      count[foodItem.name][sizeNcost.size_name]--;
      countSpan.textContent = count[foodItem.name][sizeNcost.size_name];
      countTotal--;
      totalAmount -= parseFloat(sizeNcost.cost);
      p_amount = totalAmount.toFixed(2) * 100;
      //console.log(p_amount);
      updateTotalCount();
    }
  });
  counter.appendChild(decrementBtn);

  // Add the count span to the counter.
  const countSpan = document.createElement("span");
  countSpan.textContent = count[foodItem.name][sizeNcost.size_name];
  counter.appendChild(countSpan);

  // Add the increment button to the counter.
  const incrementBtn = document.createElement("button");
  incrementBtn.classList.add("increment");
  incrementBtn.textContent = "+";
  incrementBtn.addEventListener("click", function () {
    count[foodItem.name][sizeNcost.size_name]++;
    countSpan.textContent = count[foodItem.name][sizeNcost.size_name];
    countTotal++;
    totalAmount += parseFloat(sizeNcost.cost);
    p_amount = totalAmount.toFixed(2) * 100;
    //console.log(p_amount);
    updateTotalCount();
  });
  counter.appendChild(incrementBtn);

  // Add the counter to the <div> element.
  foodItemDiv.appendChild(counter);

  //const lineBreak2 = document.createElement("br");
// Append the line break to the foodItemDiv
foodItemDiv.appendChild(lineBreak);

//const lineBreak3 = document.createElement("br");
// Append the line break to the foodItemDiv
foodItemDiv.appendChild(lineBreak);



}

  // Add the counter to the <div> element.
 

  // Add the <div> element to the menu.
  document.querySelector(".menu").appendChild(foodItemDiv);
}

// Update the total count and total amount.
function updateTotalCount() {
  const totalCountSpan = document.querySelector("#total-count");
  totalCountSpan.textContent = countTotal;

  const totalAmountSpan = document.querySelector("#total-amount");
  totalAmountSpan.textContent = totalAmount.toFixed(2);

  //console.log(count);
  updateButtonState();

}

// Update the state of the order buttons based on the total amount
function updateButtonState() {
  const orderBtn = document.getElementById('order-btn');
  const paymentOrderBtn = document.getElementById('payment-order-btn');

  if (totalAmount > 0) {
    orderBtn.disabled = false;
    paymentOrderBtn.disabled = false;
    orderBtn.classList.remove("faded");
    paymentOrderBtn.classList.remove("faded");
  } else {
    orderBtn.disabled = true;
    paymentOrderBtn.disabled = true;
    orderBtn.classList.add("faded");
    paymentOrderBtn.classList.add("faded");
  }
}

// Function to get the value of a query parameter from the URL
function getQueryParam(name) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(name);
}

const pastOrdersButton = document.getElementById("past-orders-btn");
const pastOrdersDiv = document.getElementById("past-orders");
let pastOrdersVisible = false;

pastOrdersButton.addEventListener("click", () => {
  if (!pastOrdersVisible) {
    // Show past orders
    pastOrdersDiv.style.display = "block";
    pastOrdersButton.textContent = "Close";
    pastOrdersVisible = true;
    fetchAndDisplayPastOrders();
    // Fetch and display past orders here (see the script below).
  } else {
    // Hide past orders
    pastOrdersDiv.style.display = "none";
    pastOrdersButton.textContent = "View Your Past Orders";
    pastOrdersVisible = false;
  }
});

// Function to display the past orders
function fetchAndDisplayPastOrders() {
  // Get the user's mobile number from the URL
  const mobileNumber = getQueryParam('mobileNumber');

  // Fetch past orders using AJAX
  fetch(`../../orders/customer/view_past_o.php?mobileNumber=${mobileNumber}`)
    .then((response) => response.json())
    .then((data) => {
      const pastOrdersDiv = document.getElementById('past-orders');

      if (data.length > 0) {
        // If there are past orders, display them
        pastOrdersDiv.innerHTML = '<h2>Past Orders</h2>';
        const ordersList = document.createElement('ul');

        data.forEach((order) => {
          const orderItem = document.createElement('li');
          orderItem.textContent = `Order date: ${order.order_date}, Total: ₹${order.order_total}`;
          ordersList.appendChild(orderItem);
        });

        pastOrdersDiv.appendChild(ordersList);
      } else {
        // If there are no past orders, display a message
        console.log('No past orders found 1');
        pastOrdersDiv.innerHTML = '<p>No past orders found, lets orders something!</p>';
      }

      // Show the past orders div
      pastOrdersDiv.style.display = 'block';

      // Update the button text to "Close"
      const pastOrdersButton = document.getElementById('past-orders-btn');
      pastOrdersButton.textContent = 'Close';
    })
    .catch((error) => {
      console.error('Error fetching past orders:', error);
    });
}


// Function to display the table number
function displayTableNumber() {
  const tableNumber = getQueryParam('tableNo');
  if (tableNumber) {
    const tableNumberDiv = document.querySelector(".table-number h2");
    tableNumberDiv.textContent = "Table Number: " + tableNumber;
  }
}

// Call the displayTableNumber function when the page loads
window.addEventListener('DOMContentLoaded', displayTableNumber);

function sendOrderData() {
  const orderData = {
    table_number: getQueryParam('tableNo'),
    total_amount: totalAmount.toFixed(2),
    mobile_number: getQueryParam('mobileNumber'),
    instructions: document.getElementById("instructions").value,
    payment_mode: paymentMode,
    payment_id: pay_id,
    items: []
  };

  for (const itemName in count) {
    for(const sizeName in count[itemName]) {
      if (count[itemName][sizeName] > 0) {
        orderData.items.push({
          name: itemName,
          size: sizeName,
          quantity: count[itemName][sizeName]
        });
      }
    }
  }

  // Send the order data to the PHP script using the Fetch API
  fetch("../../orders/customer/save_order.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(orderData)
    })
    .then(response => response.json())
    .then(data => {
      console.log("Response:", data); // Log the response
      // Handle the response from the server, if needed
      //console.log(data);
      window.location.href = `https://localhost/DINE SCAN/src/orders/customer/thankyou.html?mobileNumber=${getQueryParam('mobileNumber')}`;
    })
    .catch(error => {
      // Handle any errors that occur during the request
      console.error("Error:", error);
    });
}

    function handleConfirmation(event, callback) {
        event.preventDefault();
        const modal = document.getElementById("confirmation-modal");
        const confirmBtn = document.getElementById("confirm-btn");
        const cancelBtn = document.getElementById("cancel-btn");

        // Show the modal
        modal.style.display = "block";

        // Add event listeners to the buttons
        confirmBtn.addEventListener("click", function () {
            modal.style.display = "none";
            callback();
        });

        cancelBtn.addEventListener("click", function () {
            modal.style.display = "none";
        });
    }


// Add an event listener to the 'Order' button
const orderBtn = document.getElementById("order-btn");
orderBtn.addEventListener("click", function (event) {
  handleConfirmation(event, sendOrderData);
});

function sendPaymentAndOrderData() {
  // Similar to your existing sendOrderData function
  // Include the logic to send payment data along with the order data
  // ...

  let table_number = getQueryParam('tableNo');
  var options = {
    key: 'rzp_test_CWhdyu6tSj20m0', // Replace with your Key ID
    amount: p_amount, // The amount to be charged in the smallest currency unit (e.g., paise for INR)
    currency: 'INR', // The currency code
    name: 'DINE SCAN', // Displayed on the Razorpay Checkout page
    description: 'Your table Number is ' + table_number, // Displayed on the Razorpay Checkout page
    handler: function (response) {
      // This function will be called after a successful payment
      // You can handle the payment response here
      pay_id = response.razorpay_payment_id; // Store payment ID in global variable
          //alert('Payment successful! Payment ID: ' + pay_id);
          sendOrderData();
    }
  };

  var rzp = new Razorpay(options);

  //document.getElementById('payment-order-btn').onclick = function() {
  rzp.open();
  //};

  // Example: Redirect to payment.php with order number and total cost
  // const payData = {
  //   table_number: getQueryParam('tableNo'),
  //     total_cost: totalAmount.toFixed(2),
  // };
  // const queryString = new URLSearchParams(payData).toString();
  // window.location.href = `payment.php?${queryString}`;
}

// Add an event listener to the "Make payment and order" button
const paymentOrderBtn = document.getElementById('payment-order-btn');
paymentOrderBtn.addEventListener('click', function (event) {
  handleConfirmation(event, sendPaymentAndOrderData);
});

// Add an event listener to the order-type toggle
const orderTypeToggle = document.getElementById('order-type-toggle');
const prepaidLabel = document.getElementById('prepaid-label');

orderTypeToggle.addEventListener('change', function () {
  if (!orderTypeToggle.checked) {
    // Postpaid order
    paymentMode = "postpaid";
    orderBtn.style.display = 'block';
    paymentOrderBtn.style.display = 'none';
    prepaidLabel.style.color = '#c25920';
    prepaidLabel.textContent = 'Make it a Prepaid Order ?';
  } else {
    // Prepaid order
    paymentMode = "prepaid";
    orderBtn.style.display = 'none';
    paymentOrderBtn.style.display = 'block';
    prepaidLabel.style.color = 'green';
    prepaidLabel.textContent = 'It is a Prepaid Order';
  }
});
