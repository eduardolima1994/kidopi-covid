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
    $totalDead = 0;
    $totalConfirmed = 0;

    for ($counter = 0; $counter < $numberKeys; $counter++) {
      array_push($countries, $data->{$counter});
      $totalDead = $totalDead + $data->{$counter}->Mortos;
      $totalConfirmed = $totalConfirmed + $data->{$counter}->Confirmados;
    };

    require './db/insert.php';

  ?>


  <session>
    <h3><?php echo $country ?></h3>
      <p><?php echo 'Quantidade de casos: ', $totalConfirmed ?></p>
      <p><?php echo 'Quantidade de óbitos: ', $totalDead ?></p>
  </session>
  <br>

<!--

  <session>
    <?php foreach ($countries as $valor) { ?>
      <h3><?php echo 'Estado: ', $valor->ProvinciaEstado ?></h3>
      <p><?php echo 'Casos confirmados: ', $valor->Confirmados ?></p>
      <p><?php echo 'Óbitos: ', $valor->Mortos ?></p>
      <p><?php echo 'País de referência: ', $valor->Pais ?></p>
      <br>
    <?php } ?>
  </session>

-->
  <?php

    $purchased = array();
    $sold = array();

    foreach ($countries as $valor) {
      array_push($purchased, $valor->Confirmados);
      array_push($sold, $valor->Mortos);
    }

    $dataPoints = array();
    $dataPoints2 = array();
    $numberPurchased = count($purchased);
     
    
    for ($counter = 0; $counter < $numberPurchased; $counter++) {
      array_push($dataPoints, array("label"=> $countries[$counter]->ProvinciaEstado, "y"=> $purchased[$counter]));
      array_push($dataPoints2, array("label"=> $countries[$counter]->ProvinciaEstado, "y"=> $sold[$counter]));
    };

  ?>

  <div id="chartContainer" style="height: 300px; width: 100%;"></div>

  <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
  
  <script>
      window.onload = function () {
          
          var chart = new CanvasJS.Chart("chartContainer", {
              animationEnabled: true,
              title:{
                  text: "Estados"
              },    
              axisY: {
                  title: "Confirmados",
                  titleFontColor: "#25283D",
                  lineColor: "#25283D",
                  labelFontColor: "#25283D",
                  tickColor: "#25283D"
              },
              axisY2: {
                  title: "Óbitos",
                  titleFontColor: "#C0504E",
                  lineColor: "#C0504E",
                  labelFontColor: "#C0504E",
                  tickColor: "#C0504E"
              },    
              toolTip: {
                  shared: true
              },
              legend: {
                  cursor:"pointer",
                  itemclick: toggleDataSeries
              },
              data: [{
                  type: "column",
                  name: "Confirmados",
                  legendText: "Confirmados",
                  showInLegend: true, 
                  dataPoints:<?php echo json_encode($dataPoints,
                          JSON_NUMERIC_CHECK); ?>
              },
              {
                  type: "column",    
                  name: "Óbitos",
                  legendText: "Óbitos",
                  axisYType: "secondary",
                  showInLegend: true,
                  dataPoints:<?php echo json_encode($dataPoints2,
                          JSON_NUMERIC_CHECK); ?>
              }]
          });
          chart.render();
              
          function toggleDataSeries(e) {
              if (typeof(e.dataSeries.visible) === "undefined"
                          || e.dataSeries.visible) {
                  e.dataSeries.visible = false;
              }
              else {
                  e.dataSeries.visible = true;
              }
              chart.render();
          }
          
      }
  </script>

</body>
</html>
