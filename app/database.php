<?php

namespace App; // Define o namespace da classe (organização do projeto)

use mysqli;
use Exception;

class Database {
    // Conexão ativa com o banco
    private mysqli $conn;
    // Guarda o statement preparado (usado em prepare() e execute())
    private $stmt;

    /*
     |-------------------------------------------------------------
     | CONSTRUTOR — inicia a conexão com o banco ao criar o objeto
     |-------------------------------------------------------------
    */
    public function __construct(string $envFile = ".env") {

        // Carrega as credenciais do arquivo .env
        $config = $this->loadEnv($envFile);

        // Pega os valores (ou valores padrão caso não existam)
        $host    = $config['DB_HOST'] ?? 'localhost';
        $dbname  = $config['DB_NAME'] ?? '';
        $user    = $config['DB_USER'] ?? 'root';
        $pass    = $config['DB_PASS'] ?? '';
        $charset = $config['DB_CHARSET'] ?? 'utf8mb4';

        // Tenta conectar ao banco usando mysqli
        $this->conn = new mysqli($host, $user, $pass, $dbname);

        $this->conn->set_charset("utf8mb4");

        // Se falhar, lança uma EXCEÇÃO (ao invés de dar "die")
        if ($this->conn->connect_error) {
            throw new Exception("DB Connection failed: " . $this->conn->connect_error);
        }
    
        // Define charset (importante para acentos e caracteres especiais)
        $this->conn->set_charset($charset);
        
    }

    /*
     |-------------------------------------------------------------
     | FUNÇÃO loadEnv() — lê o arquivo .env e extrai as variáveis
     |-------------------------------------------------------------
    */
    private function loadEnv(string $file): array {

        // Caso o .env não exista, lança exceção
        if (!file_exists($file)) {
            throw new Exception("Missing .env file: $file");
        }

        $config = [];

        // Lê cada linha do arquivo
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {

            // Ignora linhas começando com "#"
            if (strpos(trim($line), '#') === 0) continue;

            // Ignora linhas sem "="
            if (!str_contains($line, '=')) continue;

            // Divide a linha em "chave = valor"
            [$key, $value] = explode("=", $line, 2);

            // Salva no array de configuração
            $config[trim($key)] = trim($value);
        }

        return $config;
    }

    /*
     |-------------------------------------------------------------
     | MÉTODO query() — SELECT simples sem parâmetros
     |-------------------------------------------------------------
    */
    public function query(string $sql) {

        $result = $this->conn->query($sql);

        // Se houve erro na query, lança exceção
        if ($this->conn->error) {
            throw new Exception("Query Error: " . $this->conn->error);
        }

        return $result; // Retorna o resultado do SELECT
    }

    /*
     |-------------------------------------------------------------
     | MÉTODO prepare() — SELECT com parâmetros (prepared statement)
     |-------------------------------------------------------------
    */
    public function prepare(string $sql, string $types, array $params) {

        // Prepara a query
        $this->stmt = $this->conn->prepare($sql);

        // Caso falhe
        if (!$this->stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        // Faz o bind dos parâmetros ("s", "i", etc.)
        $this->stmt->bind_param($types, ...$params);

        // Executa
        $this->stmt->execute();

        // Retorna o resultado (apenas para SELECT)
        return $this->stmt->get_result();
    }

    /*
     |-------------------------------------------------------------
     | MÉTODO execute() — INSERT, UPDATE ou DELETE (com bind)
     |-------------------------------------------------------------
    */
    public function execute(string $sql, string $types, array $params): int {

        // Prepara o comando
        $this->stmt = $this->conn->prepare($sql);

        if (!$this->stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        // Faz o bind dos valores
        $this->stmt->bind_param($types, ...$params);

        // Executa o comando
        $this->stmt->execute();

        // Retorna quantas linhas foram afetadas
        return $this->stmt->affected_rows;
    }

    /*
     |-------------------------------------------------------------
     | Retorna ID da última linha inserida (para INSERT)
     |-------------------------------------------------------------
    */
    public function lastInsertId(): int {
        return $this->conn->insert_id;
    }

    /*
     |-------------------------------------------------------------
     | Fecha a conexão com o banco
     |-------------------------------------------------------------
    */
    public function close(): void {
        $this->conn->close();
    }
}
