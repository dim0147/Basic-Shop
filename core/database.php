<?php
    $database = NULL;

    class database{
        public $pdo = NULL;
        public $server_name = SERVER_NAME;
        public $dbname = DB_NAME;
        public $db_user = DB_USER;
        public $db_password = DB_PASSWORD;
        public $table = '';

        function __construct(){
            
        }

        protected function connect($table){
            try{
                global $database;
                $this->table = $table;
                if(is_null($database)){
                    $database = new PDO("mysql:host=$this->server_name;dbname=$this->dbname", $this->db_user, $this->db_password);
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

        public function getAll(){
            if(is_null($this->pdo))
                return NULL;
            $stmt = $this->pdo->prepare("SELECT * FROM $this->table");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        public function select($field = NULL, $condition , $table = NULL){
            if(is_null($this->pdo))
                    return false;
            if ($table === NULL)
                $table = $this->table;
            if($field === NULL)
                $field = ['*'];
            $condition = createCheckQuery($condition);
            $field = implode(',' , $field);
            $stmt = $this->pdo->prepare("SELECT $field FROM $table WHERE $condition");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        public function insert($value, $table = NULL){
            try{
                if(is_null($this->pdo))
                    return false;
                if ($table === NULL)
                    $table = $this->table;
                echo $table . " - " . $value;
                $stmt = $this->pdo->prepare("INSERT INTO $table VALUES $value");
                $stmt->execute();
                return true;
                
            }
            catch(PDOException $err){
                die($err);
            }
        }

        public function update($query, $condition, $table = NULL){
            try{
                if(is_null($this->pdo))
                    return false;
                if ($table === NULL)
                    $table = $this->table;
                $condition = createQuery($condition, TRUE);
                $query = createQuery($query, TRUE);
                $stmt = $this->pdo->prepare("UPDATE $table SET $query WHERE $condition");
                $stmt->execute();
                return true;
            }
            catch(PDOException $err){
                die($err);
            }
        }

        public function delete($condition, $table = NULL){
            try{
                if(is_null($this->pdo))
                    return false;
                if ($table === NULL)
                    $table = $this->table;
                $condition = createCheckQuery($condition);
                $stmt = $this->pdo->prepare("DELETE FROM $table WHERE $condition");
                $stmt->execute();
            }
            catch(PDOException $ex){
                die($ex);
            }
        }
    }
?>