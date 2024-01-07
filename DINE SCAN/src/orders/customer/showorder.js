$(document).ready(function() {
    // Extract mobile number from URL
    const urlParams = new URLSearchParams(window.location.search);
    const mobileNumber = urlParams.get('mobileNumber');

    // Send AJAX request to retrieve order details
    $.ajax({
      url: 'getLastOrderSummary.php',
      type: 'GET',
      data: {
        mobileNumber: mobileNumber
      },
      success: function(response) {
        // Display order details
        console.log(response); // Print the response in the console
        
        //const orderDetails = JSON.parse(response);
        //console.log(orderDetails); // Print the orderDetails variable in the console

        if (response.length > 0) {
          const order = response[0];
          console.log(order); // Print the first order in the console

          addOrder(order);
        }
        
        //addOrder(response);
      },
      error: function() {
        $('#orderSummary').html('Error occurred while retrieving order details.');
      }
      
    });

    function addOrder(order) {
      const ordersContainer = document.getElementById("orders-container");

      // Create a new order card
      const orderCard = document.createElement("div");
      orderCard.classList.add("order-card");

      // Add the order number to the order card
      const orderNumber = document.createElement("h3");
      orderNumber.textContent = "Your Order Number is #" + order.order_id;
      orderCard.appendChild(orderNumber);
      
      const orderCost = document.createElement("h4");
      orderCost.textContent = "Amount : ₹" + order.total_amount;
      orderCard.appendChild(orderCost);

      const orderDate = document.createElement("h4");
      orderDate.textContent = "Date & Time: " + order.order_date;
      orderCard.appendChild(orderDate);

      const orderPID = document.createElement("h4");
      orderPID.textContent = "Transaction ID: " + order.payment_id;
      orderCard.appendChild(orderPID);

      const orderDetails = document.createElement("div");
      orderDetails.classList.add("order-details");

      const tableNo = document.createElement("p");
      tableNo.classList.add("table-no");
      tableNo.innerHTML = "Table No: " + order.table_number;
      orderDetails.appendChild(tableNo);

      // Create a new list for order items
      const orderList = document.createElement("ul");

      // Loop through the order items and add them to the order list
      for (let item of order.items) {
          const listItem = document.createElement("li");
          const listItem2 = document.createElement("li");
          const listItem3 = document.createElement("li");
          const listItem4 = document.createElement("li");
          const listItem5 = document.createElement("li");

           // Add the food item image to the list item
          const foodItemImage = document.createElement("img");
          foodItemImage.src = 'data:image/jpeg;base64,' + item.image;
          foodItemImage.alt = "Food Item";
          foodItemImage.classList.add("food-image");
          listItem.appendChild(foodItemImage);

          const foodItemName = document.createElement("p");
          foodItemName.textContent = "Food Item: " + item.name;
          listItem2.appendChild(foodItemName);

          const size = document.createElement("p");
          size.textContent = "Size: " + item.size_name;
          listItem3.appendChild(size);

          const quantity = document.createElement("p");
          quantity.textContent = "Quantity of item: " + item.quantity;
          listItem4.appendChild(quantity);

          const icost = document.createElement("p");
          icost.textContent = "Cost: ₹" + item.icost;
          listItem5.appendChild(icost);
          
          orderList.appendChild(listItem);
          orderList.appendChild(listItem2);
          orderList.appendChild(listItem3);
          orderList.appendChild(listItem4);
          orderList.appendChild(listItem5);
          orderDetails.appendChild(orderList);
      }

      const instructions = document.createElement("p");
      instructions.textContent = "Instructions: " + order.instructions;
      orderDetails.appendChild(instructions);

      // Create a "Save" button
      const saveButton = document.createElement("button");
      saveButton.classList.add("button");
      saveButton.textContent = "Download Order Summary";
      saveButton.addEventListener("click", function() {
        showConfirmationBox(order);
      });
      

      orderCard.appendChild(orderDetails);

      orderCard.appendChild(saveButton);

      ordersContainer.appendChild(orderCard);
    }

    function showConfirmationBox(order) {
      const confirmationBox = document.createElement("div");
      confirmationBox.classList.add("confirmation-box");

      const message = document.createElement("p");
      message.textContent = "Do you want to download the order summary?";
      confirmationBox.appendChild(message);

      const confirmButton = document.createElement("button");
      confirmButton.classList.add("button");
      confirmButton.textContent = "Confirm";
      confirmButton.addEventListener("click", function() {
        printOrderSummary(order);
        confirmationBox.remove();
      });
      confirmationBox.appendChild(confirmButton);

      const cancelButton = document.createElement("button");
      cancelButton.classList.add("button");
      cancelButton.textContent = "Cancel";
      cancelButton.addEventListener("click", function() {
        confirmationBox.remove();
      });
      confirmationBox.appendChild(cancelButton);

      document.body.appendChild(confirmationBox);
    }

});