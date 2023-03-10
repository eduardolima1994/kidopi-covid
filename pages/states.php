<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kinopi - Covid-19</title>
</head>
<body>

  <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $country = $_POST['country'];
    } else {
      $country = 'Brasil';
    }
     
    $url = 'https://dev.kidopilabs.com.br/exercicio/covid.php?pais=';
    $completeUrl = $url . $country;
    $json = file_get_contents($completeUrl);
    $data = json_decode($json);
    $numberKeys = count(get_object_vars($data));
    $countries = array();
    $totalDead = 0;
    $totalConfirmed = 0;

    for ($counter = 0; $counter < $numberKeys; $counter++) {
      array_push($countries, $data->{$counter});
      $totalDead = $totalDead + $data->{$counter}->Mortos;
      $totalConfirmed = $totalConfirmed + $data->{$counter}->Confirmados;
    };

    require './db/db.php';

  ?>

  <session>
    <h3>Dados do país: <?php echo $country ?></h3>
      <p><?php echo 'Quantidade de casos: ', $totalConfirmed ?></p>
      <p><?php echo 'Quantidade de óbitos: ', $totalDead ?></p>
  </session>

  <hr>

  <session>
    <?php foreach ($countries as $valor) { ?>
      <h3><?php echo 'Estado: ', $valor->ProvinciaEstado ?></h3>
      <p><?php echo 'Casos confirmados: ', $valor->Confirmados ?></p>
      <p><?php echo 'Óbitos: ', $valor->Mortos ?></p>
      <p><?php echo 'País de referência: ', $valor->Pais ?></p>
    <?php } ?>
  </session>

</body>
</html>
