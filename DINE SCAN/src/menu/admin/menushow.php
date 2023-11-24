<?php

// Connect to the database.
require_once('../../DB/db_conn.php');

try {
  // Get the list of food items.
  $sql = "SELECT menu_items.id, menu_items.name, menu_items.image, menu_items.description, menu_items.visibility, categories.category_name AS category_name, menu_sizesNcosts.size_id, menu_sizesNcosts.cost, size_options.size_name as size_name 
      FROM menu_items 
      INNER JOIN categories ON menu_items.category_id = categories.category_id
      INNER JOIN menu_sizesNcosts ON menu_items.id = menu_sizesNcosts.menu_id
      INNER JOIN size_options ON menu_sizesNcosts.size_id = size_options.size_id
      WHERE menu_items.visibility = 'visible' order by menu_items.id ASC";

  $result = $conn->query($sql);

  if (!$result) {
    throw new Exception("Failed to fetch food items from the database.");
  }

  $foodItems = [];

  while ($row = $result->fetch_assoc()) {
    $menu_id = $row["id"];
      $name = $row["name"];
      $image = $row["image"];
      $category = $row["category_name"];
     
    

    $sizeNcost = [
      "size_id" => $row["size_id"],
      "size_name" => $row["size_name"],
      "cost" => $row["cost"],
    ];

    if (!isset($foodItems[$menu_id])) {
      $foodItems[$menu_id] = [
          "menu_id" => $menu_id,
          "name" => $name,
          "image" => $image,
          "category" => $category,
          "sizes" => []
      ];
  }

    $foodItems[$menu_id]["sizesNcosts"][] = $sizeNcost;

    //$foodItems[] = $foodItem;
  }


  header('Content-Type: application/json');

  // Return the array of food items.
  echo json_encode(array_values($foodItems));
} catch (Exception $e) {
  // Handle the exception and report the error.
  error_log($e->getMessage());
  echo json_encode(["error" => $e->getMessage()]);
}
