<?php
require_once('../../DB/db_conn.php');
// Replace 'your_mobile_number_column_name' with the actual name of the column in your 'orders' table.
$mobileNumber = $_GET['mobileNumber'];


// Query to fetch past orders for the specific user

//$query = "SELECT orders.order_id, orders.order_date, orders.total_amount, order_items.quantity, menu_items.name
        // FROM orders
        // INNER JOIN order_items ON orders.order_id = order_items.order_id
        // INNER JOIN menu_items ON order_items.menu_item_id = menu_items.id
        // where mobileNumber=$mobileNumber
        // ORDER BY orders.order_date DESC";

$query = "SELECT * FROM orders WHERE mobileNumber = '$mobileNumber'";
$result = mysqli_query($conn, $query);

// Check if there are past orders
if (mysqli_num_rows($result) > 0) {
    $pastOrders = array();

    while ($row = mysqli_fetch_assoc($result)) {
        // You can structure the past order data as needed and add it to the $pastOrders array.
        $pastOrders[] = array('order_id' => $row['order_id'], 'order_total' => $row['total_amount'], 'order_date' => $row['order_date']);
        // $order_id = $row["order_id"];
        // $order_date = $row["order_date"];
        // $total_amount = $row["total_amount"];

        // $item = array(
        //     "name" => $row["name"],
        //     "quantity" => $row["quantity"]
        // );

        // if (!isset($pastOrders[$order_id])) {
        //     $pastOrders[$order_id] = array(
        //         "order_date" => $order_date,
        //         "total_amount" => $total_amount,
        //         "items" => array()
        //     );
        // }

        //$pastOrders[$order_id]["items"][] = $item;
    }

    // Return the past orders as JSON
    header('Content-Type: application/json');
    echo json_encode($pastOrders);
} else {
    // No past orders found
    echo json_encode(array('message' => 'No past orders found, lets order something!'));
}

// Close the database connection
mysqli_close($conn);
?>
