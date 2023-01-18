<?php

class Intakes{

    private $conn;

    public function __construct($db)
    {
        $this -> conn = $db;
    }

    public function insert($patient, $medicine, $time, $quant, $start, $end){
        date_default_timezone_set("America/Mazatlan");
        $currentTime = date("Y-m-d H:i:s");
        $totalTime = $time;
        $stmt = null;
        while(($currentTime > $start)&&($currentTime < $end)){
            $currentTime = date("Y-m-d H:i:s", strtotime('+'.$totalTime.' hours'));
            $query = "INSERT INTO ingestas (medicina, cantidad, ingerido, paciente, tiempo, tiempoingerido) VALUES (?,?,?,?,?,?)";
            $stmt = $this -> conn -> prepare($query);
            $stmt -> execute([$medicine, $quant, 0, $patient, $currentTime, null]);
            $totalTime = $totalTime + $time;
        }

        return $stmt;
    }

    public function fetch($patient){
        $stmt = null;
        $query= "SELECT id, medicina, ingerido, paciente, tiempo, tiempoingerido FROM ingestas WHERE paciente = ? ORDER BY tiempo DESC";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$patient]);
        return $stmt;
    }

    public function fetchTaken($manager){
        $stmt = null;
        $query= "SELECT pacientes.nombre, pacientes.id as idPaciente, ingestas.medicina, ingestas.ingerido, ingestas.tiempo, ingestas.id as idIngesta FROM pacientes INNER JOIN ingestas ON pacientes.id = ingestas.paciente WHERE pacientes.encargado = ? AND ingestas.ingerido != 0 ORDER BY ingestas.tiempo DESC LIMIT 5";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$manager]);
        return $stmt;
    }

    public function fetchRemaining($manager){
        $stmt = null;
        $query= "SELECT pacientes.nombre, pacientes.id as idPaciente, ingestas.medicina, ingestas.ingerido, ingestas.tiempo, ingestas.id as idIngesta FROM pacientes INNER JOIN ingestas ON pacientes.id = ingestas.paciente WHERE pacientes.encargado = ? AND ingestas.ingerido = 0 ORDER BY ingestas.tiempo ASC LIMIT 5";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$manager]);
        return $stmt;
    }

    public function update($id, $taken, $time){
        $stmt = null;
        $query = "UPDATE ingestas SET ingerido = ?, tiempoingerido = ? WHERE id = ?";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute ([$taken, $time, $id]);
        return $stmt;
    }
}