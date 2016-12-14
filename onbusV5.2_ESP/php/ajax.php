<?php
	session_start();
	$latitude = $_POST['latit'];
	$longitude = $_POST['longit'];
	$_SESSION['latit'] = -12.938404;
	$_SESSION['longit'] = -38.386640; 

	//echo "<script>alert('BIRL');</script>";
	echo "<script>window.location.href ='../php/mapa.php';</script>";

?>