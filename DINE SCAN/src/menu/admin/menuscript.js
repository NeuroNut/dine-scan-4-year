const foodForm = document.getElementById("foodForm");

foodForm.addEventListener("submit", async function (event) {
  event.preventDefault();

  const foodName = document.getElementById("foodName").value;
  const foodImage = document.getElementById("foodImage").files[0]; // Get the selected image file
  const foodDescription = document.getElementById("foodDescription").value;
  const foodCost = document.getElementById("foodCost").value;

  const newFoodItem = new FormData(); // Create a new FormData object
  newFoodItem.append("name", foodName);
  newFoodItem.append("image", foodImage); // Append the image file to the FormData
  newFoodItem.append("description", foodDescription);
  newFoodItem.append("cost", foodCost);

  console.log("New Food Item:", newFoodItem);

  const response = await saveToDatabase(newFoodItem);

  if (response.success) {
    alert("Food item added successfully!");
    foodForm.reset();
  } else {
   // console.log(response.message);
    alert("Failed to add food item. Please try again.");
  }
});

async function saveToDatabase(foodItem) {
  try {
    console.log("Food Item:", foodItem);
    const response = await fetch("save_food_item.php", {
      method: "POST",
      body: foodItem, // Send the FormData object as the request body
    });

    return await response.json();
  } catch (error) {
    console.error("Error saving data to the database:", error);
    return { success: false };
  }
}
