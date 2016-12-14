<?php
  	require_once "header.php"; 

    // Criando as istancias que irão ser utilizadas
    $AcessUser = acessUser::getInstance(Conexao::getInstance());
    $User = crudBus::getInstance(Conexao::getInstance());
    $UserInfo = crudUser::getInstance(Conexao::getInstance());
    $Point = crudPoint::getInstance(Conexao::getInstance());
    $Line = crudLine::getInstance(Conexao::getInstance());
    $logger = $AcessUser->session();

    $latitude = -12.938404;
    $longitude = -38.386640;

    $pegarPonto = $Point->pegarPonto($latitude, $longitude);

    $_SESSION['id_ponto'] = $pegarPonto;

    $dados = $Line->viewLine();
    $getPoint = $Point->getPoint($pegarPonto);
    $loger = $_SESSION['login']; 
    $pass = $_SESSION['senha']; 
    $id_usuario = $_SESSION['id_usuario'];
    $nivel = $_SESSION['nivel']; 
    $dadosUser = $UserInfo->getInfoUser($loger, $pass);
    $calcTime = $AcessUser->calcTime();   

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="utf-8">
    <title>OnBus</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../img/favicon.ico" />
    <!-- BootStrap-->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script type="text/javascript" src="../js/mapa.js"></script>
    <script src="//maps.googleapis.com/maps/api/js?v=3.exp&sensor=true"></script>
    <script type="text/javascript" src="../js/default.js"></script>

</head>
<body>

    <div class="col-xs-12 col-sm-12" id="header">
        <div class="row">
            <div id="logo" class="col-xs-4 col-sm-2">
                <img src="../img/logo.png">
            </div>
            <div class="col-xs-2 col-xs-offset-6 col-sm-2 col-sm-offset-8" id="menu">
                <button class="c-hamburger c-hamburger--htx" id="button-menu" onclick="menu()">
                    <span>toggle menu</span>
                </button>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12" id="menu-mobile">
        <div class="sub-menu">
          <div class="user">
              <span>Usuario: <?php 
                  echo $loger;
               ?></span>
          </div> 
          <?php if( $nivel == 0) { ?>
            <ul>
              <li><a href="mapa.php">Home</a></li>
              <li><a href="updateUser.php">Perfil</a></li>
              <li><a href="destroy.php">Sair</a></li>
            </ul>
          <?php } ?>
         
          <?php if( $nivel == 1) { ?>
            <ul>
              <li><a href="updateUser.php">Perfil</a></li>
              <li><a href="cadastros.php">Administrativo</a></li>
              <li><a href="destroy.php">Sair</a></li>
            </ul>
          <?php } ?>


        </div>
    </div>

    
    <!-- MAPA -->
     <div id="mapholder"></div>
           <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDj1LGk73-nj7xk3k3xNI314t3vPga90iQ&signed_in=true&callback=geoLocation"
        async defer>
    </script>
    

    <div class="col-xs-12" id="pedido">
        <div class="row">
            <div class="item_bus">
					<?php foreach ($getPoint as $reg){ ?>
  						<img class="bus" src="../img/bus.png"></img><br>
  						<span class="name_bus"><?php echo $reg->desc_linha; $dadosLine = $Line->viewInfoLine($reg->desc_linha);?></span>
  						<span class="linha_bus"><?php echo $reg->num_linha; ?></span><br><br>

              <form action="insertRequest.php" method="post">
                  <input id="signupEmailagain" type="hidden"  name="id_usuario" class="form-control" value="<?php 
                       echo $id_usuario; ?>"></input>
                      
                  <input id="signupEmailagain" type="hidden"  name="id_linha" class="form-control" value="<?php 
                      foreach ($dadosLine as $reg){ echo $reg->id_linha;  } ?>"></input>              

        					<input type="submit"  class="btn btn-primary btn-lg pedi_bus" value="Solicitar ponto"
                  <?php //if($calcTime != 0){ echo "disabled"; }  ?>
                   ></input>

              </form>
				  <?php }  ?>
              <?php 
                if(empty($getPoint)){
                   echo "<p class='name_bus'> Não existe nenhuma linha disponivel no momento</p>";
              }  ?>
          
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


function menu(){
    if(document.getElementById("menu-mobile").style.display == "block"){
      document.getElementById("menu-mobile").style.display="none"; 
    }else{
      document.getElementById("menu-mobile").style.display="block";
    }
    
  };

</script>

</body>
</html>