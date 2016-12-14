<?php



if( !empty( $_GET ) && isset( $_GET['id'], $_GET['lai'], $_GET['loi'], $_GET['laf'], $_GET['lof'], $_GET['qtd'] ) ){
	
	include_once "../classes/conexao.php";
	include_once "../classes/crudBus.class.php";
	include_once "../classes/calculaDistancia.php";

	$id   = $_GET['id'];  // .................... Id do ônibus
	$lati  = $_GET['lai']; // .................... Latitude do inicio do trajeto
	$longi = $_GET['loi']; // .................... Longitude do inicio do trajeto
	$latf  = $_GET['laf']; // .................... Latitude do fim do trajeto
	$longf = $_GET['lof']; // .................... Longitude do fim do trajeto
	$qtd  = $_GET['qtd']; // .................... Quantidade de pontos no trajeto

	session_id( $id );
	//session_name( $id );
	session_start();

	$_SESSION['qtd'] = $qtd;
	
	/*
	 *	Para o id do onibus, definimos seu itinerario.
	 *	Com base nas posicoes iniciais e finais definidas na NodeMCU,
	 *	o trajeto sera uma linha reta do inicio ao fim com a quantidade
	 *	de pontos definida no codigo tambem.
	 *	Essas informacoes serao atualizadas no banco de dados.
	 *
	 */
	
	$bd = crudBus::getInstance( Conexao::getInstance() ); // Cria instância de acesso ao BD.
	
	$query = "SELECT id_linha FROM onibus NATURAL JOIN onibus_linha NATURAL JOIN linha WHERE id_onibus = {$id}";
	$linha = $bd->query( $query )[0]->id_linha;
	
	$query = "SELECT id_ponto FROM itinerario NATURAL JOIN linha WHERE id_linha = {$linha}";
	$pontosTrajeto = $bd->query( $query );

	$query = "DELETE FROM ponto WHERE id_ponto IN (" .  implode( ',', array_map( function( $o ){return $o->id_ponto;}, $pontosTrajeto ) ) . ")";
	if( $pontosTrajeto ) $bd->delete( $query );

	$distAngLat = ( $latf - $lati ) / $qtd;   // Calcula distância angular latitudinal
	$distAngLon = ( $longf - $longi ) / $qtd; // Calcula distância angular longitudinal

	$query = "INSERT INTO ponto ( latitude_ponto, longitude_ponto, descricao_ponto ) VALUES (?,?,?)";
	$i = 0;
	while( $i < $qtd ){
		if( $bd->insert( $query, array( $lati + $distAngLat*$i, $longi + $distAngLon*$i, "Ponto simulado no. {$i} para o ônibus {$id}" ) ))
			if($idPonto = $bd->query( "SELECT id_ponto FROM ponto ORDER BY id_ponto DESC LIMIT 1" )[0]->id_ponto)// Obtém id do ponto mais recente
				$bd->insert( "INSERT INTO itinerario ( id_linha, id_ponto ) VALUES (?,?)", array( $linha, $idPonto ) );
		$i++;
	}

}



?>
