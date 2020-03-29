<?php
$database = NULL;

class database
{
    public $pdo = NULL;
    public $server_name = SERVER_NAME;
    public $dbname = DB_NAME;
    public $db_user = DB_USER;
    public $db_password = DB_PASSWORD;
    public $table = '';

    function __construct()
    {

    }

    protected function connect($table)
    {
        try
        {
            global $database;
            $this->table = $table;
            if (is_null($database))
            {
                $database = new PDO("mysql:host=$this->server_name;dbname=$this->dbname", $this->db_user, $this->db_password);
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo = $database;
            }
            else
            {
                $this->pdo = $database;
            }
        }
        catch(PDOException $ex)
        {
            die($ex->getMessage());
        }
    }

    public function select($field = NULL, $condQuery, $table = NULL)
    {
        if (is_null($this->pdo)) return false;

        // Check table, if not pass param, use default table
        if ($table === NULL) $table = $this->table;

        // Check field require, if not pass param, use '*'
        if ($field === NULL) $field = ['*'];
        $field = implode(',', $field);

        //  If the script is select ALL from table name
        if ($condQuery === 'ALL' || $condQuery === '*' || $condQuery === 'All' || $condQuery === 'all')
        {
            $sql = "SELECT $field FROM $table"; //  Set up SQL
            $stmt = $this
                ->pdo
                ->prepare($sql);
            $stmt->execute(); //  Execute sql
            //  Return result
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        //  Create condition
        $condition = createCondQuery($condQuery);

        //  Set up SQL
        $sql = "SELECT $field 
                    FROM $table 
                    WHERE $condition";
        $stmt = $this
            ->pdo
            ->prepare($sql);

        // Blind value to condition
        foreach ($condQuery as $field => & $value)
        {
            $typeVal = validateDataPDO($value);
            $stmt->bindParam(':' . $field, $value, $typeVal); //  bind param to field
            
        }

        // Execute query
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function selectMany($fields = NULL, $join = NULL, $condQuery, $table = NULL)
    {
        if (is_null($this->pdo)) return false;

        // Check table, if not pass param, use default table
        if ($table === NULL) $table = $this->table;

        // Check field require, if not pass param, use '*'
        if ($fields === NULL) $fields = ['*'];
        $fields = implode(',', $fields);

        //  Initilize condition array, contain['title IN (?,?,?)', 'id IN (?,?,?)']
        $condition = [];

        //  Value to insert
        $valueToInsert = [];

        //  Loop through
        foreach ($condQuery as $field => $value)
        {
            //  Create element place holder, ex: 'id IN(?,?,?)'
            $condTemp = $field . ' IN (' . createPlaceHold(count($value)) . ')';
            //  Push to condition array
            array_push($condition, $condTemp);
            //  Loop though value
            foreach ($value as $element)
            {
                //  Push every element in value, ex: id IN (1,2,3)/ 1,2,3 is element loop though
                array_push($valueToInsert, $element);
            }
        }

        //  Implode condition to get query
        $condition = implode(' AND ', $condition);

        //  Create query
        $sql = "SELECT $fields FROM $table $join WHERE $condition";
        $stmt = $this
            ->pdo
            ->prepare($sql);
        $stmt->execute($valueToInsert);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($arrValue, $arrColumn = NULL, $table = NULL)
    {
        try
        {
            if (is_null($this->pdo)) return false;

            // Identify table, if not specific use default table
            if ($table === NULL) $table = $this->table;

            //  Column to insert
            $column = NULL;
            if ($arrColumn != NULL) $column = "(" . implode(',', $arrColumn) . ")"; // (field1, field2, ect..)
            //  Set up PlaceHolder[(?,?,?), (?,?,?)] and
            $placeHolderArr = [];

            // Value to Insert when execute query
            $valueInsert = [];

            //  Loop through data to insert, $values = ['Josh', 'NewYork' , 32]
            foreach ($arrValue as $values)
            {
                //  Create place holder an store into array, get total element of $values, then add "(" | ")"
                //  and add it to $placeHolderArr array, later implode it.
                $placeHolderArr[] = "(" . createPlaceHold(count($values)) . ")";

                // Add every element in $values array to array $valueInsert
                foreach ($values as $val)
                {
                    array_push($valueInsert, $val);
                }
            }

            //  Implode $placeHoldVal array, return (?,?,?), (?,?,?)
            $placeHoldVal = implode(', ', $placeHolderArr);

            //  Set up SQL
            $sql = "INSERT INTO $table $column VALUES $placeHoldVal";
            $stmt = $this
                ->pdo
                ->prepare($sql);

            // Execute with array valueInsert use to pass into $placeHoldVal above
            $stmt->execute($valueInsert);
            return true;
        }
        catch(PDOException $err)
        {
            die($err);
        }
    }

    public function update($updateQuery, $condQuery, $table = NULL)
    {
        try
        {
            if (is_null($this->pdo)) return false;

            // Check table, if not pass param, use default table
            if ($table === NULL) $table = $this->table;

            //  Create field update
            $fieldUpdate = createUpdateQueryV1($updateQuery);
            //  Create condition
            $condition = createCondQuery($condQuery);

            // Set up SQL
            $sql = "UPDATE $table SET
                             $fieldUpdate
                         WHERE $condition";
            $stmt = $this
                ->pdo
                ->prepare($sql);

            // Blind value to Update Field
            foreach ($updateQuery as $field => & $value)
            {
                $typeVal = validateDataPDO($value);
                $stmt->bindParam(':' . $field, $value, $typeVal); //  bind param to field
                
            }
            // Blind value to condition
            foreach ($condQuery as $field => & $value)
            {
                $typeVal = validateDataPDO($value);
                $stmt->bindParam(':' . $field, $value, $typeVal); //  bind param to field
                
            }
            $stmt->execute();
            return true;
        }
        catch(PDOException $err)
        {
            die($err);
        }
    }

    public function delete($condQuery, $table = NULL)
    {
        try
        {
            if (is_null($this->pdo)) return false;
            if ($table === NULL) $table = $this->table;

            //  Initilize condition array, contain['title IN (?,?,?)', 'id IN (?,?,?)']
            $condition = [];

            //  Value to insert
            $valueToInsert = [];

            //  Loop through
            foreach ($condQuery as $field => $value)
            {
                $condTemp = $field . ' IN (' . createPlaceHold(count($value)) . ')';
                array_push($condition, $condTemp);
                foreach ($value as $element)
                {
                    array_push($valueToInsert, $element);
                }
            }

            $condition = implode(' AND ', $condition);

            //  Create query
            $sql = "DELETE FROM $table WHERE $condition";

            $stmt = $this
                ->pdo
                ->prepare($sql);

            $stmt->execute($valueToInsert);
        }
        catch(PDOException $ex)
        {
            die($ex);
        }
    }

    public function getAllCategory()
    {
        $stmt = $this
            ->pdo
            ->prepare("SELECT * FROM categorys");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastInsertId()
    {
        return $this
            ->pdo
            ->lastInsertId();
    }
}
?>
