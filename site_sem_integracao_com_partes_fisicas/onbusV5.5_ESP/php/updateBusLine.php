    <?php
	/*  
    * Cabeçalho e requerimento dos scripts necessarios  
    */
    require_once "header.php"; 

	
    $User = crudBus::getInstance(Conexao::getInstance());
    
	
	$id_onibus = $_POST["onibus"];
    $id_linha = $_POST["linha"];


    $infoBusLine = $User->updateBusInLine($id_onibus, $id_linha);
    
    

            
            
    
    
?>