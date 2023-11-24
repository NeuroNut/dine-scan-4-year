<?php
$err ="";
$ses = "";
$no = "";

$tableNo;

session_start();

if (isset($_POST['btn'])) {

$otp = rand(10000,99999);
$_SESSION['otp']=$otp;
$no = $_POST['num'];
// echo $no;
if(preg_match("/^\d+\.?\d*$/",$no) && strlen($no)==10){

$fields = array(
"variables_values" => "$otp",
"route" => "otp",
"numbers" => "$no",
);

$curl = curl_init();

curl_setopt_array($curl, array(
CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 30,
CURLOPT_SSL_VERIFYHOST => 0,
CURLOPT_SSL_VERIFYPEER => 0,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "POST",
CURLOPT_POSTFIELDS => json_encode($fields),
CURLOPT_HTTPHEADER => array(
"authorization: GVMdL26paEvAUkjozyq7ZFnTCOmb8uSie5hr1IX9BxH4PcDsQgSdmzP3Z4UgHEp7I9ce6tyNlGOChrTB",
"accept: */*",
"cache-control: no-cache",
"content-type: application/json"
),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
    } else {
        // echo $response;
    $data = json_decode($response);
    $sts = $data->return;
    if ($sts == false) {
    $err = "Otp Not Send";
    }else{
    $ses = "Your OTP has been sent";
    }
    }
}else{
$err = "Invalid Mobile Number";
}

}

if (isset($_POST['num'])) {
  $no = $_POST['num']; // Store the mobile number if set
}
if (isset($_POST['btn2'])){
    if (isset($_GET['tableNo'])) {
        $tableNo = $_GET['tableNo'];
        //echo $tableNo;
        }
    $input_otp = $_POST['inputotp'];
    $stored_otp = $_SESSION['otp'];
    
    if($input_otp == $stored_otp) {
      header("Location: http://localhost/DINE SCAN/src/menu/customer/customer_menu.html?tableNo=" . urlencode($tableNo) . "&mobileNumber=" . urlencode($no));
          }
    else{
        
        $err = "wrong otp try again";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
<link rel="shortcut icon" href="../../assets/images/dinescan.png">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OTP Login</title>
  <link rel="stylesheet" href="../../css/style2.css">
  <!-- <script src="script.js"></script> -->
</head>
<body>
<header>
    <img src="../../assets/images/dinescan.png" alt="Dine Logo">
    <h1>MIT Fast Foods(Abstract Coders undertaken)</h1>
  </header>
  <div class="table-number">
      <h2>Table Number: 0</h2>
    </div>

<div class=" container mt-5" align="center">
<h1 class="text-center">Enter OTP to login into Dine Scan</h1>
<form action="" method="post" class="mt-5">
<!-- Add the value attribute to the mobile number input field -->
<input type="text" placeholder="Mobile number" name="num" class="premium-input" value="<?php echo $no; ?>"><br><br>
<input type="submit" class="order-button" value="Send OTP" name="btn"><br><br>
<input type="number" placeholder="Enter otp" name="inputotp" class="premium-input"><br><br>
<input type="submit" class="order-button" value="Validate OTP" name="btn2"><br>
</form>
<p class=" text-center text-danger"><?php echo $err; ?></p>
<p class=" text-center text-success"><?php echo $ses; ?></p>
</div>

<script>
    function getQueryParam(name) {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get(name);
    }
  
    // Function to display the table number
    function displayTableNumber() {
      const tableNumber = getQueryParam('tableNo');
      if (tableNumber) {
        const tableNumberDiv = document.querySelector(".table-number h2");
        tableNumberDiv.textContent = "Table Number: " + tableNumber;
      }
    }
  
    // Call the displayTableNumber function when the page loads
    window.addEventListener('DOMContentLoaded', displayTableNumber);
</script>

<footer>
    <p>Copyright &copy; 2023 Abstract Coders</p>
  </footer>
</body>

</html>