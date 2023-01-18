<?php 

class Recipes{
    private $conn;

    public function __construct($db){
        $this -> conn = $db;
    }

    public function fetch($patientID){
        $stmt = null;
        $query = "SELECT paciente, medicina1, medicina2, medicina3, medicina4, tiempo1, tiempo2, tiempo3, tiempo4, cantidad1, cantidad2, cantidad3, cantidad4, inicio, fin FROM recetas WHERE paciente = ?";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$patientID]);
        return $stmt;        
    }

    public function insert($patientID, $medicine1, $medicine2, $medicine3, $medicine4, $time1, $time2, $time3, $time4, $quant1, $quant2, $quant3, $quant4, $start, $end){
        $stmt = null;
        $query= "INSERT INTO recetas(paciente, medicina1, medicina2, medicina3, medicina4, tiempo1, tiempo2, tiempo3, tiempo4, cantidad1, cantidad2, cantidad3, cantidad4, inicio, fin) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt= $this -> conn -> prepare($query);
        $stmt -> execute([
            $patientID, 
            $medicine1, $medicine2, $medicine3, $medicine4,
            $time1, $time2, $time3, $time4,
            $quant1, $quant2, $quant3, $quant4,
            $start, $end
        ]);
        return $stmt;
    }

    public function delete($id){
        $stmt = null;
        $query = "DELETE FROM recetas WHERE id = ?";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$id]);
        return $stmt;
    }

}

?>