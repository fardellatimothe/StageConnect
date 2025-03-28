<?php

namespace App\Models;
use PDO;

class connexion {
    private $user = "root";
    private $password = 'root123';
    private $host = "db";
    private $dbname = "stageconnectbdd";
    private $pdo;

    public function __construct(){
        try {
            $this ->pdo = new PDO("mysql:host=". $this->host.";dbname=". this->dbname .";charset=utf8", $this->user, $this->password);
        } catch (PDOException $e) {
            die("erreur de connexion à la BDD :" .$e->getMessage());
        }
    }

    public function getpdo() {
        return $this->pdo;
    }

    //Méthodes CRUD
    public function select($table, $conditions = []) {
        $sql = "SELECT * FROM $table";
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", array_map(fn($key) => "$key = :$key", array_keys($conditions)));
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($conditions);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function update($table, $data, $condition) {
        $setPart = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $wherePart = implode(" AND ", array_map(fn($key) => "$key = :cond_$key", array_keys($conditions)));
        $sql = "UPDATE $table SET $setPart WHERE $wherePart";
        $stmt = $this->pdo->prepare($sql);
        foreach ($conditions as $key => $value) {
            $data["cond_" . $key] = $value;
        }
        return $stmt->execute($data);
    }

    public function delete($table, $conditions) {
        $wherePart = implode(" AND ", array_map(fn($key) => "$key = :$key", array_keys($conditions)));
        $sql = "DELETE FROM $table WHERE $wherePart";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($conditions);
    }

    public function create($table, $data) {
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }
}
?>