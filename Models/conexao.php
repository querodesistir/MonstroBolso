<?php 
//por no autoload
class conexao { 
    
    public function __construct(){ 
        $pdo = new PDO("mysql:host=localhost;bdname=Pou, 'root', '', charset= utf8mb4");

        try{
            $pdo ->setAttribute(PDO::ATTR_ERRMODE, PDO ::ERRMODE_EXCEPTION);
            echo "@Conexao_Presente!";
        } catch (PDOException $e) {
    error_log('Erro na conexão com o banco: ' . $e->getMessage());
    throw $e;
}
        
        return $pdo;
    }

}

?>