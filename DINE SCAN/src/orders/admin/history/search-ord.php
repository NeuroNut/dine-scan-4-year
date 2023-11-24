<?php


// $db = new mysqli($host, $user, $password, $database);

require_once('../../../DB/db_conn.php');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle GET request to retrieve orders data
if ($_SERVER["REQUEST_METHOD"] == "GET") {
   $search = isset($_GET["search"]) ? $_GET["search"] : "";
   $searchd = isset($_GET["searchd"]) ? $_GET["searchd"] : "";

  $query = "SELECT * FROM orders where 1=1";
  if ($search) {
   // $search = mysqli_real_escape_string($conn, $search);
    $query .= " AND order_id LIKE '%$search%' OR table_number LIKE '%$search%' OR total_amount LIKE '%$search%' OR mobileNumber LIKE '%$search%' OR payment_mode LIKE '%$search%' OR status LIKE '%$search%'";
  }
  if ($searchd) {
    // $search = mysqli_real_escape_string($conn, $search);
     $query .= " AND order_date LIKE '%$searchd%'";
   }
   if (1) {
    $query .= " order by order_date desc";
   }

  $result = $conn->query($query);

  if ($result) {
    $orders = array();
    while ($row = $result->fetch_assoc()) {
      array_push($orders, $row);
    }
    echo json_encode($orders);
  } else {
    http_response_code(500);
    echo "Failed to retrieve order data";
  }
}

?>
