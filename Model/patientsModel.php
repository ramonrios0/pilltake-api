<?php

class Patients{
    private $conn;

    public function __construct($db){
        $this -> conn = $db;
    }

    public function fetch($id){
        $stmt = null;
            $query = "SELECT id, nombre, encargado, receta FROM pacientes WHERE id=?";
            $stmt = $this -> conn -> prepare($query);
            $stmt -> execute([$id]);
            return $stmt;
    }

    public function fetchMedic($patientID, $medicID){
        $stmt = null;
        $query = "SELECT id, nombre, encargado FROM pacientes WHERE id=? AND medico=?";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$patientID, $medicID]);
        return $stmt;
    }

    public function fetchAllMedic($medicID){
        $stmt = null;
        $query = "SELECT pacientes.id, pacientes.nombre, encargados.nombre AS encargado, encargados.contacto FROM pacientes INNER JOIN encargados ON pacientes.encargado = encargados.id WHERE pacientes.medico=?";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$medicID]);
        return $stmt;
    }

    public function fetchAllManager($managerID){
        $stmt = null;
        $query = "SELECT pacientes.id, pacientes.nombre, recetas.inicio, recetas.fin  FROM pacientes INNER JOIN recetas ON pacientes.id = recetas.paciente WHERE pacientes.encargado=?";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$managerID]);
        return $stmt;
    }

    public function insert($name, $medicID, $managerID){
        $stmt = null;
        $query = "INSERT INTO pacientes (nombre, medico, encargado) VALUES (?,?,?)";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$name, $medicID, $managerID]);
        return $stmt;
    }

    public function delete($id){
        $stmt = null;
        $query = "DELETE FROM pacientes WHERE id = ?";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$id]);
        return $stmt;
    }
}

?>