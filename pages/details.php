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
  <br>

  <?php

    $confirmed = array();
    $deaths = array();

    foreach ($countries as $valor) {
      array_push($confirmed, $valor->Confirmados);
      array_push($deaths, $valor->Mortos);
    }

    $dataPoints = array();
    $dataPoints2 = array();
    $numberConfirmed = count($confirmed);
         
    for ($counter = 0; $counter < $numberConfirmed; $counter++) {
      array_push($dataPoints, array("label"=> $countries[$counter]->ProvinciaEstado, "y"=> $confirmed[$counter]));
      array_push($dataPoints2, array("label"=> $countries[$counter]->ProvinciaEstado, "y"=> $deaths[$counter]));
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
