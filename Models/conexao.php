<?php
class conexao {

    public $pdo;

    public function __construct() {
        try {
            $pdo = new PDO(
                "mysql:host=localhost;dbname=monstrobolso;charset=utf8mb4",
                "root",
                ""
            );

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->pdo = $pdo;

        } catch (PDOException $e) {
            error_log('Erro na conexão com o banco: ' . $e->getMessage());
            throw $e;
        }
    }
}