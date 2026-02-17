<?php

mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli("localhost", "qu1qu3h4ck", "qu1qu3h4ck123", "RedTeamLab");


$user = $_GET['user'] ?? '';
$pass = $_GET['pass'] ?? '';


$query = "SELECT username, role FROM users WHERE username = '$user' AND password = '$pass'";

$data = mysqli_query($conn, $query) or die("SQL error: " . mysqli_error($conn));

$response = mysqli_fetch_assoc($data);

if ($response){
  echo "Bienvenido " . $response['username'] . " (" . $response['role'] . ")";
}else{
  echo "Credenciales inválidas";
}

?>
