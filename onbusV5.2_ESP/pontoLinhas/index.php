<?php
/*
 *	Algoritmo de envio de tempo de chegada das linhas
 *	aos pontos de ônibus
 *
 *
 *	Algoritmo simula posição do ponto de ônibus baseado
 *	no ônibus. Isso feito apenas para demonstração.
 *	Real intensão seria o request ser feito de acordo
 *	com o número do ponto.
 *
 */

define( 'device', "/dev/ttyUSB1");

echo "PT\n\r"; // Informar que foi acessado o script

if( session_status() == PHP_SESSION_NONE ){
	session_id( $id );
	//session_name( $id );
	session_start();
}

$qtd = (isset($_SESSION['qtd'])) ? $_SESSION['qtd'] : 15;

inicio:;
// Setando Baud Rate da porta serial do arduino.
exec( "ssty -F ". device ." 9600 ", $vet );

$arduino = fopen( device, "w+b");
if( $arduino ){

	include_once "../classes/conexao.php";
	include_once "../classes/crudBus.class.php";
	include_once "../classes/calculaDistancia.php";

	sleep( 2 );
	$idPonto = fread( $arduino, 64 );
	fflush( $arduino );
	print $idPonto;
	if( !is_integer( $idPonto ) && $idPonto < 1 ){ die( "Falha ao ler id"); }

	$bd = crudBus::getInstance( Conexao::getInstance() );

	// PELO ID DO PONTO PRECISO PEGAR AS LINHAS ASSOCIADAS A ELE E GERAR UMA LISTAGEM DAS POSIÇÕES DAS LINHAS EM RELAÇÃO AO PONTO.
	// LOGO APÓS, ENVIAR AO PONTO A DEMORA DE CHEGADA DE CADA LINHA

	// Pega as linhas associadas ao ponto de ônibus pelo envio do estado ulteriores a trinta min atrás
	$query = "SELECT * FROM estado_onibus NATURAL JOIN onibus_linha NATURAL JOIN linha NATURAL JOIN itinerario NATURAL JOIN ponto WHERE id_onibus = {$id} AND unix_timestamp( hora_inf_recebida ) >= (unix_timestamp(NOW()) - 30*60) GROUP BY id_ponto, id_linha ORDER BY id_ponto ASC LIMIT {$qtd}";
	$linhasProx = $bd->query( $query );

	if( !sizeof( $linhasProx ) ) die("NONE\n\r"); // Sem previsoes recentes de onibus
	if( sizeof( $linhasProx ) < $idPonto ) $idDoPonto = $linhasProx[sizeof( $linhasProx )-1]->id_ponto;
	else $idDoPonto = $linhasProx[$idPonto-1]->id_ponto;

	$query = "SELECT DISTINCT id_linha, longitude_ponto, latitude_ponto FROM linha NATURAL JOIN itinerario NATURAL JOIN ponto WHERE id_ponto = {$idDoPonto}";
	$linhasDoPonto = $bd->query( $query );

	//var_dump( $linhasDoPonto);

	$query = "SELECT hora_inf_recebida, id_linha, latitude_act, longitude_act, velocidade_act FROM estado_onibus NATURAL JOIN onibus_linha WHERE  unix_timestamp( hora_inf_recebida ) >= (unix_timestamp(NOW()) - 30*60) AND (hora_inf_recebida, id_linha) IN (SELECT MAX(hora_inf_recebida), id_linha FROM estado_onibus NATURAL JOIN onibus_linha WHERE id_linha IN (" . implode( ',', array_map( function( $o ){return $o->id_linha;}, $linhasDoPonto ) ) . ") GROUP BY id_linha ORDER BY id_estado_onibus DESC)";
	$estados = $bd->query( $query );

	//var_dump($estados);

	$dado = null;
	/* Código caso arduino seja associado a posição da trajetória da linha */
	foreach( $estados as $pedaco ){
		$tempoCheg = calcDistancia( $_SESSION['la'],
									$_SESSION['lo'],
									$linhasDoPonto[$pedaco->id_linha-1]->latitude_ponto,
									$linhasDoPonto[$pedaco->id_linha-1]->longitude_ponto )
									/ $_SESSION['vl'];
		if( $tempoCheg > 994.0 ) $tempoCheg = 994;
		$dado .= "ID:" . str_pad( $pedaco->id_linha, 3, '0', STR_PAD_LEFT ) . "TP:" . str_pad( floor( $tempoCheg ) + 5, 3, '0', STR_PAD_LEFT ) ."\n\r";	
	}
	/* Código caso arduino seja associado ao ponto.
	foreach( $linhasProx as $indiv ){
		//print(calcDistancia( $indiv->latitude_act, $indiv->longitude_act, $indiv->latitude_ponto, $indiv->longitude_ponto ));
		$tempoCheg = calcDistancia( $indiv->latitude_act, $indiv->longitude_act, $indiv->latitude_ponto, $indiv->longitude_ponto ) / $indiv->velocidade_act;
		print($tempoCheg);
		$dado .= "ID:" . str_pad( $indiv->id_linha, 3, '0', STR_PAD_LEFT ) . "TP:" . floor( $tempoCheg )."\n\r";
	}*/

	$dado .= "OK\n\r";
	//echo "TAM:".strlen($dado);
	echo "SEND:".$dado;
	$qt = fwrite( $arduino, $dado, strlen( $dado ) );
	//echo "<br>Quantidade de charac escritos: ". $qt;
	sleep( 2 );
	$resp = fread( $arduino, strlen($dado) );
	print "RECE:".$resp;
	//echo 'sao iguais  '; print strcmp( $dado, $resp );

	fclose( $arduino );

}else{
	echo "Arduino bloqueado!<br>";
	goto inicio;
}
if (strcmp( $dado, $resp ) != 0){
	goto inicio;
}
?>
