<?php

class Authenticator
{
    private ?object $connection = null;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function authenticate(string $table, string $email, string $password): bool
    {
        try
        {
            $sql = "SELECT password FROM $table WHERE email = :email";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);

            $stmt->execute();

            $hash = $stmt->fetchColumn();

            if (password_verify($password, $hash))
            {
                return true;
            }

            return false;
        }
        catch (PDOException $e)
        {
            error_log("\n\n" . date("Y-m-d H:i:s") . " | " . $e, 3, "./errors.log");
            return false;
        }
    }
}