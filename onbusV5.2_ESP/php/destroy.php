<?php
	/*  
    * Cabeçalho e requerimento dos scripts necessarios  
    */
    require_once "header.php"; 

	
    $User = acessUser::getInstance(Conexao::getInstance());


	$dados = $User->deslogerUser();
	header("Location: ../index.php");

?>