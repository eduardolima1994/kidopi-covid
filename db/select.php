<?php

  require 'config.php';

  $conn = mysqli_connect($host, $user, $password, $dbname);

  if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
  }

  $query = sprintf("SELECT * FROM access_logs ORDER BY id DESC LIMIT 1,1;");
  // executa a query
  $dados = mysqli_query($conn, $query);
  // transforma os dados em um array

  foreach($dados as $row) {
    echo "<strong>Último país acessado:  </strong>".$row['country'];
    echo "<br>";
    echo "<strong>Data e horário do acesso:  </strong> ".$row['access_datetime'];
  }

  mysqli_close($conn);

?>