<?php
/*
 Ponto   = array(  Latitude ,  Longitude );   */
$mato 	 = array( -12.931191, -38.386704 );
$viaduto = array( -12.944772, -38.385004 );

$passoLat = ($viaduto[0]-$mato[0])/5;
$passoLong = ($viaduto[1]-$mato[1])/5;

echo "Passo: Lat: $passoLat; Long: $passoLong <br>";

for($i=1; $i <= 5; $i++) { 
	echo "Ponto $i: ". ($mato[0]+$passoLat*$i). ", ". ($mato[1]+$passoLong*$i)."<br>";
}


include_once "../onbus V4/classes/calculaDistancia.php";

$distVert = calcDistancia( $mato[0], $mato[1], $viaduto[0], $mato[1] );
$distHor = calcDistancia( $viaduto[0], $viaduto[1], $viaduto[0], $mato[1] );

echo "Velocidade: Vert: {$distVert}; Hor: {$distHor} <br>";

print(calcDistancia( $viaduto[0], $viaduto[1], $mato[0] , $mato[1] )); print "<br>";
print(calcDistancia1( deg2rad( $viaduto[0] ), deg2rad( $viaduto[1] ), deg2rad( $mato[0] ), deg2rad( $mato[1] ) )); print "<br>";

echo "Velocidade: ". (calcDistancia1( deg2rad( $viaduto[0] ), deg2rad( $viaduto[1] ), deg2rad( $mato[0] ), deg2rad( $mato[1] ) )/50.0) . "<br>";

echo "VelocidadeV: ". (calcDistancia1( deg2rad( $mato[0] ), deg2rad( $mato[1] ), deg2rad( $viaduto[0] ), deg2rad( $mato[1] ) )/50.0) . "<br>";
echo "VelocidadeH: ". (calcDistancia1( deg2rad( $viaduto[0] ), deg2rad( $viaduto[1] ), deg2rad( $viaduto[0] ), deg2rad( $mato[1] ) )/50.0) . "<br>";

echo "Velocidade angular Lat: " . (abs($mato[0]-$viaduto[0])/50.0) . "<br>";
echo "Velocidade angular Lon: " . (abs($mato[1]-$viaduto[1])/50.0) . "<br>";

?>