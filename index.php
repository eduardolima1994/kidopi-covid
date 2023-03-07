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
    $json = file_get_contents("https://dev.kidopilabs.com.br/exercicio/covid.php?listar_paises=1");
    $data = json_decode($json);
    $countries = array();
    array_push($countries, $data->{9}, $data->{24}, $data->{33});
  ?>

  <form method="POST" action="index.php">
    <select name="country" required="required">
      <?php foreach ($countries as $valor) { ?>
        <option id="variable" name="variable" value=<?php echo $valor ?>><?php echo $valor ?></option>
      <?php } ?>
    </select>
    <button type="submit">Enviar</button>
  </form>

  <?php
    include('pages/states.php');
  ?>

</body>
</html>
