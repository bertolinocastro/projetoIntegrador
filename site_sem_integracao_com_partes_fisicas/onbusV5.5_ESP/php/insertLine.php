<?php
	/*  
    * Cabeçalho e requerimento dos scripts necessarios  
    */
    require_once "header.php"; 

	
    $Line = crudLine::getInstance(Conexao::getInstance());
	
	$num_linha = $_POST["num_linha"];
    $desc_linha = $_POST["desc_linha"];
    $variacao_linha = $_POST["variacao_linha"];

	$dados = $Line->insertLine($num_linha, $variacao_linha, $desc_linha);
	
?>