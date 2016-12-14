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
		  	if (!empty($latitude) && !empty($longitude) && !empty($descricao_ponto)){
				try{   
				   $sql = "INSERT ponto (latitude_ponto, longitude_ponto, descricao_ponto) VALUES (?, ?, ?)";
				   $stm = $this->pdo->prepare($sql);   
				   $stm->bindValue(1, $latitude);   
				   $stm->bindValue(2, $longitude); 
				   $stm->bindValue(3, $descricao_ponto);         
				   $stm->execute();   
				   echo "<script>alert('Registro inserido com sucesso')</script>";   
				   echo "<script>window.location.href ='../php/mapa.php';</script>";
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
			// Chama o método viewPoint() para pegar todos os pontos registrados no banco de dados
			$Info = $this->viewPoint();
			//var_dump( $Info );
			// Chamanando o outro metodo enviando as coordenadas do usuario e do ponto para fazer os calculos
			$calculo = $this->distancia($latitudeUser, $longitudeUser, $Info[0]->latitude_ponto, $Info[0]->longitude_ponto);
			$ponto = $Info[0]->id_ponto;
			foreach ($Info as $reg) {
				// Verficando se o ponto está a 30 metros de distância
				if($calculo > $this->distancia($latitudeUser, $longitudeUser, $reg->latitude_ponto, $reg->longitude_ponto) ){
					$ponto = $reg->id_ponto;
					$calculo = $this->distancia($latitudeUser, $longitudeUser, $reg->latitude_ponto, $reg->longitude_ponto);
				}
				//echo "ponto: "; var_dump($ponto); echo "calculo: "; var_dump( $calculo );
			}
			return $ponto;
		}

		// Metodo para Calcular distancia 
	  public function distancia( $lat1, $long1, $lat2, $long2 ){
	    $d2r = 0.017453292519943295769236;

	    $dlong = ($long2 - $long1) * $d2r;
	    $dlat = ($lat2 - $lat1) * $d2r;

	    $temp_sin = sin($dlat/2.0);
	    $temp_cos = cos($lat1 * $d2r);
	    $temp_sin2 = sin($dlong/2.0);

	    $a = ($temp_sin * $temp_sin) + ($temp_cos * $temp_cos) * ($temp_sin2 * $temp_sin2);
	    $c = 2.0 * atan2(sqrt($a), sqrt(1.0 - $a));

	    return 6368.1 * $c;
	}

} 


?>