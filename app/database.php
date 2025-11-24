<?php

namespace App;

use mysqli;
use Exception;

class Database {
    private mysqli $conn;
    private $stmt;

    public function __construct(string $envFile = ".env") {
        $config = $this->loadEnv($envFile);

        $host    = $config['DB_HOST'] ?? 'localhost';
        $dbname  = $config['DB_NAME'] ?? '';
        $user    = $config['DB_USER'] ?? 'root';
        $pass    = $config['DB_PASS'] ?? '';
        $charset = $config['DB_CHARSET'] ?? 'utf8mb4';

        $this->conn = new mysqli($host, $user, $pass, $dbname);

        if ($this->conn->connect_error) {
            throw new Exception("DB Connection failed: " . $this->conn->connect_error);
        }

        $this->conn->set_charset($charset);
    }

    private function loadEnv(string $file): array {
        if (!file_exists($file)) {
            throw new Exception("Missing .env file: $file");
        }

        $config = [];
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            if (!str_contains($line, '=')) continue;

            [$key, $value] = explode("=", $line, 2);
            $config[trim($key)] = trim($value);
        }

        return $config;
    }

    // Simple SELECT without parameters
    public function query(string $sql) {
        $result = $this->conn->query($sql);
        if ($this->conn->error) {
            throw new Exception("Query Error: " . $this->conn->error);
        }
        return $result;
    }

    // Prepared SELECT with parameters
    public function prepare(string $sql, string $types, array $params) {
        $this->stmt = $this->conn->prepare($sql);
        if (!$this->stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        $this->stmt->bind_param($types, ...$params);
        $this->stmt->execute();

        return $this->stmt->get_result();
    }

    // INSERT / UPDATE / DELETE with parameters
    public function execute(string $sql, string $types, array $params): int {
        $this->stmt = $this->conn->prepare($sql);
        if (!$this->stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        $this->stmt->bind_param($types, ...$params);
        $this->stmt->execute();

        return $this->stmt->affected_rows;
    }

    public function lastInsertId(): int {
        return $this->conn->insert_id;
    }

    public function close(): void {
        $this->conn->close();
    }
}
