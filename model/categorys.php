<?php 
    class CategoryModel extends database{

        function __construct(){
            $this->connect('categorys');
        }

        public function getCategory(){
            try{
                if($this->pdo === NULL)
                    return false;
                $stmt = $this->pdo->prepare("SELECT DISTINCT name, id FROM categorys");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            catch(PDOException $err){
                die($err);
            }
        }

    }
?>