<?php
	/*  
    * Cabeçalho e requerimento dos scripts necessarios  
    */
    require_once "header.php"; 

	
    $Point = crudPoint::getInstance(Conexao::getInstance());
	
	$latitude = $_POST["latitude"];
    $longitude = $_POST["longitude"];
    $descricao_ponto = $_POST["descricao_ponto"];

	$dados = $Point->insertPoint($latitude, $longitude, $descricao_ponto);

?>