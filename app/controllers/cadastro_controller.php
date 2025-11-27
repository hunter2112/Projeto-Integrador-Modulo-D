<?php

namespace App;

use Exception;

class CadastroController
{
    public function salvar()
    {
        try {
            $db = new Database();

            // Dados enviados
            $nome = $_POST['nome'];
            $cientifico = $_POST['nome_cientifico'];
            $descricao = $_POST['descricao'];
            $caract = $_POST['caracteristicas'];
            $uso = $_POST['uso'] ?? null;

            // Upload da imagem
            $hash = md5($nome);
            $destino = "assets/img/plantas/$hash.jpg";

            if (!empty($_FILES['imagem']['tmp_name'])) {
                move_uploaded_file($_FILES['imagem']['tmp_name'], $destino);
            }

            // Salvar no banco
            $sql = "INSERT INTO plantas (nome, nome_cientifico, descricao, caracteristicas, uso)
                    VALUES (?, ?, ?, ?, ?)";

            $db->execute($sql, "sssss", [
                $nome, $cientifico, $descricao, $caract, $uso
            ]);

            echo "Planta cadastrada com sucesso!";

        } catch (Exception $e) {
            http_response_code(500);
            echo "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
