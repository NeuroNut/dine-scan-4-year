<?php
// Establish a database connection


require_once("../../DB/db_conn.php");
if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if data is sent via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    if (isset($_POST['foodItem'])) {
        $foodItem = json_decode($_POST['foodItem'], true); // true makes it an associative array
    
        // Now you can access the values like this:
        $name = $foodItem['name'];
        $description = $foodItem['description'];
        $category = $foodItem['category'];
        $visibility = $foodItem['visibility'];
        $sizes = $foodItem['sizeOptions'];
        $costs = $foodItem['costs'];
    }

    if (isset($_FILES["image"]) && isset($_FILES["image"]["tmp_name"]) && !empty($_FILES["image"]["tmp_name"])) {
        $imageData = file_get_contents($_FILES["image"]["tmp_name"]);
        $base64Image = base64_encode($imageData);
        $imageMessage = "Image uploaded successfully!";
    } else {
        $base64Image = getDefaultImage();
        $imageMessage = "No image uploaded. Using default image.";
    }

    error_log($imageMessage); // Log image message to the console

    // $sizes = isset($formData['sizeOptions']) ? $formData['sizeOptions'] : [];
    // $costs = isset($formData['costs']) ? $formData['costs'] : [];

    // Inserting data into the database
    $query = "INSERT INTO menu_items (name, image, description, category_id, visibility) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssis', $name, $base64Image, $description, $category, $visibility);
    $stmt->execute();
    $menuItemID = $stmt->insert_id;

    //for loop iterating through the sizes and costs arrays
    for($i = 0; $i < count($sizes); $i++) {
        $size = $sizes[$i];
        $cost = $costs[$i];
        $query = "INSERT INTO menu_sizesNcosts (menu_id, size_id, cost) VALUES (?,?,?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iid', $menuItemID, $size, $cost);
        $stmt->execute();
    }

    //send back a success response
    $response = ["success" => true, "message" => "Food item added successfully!", "id" => $menuItemID];
   
    echo json_encode($response);
} else {
    // If Not a POST request
    echo json_encode(array("success" => false, "message" => "Invalid request method."));
}

function getDefaultImage() {
    // Provide the path or URL of your default image
    $defaultImagePath = "../../assets/images/default-image.jpg";
    if (file_exists($defaultImagePath)) {
        $defaultImageData = file_get_contents($defaultImagePath);
        return base64_encode($defaultImageData);
    } else {
        die("Error: Default image not found at '$defaultImagePath'.");
    }
}

?>
