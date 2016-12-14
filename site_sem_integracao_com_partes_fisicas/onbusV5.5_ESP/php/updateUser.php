<?php
	require_once "header.php"; 
	$AcessUser = acessUser::getInstance(Conexao::getInstance());
  $User = crudUser::getInstance(Conexao::getInstance());
  $logger = $AcessUser->session();
  $id_usuario = $_SESSION['id_usuario']; 
	$dados = $User->getInfoUser($id_usuario);
  $nivel = $_SESSION['nivel']; 
  $loger = $_SESSION['login']; 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="utf-8">
    <title>OnBus</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../img/favicon.ico" />
    <link rel="stylesheet" href="../css/update.css">
    <!-- BootStrap-->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script type="text/javascript" href="../js/default.js"></script>

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
                <span>Usuario:  <?php echo $loger;?></span>
            </div> 
          <?php if( $nivel == 0) { ?>
            <ul>
              <li><a href="mapa.php?tipo=mapa">Home</a></li>
              <li><a href="updateUser.php">Perfil</a></li>
              <li><a href="destroy.php">Sair</a></li>
            </ul>
          <?php } ?>
         
          <?php if( $nivel == 1) { ?>
            <ul>
              <li><a href="mapa.php?tipo=mapa">Home</a></li>
              <li><a href="cadastros.php">Administrativo</a></li>
              <li><a href="destroy.php">Sair</a></li>
            </ul>
          <?php } ?>
        </div>
    </div>
      <div class="container">
    <div class="row">
      <div class="panel panel-primary">
        <div class="panel-body">
          <form method="POST" action="update.php" role="form">
            <div class="form-group">
              <h2>Atualizar dados</h2>
            </div>
            <div class="form-group">
              <label class="control-label" for="signupEmail">Login</label>
              <input id="signupEmail" type="text" name="login" disabled maxlength="50" required="True"class="form-control" value="<?php 
              foreach ($dados as $reg){ echo $reg->login;  } ?>">
            </div>
            <div class="form-group">
              <label class="control-label" for="signupEmail">Senha</label>
              <input id="signupEmail" type="password" name="senha" maxlength="50" required="True"class="form-control" value="<?php 
              foreach ($dados as $reg){ echo $reg->senha;  } ?>">
            </div>
            <div class="form-group">
              <label class="control-label" for="signupEmail">Email</label>
              <input id="signupEmail" type="text" name="email" maxlength="50" disabled required="True"class="form-control" value="<?php 
              foreach ($dados as $reg){ echo $reg->email;  } ?>">
            </div>
            <div class="form-group">
              <label class="control-label" for="signupEmail">Nascimento</label>
              <input id="signupEmail" type="date" name="nascimento" maxlength="50" disabled required="True"class="form-control" value="<?php 
              foreach ($dados as $reg){ echo $reg->nascimento;  } ?>">
            </div>
            <div class="form-group">
              <input id="signupEmailagain" type="hidden"  name="id_usuario" class="form-control" value="<?php 
              foreach ($dados as $reg){ echo $reg->id_usuario;  } ?>">
            </div>
            <div class="form-group">
                    <label class="control-label" for="signupPassword">Especial</label><br>
                    <input type="radio" name="especial" value="1" /> Sim &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                    &nbsp<input type="radio" name="especial" value="0" CHECKED/> Não
            </div>
            <div class="form-group">
              <button id="signupSubmit" type="submit" class="btn btn-info btn-block">Atualizar dados</button>
            </div>
          </form>
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
window.onload = function(){
      getLocation();
    };

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