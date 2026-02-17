<?php

// Laboratorio vulnerable

  mysqli_report(MYSQLI_REPORT_OFF);

  $server = "localhost";
  $username = "qu1qu3h4ck";
  $password = "qu1qu3h4ck123";
  $database = "RedTeamLab";

  // Conexión a la BBDD

  $conn = new mysqli($server, $username, $password, $database);

  if($conn->connect_error){
    die("DB error de conexión: " . $conn->connect_error);
  }

  $id = $_GET['id'] ?? '';

  $data = mysqli_query($conn, "SELECT username FROM users WHERE id = '$id'")
    or die("SQL error: " . mysqli_error($conn));

  $response = mysqli_fetch_array($data);

  if(!$response) {
    die("Sin resultados para id=$id");
  }


  echo $response['username'];


?>

