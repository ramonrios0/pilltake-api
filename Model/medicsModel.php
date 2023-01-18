<?php

class Medics{

    private $conn;

    public function  __construct($db){
        $this -> conn = $db;
    }

    public function fetch($id){
        $stmt = null;
        $query = "SELECT users.id, users.nombre, users.email, medicos.especialidad, medicos.contacto FROM users INNER JOIN medicos ON users.id = ? AND users.id = medicos.idmedico;";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$id]);
        return $stmt;
    }

    public function fetchAll(){
        $stmt = null;
        $query = "SELECT users.id, users.nombre, users.email, medicos.especialidad, medicos.contacto FROM users INNER JOIN medicos ON users.id = medicos.idmedico;";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute();
        return $stmt;        
    }

    public function insert($name, $pass, $email, $specialty, $contact, $token){
        $stmt = null;
        $query = "INSERT INTO users(nombre, email, pass, tipo, token) VALUES (?,?,?,?,?)";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$name, $email, $pass, 2, $token]);

        $stmt2 = null;
        $query2 = "SELECT id FROM users WHERE nombre = ?";
        $stmt2 = $this -> conn -> prepare($query2);
        $stmt2 -> execute([$name]);

        $helper = 0;

        while($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $helper = $id;
        }

        $stmt3 = null;
        $query3 = "INSERT INTO medicos (especialidad, contacto, idmedico) VALUES (?,?,?)";
        $stmt3 = $this -> conn -> prepare($query3);
        $stmt3 -> execute([$specialty, $contact, $helper]);

        return $stmt2;
    }

    public function update($id, $name, $email, $specialty, $contact){
        $stmt = null;
        $query = "UPDATE users SET nombre=?, email=? WHERE id=?";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$name, $email, $id]);

        $stmt2 = null;
        $query2 = "UPDATE medicos SET especialidad=?, contacto=? WHERE idmedico=?";
        $stmt2 = $this -> conn -> prepare($query2);
        $stmt2 -> execute([$specialty, $contact, $id]);

        return $stmt2;
    }

    public function delete($id){
        $stmt = null;
        $query = "DELETE FROM users WHERE id=?";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$id]);

        $stmt2 = null;
        $query2 = "DELETE FROM medicos WHERE idmedico=?";
        $stmt2 = $this -> conn -> prepare($query2);
        $stmt2 -> execute([$id]);

        return $stmt2;
    }
}

?>