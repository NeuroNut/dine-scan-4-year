const foodForm = document.getElementById("foodForm");

foodForm.addEventListener("submit", async function (event) {
  event.preventDefault();

  const foodName = document.getElementById("foodName").value;
  const foodImage = document.getElementById("foodImage").files[0];
  const foodDescription = document.getElementById("foodDescription").value;

  // Create an array to store size and cost pairs
  const sizeAndCostPairs = [];

  // Iterate over all size selects and cost inputs
  document.querySelectorAll(".size-select").forEach((sizeSelect, index) => {
    const size = sizeSelect.value;
    const cost = document.getElementById(`foodCostBySize_${index}`).value;

    // Check if both size and cost are provided
    if (size && cost) {
      sizeAndCostPairs.push({ size, cost });
    }
  });

  const newFoodItem = new FormData();
  newFoodItem.append("name", foodName);
  newFoodItem.append("image", foodImage);
  newFoodItem.append("description", foodDescription);
  newFoodItem.append("sizeAndCostPairs", JSON.stringify(sizeAndCostPairs));

  console.log("New Food Item:", newFoodItem);

  //const response = await saveToDatabase(newFoodItem);

  if (!sizeAndCostPairs.length === 0) {
    alert("Food item added successfully!");
    foodForm.reset();
  } else {
    alert("Failed to add food item. Please try again.");
  }
});

async function saveToDatabase(foodItem) {
  try {
    const response = await fetch("save_food_item.php", {
      method: "POST",
      body: foodItem,
    });

    return await response.json();
  } catch (error) {
    console.error("Error saving data to the database:", error);
    return { success: false };
  }
}
