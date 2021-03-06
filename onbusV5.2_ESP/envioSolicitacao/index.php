<?php

/*
 *
 *	Script de envio de dados para a esp.
 *	
 * 	Envio de solicitação de parada de ônibus.
 *
 */

/*
printar solicitação segundo protocolo.

verificar existência de solicitacao no banco de dados.

número da linha/onibus, localização(lat,long), lotação, velocidade, hora.

retorna: ponto solicitado (lat, long)

*/

if( !empty( $_GET ) && isset( $_GET['id'], $_GET['la'], $_GET['lo'], $_GET['lt'], $_GET['vl'], $_GET['ap'] ) ){
	
	include_once "../classes/conexao.php";
	include_once "../classes/crudBus.class.php";

	$id   = $_GET['id']; // .................... Id do ônibus
	$lat  = $_GET['la']; // .................... Latitude do ônibus
	$long = $_GET['lo']; // .................... Longitude do ônibus
	$lota = $_GET['lt']; // .................... Lotação do ônibus
	$velo = $_GET['vl']; // .................... Velocidade do ônibus
	$ap   = $_GET['ap']; // .................... Deve ou nao informar ponto

	/*	Pesquisar linha do ônibus pelo id e checar se existe solicitação para essa linha.
	 * 		Se existir, verificar o ponto solicitado juntamente com a posição do ônibus.
	 *			Para tanto, checar se o ônibus já passou do ponto em questão da trajetória.
	 *				Se não passou, retorna a solicitação com o id do ponto do itinerário.
	 *				Se passou, retorna "OK".
	 *		Se não existir, retorna "OK".
	 */
	
	$bd = crudBus::getInstance( Conexao::getInstance() ); // Cria instância de acesso ao BD.
	
	$onibLinha = $bd->getObj( "onibus_linha", "id_onibus_linha", $id );
	
	if( !$onibLinha ) { echo "ER1\n\r"; exit; } // Avisa falha no ID do ônibus
	$onibLinha = $onibLinha[0]->id_linha; // Recupera a linha do ônibus.

	$id_onib_lin = $bd->query( "SELECT id_onibus_linha FROM onibus_linha WHERE id_onibus={$id} AND id_linha={$onibLinha}" );
	$id_onib_lin = $id_onib_lin[0]->id_onibus_linha;

	$query = "SELECT DISTINCT id_ponto FROM ponto NATURAL JOIN itinerario WHERE id_linha = {$onibLinha}";
	$pontos = $bd->query( $query );

	/* Obtém todos os pontos que foram solicitados para a linha */
	$pontosSolicitados = $bd->query( "SELECT DISTINCT id_ponto FROM peticao WHERE id_linha = {$onibLinha} AND unix_timestamp( hora_pedido ) > unix_timestamp(NOW()) - 30*60");

	if( !$pontosSolicitados ) echo "NONE\n\r";
	else
		foreach( $pontosSolicitados as $indivPonto )
			echo "ID:" . str_pad( array_search( $indivPonto->id_ponto, array_map(function($o){return $o->id_ponto;}, $pontos ) ) + 1, 3, '0', STR_PAD_LEFT ) . "\n\r";

	/* Insere dados obtidos do ônibus diretamente na tabela estado_onibus. */
	$query = "INSERT INTO estado_onibus (id_onibus_linha, velocidade_act, latitude_act, longitude_act, lotacao_act, hora_inf_recebida) VALUES (?, ?, ?, ?, ?, NOW())";
	if( $bd->insert( $query, array( $id_onib_lin, $velo, $lat, $long, $lota ) ) ) echo "OK\n\r";
	else echo "ER2\n\r";
	
	if( session_status() == PHP_SESSION_NONE ){
		@session_id( $id );
		//session_name( $id );
		@session_start();
		$_SESSION['lo'] = $long;
		$_SESSION['la'] = $lat;
		$_SESSION['vl'] = $velo;
	}

	if( $ap ) include_once "../pontoLinhas/index.php";

}else{
	echo "EMP\n\r"; // Avisa método get vazio # para tentar reenviar
}


?>