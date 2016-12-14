    <?php
	/*  
    * Cabeçalho e requerimento dos scripts necessarios  
    */
    require_once "header.php"; 
	
    $Bus = crudBus::getInstance(Conexao::getInstance());
	
	$id_onibus = $_POST["onibus"];
    $id_linha = $_POST["linha"];

    $infoBusLine = $Bus->busLine($id_onibus);
    if(empty($infoBusLine)){
             $dados = $Bus->insertBusInLine($id_onibus, $id_linha); 
    } else {
        echo "<script>alert('Ônibus já vinculado a linha'); window.location.href='./links.php';</script>";
    }
            
            
    
    
?>