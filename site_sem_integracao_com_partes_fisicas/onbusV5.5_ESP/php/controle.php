<?php
	/*  
    * Cabeçalho e requerimento dos scripts necessarios  
    */
    //header("Content-type: text/html; charset=utf-8");
    require_once "header.php"; 

	
    $User = acessUser::getInstance(Conexao::getInstance());

    $login = $_POST['login'];
    $senha = $_POST['senha'];
	
	$dados = $User->getUserLogin($login,$senha);
	
	header("Location: ./mapa.php?tipo=mapa");

?>

