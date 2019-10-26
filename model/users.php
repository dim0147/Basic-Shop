<?php
    class UserModel extends database{
        function __construct(){
            $this->connect('users');
        }
        
        public function getUserPassword($value){
            try{
                if(!$this->pdo) return NULL;
                $value = createCheckQuery($value);
                $stmt = $this->pdo->prepare("SELECT password FROM users WHERE $value");
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if($result)
                    return $result['password'];
                else
                    return false;
            }
            catch(PDOException $err){
                die($err);
            }
        }

    }
?>