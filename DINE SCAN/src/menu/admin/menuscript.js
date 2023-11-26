const foodForm = document.getElementById("foodForm");

foodForm.addEventListener("submit", async function (event) {
    //event.preventDefault();
  
    const foodName = document.getElementById("foodName").value;
    const foodImage = document.getElementById("foodImage").files[0]; // Get the selected image file
    const foodDescription = document.getElementById("foodDescription").value;
    const foodCategory = document.getElementById("foodCategory").value;
    const foodVisibility = document.getElementById("foodVisibility").value; 
    const optionElements = optionsContainer.getElementsByClassName("option-group");
     
  
      // Store the size options and costs
      const sizeOptions = Array.from(optionElements).map((option, i) => option.querySelector(`select[name="option[${i}]"]`).value);
      const costs = Array.from(optionElements).map((option, i) => option.querySelector(`input[name="cost[${i}]"]`).value);
  
  
    const newFoodItem = {
      name: foodName,
      description: foodDescription,
      category: foodCategory,
      visibility: foodVisibility,
      sizeOptions: sizeOptions,
      costs: costs
    };
  
    console.log("New Food Item:", newFoodItem);
  
    const response = await saveToDatabase(newFoodItem, foodImage);

    if (response.success) {
      
      console.log("the insert id is: ", response.id);
      alert("Food item added successfully.");
    } else {
        // console.log(response);
        // console.log("the insert id is: ", response.id);
        // console.log(response.message);
        alert("Failed to add food item without image. Please try again.");
        }
   });
  
   async function saveToDatabase(foodItem, imageFile) {
    try {
      const formData = new FormData();
      formData.append('image', imageFile);
      formData.append('foodItem', JSON.stringify(foodItem));
  
      const response = await fetch('save_food_item.php', {
        method: 'POST',
        body: formData
      });
  
      const responseData = await response.json();
      console.log(responseData);
      return responseData;
    } catch (error) {
      console.error('Error saving data to the database:', error);
      return { success: false, message: error };
    }
  }
