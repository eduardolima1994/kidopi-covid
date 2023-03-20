<!DOCTYPE html>
<html lang="pt-br" >
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css'>
    <link rel="stylesheet" href="./view/css/style.css">
    <link rel="icon" href="src/img/icon.ico">
    <title>Kidopi - Painel Covid-19</title>
</head>
<body>

<header>
    <div class="container">
        <div class="menu">
            <a href="index.php" class="logo">Kidopi - Painel Covid-19</a>
            <a href="index.php"><img src="src/img/logo.gif" alt="Kidopi - Painel Covid-19" /></a>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="#">Países</a></li>
                <li><a href="#">Contato</a></li>
                <li><a href="#">Sobre</a></li>
            </ul>
            <div class="hamburger-menu">
                <div class="bar"></div>
            </div>
        </div>
    </div>
</header>

<section class="section section1">
  
    <?php
        $json = file_get_contents("https://dev.kidopilabs.com.br/exercicio/covid.php?listar_paises=1");
        $data = json_decode($json);
        $countries = array();
        array_push($countries, $data->{9}, $data->{24}, $data->{33});
    ?>

    <form id="form-profession" class="selectdiv" method="POST" action="index.php">
        <select id="select-profession" name="country" onchange="enviarFormulario()"> 
            <option>Selecione um país</option>
            <?php foreach ($countries as $valor) { ?>
                <option id="variable" name="variable" value=<?php echo $valor ?>><?php echo $valor ?></option>
            <?php } ?>
        </select>
    </form>

    <?php
        include('pages/details.php');
    ?>

</section>

<section class="section section2">

    <h1>Compare países:</h1>
    
    <?php
        $json = file_get_contents("https://dev.kidopilabs.com.br/exercicio/covid.php?listar_paises=1");
        $data = json_decode($json);
        $countries = array();
        foreach ($data as $key => $value) {
            array_push($countries, $value);
        }
    ?>

    <form id="form-profession" class="selectdiv" method="POST" action="pages/compareCountries.php">
        
        <select id="select-profession" name="country1"> 
            <option>Selecione um país</option>
            <?php foreach ($countries as $valor) { ?>
                <option id="variable" name="variable" value=<?php echo $valor ?>><?php echo $valor ?></option>
            <?php } ?>
        </select>
    
        <select id="select-profession" name="country2"> 
            <option>Selecione um país</option>
            <?php foreach ($countries as $valor) { ?>
                <option id="variable" name="variable" value=<?php echo $valor ?>><?php echo $valor ?></option>
            <?php } ?>
        </select>

        <button type="submit">Comparar</button>

    </form>

</section>
<section class="section section3">
  <?php
    require './db/select.php';
  ?>
</section>

<script>
  function enviarFormulario() {
    document.getElementById("form-profession").submit();
  }
</script>

<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/lodash@4.17.11/lodash.min.js'></script>
<script  src="./view/js/nav-bag.js"></script>
<script  src="./view/js/select.js"></script>

</body>
</html>
