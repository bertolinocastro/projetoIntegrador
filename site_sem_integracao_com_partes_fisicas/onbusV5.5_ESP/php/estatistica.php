<?php 
    require_once "header.php"; 

    $AcessUser = acessUser::getInstance(Conexao::getInstance());
    $static = estatistica::getInstance(Conexao::getInstance());
    $logger = $AcessUser->session();
    $nivel = $_SESSION['nivel']; 
    $staticLine = $static->estatisticaLinha();
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
                     <h2>Estatisticas</h2>   
                    </div>
                    <form action="estatistica.php?registrar=true" method="post">
                        <input class="form-control" placeholder="INSIRA O NOME DA LINHA" name="linha" />
                        <input type="submit" name="Enviar" class="btn btn-primary"></input>
                    </form>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
                  <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Número da linha</th>        
                            <th>Descrição da linha</th>
                            <th>Media de lotação</th>
                            <th>Quantidade de solicitações ( dia )</th>

                        </tr>
                        </thead>
                        <tbody>  
                        <?php 
                            if( isset($_GET["registrar"] )){
                                 $desc_linha = $_POST["linha"];  
                                 $staticLine = $static->searchLinha($desc_linha);
                                 foreach ($staticLine as $reg){
                          ?>
                             <tr>
                                <td><?php echo $reg->num_linha; ?></td>        
                                <td><?php echo $reg->desc_linha; ?></td>
                                <td><?php
                                    $staticLot = $static->estatisticaLotacao($reg->desc_linha);
                                    foreach ($staticLot as $key){ 
                                        echo $key->hora; } ?></td>
                                <td><?php
                                    $staticRequest = $static->estatisticaSolicitacao($reg->id_linha);
                                    foreach ($staticRequest as $key){ 
                                        echo $key->num; } ?></td>
                            </tr>       
                        
                        <?php } } else {
                            foreach ($staticLine as $reg){ ?> 
                        <tr>
                            <td><?php echo $reg->num_linha; ?></td>        
                            <td><?php echo $reg->desc_linha; ?></td>
                            <td><?php
                                $staticLot = $static->estatisticaLotacao($reg->desc_linha);
                                foreach ($staticLot as $key){ 
                                    echo $key->hora; } ?></td>
                            <td><?php
                                $staticRequest = $static->estatisticaSolicitacao($reg->id_linha);
                                foreach ($staticRequest as $key){ 
                                    echo $key->num; } ?></td>
                        </tr>        
                     <?php }} ?>
                    </tbody>
                </table>

              
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
