<?php

class DataRepositoy
{
    private ?object $connection = null;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function create(string $table, array $data, ?array $password = null): bool
    {
        try
        {
            if ($password)
            {
                $passwordKey = array_keys($password)[0];
                $password[$passwordKey] = password_hash($password[$passwordKey], PASSWORD_DEFAULT); 

                $data[$passwordKey] = $password[$passwordKey];
            }

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

    public function read(string $table, ?string $parameters = null): array|bool
    {
        try
        {
            $sql = "SELECT * FROM $table $parameters";

            $data = $this->connection->query($sql)->fetchAll(PDO::FETCH_ASSOC);

            return $data;
        }
        catch (PDOException $e)
        {
            error_log("\n\n" . date("Y-m-d H:i:s") . " | " . $e, 3, "./errors.log");
            return false;
        }
    }

    public function search(string $table, array $columns, string $keyWord): array|bool
    {
        try
        {
            $likeClauses = [];

            foreach ($columns as $column)
            {
                $likeClauses[] = "$column LIKE :keyWord";
            }

            $whereClause = implode(" OR ", $likeClauses);

            $sql = "SELECT * FROM $table WHERE $whereClause";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':keyWord', '%' . $keyWord . '%', PDO::PARAM_STR);
            $stmt->execute();

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
            $keyName = array_keys($key)[0];
            $keyValue = $key[$keyName];
            $updateAssignments = [];

            $data[$keyName] = $keyValue;

            foreach ($data as $column => $value)
            {
                $updateAssignments[] = "$column = :$column";
            }

            $updates = implode(", ", $updateAssignments);

            $sql = "UPDATE $table SET $updates WHERE $keyName = :$keyName";

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

    public function delete(string $table, array $key): bool
    {
        try
        {
            $keyName = array_keys($key)[0];

            $sql = "DELETE FROM $table WHERE $keyName = :$keyName";

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