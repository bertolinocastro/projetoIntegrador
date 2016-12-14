<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="utf-8">
	<title>OnBus Login</title>
	<link rel="stylesheet" href="./css/bootstrap.min.css">
	<link rel="stylesheet" href="./css/login.css">
	<link rel="shortcut icon" href="img/favicon.ico" />
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"
  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>
  <script type="text/javascript" src="./js/bootstrap.js"></script>
  <script type="text/javascript" src="./js/login.js"></script>

</head>
<body>
	
	
	<div class="container">
        <div class="row">
		<div id="logo" class="col-xs-4 col-sm-2">
                <img src="./img/logo.png">
        </div>
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-login">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-6">
							<a href="#" class="active" id="login-form-link">Login</a>
						</div>
						<div class="col-xs-6">
							<a href="#" id="register-form-link">Cadastrar</a>
						</div>
					</div>
				<hr>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">
						<form id="login-form" action="php/controle.php" method="post" style="display: block;">
							<div class="form-group">
								<input type="text" name="login" id="login" tabindex="1" class="form-control" placeholder="Login" required="True">
							</div>
							<div class="form-group">
								<input type="password" name="senha" id="password" tabindex="2" class="form-control" placeholder="Senha" required="True">
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-sm-6 col-sm-offset-3">
										<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="LOGIN">
									</div>
								</div>
							</div>

						</form>
						<form id="register-form" action="./php/insertUser.php" method="post" style="display: none;">
							<div class="form-group">
								<input type="text" name="login" id="login" tabindex="1" class="form-control" placeholder="Login" required="True">
							</div>
							<div class="form-group">
								<input type="password" name="senha" id="email" tabindex="1" class="form-control" placeholder="Senha" required="True">
							</div>
							<div class="form-group">
								<input type="text" name="email" id="password" tabindex="2" class="form-control" placeholder="Email" required="True">
							</div>
							<div class="form-group">
								<input type="text" name="cpf" id="cpf" tabindex="2" class="form-control" placeholder="CPF" required="True">
							</div>
							<div class="form-group">
								<input type="date" name="nascimento" id="data" tabindex="2" class="form-control" placeholder="Data de nascimento" required="True">
							</div>
							<div class="form-group">
								Especial:<br />
								<input type="radio" name="especial" value="1" /> Sim<br />
								<input type="radio" name="especial" value="0" /> Não<br />
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6 col-sm-offset-3">
										<input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Registrar">
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

</body>
</html>


