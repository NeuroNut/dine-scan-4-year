<?php

// Read the raw JSON data from the request body
$data = file_get_contents("php://input");
$feedbackData = json_decode($data, true); // Use true to decode as an associative array

if ($feedbackData === null) {
    echo json_encode(array('success' => false, 'message' => 'Invalid JSON data'));
    exit;
}

try {
    
    require_once('../../DB/db_conn.php');

    if ($conn->connect_error) {
        echo json_encode(array('success' => false, 'message' => 'Connection failed'));
        exit;
    }

    $rating = $feedbackData['rating'];
    $feedbackText = $feedbackData['feedback'];
    $mobileNumber = $feedbackData['mobile_number'];
    $payment_mode = $feedbackData['payment_mode'];
    $total_amount = $feedbackData['total_amount'];

    $query = "INSERT INTO feedback (rating, feedback_text, mobile_number, payment_mode, total_amount) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('isdsd', $rating, $feedbackText, $mobileNumber, $payment_mode, $total_amount);
    $stmt->execute();
    $feedbackID = $stmt->insert_id;

    $response = array('success' => true, 'message' => 'Feedback saved successfully!');
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(array('success' => false, 'message' => 'Failed to save feedback. Please try again.'));
}
?>


