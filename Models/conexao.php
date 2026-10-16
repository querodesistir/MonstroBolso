<?php 
class conexao { 
   public $pdo;
    
    public function __construct(){ 
       $pdo = new PDO(
    "mysql:host=localhost;dbname=Pou;charset=utf8mb4","root","");

        try{
            $pdo ->setAttribute(PDO::ATTR_ERRMODE, PDO ::ERRMODE_EXCEPTION);
            echo "@Conexao_Presente!";
        } catch (PDOException $e) {
    error_log('Erro na conexão com o banco: ' . $e->getMessage());
    throw $e;
}
        $this->pdo = $pdo;

    }


}

?>