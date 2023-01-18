<?php

/**
 * Esta clase se encarga de establecer la conexión con la base de datos
 * MySQL mediante PDO
 */
    class Config {

        private const DBHOST = "";
        private const DBUSER = "";
        private const DBPASS = "";
        private const DBNAME = "";
        private $dsn ='mysql:host=' . self::DBHOST . ';dbname=' .self::DBNAME . '';
        protected $connection = null;

        public function open()
        {
            try{
                $this -> connection = new  PDO($this-> dsn, self::DBUSER, self::DBPASS);
                $this -> connection -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $this -> connection -> exec('set names utf8');
            }catch(PDOException $e){
                echo "Conexión fallida: ".$e->getMessage();
            }
            return $this -> connection;
        }
    }

?>