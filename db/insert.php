<?php

  require 'config.php';

  $conn = mysqli_connect($host, $user, $password, $dbname);

  if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
  }

  $timeZone = new DateTimeZone('America/Sao_Paulo');
  $dateTime = (new DateTime('now', $timeZone))->format('Y-m-d H:i:s');

  $sql = "INSERT INTO access_logs (access_datetime, country) VALUES ('$dateTime', '$country')";

  if (mysqli_query($conn, $sql)) {
    //echo "Dados inseridos com sucesso!";
  } else {
    echo "Erro: " . mysqli_error($conn);
  }

  mysqli_close($conn);

?>