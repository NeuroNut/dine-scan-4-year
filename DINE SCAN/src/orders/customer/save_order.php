
<?php

// Read the raw JSON data from the request body
$data = file_get_contents("php://input");
$orderData = json_decode($data, true); // Use true to decode as an associative array

if ($orderData === null) {
    echo json_encode(array('success' => false, 'message' => 'Invalid JSON data'));
    exit;
}

// Check if the required fields exist
if (!isset($orderData['table_number']) || !isset($orderData['total_amount']) || !isset($orderData['mobile_number']) || !isset($orderData['items']) || !isset($orderData['payment_mode'])) {
    echo json_encode(array('success' => false, 'message' => 'Missing required fields'));
    exit;
}

try {
    // $conn = mysqli_init();
    // mysqli_ssl_set($conn, NULL, NULL, "DigiCertGlobalRootCA.crt.pem", NULL, NULL);
    // mysqli_real_connect($conn, "dinescanfrt.mysql.database.azure.com", "abstractcoders", "Universe$1", "dinescanmenu", 3306, MYSQLI_CLIENT_SSL);    

    require_once('../../DB/db_conn.php');

    if ($conn->connect_error) {
        echo json_encode(array('success' => false, 'message' => 'Connection failed'));
        exit;
    }

    // Insert the order details into the 'orders' table
    $tableNumber = $orderData['table_number'];
    $totalAmount = $orderData['total_amount'];
    $mobileNumber = $orderData['mobile_number'];
    $instructions = $orderData['instructions'];
    $paymentMode = $orderData['payment_mode'];
    $paymentId = $orderData['payment_id'];

    $query = "INSERT INTO orders (table_number, total_amount, mobileNumber, instructions, payment_mode, payment_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iddsss', $tableNumber, $totalAmount, $mobileNumber, $instructions, $paymentMode, $paymentId);
    $stmt->execute();
    $orderID = $stmt->insert_id;

    // Insert order items into the 'order_items' table
    foreach ($orderData['items'] as $item) {
        $itemName = $item['name'];
        $sizeName = $item['size'];
        $quantity = $item['quantity'];

        $stmt = $conn->prepare("SELECT id FROM menu_items WHERE name = ?");
        $stmt->bind_param('s', $itemName);
        $stmt->execute();
        $result = $stmt->get_result();
        $menuItem = $result->fetch_assoc();

        if ($menuItem) {
            $menuItemID = $menuItem['id'];

            $stmt = $conn->prepare("INSERT INTO order_items (order_id, menu_item_id, size_name, quantity) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('iisi', $orderID, $menuItemID, $sizeName, $quantity);
            $stmt->execute();
        }
    }

    // Send a success response back to the frontend
    echo json_encode(array('success' => true));

    // Close the database connection
    $conn->close();
} catch (Exception $e) {
    // Handle database errors
    echo json_encode(array('success' => false, 'error' => $e->getMessage()));
    error_log($e->getMessage()); // Log the error message
}
?>
