<!-- billing_management.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Management</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Billing Management Dashboard</h2>

    <?php
    include '../../DB/db_conn.php';

    // Function to handle errors
    function handle_error($error_message) {
        echo "<script>console.error('$error_message');</script>";
    }

    // Retrieve data from the orders table
    $sql = "SELECT * FROM orders";
    $result = $conn->query($sql);

    if ($result === false) {
        handle_error("Error fetching data: " . $conn->error);
    }

    // Display total earnings by date, week, and month
    $total_earnings = 0;
    $prepaid_earnings = 0;
    $postpaid_earnings = 0;

    echo "<h3>Total Earnings</h3>";
    echo "<table>";
    echo "<tr><th>Date</th><th>Total Amount</th></tr>";

    while ($row = $result->fetch_assoc()) {
        // Assuming 'order_date' is in timestamp format
        $order_date = strtotime($row['order_date']);
        $formatted_date = date('Y-m-d', $order_date);

        $total_earnings += $row['total_amount'];

        if ($row['payment_mode'] == 'prepaid') {
            $prepaid_earnings += $row['total_amount'];
        } else {
            $postpaid_earnings += $row['total_amount'];
        }

        echo "<tr><td>$formatted_date</td><td>{$row['total_amount']}</td></tr>";
    }

    echo "</table>";

    // Display separate observations for prepaid and postpaid earnings
    echo "<h3>Prepaid Earnings: $prepaid_earnings</h3>";
    echo "<h3>Postpaid Earnings: $postpaid_earnings</h3>";
    echo "<h3>Combined Earnings: $total_earnings</h3>";

    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
