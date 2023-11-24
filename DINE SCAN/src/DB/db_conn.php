<?php

// $conn = mysqli_init();
// mysqli_ssl_set($conn,NULL,NULL, "DigiCertGlobalRootCA.crt.pem", NULL, NULL);
// mysqli_real_connect($conn, "dinescanfrt.mysql.database.azure.com", "abstractcoders", "asli_password_nahi", "dinescanmenu", 3306, MYSQLI_CLIENT_SSL);

$conn = mysqli_init();
// mysqli_ssl_set($conn, NULL, NULL, "DigiCertGlobalRootCA.crt.pem", NULL, NULL);
mysqli_real_connect($conn, "localhost", "root", "", "dinescanmenu", 3306);

if (!$conn) {
	echo "Connection failed!";
}
