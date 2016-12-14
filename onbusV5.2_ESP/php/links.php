<?php
	require_once "header.php"; 
    $User = crudBus::getInstance(Conexao::getInstance());
    $Line = crudline::getInstance(Conexao::getInstance());
	$dadosBus = $User->viewBus();
	$dadosLine = $Line->viewLine();
	$busLine = $User->viewBusInLine();
	$AcessUser = acessUser::getInstance(Conexao::getInstance());
	$logger = $AcessUser->session();
    $nivel = $_SESSION['nivel']; 

?>

<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="utf-8">
	<title>OnBus</title>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/login.css">
	<link rel="stylesheet" href="../css/sidebar.css">
	<link rel="shortcut icon" href="../img/favicon.ico" />
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"
  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>
  <script type="text/javascript" src="../js/bootstrap.js"></script>
  <script type="text/javascript" src="../js/login.js"></script>

  <link rel="stylesheet" href="../css/styleAdmin.css">

</head>
<body>
	<?php if($nivel == 0){
		header("Location: ./mapa.php");
	} else {
		?>
		<nav class="navbar navbar-default sidebar" role="navigation">
	    <div class="container-fluid">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>      
	    </div>
	    <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
	      <ul class="nav navbar-nav">
	        <li class="active">
	        	<a href="mapa.php">Home
	        		<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-home"></span></a></li>
	        <li ><a href="updateUser.php">Perfil
	        	<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-user"></span></a></li>
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Administrativo
	          		<span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-th-list"></span></a>
	          <ul class="dropdown-menu1 forAnimate" role="menu">
	            <li><a href="cadastros.php">Cadastrar ônibus e linha</a></li>
	            <li><a href="links.php">Trocar linha e vincular<br> ônibus com a linha</a></li>
	            <li><a href="updateLine.php">Inserir ponto e itinerario</a></li>
	          </ul>
	        </li>          
	        <li ><a href="destroy.php">Sair<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicons glyphicons-power" ></span></a></li>
	      </ul>
	    </div>
	  </div>
</nav>


	<div class="container">
        <div class="row">
			<div class="col-md-6 col-md-offset-3">
			<div id="logo" class="logoBus">
							<img src="../img/logo.png">
						</div>
				<div class="panel panel-login">
				
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
								<a href="#" class="active" id="login-form-link">Inserir linhas nos ônibus </a>
							</div>
							<div class="col-xs-6">
								<a href="#" id="register-form-link">Trocar linha</a>
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<form id="login-form" action="insertBusInLine.php" method="post" role="form" style="display: block;">
									<div class="form-group">
									Número do Ônibus:    
										<select name="onibus" required>
											<option value="">Selecione o número do ônibus</option>
											<?php foreach ($dadosBus as $reg){?>
												<option name='onibus' value=" <?php echo $reg->id_onibus; ?> "> <?php echo $reg->numero_onibus; ?>  </option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group">
									Descritivo da linha:  
										<select name="linha" required>
											<option value="">Selecione uma linha</option>
											<?php foreach ($dadosLine as $reg){?>
												<option name='linha'  value=" <?php echo $reg->id_linha; ?> "> <?php echo $reg->num_linha. "  -  ". $reg->desc_linha; ?> </option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="INSERIR">
											</div>
										</div>
									</div>

								</form>
								<form id="register-form" action="updateBusLine.php" method="post" role="form" style="display: none;">
									<div class="form-group">
									Número do Ônibus:    
										<select name="onibus" required>
											<option value="">Selecione o número do ônibus</option>
											<?php foreach ($busLine as $reg){?>
												<option name='onibus' value=" <?php echo $reg->id_onibus; ?> "> <?php echo $reg->numero_onibus; ?>  </option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group">
									Descritivo da linha:  
										<select name="linha" required>
											<option value="">Selecione uma linha</option>
											<?php foreach ($dadosLine as $reg){?>
												<option name='linha'  value=" <?php echo $reg->id_linha; ?> "> <?php echo $reg->num_linha. "  -  ". $reg->desc_linha; ?> </option>
											<?php } ?>
										</select>

									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Atualizar">
											</div>
										</div>
									</div>

								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<script>
(function() {

  "use strict";

  var toggles = document.querySelectorAll(".c-hamburger");

  for (var i = toggles.length - 1; i >= 0; i--) {
    var toggle = toggles[i];
    toggleHandler(toggle);
  };

  function toggleHandler(toggle) {
    toggle.addEventListener( "click", function(e) {
      e.preventDefault();
      (this.classList.contains("is-active") === true) ? this.classList.remove("is-active") : this.classList.add("is-active");
    });
  }

})();
</script>



<?php } ?>
</body>
</html>


