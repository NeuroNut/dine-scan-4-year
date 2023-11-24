<?php
// Include the db_conn.php file
require_once('../../DB/db_conn.php');

// Get the current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// Retrieve data from the orders table for the current month
$query = "SELECT * FROM orders WHERE MONTH(order_date) = $currentMonth AND YEAR(order_date) = $currentYear";
$result = mysqli_query($conn, $query);

// Check for errors and report to the console
if (!$result) {
    $error = mysqli_error($conn);
    error_log("Database error: " . $error);
    exit("An error occurred. Please try again later.");
}

// Calculate total earnings according to date, week, month, etc.
$totalEarnings = 0;
$prepaidEarnings = 0;
$postpaidEarnings = 0;

// Create an array to store the earnings for each date
$dateEarnings = array();

while ($row = mysqli_fetch_assoc($result)) {
    $totalEarnings += $row['total_amount'];

    if ($row['payment_mode'] == 'prepaid') {
        $prepaidEarnings += $row['total_amount'];
    } elseif ($row['payment_mode'] == 'postpaid') {
        $postpaidEarnings += $row['total_amount'];
    }

    // Store the earnings for each date
    $date = date('Y-m-d', strtotime($row['order_date']));
    if (!isset($dateEarnings[$date])) {
        $dateEarnings[$date] = array(
            'total' => 0,
            'prepaid' => 0,
            'postpaid' => 0
        );
    }
    $dateEarnings[$date]['total'] += $row['total_amount'];
    if ($row['payment_mode'] == 'prepaid') {
        $dateEarnings[$date]['prepaid'] += $row['total_amount'];
    } elseif ($row['payment_mode'] == 'postpaid') {
        $dateEarnings[$date]['postpaid'] += $row['total_amount'];
    }
}

// Close the database connection
//mysqli_close($conn);
?>

<!DOCTYPE html>

<html lang="en">
<head>
<link rel="shortcut icon" href="../../assets/images/dinescan.png">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Billing Overview</title>
<link rel="stylesheet" href="../../css/style.css">
<style>
  /* Add CSS for the side menu animation */
  .side-menu {
    display: none;
    transition: transform 5.3s ease-in-out;
    transform: translateX(-100%);
  }

  .side-menu.open {
    display: block;
    transform: translateX(0);
  }

  /* .side-menu {
    width: 100%;
    text-align: center;
  } */
  
  .side-menu ul {
    list-style: none;
  }
  
  .side-menu ul li {
    margin-bottom: 10px;
  }
  
  .side-menu ul li a {
    color: #fff;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s;
  }
  
  .side-menu ul li a:hover {
    color: #fdd835;
  }
</style>

<style>
        /* Add your CSS styles here */
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1, h2 {
            text-align: center;
        }

        h3, h4 {
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        form {
            margin-bottom: 20px;
            text-align: center;
        }

        input[type="date"] {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>

    
</head>
<body>
  <div class="animation-container">
    <img src="../../assets/images/dinescan.png" alt="Dine Logo" class="animation-logo">
  </div>
  
  <header class="header">
    <div class="logo">
      <img src="../../assets/images/dinescan.png" alt="Dine Logo" class="logo-img">
      <h1>MIT Fast Foods (Abstract Coders undertaken)</h1>
    </div>
  </header>
  
  <div class="main-container">
    <div class="left-pane">
      <div class="hamburger-menu">
        <div class="hamburger-line"></div>
        <div class="hamburger-line"></div>
        <div class="hamburger-line"></div>
      </div>
      <nav class="side-menu">
        <ul>
          <li><a href="index.html">Orders</a></li>
<li><a href="history/order_history.html">Order History</a></li>
          <li><a href="../../QR/qr.html">QR Generator</a></li>
          <li><a href="../../menu/admin/menu-management.html">Menu Management</a></li>
          <li><a href="#">Billing Management</a></li>
          <li><a href="../../analytics/analytics.html">Analytics</a></li>
        </ul>
      </nav>
    </div>
    <div class="right-pane">
    <div class="container">
        <h1>Billing Observation Dashboard - This Month</h1>

        <h4>Total Earnings</h4>
        <p><?php echo $totalEarnings; ?></p>

        <h4>Prepaid Earnings</h4>
        <p><?php echo $prepaidEarnings; ?></p>

        <h4>Postpaid Earnings</h4>
        <p><?php echo $postpaidEarnings; ?></p>

        <h2>Billing Information</h2>
        <form method="GET" action="">
            <label for="from_date">From Date:</label>
            <input type="date" id="from_date" name="from_date" required>

            <label for="to_date">To Date:</label>
            <input type="date" id="to_date" name="to_date" required>

            <input type="submit" value="Submit">
        </form>

        <?php
        // Check if the form is submitted
        if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
            // Get the selected dates
            $fromDate = $_GET['from_date'];
            $toDate = $_GET['to_date'];

            // Retrieve data from the orders table between the selected dates
            $query = "SELECT * FROM orders WHERE order_date BETWEEN '$fromDate' AND '$toDate'";
            $result = mysqli_query($conn, $query);

            // Check for errors and report to the console
            if (!$result) {
                $error = mysqli_error($conn);
                error_log("Database error: " . $error);
                exit("An error occurred. Please try again later.");
            }

            // Calculate earnings based on the selected dates
            $totalEarnings = 0;
            $prepaidEarnings = 0;
            $postpaidEarnings = 0;

            // Create an array to store the earnings for each date
            $dateEarnings = array();

            while ($row = mysqli_fetch_assoc($result)) {
                $totalEarnings += $row['total_amount'];

                if ($row['payment_mode'] == 'prepaid') {
                    $prepaidEarnings += $row['total_amount'];
                } elseif ($row['payment_mode'] == 'postpaid') {
                    $postpaidEarnings += $row['total_amount'];
                }

                // Store the earnings for each date
                $date = date('Y-m-d', strtotime($row['order_date']));
                if (!isset($dateEarnings[$date])) {
                    $dateEarnings[$date] = array(
                        'total' => 0,
                        'prepaid' => 0,
                        'postpaid' => 0
                    );
                }
                $dateEarnings[$date]['total'] += $row['total_amount'];
                if ($row['payment_mode'] == 'prepaid') {
                    $dateEarnings[$date]['prepaid'] += $row['total_amount'];
                } elseif ($row['payment_mode'] == 'postpaid') {
                    $dateEarnings[$date]['postpaid'] += $row['total_amount'];
                }
            }

            // Display the earnings based on the selected dates
            echo "<h4>Total Earnings (Selected Dates)</h4>";
            echo "<p>$totalEarnings</p>";

            echo "<h4>Prepaid Earnings (Selected Dates)</h4>";
            echo "<p>$prepaidEarnings</p>";

            echo "<h4>Postpaid Earnings (Selected Dates)</h4>";
            echo "<p>$postpaidEarnings</p>";

            // Display the table of earnings for each date
            echo "<h2>Earnings by Date (Selected Dates)</h2>";
            echo "<table>";
            echo "<tr><th>Date</th><th>Total Earnings</th><th>Prepaid Earnings</th><th>Postpaid Earnings</th></tr>";
            foreach ($dateEarnings as $date => $earnings) {
                echo "<tr>";
                echo "<td>$date</td>";
                echo "<td>{$earnings['total']}</td>";
                echo "<td>{$earnings['prepaid']}</td>";
                echo "<td>{$earnings['postpaid']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        ?>
    </div>
    </div>
  </div>

  <script>
    // Add JavaScript to toggle the side menu
    const hamburgerMenu = document.querySelector('.hamburger-menu');
    const sideMenu = document.querySelector('.side-menu');

    hamburgerMenu.addEventListener('click', () => {
      sideMenu.classList.toggle('open');
    });
  </script>
</body>
</html>

