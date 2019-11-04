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

        public function select($field = NULL, $condQuery , $table = NULL){
            if(is_null($this->pdo))
                    return false;

             // Check table, if not pass param, use default table
            if ($table === NULL)
                $table = $this->table;

             // Check field require, if not pass param, use '*'
            if($field === NULL)
                $field = ['*'];
            $field = implode(',' , $field);

            //  Create condition
            $condition = createCondQuery($condQuery);

            //  Set up SQL
            $sql = "SELECT $field 
                    FROM $table 
                    WHERE $condition";
            $stmt = $this->pdo->prepare($sql);

            // Blind value to condition
            foreach($condQuery as $field => &$value){
                $typeVal = validateDataPDO($value);
                $stmt->bindParam(':'. $field, $value, $typeVal);   //  bind param to field
            }

            // Execute query
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
                $stmt = $this->pdo->prepare("INSERT INTO $table VALUES $value");
                $stmt->execute();
                return true;
                
            }
            catch(PDOException $err){
                die($err);
            }
        }

        public function insertMany($arrValue, $arrColumn = NULL, $table = NULL){
            try{
                if(is_null($this->pdo))
                    return false;
                
                // Identify table, if not specific use default table
                if ($table === NULL)
                    $table = $this->table;

                //  Column to insert
                $column = NULL;
                if($arrColumn != NULL)
                    $column = "(" . implode(',', $arrColumn) . ")"; // (field1, field2, ect..)
                
                //  Set up PlaceHolder[(?,?,?), (?,?,?)] and 
                $placeHolderArr = [];

                // Value to Insert when execute query
                $valueInsert = [];

                //  Loop through data to insert, $values = ['Josh', 'NewYork' , 32]
                foreach($arrValue as $values){
                    //  Create place holder an store into array, get total element of $values, then add "(" | ")"
                    //  and add it to $placeHolderArr array, later implode it.
                    $placeHolderArr[] = "(" . createPlaceHold(count($values)) .")";

                    // Add every element in $values array to array $valueInsert
                    foreach($values as $val){
                        array_push($valueInsert, $val);
                    }
                }

                //  Implode $placeHoldVal array, return (?,?,?), (?,?,?)
                $placeHoldVal = implode(', ', $placeHolderArr);
                
                //  Set up SQL
                $sql = "INSERT INTO $table $column VALUES $placeHoldVal";
                $stmt = $this->pdo->prepare($sql);

                // Execute with array valueInsert use to pass into $placeHoldVal above
                $stmt->execute($valueInsert);
                return true;
            }
            catch(PDOException $err){
                die($err);
            }
        }

        public function update($updateQuery, $condQuery, $table = NULL){
            try{
                if(is_null($this->pdo))
                    return false;

                // Check table, if not pass param, use default table
                if ($table === NULL)   
                    $table = $this->table;

                 //  Create field update
                 $fieldUpdate = createUpdateQueryV1($updateQuery);
                 //  Create condition
                 $condition = createCondQuery($condQuery);

                 // Set up SQL 
                 $sql = "UPDATE $table SET
                             $fieldUpdate
                         WHERE $condition";
                 $stmt = $this->pdo->prepare($sql);

                 // Blind value to Update Field
                 foreach($updateQuery as $field => &$value){
                     $typeVal = validateDataPDO($value);
                     $stmt->bindParam(':'. $field, $value, $typeVal);  //  bind param to field
                 }
                 // Blind value to condition
                 foreach($condQuery as $field => &$value){
                     $typeVal = validateDataPDO($value);
                     $stmt->bindParam(':'. $field, $value, $typeVal);   //  bind param to field
                 }
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