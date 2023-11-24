<?php

require_once('../../../DB/db_conn.php');
// Check if the mobileNumber parameter is provided
if (isset($_GET['id'])) {
    // Get the mobileNumber from the request
    $order_id = $_GET['id'];

    // Perform database query to retrieve order details based on order_id


    if ($conn->connect_error) {
        echo("connection failed");
        die("Connection failed: " . $conn->connect_error);
    }
    
    
    // Fetch all orders and their items from the database
    $sql = "SELECT orders.order_id, orders.table_number, orders.total_amount, orders.order_date, orders.payment_mode, orders.payment_id, orders.status, order_items.quantity, order_items.size_name, menu_items.name, menu_items.image, orders.instructions
            FROM orders
            INNER JOIN order_items ON orders.order_id = order_items.order_id
            INNER JOIN menu_items ON order_items.menu_item_id = menu_items.id
            WHERE orders.order_id = '$order_id'";
    
    $result = $conn->query($sql);
    
    $orders = array();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $order_id = $row["order_id"];
            $table_number = $row["table_number"];
            
    
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
                    
                    "items" => array(),
                   
                );
            }
    
            $orders[$order_id]["items"][] = $item;
        }
    }

    // Print the order details to the console
    //print_r($orders);

    header('Content-Type: application/json');
echo json_encode(array_values($orders));

} else {
    // If order id parameter is not provided, send an error response
    echo 'Order Id number is required.';
}

?>