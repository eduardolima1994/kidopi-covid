<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>Kidopi - Painel Covid-19</title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700" rel="stylesheet">
	<link rel="stylesheet" href="css/fontAwesome.min.css">
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/milligram.min.css">
	<link rel="stylesheet" href="css/styles.css">
	<link rel="icon" href="img/icon.ico">
</head>
<body>
	<div class="navbar">
		<div class="row">
			<div class="column column-30 col-site-title"><a href="./" class="site-title float-left">Kidopi Covid-19</a></div>
			<div class="column column-30">
				<div class="user-section"><a href="#">
					<a href="./"><img src="img/logo.gif" alt="profile photo" class="circle float-left profile-photo" width="50" height="auto"></a>
					<div class="username">
						<h4><a href="./">Acompanhamento</a></h4>
						<p><a href="./">Covid-19</a></p>
					</div>
				</a></div>
			</div>
		</div>
	</div>
	<div class="row">
		<div id="sidebar" class="column">
			<h5>Nevegação</h5>
			<ul>
				<li><a href="#"><em class="fa fa-home"></em> Início</a></li>
				<li><a href="#country"><em class="fa fa-hand-o-up"></em> Países</a></li>
				<li><a href="#graphic"><em class="fa fa-bar-chart"></em> Estados</a></li>
				<li><a href="#compare"><em class="fa fa fa-clone"></em> Comparativo</a></li>
				<li><a href="#about"><em class="fa fa-pencil-square-o"></em> Sobre</a></li>
				
			</ul>
		</div>
		<section id="main-content" class="column column-offset-20">
			<div class="row grid-responsive">
				<div class="column page-heading">
					<div class="large-card">
						<h1>Bem-vindo(a)!</h1>
						<p class="text-large">Este é o nosso sistema de acompanhamento de casos da COVID-19! </p>
						<p>Estamos felizes em tê-lo aqui conosco nesta importante missão de combate à pandemia que assola o mundo. Com este sistema, você terá acesso a informações atualizadas e precisas sobre a evolução da COVID-19 em todos os países. Sabemos que este é um momento difícil para todos, mas juntos podemos superar esta crise. Com a colaboração de cada um, podemos controlar a disseminação do vírus e proteger os mais vulneráveis. <em>(Juntos, podemos vencer esta batalha!)</em></p>
						<a href="#country" class="button">Consulte os países</a>
					</div>
				</div>
			</div>

			<!--Country-->
			<h5 class="mt-2">País</h5><a class="anchor" name="country"></a>
			<div class="row grid-responsive">
				<div class="column ">
					<div class="card">
						<div class="card-title">
							<h3>Consulte os dados</h3>
						</div>
						<div class="card-block">

							<?php
									$json = file_get_contents("https://dev.kidopilabs.com.br/exercicio/covid.php?listar_paises=1");
									$data = json_decode($json);
									$countries = array();
									array_push($countries, $data->{9}, $data->{24}, $data->{33});
							?>

							<form id="form-profession" class="selectdiv" method="POST" action="index.php#country">
									<label for="ageRangeField">Selecione um país</label>
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

						</div>
					</div>
				</div>
			</div>

			<!--Graphic-->
			<h5>Estados</h5><a class="anchor" name="graphic"></a>
			<div class="row grid-responsive">
				<div class="column column-100">
					<div class="card">
						<div class="card-title">
							<h2><?php echo "Estados do país: ".$country ?></h2>
						</div>
						<div class="card-block">
							<div class="canvas-wrapper">
								<canvas class="chart" id="bar-chart" height="auto" width="auto"></canvas>
								<canvas class="chart" id="line-chart" height="0" width="0"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!--Compare-->
			<h5 class="mt-2">Comparativo</h5><a class="anchor" name="compare"></a>
			<div class="row grid-responsive">
				<div class="column ">
					<div class="card">
        
						<?php
								$json = file_get_contents("https://dev.kidopilabs.com.br/exercicio/covid.php?listar_paises=1");
								$data = json_decode($json);
								$countries = array();
								foreach ($data as $key => $value) {
										array_push($countries, $value);
								}
						?>

						<div class="card-title">
							<h3>Selecione países para comparação</h3>
						</div>
						<div class="card-block">
							<form  id="form-profession" class="selectdiv" method="POST" action="pages/compareCountries.php">
								<fieldset>
									<label for="ageRangeField">Selecione o primeiro país</label>
									<select id="select-profession" name="country1">
										<option>Selecione um país</option>
										<?php foreach ($countries as $valor) { ?>
												<option id="variable" name="variable" value=<?php echo $valor ?>><?php echo $valor ?></option>
										<?php } ?>
									</select>
									<label for="ageRangeField">Selecione o segundo país</label>
									<select id="select-profession" name="country2">
										<option>Selecione um país</option>
										<?php foreach ($countries as $valor) { ?>
												<option id="variable" name="variable" value=<?php echo $valor ?>><?php echo $valor ?></option>
										<?php } ?>
									</select>
									<button type="submit" class="button-compare">Comparar</button>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		
			<a class="anchor" name="about"></a>
			<?php
				require './db/select.php';
			?>
			<p class="credit">KIDOPI PAINEL COVID-19 by <a href="https://github.com/eduardolima1994">Eduardo Lima</a></p>

		</section>
	</div>
	
	<script src="js/chartConfig.js"></script>

</body>
</html> 