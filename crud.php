<?php

class CRUD
{
    private string $hostname = "localhost";
    private string $username = "root";
    private string $password = "";
    private string $database = "test_db";
    private ?object $connection = null;

    public function __construct()
    {
        try
        {
            date_default_timezone_set('America/Sao_Paulo');

            $this->connection = new PDO("mysql:host=$this->hostname;dbname=$this->database;charset=UTF8", $this->username, $this->password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        }
        catch (PDOException $e)
        {
            error_log("\n\n" . date("Y-m-d H:i:s") . " | " . $e, 3, "./errors.log");
        }
    }

    public function __destruct()
    {
        $this->connection = null;
    }

    public function create(string $table, array $data): bool
    {
        try
        {
            $columns = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));

            $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute($data);

            return true;
        }
        catch (PDOException $e)
        {
            error_log("\n\n" . date("Y-m-d H:i:s") . " | " . $e, 3, "./errors.log");
            return false;
        }
    }

    public function read(string $table, array $condition = [1 => 1], array $searching_term = []): array|bool
    {
        try
        {
            if ($searching_term)
            {
                $searching_key = array_keys($searching_term)[0];
                $searching_value = $searching_term[$searching_key];
                $searching_value_wildcarded = "%$searching_value%";
                
                $binding_params = [$searching_key => $searching_value_wildcarded];
                
                $sql = "SELECT * FROM $table WHERE $searching_key LIKE :$searching_key";
            }
            else
            {
                $condition_key = array_keys($condition)[0];
                $condition_value = $condition[$condition_key];

                $binding_params = [$condition_key => $condition_value];

                $sql = "SELECT * FROM $table WHERE $condition_key = :$condition_key";
            }

            $stmt = $this->connection->prepare($sql);
            $stmt->execute($binding_params);

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $data;
        }
        catch (PDOException $e)
        {
            error_log("\n\n" . date("Y-m-d H:i:s") . " | " . $e, 3, "./errors.log");
            return false;
        }
    }

    public function update(string $table, array $data, array $key): bool
    {
        try
        {
            $key_name = array_keys($key)[0];
            $key_value = $key[$key_name];
            $update_assignments = [];

            foreach ($data as $column => $value)
            {
                $update_assignments[] = "$column = :$column";
            }

            $updates = implode(", ", $update_assignments);

            $sql = "UPDATE $table SET $updates WHERE $key_name = :$key_name";
            $stmt = $this->connection->prepare($sql);

            $data[$key_name] = $key_value;
            $stmt->execute($data);

            return true;
        }
        catch (PDOException $e)
        {
            error_log("\n\n" . date("Y-m-d H:i:s") . " | " . $e, 3, "./errors.log");
            return false;
        }
    }

    public function delete(string $table, array $key): bool
    {
        try
        {
            $key_name = array_keys($key)[0];

            $sql = "DELETE FROM $table WHERE $key_name = :$key_name";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute($key);

            return true;
        }
        catch (PDOException $e)
        {
            error_log("\n\n" . date("Y-m-d H:i:s") . " | " . $e, 3, "./errors.log");
            return false;
        }
    }
}