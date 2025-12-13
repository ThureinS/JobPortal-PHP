<?php

class Database
{
    public $conn;

    public function __construct($config)
    {
        // Connection string (DSN)
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";


        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ

        ];

        try {
            // Create a PDO instance
            $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);


            // Set PDO to throw exceptions on error

            echo "Database Connected...";


            // You are now connected to the database, and $pdo contains the connection object


            // You can perform database operations here


        } catch (PDOException $e) {
            // If there is an error with the connection, catch it here
            echo "Connection failed: " . $e->getMessage();
        }
    }

    /**
     * @param string $query
     */

    public function query($query)
    {
        try {
            $sth = $this->conn->prepare($query);
            $sth->execute();
            return $sth;
        } catch (PDOException $e) {
            throw new Exception("Query failed: " . $e->getMessage());
        }
    }
}
