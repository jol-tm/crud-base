<?php

class CRUD
{
    private string $hostname = "localhost";
    private string $username = "user";
    private string $password = "";
    private string $database = "mydb";
    private ?object $connection;

    public function __construct()
    {
        try {
            $this->connection = new PDO("mysql:host=$this->hostname;dbname=$this->database;charset=UTF8", $this->username, $this->password);
        } catch (PDOException $e) {
            error_log($e->getMessage(), 3, "./errors.log");
            echo $e->getMessage();
        }
    }

    public function create(string $table, array $data): bool
    {
        try {
            $columns = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));

            $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute($data);
            $stmt->closeCursor();

            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage(), 3, "./errors.log");
            return false;
        }
    }

    public function read(string $table): array|bool
    {
        try {
            $sql = "SELECT * FROM $table";
            
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $stmt->closeCursor();
            return $data;
        } catch (PDOException $e) {
            error_log($e->getMessage(), 3, "./errors.log");
            return false;
        }
    }

    public function update(string $table, array $data, array $key): bool
    {
        try {
            $keyName = array_keys($key)[0];
            $keyValue = $key[$keyName];
            $update_assignments = [];

            foreach ($data as $column => $value) {
                $update_assignments[] = "$column = :$column";
            }

            $updates = implode(", ", $update_assignments);

            $sql = "UPDATE $table SET $updates WHERE $keyName = :$keyName";
            $stmt = $this->connection->prepare($sql);

            $data[$keyName] = $keyValue;
            $stmt->execute($data);

            $stmt->closeCursor();
            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage(), 3, "./errors.log");
            return false;
        }
    }

    public function delete(string $table, array $key): bool
    {
        try {
            $keyName = array_keys($key)[0];

            $sql = "DELETE FROM $table WHERE $keyName = :$keyName";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute($key);

            $stmt->closeCursor();
            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage(), 3, "./errors.log");
            return false;
        }
    }
}