<?php
    $database = NULL;

    class database{
        public $pdo = NULL;
        public $servername = SERVERNAME;
        public $dbname = DB_NAME;
        public $dbuser = DB_USER;
        public $dbpassword = DB_PASSWORD;
        public $table = '';

        function __construct(){
            
        }

        protected function connect($table){
            try{
                global $database;
                $this->table = $table;
                if(is_null($database)){
                    $database = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->dbuser, $this->dbpassword);
                    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $this->pdo = $database;
                }
                else{
                    $this->pdo = $database;
                }
            }
            catch(PDOException $ex){
                die($ex->getMessage());
            }
        }

        function getAll(){
            if(is_null($this->pdo))
                return NULL;
            $stmt = $this->pdo->prepare("SELECT * FROM $this->table");
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        }

        function getByID($id){
            if(is_null($this->pdo))
                return NULL;
            $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE id=$id");
            $stmt->execute();
            return $stmt->fetch();
        }

        

    }
?>