<?php

class crudPoint{  
 
	  /*  
	   * Atributo para conexão com o banco de dados   
	   */  
	  private $pdo = null;  
	 
	  /*  
	   * Atributo estático para instância da própria classe    
	   */  
	  private static $crudPoint = null; 
	 

	  private function __construct($conexao){  
		$this->pdo = $conexao;  
	  }  
	  
	  /*
	  * Método estático para retornar um objeto crudPoint    
	  * Verifica se já existe uma instância desse objeto 
	  */   
	  public static function getInstance($conexao){   
	   if (!isset(self::$crudPoint)){  
		  self::$crudPoint = new crudPoint($conexao);   
	   }
	   return self::$crudPoint;    
	  } 


	   public function insertPoint($latitude, $longitude, $descricao_ponto){
	   	// Atribui uma instância da classe crudPoint e passa uma conexão como argumento
			$Point = crudPoint::getInstance(Conexao::getInstance());
			$dados = $Point->viewPoint();
		  	if (!empty($latitude) && !empty($longitude) && !empty($descricao_ponto)){
				try{  
					foreach ($dados as $key) {
			  			if($key->latitude_ponto == $latitude and $key->longitude_ponto == $longitude){
			  				echo "<script>alert('Este ponto já foi cadastrado')</script>";   
					   		echo "<script>window.location.href ='../php/updateLine.php';</script>";
						}
					} 
				   $sql = "INSERT ponto (latitude_ponto, longitude_ponto, descricao_ponto) VALUES (?, ?, ?)";
				   $stm = $this->pdo->prepare($sql);   
				   $stm->bindValue(1, $latitude);   
				   $stm->bindValue(2, $longitude); 
				   $stm->bindValue(3, $descricao_ponto);         
				   $stm->execute();   
				   echo "<script>alert('Registro inserido com sucesso')</script>";   
				   echo "<script>window.location.href ='../php/updateLine.php';</script>";
				  } catch(PDOException $erro){   
				   echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
				}   
		 	} 
	  	}

	  // Metodo para pegar as informações da linha que passa naquele determinado ponto que o usuário está

	  public function getPoint($id_ponto){
	  	 try{   
	  	 	  // Query que vai ser executada no banco de dados para pegar as infromações
			  $sql = "SELECT * FROM ponto NATURAL JOIN itinerario NATURAL JOIN linha WHERE id_ponto=?";   
			  $stm = $this->pdo->prepare($sql);
			  $stm->bindValue(1, $id_ponto);     
			  $stm->execute();   
			  // Armazenando o resultado da query 
			  $dados = $stm->fetchAll(PDO::FETCH_OBJ);   
			  return $dados;   
			 }catch(PDOException $erro){   
			  echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			 }   
	  }


	  public function viewPoint(){
		 try{   
			  $sql = "SELECT * FROM ponto";   
			  $stm = $this->pdo->prepare($sql);   
			  $stm->execute();   
			  $dados = $stm->fetchAll(PDO::FETCH_OBJ);   
			  return $dados;   
			 }catch(PDOException $erro){   
			  echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			 }   
	  }


	  public function pegarPonto($latitudeUser, $longitudeUser){
			// Atribui uma instância da classe crudPoint e passa uma conexão como argumento
			$Point = crudPoint::getInstance(Conexao::getInstance());
			// Chama o método viewPoint() para pegar todos os pontos registrados no banco de dados
			$Info = $Point->viewPoint();
			foreach ($Info as $reg) {
				// Chamanando o outro metodo enviando as coordenadas do usuario e do ponto para fazer os calculos
				$calculo = $Point->distancia($latitudeUser, $longitudeUser, $reg->latitude_ponto, $reg->longitude_ponto);
				// Verficando se o ponto está a 50 metros de distância
				if($calculo < 50){
					$ponto = $reg->id_ponto;
					return $ponto;
				}
			}
		}

		// Metodo para Calcular distancia 

		public function distancia($lat1, $lon1, $lat2, $lon2) {
			// Convertendo os números em graus para radiano
			$lat1 = deg2rad($lat1); 
			$lat2 = deg2rad($lat2);
			$lon1 = deg2rad($lon1);
			$lon2 = deg2rad($lon2);
			// Calculando a diferença de dos dois pontos
			$latD = $lat2 - $lat1;
			$lonD = $lon2 - $lon1;
			//Calculando a distancia em Km
			$dist = 2 * asin(sqrt(pow(sin($latD / 2), 2) +
			cos($lat1) * cos($lat2) * pow(sin($lonD / 2), 2)));
			$dist = $dist * 6371;
			// Formatando o resutltado e transformando em metros
			return number_format($dist, 2, '.', '') * 1000;
	  }

} 


?>