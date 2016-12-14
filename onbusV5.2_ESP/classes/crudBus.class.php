<?php 
header("Content-type: text/html; charset=utf-8");
/*  
 * Criando classe crudBus
 */
class crudBus{  
 
	  /*  
	   * Atributo para conexão com o banco de dados   
	   */  
	  private $pdo = null;  
	 
	  /*  
	   * Atributo estático para instância da própria classe    
	   */  
	  private static $crudBus = null; 
	 

	  private function __construct($conexao){  
		$this->pdo = $conexao;  
	  }  
	  
	  /*
	  * Método estático para retornar um objeto crudBus    
	  * Verifica se já existe uma instância desse objeto 
	  */   
	  public static function getInstance($conexao){   
	   if (!isset(self::$crudBus)){  
		  self::$crudBus = new crudBus($conexao);   
	   }
	   return self::$crudBus;    
	  } 
	  

	  
	  public function viewBus(){
		   try{   
			  $sql = "SELECT * FROM onibus";   
			  $stm = $this->pdo->prepare($sql);   
			  $stm->execute();   
			  $dados = $stm->fetchAll(PDO::FETCH_OBJ);   
			  return $dados;   
			 }catch(PDOException $erro){   
			  echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			 }   
	  }

	    
	  
	  public function insertBus($placa, $lotacao_max, $num_onibus){
		 if (!empty($placa) && !empty($lotacao_max) && !empty($num_onibus)){
			try{   
			   $sql = "INSERT INTO onibus (placa, lotacao_max, numero_onibus) VALUES (?, ?, ?)";   
			   $stm = $this->pdo->prepare($sql);   
			   $stm->bindValue(1, $placa);   
			   $stm->bindValue(2, $lotacao_max);   
			   $stm->bindValue(3, $num_onibus);     
			   $stm->execute();   
			   echo "<script>alert('Registro inserido com sucesso')</script>";   
			   echo "<script>window.location.href ='../php/mapa.php';</script>";
			  } catch(PDOException $erro){   
			   echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			}   
		   } 
	  }

	  
	  public function insertBusInLine($id_onibus, $id_linha){
		  if (!empty($id_onibus) && !empty($id_linha) ){
			try{   
			   $sql = "INSERT INTO onibus_linha (id_onibus, id_linha) VALUES (?, ?)";   
			   $stm = $this->pdo->prepare($sql);   
			   $stm->bindValue(1, $id_onibus);   
			   $stm->bindValue(2, $id_linha);      
			   $stm->execute();   
			   echo "<script>alert('Registro inserido com sucesso')</script>";   
			   echo "<script>window.location.href ='../php/mapa.php';</script>";
			  } catch(PDOException $erro){   
			   echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			}   
		  } 
	  }


	  public function updateBusInLine($id_onibus, $id_linha){
		  if (!empty($id_onibus) && !empty($id_linha)){
			try{   
			   $sql = "UPDATE onibus_linha SET id_onibus=?, id_linha=? WHERE id_onibus=?";   
			   $stm = $this->pdo->prepare($sql);   
			   $stm->bindValue(1, $id_onibus);   
			   $stm->bindValue(2, $id_linha);  
			   $stm->bindValue(3, $id_onibus);      
			   $stm->execute();   
			   echo "<script>alert('Registro atualizado com sucesso')</script>";   
			   echo "<script>window.location.href ='../php/mapa.php';</script>";
			  } catch(PDOException $erro){   
			   echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			}   
		  } 
	  }


	   public function viewBusInLine(){
		   try{   
			  $sql = "SELECT * FROM linha NATURAL JOIN onibus_linha NATURAL JOIN onibus";   
			  $stm = $this->pdo->prepare($sql);   
			  $stm->execute();   
			  $dados = $stm->fetchAll(PDO::FETCH_OBJ);   
			  return $dados;   
			 }catch(PDOException $erro){   
			  echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			 }   
	  }



	  public function busLine($id_onibus){
		   try{   
			  $sql = "SELECT * FROM onibus_linha WHERE  id_onibus=?";   
			  $stm = $this->pdo->prepare($sql);   
			  $stm->bindValue(1, $id_onibus); 
			  $stm->execute();   
			  $dados = $stm->fetchAll(PDO::FETCH_OBJ);   
			  return $dados;   
			 }catch(PDOException $erro){   
			  echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			 }   
	  }

	  public function getObj( $table, $primary_key, $clausule ){
		   try{   
			  $sql = "SELECT * FROM {$table} WHERE {$primary_key}=?";   
			  $stm = $this->pdo->prepare($sql);
			  $stm->bindValue(1, $clausule); 
			  $stm->execute();   
			  $dados = $stm->fetchAll(PDO::FETCH_OBJ);   
			  return $dados;   
			 }catch(PDOException $erro){   
			  echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			 }   
	  }

	  public function query( $query ){
		   try{   
			  $sql = $query;
			  $stm = $this->pdo->prepare($sql);
			  $stm->execute();
			  return $stm->fetchAll(PDO::FETCH_OBJ);
			 }catch(PDOException $erro){
			 	var_dump( $erro );
			 	echo $erro->xdebug_message;
			 	return false;
			 }
	  }

  	  public function insert( $query, $array = NULL ){
		   try{
			  $sql = $query;
			  $stm = $this->pdo->prepare($sql);
			  $i = 1;
				foreach( $array as $elem ) {
					$stm->bindValue($i++, $elem); 
				}
			  $stm->execute();
			  return true;
			 }catch(PDOException $erro){
			 	var_dump( $erro );
			 	echo $erro->xdebug_message;
			 	return false;
			 }
	  }

	  public function delete( $query ){
		   try{   
			  $sql = $query;
			  $stm = $this->pdo->prepare($sql);
			  $stm->execute();
			  return true;
			 }catch(PDOException $erro){
			 	var_dump( $erro );
			 	echo $erro->xdebug_message;
			 	return false;
			 }
	  }

}
	  

	  
	  
  
?>