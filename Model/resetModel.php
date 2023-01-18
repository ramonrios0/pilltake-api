<?php

class ResetPassword{
    
    private $conn;
    
    public function __construct($db)
    {
        $this -> conn = $db;
    }

    public function resetMedic($token, $password){
        $stmt = null;
        $query = "UPDATE users SET pass=? WHERE BINARY token=?";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$password, $token]);
        return $stmt;
    }

    public function resetManager($token, $password){
        $stmt = null;
        $query = "UPDATE encargados SET pass=? WHERE BINARY token=?";
        $stmt = $this -> conn -> prepare($query);
        $stmt -> execute([$password, $token]);
        return $stmt;
    }
}

?>