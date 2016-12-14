<?php
	session_start();
	$latitude = $_POST['latit'];
	$longitude = $_POST['longit'];
	$_SESSION['latit'] = $latitude; 
	$_SESSION['longit'] = $longitude; 


	//echo "<script>alert('BIRL');</script>";
	echo "<script>window.location.href ='../php/mapa.php';</script>";

?>