<?php

// $conn = mysqli_init();
// // mysqli_ssl_set($conn, NULL, NULL, "DigiCertGlobalRootCA.crt.pem", NULL, NULL);
// mysqli_real_connect($conn, "localhost", "root", "", "dinescanmenu", 3306);
require_once('../../DB/db_conn.php');
if ($conn->connect_error) {
    echo("connection failed");
    die("Connection failed: " . $conn->connect_error);
}


// Fetch all orders and their items from the database
$sql = "SELECT orders.order_id, orders.table_number, orders.total_amount, orders.order_date, orders.payment_mode, orders.payment_id, orders.status, order_items.quantity, order_items.size_name, menu_items.name, menu_items.image, orders.instructions
FROM orders
INNER JOIN order_items ON orders.order_id = order_items.order_id
INNER JOIN menu_items ON order_items.menu_item_id = menu_items.id
ORDER BY orders.order_id DESC";

$result = $conn->query($sql);

$orders = array();

if ($result->num_rows > 0) {
while ($row = $result->fetch_assoc()) {
$order_id = $row["order_id"];
$table_number = $row["table_number"];
$total_amount = $row["total_amount"];
$order_date = $row["order_date"];
$payment_mode = $row["payment_mode"];
$payment_id = $row["payment_id"];
$status = $row["status"];
$instructions = $row["instructions"];

$item = array(
    "name" => $row["name"],
    "image" => $row["image"],
    "size_name" => $row["size_name"],
    "quantity" => $row["quantity"],
    
);

if (!isset($orders[$order_id])) {
    $orders[$order_id] = array(
        "order_id" => $order_id,
        "table_number" => $table_number,
        "total_amount" => $total_amount,
        "order_date" => $order_date,
        "payment_mode" => $payment_mode,
        "payment_id" => $payment_id,
        "status" => $status,
        "items" => array(),
        "instructions" => $instructions
    );
}

$orders[$order_id]["items"][] = $item;
}
}

$conn->close();

// Return the orders data as JSON
header('Content-Type: application/json');
echo json_encode(array_values($orders));
?>
