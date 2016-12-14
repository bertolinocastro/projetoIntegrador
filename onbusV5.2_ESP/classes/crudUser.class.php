<?php
header("Content-type: text/html; charset=utf-8"); 
/*  
 * Criando classe crudUser
 */

class crudUser{  
 
  /*  
   * Atributo para conexão com o banco de dados   
   */  
  private $pdo = null;  
 
  /*  
   * Atributo estático para instância da própria classe    
   */  
  private static $crudUser = null; 
 

  private function __construct($conexao){  
    $this->pdo = $conexao;  
  }  
  
  /*
  * Método estático para retornar um objeto crudUser    
  * Verifica se já existe uma instância desse objeto 
  */   
  public static function getInstance($conexao){   
   if (!isset(self::$crudUser)){  
      self::$crudUser = new crudUser($conexao);   
   }
   return self::$crudUser;    
  } 
 
  /*   
  * Metodo para inserção de novos registros     
  */   
  public function insert($login, $senha, $email, $cpf, $nascimento, $pne){   
   if (!empty($login) && !empty($senha) && !empty($email) && !empty($cpf) && !empty($nascimento) && isset($pne)){
    try{   
       $sql = "INSERT INTO usuario (login,senha,email,cpf,nascimento,pne) VALUES (?, ?, ?, ?, ?, ?)";   
       $stm = $this->pdo->prepare($sql);   
       $stm->bindValue(1, $login);   
       $stm->bindValue(2, $senha);   
       $stm->bindValue(3, $email);  
       $stm->bindValue(4, $cpf);   
       $stm->bindValue(5, $nascimento);   
       $stm->bindValue(6, $pne);    
       $stm->execute();   
       print "<script>alert('Registro inserido com sucesso')</script>";   
	   print "<script>window.location.href ='../php/mapa.php?tipo=mapa';</script>";
      } catch(PDOException $erro){   
       print "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
    }   
   }  
  } 
 
  /*   
  * Metodo para edição de registros    
  */   
  public function update($senha, $pne, $id_usuario){   
     if (!empty($senha)  && !empty($pne) && !empty($id_usuario)){ 
      try{   
         $sql = "UPDATE usuario SET senha=?, pne=? WHERE id_usuario=?";   
         $stm = $this->pdo->prepare($sql);     
         $stm->bindValue(1, $senha);     
         $stm->bindValue(2, $pne); 
         $stm->bindValue(3, $id_usuario);     
         $stm->execute();   
         $_SESSION['senha'] = $senha; 
         echo "<script>alert('Registro atualizado com sucesso'); window.location.href ='../php/mapa.php?tipo=mapa';</script>";   
        } catch(PDOException $erro){   
         echo "<script>alert('Erro na linha: {$erro->getLine()}'); window.location.href ='../php/mapa.php?tipo=mapa';</script>";   
      }   
     } 
  }
 
  /*   
  * Metodo para consulta no banco de dados
  */   
  public function getInfoUser($login, $senha){   
     try{   
      $sql = "SELECT * FROM usuario WHERE login=? and senha=?";   
      $stm = $this->pdo->prepare($sql);   
      $stm->bindValue(1,$login);
      $stm->bindValue(2,$senha);
      $stm->execute();   
      $dados = $stm->fetchAll(PDO::FETCH_OBJ);   
      return $dados;   
     }catch(PDOException $erro){   
      echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
     }   
  }

  public function getInUser($login, $cpf, $email){   
   try{   
    $sql = "SELECT * FROM usuario WHERE login=? or cpf=? or email=?";   
    $stm = $this->pdo->prepare($sql);  
    $stm->bindValue(1,$login);
    $stm->bindValue(2,$cpf); 
    $stm->bindValue(3,$email);
    $stm->execute();   
    $dados = $stm->fetchAll(PDO::FETCH_OBJ);   
    return $dados;   
   }catch(PDOException $erro){   
    echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
   }   
  }   
  
}  