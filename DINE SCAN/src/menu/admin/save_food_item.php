<?php
// Establish a database connection
require_once("../../DB/db_conn.php");
if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if data is sent via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Your code goes here

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $cost = floatval($_POST['cost']);

    if (isset($_FILES["image"]) && isset($_FILES["image"]["tmp_name"]) && !empty($_FILES["image"]["tmp_name"])) {
        $imageData = file_get_contents($_FILES["image"]["tmp_name"]);
        $base64Image = base64_encode($imageData);
        $imageMessage = "Image uploaded successfully!";
    } else {
        $base64Image = getDefaultImage();
        $imageMessage = "No image uploaded. Using default image.";
    }

    error_log($imageMessage); // Log image message to the console

    // Inserting data into the database
    $query = "INSERT INTO menu_items (name, image, description, cost) VALUES ('$name', '$base64Image', '$description', $cost)";

    if (mysqli_query($conn, $query)) {
        $response = array("success" => true, "message" => "Food item added successfully!");
    } else {
        error_log("Error: " . $query . "<br>" . $conn->error); // Log database error to the console
        $response = array("success" => false, "message" => "Failed to add food item. Please try again.");
    }

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
