<?php

class Medicines{

    private $conn;

    public function __construct($db){
        $this -> conn = $db;
    }

    public function fetch($id){
        $stmt = null;
        $query = "SELECT id, nombre FROM medicinas WHERE id=?";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$id]);
        return $stmt;    
    }

    public function fetchAll(){
        $stmt = null;
        $query = "SELECT id, nombre FROM medicinas";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute();
        return $stmt;    
    }

    public function insert($name){
        $stmt = null;
        $query = "INSERT INTO medicinas(nombre) VALUES (?)";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$name]);
        return $stmt;    
    }
}

?>