<?php
require_once('db_conn.php'); // Include the database connection script here

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $foodName = $_POST["name"];
  $foodImage = $_POST["image"];
  $foodDescription = $_POST["description"];
  $foodCost = $_POST["cost"];

  // Insert the data into the menu_items table
  $sql = "INSERT INTO menu_items (name, image, description, cost) VALUES ('$foodName', '$foodImage', '$foodDescription', $foodCost)";
  if ($conn->query($sql) === TRUE) {
    echo "Food item added successfully!";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}
?>
