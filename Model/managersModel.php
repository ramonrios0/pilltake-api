<?php

class Managers{

    private $conn;

    public function __construct($db)
    {
        $this -> conn = $db;   
    }

    public function fetch($id){
        $stmt = null;
        $query = "SELECT id, nombre, email, contacto, medico FROM encargados WHERE id=?";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$id]);
        return $stmt;
    }

    public function fetchAll(){
        $stmt = null;
        $query = "SELECT id, nombre, email, contacto FROM encargados";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute();
        return $stmt;
    }

    public function fetchRelated($id){
        $stmt = null;
        $query = "SELECT id, nombre, email, contacto FROM encargados WHERE medico=?";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$id]);
        return $stmt;
    }

    public function insert($name, $pass, $email, $contact, $medic, $token){
        $stmt = null;
        $query = "INSERT INTO encargados(nombre, pass, email, contacto, medico, token) VALUES (?,?,?,?,?,?)";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$name, $pass, $email, $contact, $medic, $token]);
        return $stmt;
    }

    public function update($id, $name, $email, $contacto){
        $stmt = null;
        $query = "UPDATE encargados SET nombre=?, email=?, contacto=? WHERE id=?";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$name, $email, $contacto, $id]);
        return $stmt;
    }

    public function delete($id){
        $stmt = null;
        $query = "DELETE FROM encargados WHERE id = ?";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$id]);
        return $stmt;
    }
}

?>