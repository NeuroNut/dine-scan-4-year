  // Fetch the live orders from the PHP script
  $.getJSON("get_live_orders.php", function(data) {
    // Loop through the orders and create HTML elements for each order
    for (let order of data) {
      addOrder(order);
    }
  });

  // Function to add an order to the orders container
  function addOrder(order) {
    const ordersContainer = document.getElementById("orders-container");
    const completedOrdersContainer = document.getElementById("completed-orders-container");

    // Create a new order card
    const orderCard = document.createElement("div");
    orderCard.classList.add("order-card");
    orderCard.id = `order-${order.order_id}`;

    // Add the order number to the order card
    const orderNumber = document.createElement("h3");
    orderNumber.textContent = "Order #" + order.order_id;
    orderCard.appendChild(orderNumber);

    const orderDetails = document.createElement("div");
    orderDetails.classList.add("order-details");

    const tableNo = document.createElement("p");
    tableNo.innerHTML = "Table No: <span class='table-no'>" + order.table_number + "</span>";
    orderDetails.appendChild(tableNo);

    // Create a new list for order items
    const orderList = document.createElement("ul");

    // Loop through the order items and add them to the order list
    for (let item of order.items) {
      const listItem = document.createElement("li");

      const foodItemName = document.createElement("p");
      foodItemName.textContent = "Food Item: " + item.name;
      orderDetails.appendChild(foodItemName);

      const quantity = document.createElement("p");
      quantity.textContent = "Quantity: " + item.quantity;
      orderDetails.appendChild(quantity);

      listItem.appendChild(orderDetails);
      orderList.appendChild(listItem);
    }

    const instructions = document.createElement("p");
    instructions.textContent = "Instructions: " + order.items.instructions;
    orderDetails.appendChild(instructions);

    orderCard.appendChild(orderList);

    const toggleSlider = document.createElement("label");
    toggleSlider.classList.add("toggle-slider");
    const inputToggle = document.createElement("input");
    inputToggle.type = "checkbox";
    const sliderSpan = document.createElement("span");
    sliderSpan.classList.add("slider");
    toggleSlider.appendChild(inputToggle);
    toggleSlider.appendChild(sliderSpan);

    // Set the default status
    if (order.status === "completed") {
      toggleSlider.classList.add("completed");
      inputToggle.checked = true;
      completedOrdersContainer.appendChild(orderCard);
    } else {
      ordersContainer.appendChild(orderCard);
    }

    toggleSlider.addEventListener("click", function() {
      toggleOrderStatus(order.order_id, toggleSlider, inputToggle, orderCard);
    });

    orderCard.appendChild(toggleSlider);
  }

  function toggleOrderStatus(orderId, toggleSlider, inputToggle, orderCard) {
    $.post("update_order_status.php", { order_id: orderId }, function(data) {
      if (data.success) {
        // Toggle the status in the UI
        if (data.status === "completed") {
          toggleSlider.classList.add("completed");
          inputToggle.checked = true;
          const completedOrdersContainer = document.getElementById("completed-orders-container");
          completedOrdersContainer.appendChild(orderCard);
        } else {
          toggleSlider.classList.remove("completed");
          inputToggle.checked = false;
          const ordersContainer = document.getElementById("orders-container");
          ordersContainer.appendChild(orderCard);
        }
      }
    });
  }
  
