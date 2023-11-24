document.addEventListener('DOMContentLoaded', function() {
  const orderBtn = document.querySelector("#orderBtn");

  if (orderBtn) {
    orderBtn.addEventListener('click', async function(event) {
      event.preventDefault();

      const tnumber = document.getElementById("tn").value;
      const tamount = document.getElementById("ta").value;
      alert("value of amount is" ,Number(tamount));

      const newOrder = {
        table: tnumber,
        amount: tamount,
      };

      const response = await saveToDatabase(newOrder);

      if (response.success) {
        // alert("Order Received successfully!");
        window.location.href = "thankyou.html";
        foodForm.reset();
      } else {
        alert("Failed to add food item. Please try again.");
      }
    });
  } else {
    alert("Button does not exist.");
  }
});


async function saveToDatabase(newOrder) {
  try {
    const response = await fetch("save_order.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(newOrder),
    });

    return await response.json();
  } catch (error) {
    console.error("Error saving data to the database:", error);
    return { success: false };
  }
}
