<?php 

   /*  
    * Cabeçalho e requerimento dos scripts necessarios  
    */   
   require_once "header.php"; 
   	

   /*  
    * Atribui uma instância da classe crudUser   
    * e passa uma conexão como parâmetro  
    */   
   $User = crudUser::getInstance(Conexao::getInstance());
     
   $senha = $_POST["senha"];
   $pne = $_POST["especial"];
   $id_usuario = $_POST["id_usuario"];


   $dados = $User->update($senha,$pne,$id_usuario);


   ?>
