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
    echo "<p class='credit'><strong>Último país acessado:  </strong>".$row['country']."</p>";
    $dataHoraFormatada = date('d/m/Y H:i:s', strtotime($row['access_datetime']));
    echo "<p class='credit'><strong>Data e horário do último acesso:  </strong> ".$dataHoraFormatada."</p>";
  }

  mysqli_close($conn);

?>