<?php


class crudLine {
	

	  /*  
	   * Atributo para conexão com o banco de dados   
	   */  
	  private $pdo = null;  
	 
	  /*  
	   * Atributo estático para instância da própria classe    
	   */  
	  private static $crudLine = null; 
	 

	  private function __construct($conexao){  
		$this->pdo = $conexao;  
	  }  
	  
	  /*
	  * Método estático para retornar um objeto crudLine    
	  * Verifica se já existe uma instância desse objeto 
	  */   
	  public static function getInstance($conexao){   
	   if (!isset(self::$crudLine)){  
		  self::$crudLine = new crudLine($conexao);   
	   }
	   return self::$crudLine;    
	  } 


	  public function insertLine($num_linha, $variacao_linha, $desc_linha){
		if (!empty($num_linha) && !empty($variacao_linha) && !empty($desc_linha)){
			try{   
			   $sql = "INSERT INTO linha (num_linha, variacao_linha, desc_linha) VALUES (?, ?, ?)";   
			   $stm = $this->pdo->prepare($sql);   
			   $stm->bindValue(1, $num_linha);   
			   $stm->bindValue(2, $variacao_linha);   
			   $stm->bindValue(3, $desc_linha);     
			   $stm->execute();   
			   echo "<script>alert('Registro inserido com sucesso')</script>";   
			   echo "<script>window.location.href ='../php/mapa.php';</script>";
			  } catch(PDOException $erro){   
			   echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			}   
		   } 
	  }


	  public function viewLine(){
		 try{   
			  $sql = "SELECT * FROM linha";   
			  $stm = $this->pdo->prepare($sql);   
			  $stm->execute();   
			  $dados = $stm->fetchAll(PDO::FETCH_OBJ);   
			  return $dados;   
			 }catch(PDOException $erro){   
			  echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			 }   
	  }


	  public function viewInfoLine($desc_linha){
		   try{   
			  $sql = "SELECT * FROM linha WHERE desc_linha=?";   
			  $stm = $this->pdo->prepare($sql);   
			  $stm->bindValue(1, $desc_linha); 
			  $stm->execute();   
			  $dados = $stm->fetchAll(PDO::FETCH_OBJ);   
			  return $dados;   
			 }catch(PDOException $erro){   
			  echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			 }   
	  }


	  public function insertItinerario($id_linha, $id_ponto){
		  	if (!empty($id_linha) && !empty($id_ponto)){
				try{   
				   $sql = "INSERT itinerario (id_linha, id_ponto) VALUES (?, ?)";
				   $stm = $this->pdo->prepare($sql);   
				   $stm->bindValue(1, $id_linha);   
				   $stm->bindValue(2, $id_ponto);         
				   $stm->execute();   
				   echo "<script>alert('Registro inserido com sucesso')</script>";   
				   echo "<script>window.location.href ='../php/mapa.php';</script>";
				  } catch(PDOException $erro){   
				   echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
				}   
		 	} 
	  }



}


?>