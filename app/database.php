<?php

namespace App;

use mysqli;
class Database {
    private $conn;
    private $stmt;

    public function __construct($envFile = ".env") {
        $config = $this->loadEnv($envFile);

        $host    = $config['DB_HOST'] ?? 'localhost';
        $dbname  = $config['DB_NAME'] ?? '';
        $user    = $config['DB_USER'] ?? 'root';
        $pass    = $config['DB_PASS'] ?? '';
        $charset = $config['DB_CHARSET'] ?? 'utf8mb4';

        $this->conn = new mysqli($host, $user, $pass, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        $this->conn->set_charset($charset);
    }

    private function loadEnv($file) {
        $config = [];
        if (!file_exists($file)) {
            die("Missing .env file: $file");
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            list($key, $value) = explode("=", $line, 2);
            $config[trim($key)] = trim($value);
        }
        return $config;
    }

    // Simple query (no params)
    public function query($sql) {
        $result = $this->conn->query($sql);
        if ($this->conn->error) {
            die("Query error: " . $this->conn->error);
        }
        return $result;
    }

    // Prepared query with params
    public function prepare($sql, $types, $params) {
        $this->stmt = $this->conn->prepare($sql);
        if (!$this->stmt) {
            die("Prepare failed: " . $this->conn->error);
        }
        $this->stmt->bind_param($types, ...$params);
        $this->stmt->execute();
        return $this->stmt->get_result();
    }

    // Insert/update/delete
    public function execute($sql, $types, $params) {
        $this->stmt = $this->conn->prepare($sql);
        if (!$this->stmt) {
            die("Prepare failed: " . $this->conn->error);
        }
        $this->stmt->bind_param($types, ...$params);
        $this->stmt->execute();
        return $this->stmt->affected_rows;
    }

    public function lastInsertId() {
        return $this->conn->insert_id;
    }

    public function close() {
        $this->conn->close();
    }
}
