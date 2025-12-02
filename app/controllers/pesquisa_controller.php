<?php

// Define o namespace para organizar os controllers da aplicação.
namespace App\Controllers;

// Inclui o arquivo da classe Database, necessário para a conexão com o banco.
require_once("./app/database.php");
// Importa a classe Database para que possa ser usada sem o namespace completo.
use App\Database;

// O PesquisaController é responsável por gerenciar a lógica de busca de plantas.
class PesquisaController 
{
    /**
     * Método 'buscar'
     * - Pega os termos de busca da URL.
     * - Consulta o banco de dados.
     * - Renderiza a página de resultados.
     */
    public function buscar()
    {
        // Cria uma nova instância da classe Database para conectar ao banco.
        $db = new Database();

        // Coleta os dados da URL (query string). Usa o operador '??' para definir um valor padrão e evitar erros.
        $tipo = $_GET['tipo'] ?? '';
        $q    = $_GET['q'] ?? '';

        // Inicia a renderização do HTML da página de resultados.
        echo '<body class="catalogo">';
        require_once('./app/template/header.php');
        echo '<h1 id="titulo">Resultados da Pesquisa</h1>';

        // Validação básica para garantir que o tipo de pesquisa seja um dos valores permitidos.
        if ($tipo !== 'nome' && $tipo !== 'uso') {
            echo "<p>Tipo de pesquisa inválido.</p>";
            return;
        }

        // Escolhe a consulta SQL a ser executada com base no tipo de pesquisa selecionado.
        if ($tipo === 'nome') {
            $sql = "SELECT * FROM plantas WHERE nome LIKE ?";
        } else {
            $sql = "SELECT * FROM plantas WHERE uso LIKE ?";
        }

        // Executa a consulta usando um prepared statement para prevenir injeção de SQL.
        // O termo de busca é envolvido por '%' para funcionar com o operador LIKE.
        $result = $db->prepare($sql, "s", ["%$q%"]);

        // Se a consulta não retornar nenhuma linha, exibe uma mensagem e encerra a execução.
        if ($result->num_rows === 0) {
            echo "<p>Nenhuma planta encontrada.</p>";
            return;
        }

        echo '<section class="plant-grid">';

        // Loop para percorrer cada linha (planta) retornada pela consulta.
        while ($row = $result->fetch_assoc()) {

            // Extrai os dados da linha atual para variáveis.
            $nome    = $row['nome'];
            $cient   = $row['nome_cientifico'];
            $desc    = $row['descricao'];

            // Gera um hash MD5 do nome da planta para criar um nome de arquivo de imagem consistente.
            $hash = md5($nome);
            $img = "assets/img/plantas/{$hash}.jpg";

            // Se o arquivo de imagem não existir, usa uma imagem padrão.
            if (!file_exists($img)) {
                $img = "assets/img/default_plant.jpg";
            }

            ?>

            <!-- Inicia a renderização do cartão da planta. Usa htmlspecialchars para prevenir ataques XSS. -->
            <div class="plant-card" data-name="<?= htmlspecialchars($nome) ?>">

                <div class="plant-image"
                     style="background-image: url('<?= $img ?>')">
                </div>

                <div class="plant-info">
                    <h3 class="plant-name"><?= htmlspecialchars($nome) ?></h3>

                    <p class="plant-scientific"><?= htmlspecialchars($cient) ?></p>

                    <!-- nl2br() converte quebras de linha (\n) em tags <br> para formatar a descrição corretamente. -->
                    <p class="plant-description">
                        <?= nl2br(htmlspecialchars($desc)) ?>
                    </p>
                </div>

            </div>

            <?php
        }

        echo '</section>';
        // Fecha o corpo do HTML. O rodapé seria incluído aqui se necessário.
        echo '</body>';
    }
}
