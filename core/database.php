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
                    // Check if this data base is null
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

        /*
        function getAll(){
            if(is_null($this->pdo)){
                return NULL;
            }
            $stmt = $this->pdo->prepare("SELECT * FROM $this->table");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        function getByID($id){
            if(is_null($this->pdo))
                return NULL;
            $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE id=$id");
            $stmt->execute();
            return $stmt->fetch();
        }
        */

        function checkExist($arr, $field = '*'){
            try{
                if(is_null($this->pdo))
                    return false;
                $value = createCheckQuery($arr);
                $stmt = $this->pdo->prepare("SELECT $field FROM $this->table WHERE $value");
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if(!$result)
                    return false;
                else
                    return true;
            }
            catch(PDOException $err){
                die($err);
            }
        }

        function insert($value){
            try{
                if(is_null($this->pdo))
                    return false;
                $stmt = $this->pdo->prepare("INSERT INTO $this->table VALUES $value");
                $stmt->execute();
                return true;
            }
            catch(PDOException $err){
                die($err);
            }
        }

        

    }
?>