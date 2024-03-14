<?php

namespace Src;

use mysqli;

class Database
{
    protected $host;
    protected $database;
    protected $user;
    protected $password;

    protected $conn;

    public function __construct($host, $user, $password, $database)
    {
        $this->host = $host;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;

        $this->createDatabaseIfNotExists();
        $this->createTableIfNotExists();
    }

    public function getConnection($database = null)
    {
        if (empty($this->conn) || $database !== null) {
            $this->conn = $this->getMysqliConnection($this->host, $this->user, $this->password, $database);
        }
        return $this->conn;
    }

    protected function createDatabaseIfNotExists()
    {
        $conn = $this->getConnection();
        $sql = 'CREATE DATABASE IF NOT EXISTS '.$this->database;
        $conn->query($sql);
        $conn->close();
    }

    protected function createTableIfNotExists()
    {
        $conn = $this->getConnection($this->database);

        $sql = 'CREATE TABLE IF NOT EXISTS transactions (
                    id int AUTO_INCREMENT PRIMARY KEY,
                    status VARCHAR(10),
                    errormessage VARCHAR(255),
                    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
                    shopsystem VARCHAR(255) NOT NULL,
                    shopversion VARCHAR(10) NOT NULL,
                    moduleversion VARCHAR(10) NOT NULL,
                    language VARCHAR(4) NOT NULL,
                    billing_firstname VARCHAR(255),
                    billing_lastname VARCHAR(255),
                    billing_street VARCHAR(255),
                    billing_zip VARCHAR(10),
                    billing_city VARCHAR(255),
                    billing_country VARCHAR(255),
                    shipping_firstname VARCHAR(255),
                    shipping_lastname VARCHAR(255),
                    shipping_street VARCHAR(255),
                    shipping_zip VARCHAR(10),
                    shipping_city VARCHAR(255),
                    shipping_country VARCHAR(255),
                    email VARCHAR(255),
                    customer_nr VARCHAR(255),
                    order_nr VARCHAR(255),
                    amount DECIMAL(8,2),
                    currency VARCHAR(3)
                    )';
        $conn->query($sql);
        $conn->close();
    }

    protected function getMysqliConnection($host, $user, $password, $database = null) {
        $this->conn = new mysqli($host, $user, $password, $database);
        if ($this->conn->connect_error) {
            die(json_encode([
                'status' => 'FAILED',
                'errormessage' => $this->conn->connect_error
            ]));
        }
        return $this->conn;
    }

    public function getDatabaseName()
    {
        return $this->database;
    }
}