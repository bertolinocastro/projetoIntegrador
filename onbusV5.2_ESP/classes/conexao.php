 <?php  
  // Constantes de parâmetros para configuração da conexão   

  define('HOST', 'localhost');   
  define('DBNAME', 'onbus');   
  define('CHARSET', 'utf8');   
  define('USER', 'root');   
  define('PASSWORD', '');   

  class Conexao {  
   // Atributo estático para instância do PDO   
   
   private static $pdo;  
   // Escondendo o construtor da classe   
     
   private function __construct() {   
    //   
   }  
   // Configurando uma conexão 
   public static function getInstance() {   
    if (!isset(self::$pdo)) {   
     try {   
      self::$pdo = new PDO("mysql:host=" . HOST . "; dbname=" . DBNAME . "; charset=" . CHARSET . ";", USER, PASSWORD);
      self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
     } catch (PDOException $e) {   
      print "Erro: " . $e->getMessage();   
     }   
    }   
    return self::$pdo;   
   }   
  }  