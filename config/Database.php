<?php
    class Database{

        private $host = 'localhost';
        private $db_name = 'fac_service_tracker';
        private $user = 'gon';
        private $port = 5432;
        private $password = '#l03e1t3@_';

        public $conn;

        public function getConnection(){
            $this->conn = null;

            try{
                $this->conn = new PDO("pgsql:host=" . $this->host . ";port=".$this->port.";dbname=" . $this->db_name, $this->user, $this->password);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            }
            catch(PDOException $exception){
                echo "Database could not be connected: " . $exception->getMessage();
            }
            return $this->conn;

        }
    }
?>
