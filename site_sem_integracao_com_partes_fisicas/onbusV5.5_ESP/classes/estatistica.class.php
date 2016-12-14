<?php 
header("Content-type: text/html; charset=utf-8");
/*  
 * Criando classe estatistica
 */
class estatistica{  
 
	  /*  
	   * Atributo para conexão com o banco de dados   
	   */  
	  private $pdo = null;  
	 
	  /*  
	   * Atributo estático para instância da própria classe    
	   */  
	  private static $estatistica = null; 
	 

	  private function __construct($conexao){  
		$this->pdo = $conexao;  
	  }  
	  
	  /*
	  * Método estático para retornar um objeto estatistica    
	  * Verifica se já existe uma instância desse objeto 
	  */   
	  public static function getInstance($conexao){   
	   if (!isset(self::$estatistica)){  
		  self::$estatistica = new estatistica($conexao);   
	   }
	   return self::$estatistica;    
	  } 
	  

	   public function searchLinha($desc_linha){
		   try{   
			  $sql = "SELECT DISTINCT id_linha, desc_linha, num_linha FROM onibus NATURAL JOIN onibus_linha NATURAL JOIN linha NATURAL JOIN estado_onibus WHERE desc_linha LIKE '%$desc_linha%'";   
			  $stm = $this->pdo->prepare($sql);    
			  $stm->execute();   
			  $dados = $stm->fetchAll(PDO::FETCH_OBJ);   
			  return $dados;   
			 }catch(PDOException $erro){   
			  echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			 }   
	  }


	  
	 public function estatisticaLinha(){
		   try{   
			  $sql = "SELECT DISTINCT id_linha, desc_linha, num_linha FROM onibus NATURAL JOIN onibus_linha NATURAL JOIN linha NATURAL JOIN estado_onibus;";   
			  $stm = $this->pdo->prepare($sql);   
			  $stm->execute();   
			  $dados = $stm->fetchAll(PDO::FETCH_OBJ);   
			  return $dados;   
			 }catch(PDOException $erro){   
			  echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			 }   
	  }

	  public function estatisticaLotacao($desc_linha){
		   try{   
			  $sql = "SELECT avg(lotacao_act) AS hora FROM onibus NATURAL JOIN onibus_linha NATURAL JOIN linha NATURAL JOIN estado_onibus WHERE desc_linha=?; ";   
			  $stm = $this->pdo->prepare($sql);  
			  $stm->bindValue(1, $desc_linha);  
			  $stm->execute();   
			  $dados = $stm->fetchAll(PDO::FETCH_OBJ);   
			  return $dados;   
			 }catch(PDOException $erro){   
			  echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			 }   
	  }

	  public function estatisticaSolicitacao($desc_linha){
		   try{   
			  $sql = "SELECT count(*) as num FROM peticao WHERE id_linha=? AND UNIX_TIMESTAMP(now() ) - UNIX_TIMESTAMP(hora_pedido) < 86400; ";   
			  $stm = $this->pdo->prepare($sql);  
			  $stm->bindValue(1, $desc_linha);  
			  $stm->execute();   
			  $dados = $stm->fetchAll(PDO::FETCH_OBJ);   
			  return $dados;   
			 }catch(PDOException $erro){   
			  echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			 }   
	  }

	    




}
	  

	  
	  
  
?>