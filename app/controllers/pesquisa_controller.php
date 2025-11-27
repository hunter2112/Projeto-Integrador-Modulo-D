<?php

namespace App\Controllers;

require_once("./app/database.php");
use App\Database;

class PesquisaController 
{
    public function buscar()
    {
        $db = new Database();

        // Coleta dados da URL
        $tipo = $_GET['tipo'] ?? '';
        $q    = $_GET['q'] ?? '';

        echo '<body class="catalogo">';
        require_once('./app/template/header.php');
        echo '<h1 id="titulo">Resultados da Pesquisa</h1>';

        // Validação básica
        if ($tipo !== 'nome' && $tipo !== 'uso') {
            echo "<p>Tipo de pesquisa inválido.</p>";
            return;
        }

        // Escolhe consulta conforme tipo
        if ($tipo === 'nome') {
            $sql = "SELECT * FROM plantas WHERE nome LIKE ?";
        } else {
            $sql = "SELECT * FROM plantas WHERE uso LIKE ?";
        }

        // Consulta preparada
        $result = $db->prepare($sql, "s", ["%$q%"]);

        if ($result->num_rows === 0) {
            echo "<p>Nenhuma planta encontrada.</p>";
            return;
        }

        echo '<section class="plant-grid">';

        // Loop pelos resultados
        while ($row = $result->fetch_assoc()) {

            $nome    = $row['nome'];
            $cient   = $row['nome_cientifico'];
            $desc    = $row['descricao'];

            // Gera hash do nome
            $hash = md5($nome);
            $img = "assets/img/plantas/{$hash}.jpg";

            if (!file_exists($img)) {
                $img = "assets/img/default_plant.jpg";
            }

            ?>

            <div class="plant-card" data-name="<?= htmlspecialchars($nome) ?>">

                <div class="plant-image"
                     style="background-image: url('<?= $img ?>')">
                </div>

                <div class="plant-info">
                    <h3 class="plant-name"><?= htmlspecialchars($nome) ?></h3>

                    <p class="plant-scientific"><?= htmlspecialchars($cient) ?></p>

                    <p class="plant-description">
                        <?= nl2br(htmlspecialchars($desc)) ?>
                    </p>
                </div>

            </div>

            <?php
        }

        echo '</section>';
        echo '</body>';
    }
}
