<?php

namespace App;

use Exception;

class CadastroController
{
    public function salvar()
    {
        try {
            $db = new Database();

            // Campos recebidos
            $nome          = $_POST['nome'];
            $cientifico    = $_POST['nome_cientifico'];
            $descricao     = $_POST['descricao'];
            $caract        = $_POST['caracteristicas'];
            $uso           = $_POST['uso'] ?? null;

            // ============================
            //   PROCESSAR A IMAGEM
            // ============================

            $hash = md5($nome);
            $pasta = "assets/img/plantas/";
            $arquivo = $pasta . $hash . ".jpg";

            // Criar pasta caso não exista
            if (!is_dir($pasta)) {
                mkdir($pasta, 0777, true);
            }

            // Move o arquivo enviado
            if (!empty($_FILES['imagem']['tmp_name'])) {
                move_uploaded_file($_FILES['imagem']['tmp_name'], $arquivo);
                $imagem_url = $arquivo;
            } else {
                $imagem_url = null;
            }

            // ============================
            //  SALVAR NO BANCO
            // ============================

            $sql = "INSERT INTO plantas
                    (nome, nome_cientifico, descricao, caracteristicas, uso, imagem_url)
                    VALUES (?, ?, ?, ?, ?, ?)";

            $db->execute($sql, "ssssss", [
                $nome, $cientifico, $descricao, $caract, $uso, $imagem_url
            ]);

            echo "Planta cadastrada com sucesso!";

        } catch (Exception $e) {
            http_response_code(500);
            echo "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
