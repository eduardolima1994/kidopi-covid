<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kidopi - Painel Covid-19</title>
</head>
<body>

  <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $country = $_POST['country'];
    } else {
      $country = 'Brazil';
    }

    $url = 'https://dev.kidopilabs.com.br/exercicio/covid.php?pais=';
    $completeUrl = $url . $country;
    $json = file_get_contents($completeUrl);
    $data = json_decode($json);
    $numberKeys = count(get_object_vars($data));
    $countries = array();
    $totalDeaths = 0;
    $totalConfirmed = 0;

    for ($counter = 0; $counter < $numberKeys; $counter++) {
      array_push($countries, $data->{$counter});
      $totalDeaths = $totalDeaths + $data->{$counter}->Mortos;
      $totalConfirmed = $totalConfirmed + $data->{$counter}->Confirmados;
    };

    require './db/insert.php';

  ?>

  <session>
    <h3><?php echo $country ?></h3>
      <p><?php echo 'Quantidade de casos: ', number_format($totalConfirmed, 0, ',', '.') ?></p>
      <p><?php echo 'Quantidade de óbitos: ', number_format($totalDeaths, 0, ',', '.') ?></p>
  </session>

  <session>
    <?php foreach ($countries as $valor) { ?>
      <h5><?php echo 'Estado: ', $valor->ProvinciaEstado ?></h5>
      <p><h6><?php echo 'Casos confirmados: ', $valor->Confirmados ?></h6></p>
      <p><h6><?php echo 'Óbitos: ', $valor->Mortos ?></h6></p>
    <?php } ?>
  </session>

</body>
</html>
