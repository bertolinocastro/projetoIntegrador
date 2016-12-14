    <?php
	/*  
    * Cabeçalho e requerimento dos scripts necessarios  
    */
    require_once "header.php"; 

	
    $Lime = crudLine::getInstance(Conexao::getInstance());
    
	
	$id_linha = $_POST["linha"];
    $id_ponto = $_POST["ponto"];


    $infoBusLine = $Lime->insertItinerario($id_linha, $id_ponto);
    
    
?>