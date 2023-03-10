<?php

  require 'config.php';

  $conn = mysqli_connect($host, $user, $password, $dbname);

  if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
  }

  $valor1 = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
  $valor1 = $valor1->format('Y-m-d H:i:s');
  $valor2 = $country;

  $sql = "INSERT INTO access_logs (access_datetime, country) VALUES ('$valor1', '$valor2')";

  if (mysqli_query($conn, $sql)) {
    //echo "Dados inseridos com sucesso!";
  } else {
    echo "Erro: " . mysqli_error($conn);
  }

  mysqli_close($conn);

?>