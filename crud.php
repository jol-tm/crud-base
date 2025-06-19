<?php

class CRUD
{
    private $hostname = "localhost";
    private $username = "root";
    private $password = "123";
    private $database = "testdb";
    private $connection;

    public function __construct()
    {
        $this->connection = new mysqli($this->hostname, $this->username, $this->password, $this->database);

        if ($this->connection->connect_error)
        {
            error_log($this->connection->connect_error, 3, './logs/errors.log');
            return false;
        }

        $this->connection->set_charset("utf8");
    }

    public function create($table, $data, $parameters = null)
    {
        $columns = implode(", ", array_keys($data));
        $values = implode(", ", $data);

        $sql = "INSERT INTO $table ($columns) VALUES ($values) $parameters";

        if ($stmt->execute())
        {
            $stmt->close();
            return true;
        }
        
        error_log($stmt->error, 3, './logs/errors.log');
        $stmt->close();
        return false;
    }

    public function read($table, $parameters = null) 
    {
        $sql = "SELECT * FROM $table $parameters";
        $stmt = $this->connection->prepare($sql);

        if ($stmt->execute())
        {
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);

            $stmt->close();
            return $data;
        }

        error_log($stmt->error, 3, './logs/errors.log');
        $stmt->close();
        return false;
    }

    public function update($table, $data, $parameters = null)
    {
        $columns = implode(", ", array_keys($data));
        $values = implode(", ", $data);
        
        $sql = "UPDATE $table SET $columns = $values $parameters";
        
        if ($stmt->execute())
        {
            $stmt->close();
            return true;
        }
        
        error_log($stmt->error, 3, './logs/errors.log');
        $stmt->close();
        return false;
    }

    public function delete($table, $id)
    {
        $sql = "DELETE FROM $table WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute())
        {
            $stmt->close();
            return true;
        } 
        
        error_log($stmt->error, 3, './logs/errors.log');
        $stmt->close();
        return false;
    }
     
    public function __destruct() 
    {
        if ($this->connection) 
        {
            $this->connection->close();
        }
    }
}