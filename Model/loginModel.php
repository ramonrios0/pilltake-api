<?php

    class Login{
        private $conn;


        public $id;
        public $name;
        public $type;

        public function __construct($db)
        {
            $this -> conn = $db;
        }

        public function logUser($email, $password){
            $stmt = null;
            $query = "SELECT id, nombre, email, tipo FROM users WHERE BINARY email=? AND BINARY pass=?";
            $stmt = $this -> conn -> prepare($query);
            $stmt -> execute([$email, $password]);
            return $stmt;
        }

        public function logManager($email, $password){
            $stmt = null;
            $query = "SELECT id, nombre, email FROM encargados WHERE BINARY email=? AND BINARY pass=?";
            $stmt = $this -> conn -> prepare($query);
            $stmt -> execute([$email, $password]);
            return $stmt;
        }
        
        public function checkMail($mail){
            $stmt = null;
            $query = "SELECT token, email FROM users WHERE BINARY email=?";
            $stmt = $this -> conn -> prepare($query);
            $stmt -> execute([$mail]);
            return $stmt;
        }
        
        public function checkMailManager($mail){
            $stmt = null;
            $query = "SELECT token FROM encargados WHERE BINARY email=?";
            $stmt = $this -> conn -> prepare($query);
            $stmt -> execute([$mail]);
            return $stmt;
        }
    }

?>