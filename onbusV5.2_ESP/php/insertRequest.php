<?php
	require_once "header.php";    

	$Point = crudPoint::getInstance(Conexao::getInstance());
	$Acess = acessUser::getInstance(Conexao::getInstance());

	$id_user = $_POST["id_usuario"];
    $id_linha = $_POST["id_linha"];

    session_start();
    
	if(empty($_SESSION['id_ponto'])) {
		echo "<script>alert('Você está distante do ponto');</script>";
		echo "<script>window.location.href ='./mapa.php';</script>";
	} else {
		$dados = $Acess->requestBus($_SESSION['id_ponto'], $id_user, $id_linha);
		echo "<script>alert('BIRL');</script>";
		echo "<script>window.location.href ='./mapa.php';</script>";
	}

	











?>