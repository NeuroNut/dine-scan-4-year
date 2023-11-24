<?php
require_once('../../DB/db_conn.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $order_id = $_POST["order_id"];

    // Check the current status of the order
    $sql = "SELECT status FROM orders WHERE order_id = $order_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentStatus = $row["status"];

        // Toggle the status and update in the database
        if ($currentStatus === "processing") {
            $newStatus = "completed";
        } else {
            $newStatus = "processing";
        }

        $sql = "UPDATE orders SET status = '$newStatus' WHERE order_id = $order_id";

        if ($conn->query($sql) === TRUE) {
            $response = array("success" => true, "status" => $newStatus);
            echo json_encode($response);
        } else {
            $response = array("success" => false);
            echo json_encode($response);
        }
    }
}

$conn->close();
?>
