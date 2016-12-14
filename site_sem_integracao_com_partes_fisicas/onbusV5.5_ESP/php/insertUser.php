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

   $login = $_POST["login"];
   $senha = $_POST["senha"];
   $email = $_POST["email"];
   $cpf = $_POST["cpf"];
   $nascimento = $_POST["nascimento"];
   $pne = $_POST["especial"];

   $info = $User->getInUser($login, $cpf, $email);

   if( !$info ) $User->insert($login, $senha, $email, $cpf, $nascimento, $pne);
   else echo "<script>alert('Login, CPF ou EMAIL já existente:'); window.location.href='../index.php';</script>";
   

   ?>