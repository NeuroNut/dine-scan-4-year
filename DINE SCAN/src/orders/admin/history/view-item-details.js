function viewDetails(id) {
    fetch(`getOrderItemsbyId.php?id=${id}`)
        .then(response => response.json())
        .then(response => {
            const order = response;
            console.log(order); // Print the first order in the console
            showDetails(order);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function showDetails(order) {
    const ordersContainer = document.getElementById("item-details");

    // Create a new order card
    const orderCard = document.createElement("div");
    orderCard.classList.add("order-card");

    // Add the order number to the order card
    const orderNumber = document.createElement("h3");
    orderNumber.textContent = "Your Order Numerber is #" + order[0].order_id;
    orderCard.appendChild(orderNumber);

    const orderDetails = document.createElement("div");
    orderDetails.classList.add("order-details");

    const tableNo = document.createElement("p");
    tableNo.classList.add("table-no");
    tableNo.innerHTML = "Table No: " + order[0].table_number;
    orderDetails.appendChild(tableNo);

    // Create a new list for order items
    const orderList = document.createElement("ul");

    // Loop through the order items and add them to the order list
    for (let item of order[0].items) {
        const listItem = document.createElement("li");
        const listItem2 = document.createElement("li");
        const listItem3 = document.createElement("li");
        const listItem4 = document.createElement("li");

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
        quantity.textContent = "Quantity: " + item.quantity;
        listItem4.appendChild(quantity);

        
        orderList.appendChild(listItem);
        orderList.appendChild(listItem2);
        orderList.appendChild(listItem3);
        orderList.appendChild(listItem4);
        orderDetails.appendChild(orderList);
    }

    // Create a "Cancel" button
    const cancelButton = document.createElement("button");
    cancelButton.classList.add("button");
    cancelButton.textContent = "Close";
    cancelButton.addEventListener("click", function() {
      closeDetails();
    });
    

    orderCard.appendChild(orderDetails);

    orderCard.appendChild(cancelButton);

    ordersContainer.appendChild(orderCard);

    ordersContainer.classList.toggle("hidden");
  }

  function closeDetails(){
    const ordersContainer = document.getElementById("item-details");

    //remove the last appended child from ordersContainer
    ordersContainer.removeChild(ordersContainer.lastChild);
    ordersContainer.classList.toggle("hidden");
  }