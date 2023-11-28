<?php
require_once('../../DB/db_conn.php');

$mobileNumber = $_GET['mobileNumber'];



$query = "SELECT * FROM customers where PhoneNumber = '$mobileNumber'";
$result = mysqli_query($conn, $query);

// Check if there are details
if (mysqli_num_rows($result) > 0) {
    $customerDetails = array();

    while ($row = mysqli_fetch_assoc($result)) {
       
        $customerDetails[] = array('Name' => $row['Name'], 'dob' => $row['DateOfBirth']);
    }

    // Return the details as JSON
    header('Content-Type: application/json');
    echo json_encode($customerDetails);
} else {
    echo json_encode(array('message' => 'No Name found, lets order something!'));
}

// Close the database connection
mysqli_close($conn);
?>
