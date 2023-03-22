<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../view/css/style.css">
  <link rel="icon" href="src/img/icon.ico">
  <title>Kidopi - Painel Covid-19</title>
</head>
<body>

  <?php

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $country1 = $_POST["country1"];
      $country2 = $_POST["country2"];
    } else {
      $country1 = "Brazil";
      $country2 = "Brazil";
    }

    $url = "https://dev.kidopilabs.com.br/exercicio/covid.php?pais=";
    $completeUrl1 = $url . $country1;
    $completeUrl2 = $url . $country2;
    $json1 = file_get_contents($completeUrl1);
    $json2 = file_get_contents($completeUrl2);
    $data1 = json_decode($json1);
    $data2 = json_decode($json2);
    $numberKeys1 = count(get_object_vars($data1));
    $numberKeys2 = count(get_object_vars($data2));

    $countries1 = array();
    $countries2 = array();
    $totalDead1 = 0;
    $totalDead2 = 0;
    $totalConfirmed1 = 0;
    $totalConfirmed2 = 0;

    for ($counter = 0; $counter < $numberKeys1; $counter++) {
      array_push($countries1, $data1->{$counter});
      $totalDead1 = $totalDead1 + $data1->{$counter}->Mortos;
      $totalConfirmed1 = $totalConfirmed1 + $data1->{$counter}->Confirmados;
    };

    for ($counter = 0; $counter < $numberKeys2; $counter++) {
      array_push($countries2, $data2->{$counter});
      $totalDead2 = $totalDead2 + $data2->{$counter}->Mortos;
      $totalConfirmed2 = $totalConfirmed2 + $data2->{$counter}->Confirmados;
    };

  ?>

  <h1>Países</h1>

  <session>
    <h3><?php echo $country1 ?></h3>
      <p><?php echo "Quantidade de casos: ", number_format($totalConfirmed1, 0, ',', '.') ?></p>
      <p><?php echo "Quantidade de óbitos: ", number_format($totalDead1, 0, ',', '.') ?></p>
  </session>
  
  <session>
    <h3><?php echo $country2 ?></h3>
      <p><?php echo "Quantidade de casos: ", number_format($totalConfirmed2, 0, ',', '.') ?></p>
      <p><?php echo "Quantidade de óbitos: ", number_format($totalDead2, 0, ',', '.') ?></p>
  </session>

  <h1>Comparativo:</h1>

  <?php
  
    if($totalConfirmed1 === 0 || $totalConfirmed2 === 0){
      echo "<p>Faltam dados!</p>";
    } else {
      echo "<h3>", $country1, "</h3>";
      echo "<p><b>Taxa de morte: </b>", number_format(($totalDead1 / $totalConfirmed1), 4, ',', '.'), "</p>";
      echo "<h3>", $country2, "</h3>";
      echo "<p><b>Taxa de morte: </b>", number_format(($totalDead2 / $totalConfirmed2), 4, ',', '.'), "</p>";

      echo "<h3> Comparativo </h3>";
      echo "<p><b>Diferença entre países: </b>", number_format((($totalDead1 / $totalConfirmed1) - ($totalDead2 / $totalConfirmed2)), 4, ',', '.'), "</p>";
    }

  ?>

  <a href="../">Voltar</a>

</body>
</html>
