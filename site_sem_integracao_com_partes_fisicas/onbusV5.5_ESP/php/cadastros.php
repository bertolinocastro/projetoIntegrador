<?php
	require_once "header.php"; 
	$AcessUser = acessUser::getInstance(Conexao::getInstance());
	$logger = $AcessUser->session();
	$nivel = $_SESSION['nivel']; 
    $loger = $_SESSION['login'];
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ONbus</title>
    <link rel="shortcut icon" href="../img/favicon.ico" />
	<!-- BOOTSTRAP STYLES-->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
	<link href="../css/login.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="../assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
	 <script src="https://code.jquery.com/jquery-3.1.1.min.js"
  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
   <script type="text/javascript" src="../assets/js/login.js"></script>
</head>
<body>
     <?php if($nivel == 0){
		header("Location: ./mapa.php");
	} else {
		?>
           
          
    <div id="wrapper">
		<div class="navbar navbar-inverse navbar-fixed-top">
            <div class="adjust-nav">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                  <div id="logo" class="col-xs-4 col-sm-2">
                        <img src="../img/logo.png">
                </div>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="destroy.php">SAIR</a></li>
                    </ul>
                </div>

            </div>
        </div>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center user-image-back">
                        <img src="../assets/img/find_user.png" class="img-responsive" />
                        <span><B>ADMINISTRADOR:</B> <?php  
                              echo $loger;
                           ?></span>
                    </li>


                    <li>
                        <a href="mapa.php?tipo=mapa"><i class="fa fa-desktop "></i>HOME</a>
                    </li>
					 <li>
                        <a href="updateUser.php"><i class="fa fa-qrcode "></i>PERFIL</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-bar-chart-o "></i>ADMINISTRATIVO<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="cadastros.php">CADASTRO ONIBUS / LINHA</a>
                            </li>
                            <li>
                                <a href="updateLine.php">INSERIR ITINERARIO / PONTO</a>
                            </li>
                            <li>
                                <a href="links.php">TROCAR LINHA / VINCULAR ONIBUS A LINHA</a>
                            </li>
                        </ul>
                    </li>
					
                    <li>
                        <a href="estatistica.php"><i class="fa fa-table "></i>ESTATISTICAS </a>
                    </li>

					<li>
                        <a href="destroy.php"><i class="fa fa-edit "></i>SAIR </a>
                    </li>
					
                </ul>

            </div>

        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h2>CADASTRO ONIBUS / LINHA</h2>   
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
				  <div class="container">
        <div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-login">
				
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
								<a href="#" class="active" id="login-form-link">Inserir linha</a>
							</div>
							<div class="col-xs-6">
								<a href="#" id="register-form-link">Cadastrar ônibus</a>
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<form id="login-form" action="insertLine.php" method="post" role="form" style="display: block;">
									<div class="form-group">
										<input type="text" name="num_linha" id="num_linha" tabindex="1" class="form-control" placeholder="Número da linha" value="" required="True">
									</div>
									<div class="form-group">
										<input type="text" name="desc_linha" id="desc_linha" tabindex="2" class="form-control" required="True" placeholder="Descrição da linha">
									</div>
									<div class="form-group">
										<input type="text" name="variacao_linha" id="variacao_linha" required="True" tabindex="1" class="form-control" placeholder="Variacão da linha" value="">
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="INSERIR">
											</div>
										</div>
									</div>

								</form>
								<form id="register-form" action="insertBus.php" method="post" role="form" style="display: none;">
									<div class="form-group">
										<input type="text" name="placa" id="placa" tabindex="1" class="form-control" placeholder="Número da placa" value="">
									</div>
									<div class="form-group">
										<input type="text" name="lotacao_max" id="lotacao" tabindex="1" class="form-control" placeholder="Lotação máxima" value="" maxlength="3">
									</div>
									<div class="form-group">
										<input type="text" name="num_onibus" id="num_bus" tabindex="2" class="form-control" placeholder="Número do ônibus">
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
              
                 <!-- /. ROW  -->           
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="../assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="../assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="../assets/js/jquery.metisMenu.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="../assets/js/custom.js"></script>
    
 <?php } ?>  
</body>
</html>
