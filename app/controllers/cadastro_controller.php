<?php
namespace App;

use Exception;

class CadastroController
{
    public function salvar()
    {
        // Responder sempre como texto simples (AJAX espera .text())
        try {
            // habilitar para debug local (remova em produção)
            // ini_set('display_errors', 1); error_reporting(E_ALL);

            // simples validação
            $nome = trim($_POST['nome'] ?? '');
            $nomeCientifico = trim($_POST['nome_cientifico'] ?? '');
            $descricao = trim($_POST['descricao'] ?? '');
            $caracteristicas = trim($_POST['caracteristicas'] ?? '');
            $uso = trim($_POST['uso'] ?? '');

            if ($nome === '' || $descricao === '' || $caracteristicas === '') {
                http_response_code(400);
                echo "Preencha os campos obrigatórios.";
                return;
            }

            // PROCESSAR IMAGEM
            $imagem_url = null;
            if (!empty($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
                $err = $_FILES['imagem']['error'];
                if ($err !== UPLOAD_ERR_OK) {
                    $map = [
                        UPLOAD_ERR_INI_SIZE => 'Arquivo maior que upload_max_filesize.',
                        UPLOAD_ERR_FORM_SIZE => 'Arquivo maior que MAX_FILE_SIZE no form.',
                        UPLOAD_ERR_PARTIAL => 'Upload parcial.',
                        UPLOAD_ERR_NO_FILE => 'Nenhum arquivo enviado.',
                        UPLOAD_ERR_NO_TMP_DIR => 'Pasta temporária ausente.',
                        UPLOAD_ERR_CANT_WRITE => 'Falha ao escrever em disco.',
                        UPLOAD_ERR_EXTENSION => 'Upload parado por extensão.',
                    ];
                    $msg = $map[$err] ?? "Erro no upload: code $err";
                    http_response_code(400);
                    echo $msg;
                    return;
                }

                $tmp = $_FILES['imagem']['tmp_name'];
                if (!is_uploaded_file($tmp)) {
                    http_response_code(400);
                    echo "Arquivo inválido.";
                    return;
                }

                // validar imagem
                if (getimagesize($tmp) === false) {
                    http_response_code(400);
                    echo "Arquivo enviado não é uma imagem válida.";
                    return;
                }

                // Normalizar nome (sem acentos)
                    $nomeNorm = iconv('UTF-8', 'ASCII//TRANSLIT', $nome);
                    $nomeNorm = preg_replace('/[^A-Za-z0-9]/', '', $nomeNorm);
                    $nomeNorm = strtolower($nomeNorm);

                    // hash igual ao catálogo
                    $hash = md5($nomeNorm);

                    // caminho final
                    $targetDir = __DIR__ . "/../../assets/img/plantas/";
                    $targetFile = $targetDir . $hash . ".jpg";

                    // criar pasta
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }

                    // mover arquivo
                    if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $targetFile)) {
                        http_response_code(500);
                        echo "Erro ao salvar imagem.";
                        return;
                    }

                    // salva no DB (não é usado pelo catálogo, mas deixei)
                    $imagem_url = "assets/img/plantas/" . $hash . ".jpg";
                
            }

            // SALVAR NO BANCO
            $db = new Database();

            $sql = "INSERT INTO plantas (nome, nome_cientifico, descricao, caracteristicas, uso, imagem)
                    VALUES (?, ?, ?, ?, ?, ?)";
            // use try/catch em execute se sua Database lançar Exception
            $db->execute($sql, "ssssss", [
                $nome, $nomeCientifico, $descricao, $caracteristicas, $uso, $imagem_url
            ]);

            http_response_code(200);
            echo "Planta cadastrada com sucesso!";
            return;

        } catch (Exception $e) {
            // Logar em arquivo de servidor também é recomendado
            http_response_code(500);
            echo "Erro interno: " . $e->getMessage();
            return;
        }
    }
}
