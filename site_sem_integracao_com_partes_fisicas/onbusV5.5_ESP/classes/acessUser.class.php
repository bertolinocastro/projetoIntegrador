<?php 
/*  
 * Criando classe acessUser
 */
class acessUser{  
 
	  /*  
	   * Atributo para conexão com o banco de dados   
	   */  
	  private $pdo = null;  
	 
	  /*  
	   * Atributo estático para instância da própria classe    
	   */  
	  private static $acessUser = null; 
	 

	  private function __construct($conexao){  
		$this->pdo = $conexao;  
	  }  
	  
	  /*
	  * Método estático para retornar um objeto acessUser    
	  * Verifica se já existe uma instância desse objeto 
	  */   
	  public static function getInstance($conexao){   
	   if (!isset(self::$acessUser)){  
		  self::$acessUser = new acessUser($conexao);   
	   }
	   return self::$acessUser;    
	  } 


		public function getUserLogin($login,$senha){
			  try{
				$sql = "SELECT * FROM usuario WHERE login=? AND senha=? OR email=? AND senha=? OR cpf=? AND senha=?";
				$stm = $this->pdo->prepare($sql);
				$stm->bindValue(1,$login);
				$stm->bindValue(2,$senha);
				$stm->bindValue(3,$login);
				$stm->bindValue(4,$senha);
				$stm->bindValue(5,$login);
				$stm->bindValue(6,$senha);
				$stm->execute();
				$dados = $stm->fetchAll(PDO::FETCH_OBJ);  

				if(!empty($dados)){
				  foreach ($dados as $reg){
				  		$nivel = $reg->nivel;
				  		$login = $reg->login;
				  		$id_usuario = $reg->id_usuario;
				  }
				  session_start();
				  $_SESSION['login'] = $login;
				  $_SESSION['senha'] = $senha;
				  $_SESSION['nivel'] = $nivel;
				  $_SESSION['id_usuario'] = $id_usuario;
				  echo "<script>window.location.href='mapa.php'; </script>";
				  
				}else{
					echo "<script>alert('Usuario Não logado');window.location.href ='../php/cadastroUser.php';</script>";
				}
				
			  }catch(PDOException $erro){
				  echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			  }
		  }
		  
		public function deslogerUser(){
			session_start();
			session_unset();
			session_destroy();
			header("Location: ../index.php");
		}
		
		public function session(){
			  session_start(); 
			  if((!isset ($_SESSION['login']) == true) or (!isset ($_SESSION['senha']) == true)) { 
				 unset($_SESSION['login']); 
				 unset($_SESSION['senha']); 
				 header('location: ../index.php'); 
			  } 
		}
	  
	  	public function requestBus($id_ponto, $id_user, $id_linha){
	  		 if (!empty($id_ponto)  && !empty($id_user) && !empty($id_linha)){
			      try{   
			         $sql = "INSERT INTO peticao (hora_pedido,id_ponto,id_usuario,id_linha) VALUES (now(), ?, ?, ?)";
			         $stm = $this->pdo->prepare($sql);       
			         $stm->bindValue(1, $id_ponto);     
			         $stm->bindValue(2, $id_user);   
			         $stm->bindValue(3, $id_linha);  
			         $stm->execute();   
			         echo "<script>alert('Solicitação realizada com sucesso'); window.location.href='./mapa.php';</script>";   
			        } catch(PDOException $erro){   
			         echo "<script>alert('Erro na linha: {$erro->getLine()}; '); window.location.href='mapa.php';</script>";   
			      }   
			     } 

	  	}

	  	public function getUser($login, $senha){
	  		try{
		  		$sql = "SELECT * FROM usuario WHERE login=? AND senha=? OR email=? AND senha=? OR cpf=? AND senha=?";
				$stm = $this->pdo->prepare($sql);
				$stm->bindValue(1,$login);
				$stm->bindValue(2,$senha);
				$stm->bindValue(3,$login);
				$stm->bindValue(4,$senha);
				$stm->bindValue(5,$login);
				$stm->bindValue(6,$senha);
				$stm->execute();
				$dados = $stm->fetchAll(PDO::FETCH_OBJ);  
		  	}catch(PDOException $erro){
					  echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			}
		}

		// Metodo que verifica se o usuário fez uma petição nos ultimos 5 minutos

		public function verificaPush($id_usuario){
			try{
				$sql = "SELECT * FROM peticao where id_usuario=? and unix_timestamp( now() ) - unix_timestamp( hora_pedido ) < 60 * 5";
				$stm = $this->pdo->prepare($sql);
				$stm->bindValue(1, $id_usuario);
				$stm->execute();
				$dados = $stm->fetchAll(PDO::FETCH_OBJ);
				return $dados;
			}catch(PDOException $erro){
					  echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
			}
		}

		// Verificando se o usuário clicou menos de 5 minutos atrás

	    public function calcTime(){
	        $AcessUser = acessUser::getInstance(Conexao::getInstance());
	        $id_usuario = $_SESSION['id_usuario'];
	        $push = $AcessUser->verificaPush($id_usuario);
	        $push2 = count($push);
	        if($push2 > 0) {
	            return 1;
	        }
	        return 0;
	    } 
}