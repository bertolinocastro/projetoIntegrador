<?php
	/*  
    * Cabeçalho e requerimento dos scripts necessarios  
    */
    //header("Content-type: text/html; charset=utf-8");
    require_once "header.php"; 

	
    $User = crudBus::getInstance(Conexao::getInstance());
	
	$placa = $_POST["placa"];
    $lotacao_max = $_POST["lotacao_max"];
    $num_onibus = $_POST["num_onibus"];

	$dados = $User->insertBus($placa, $lotacao_max, $num_onibus);
?>