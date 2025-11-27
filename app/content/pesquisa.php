<?php
use App\Database;
?>

<body class="pesquisa">

    <?php require_once('./app/template/header.php'); ?>

    <h1 id="titulo">Pesquisar Plantas</h1>

    <!-- Formulário -->
    <form action="/pesquisar" method="GET" class="form-pesquisa">

        <select name="tipo" class="dropdown" required>
            <option value="nome" <?= ($_GET['tipo'] ?? '') === 'nome' ? 'selected' : '' ?>>Pesquisar por Nome</option>
            <option value="uso" <?= ($_GET['tipo'] ?? '') === 'uso' ? 'selected' : '' ?>>Pesquisar por Uso</option>
        </select>

        <input type="text" name="q" placeholder="Digite o termo..."
               value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" required>

        <button class="btn-pesquisar" type="submit">Buscar</button>
    </form>


<?php
// Se tem pesquisa → processa
if (isset($_GET['q'], $_GET['tipo'])) {

    $tipo = $_GET['tipo'];
    $q = '%' . $_GET['q'] . '%';

    $db = new Database();

    // Mapear tipo → coluna
    $colunas = [
        'nome' => 'nome',
        'uso'  => 'uso'
    ];

    if (!isset($colunas[$tipo])) {
        echo "<p style='color:red;'>Tipo inválido.</p>";
        return;
    }

    $coluna = $colunas[$tipo];

    // Consultar BD
    $sql = "SELECT * FROM plantas WHERE $coluna LIKE ?";
    $result = $db->prepare($sql, "s", [$q]);

    echo '<section class="plant-grid" style="margin-top: 40px;">';

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            // Dados
            $nome = $row['nome'];
            $nomeCientifico = $row['nome_cientifico'] ?? "";
            $descricao = $row['descricao'] ?? "Sem descrição cadastrada.";

            $hash = md5($nome);
            $imgPath = "assets/img/plantas/{$hash}.jpg";

            if (!file_exists($imgPath)) {
                $imgPath = "assets/img/default_plant.jpg";
            }

            // Card (idêntico ao catálogo)
            echo "
            <div class='plant-card' data-name='".htmlspecialchars($nome)."' style='margin:auto;'>
                <div class='plant-image' style=\"background-image: url('$imgPath')\"></div>

                <div class='plant-info'>
                    <h3 class='plant-name'>".htmlspecialchars($nome)."</h3>";

            if (!empty($nomeCientifico)) {
                echo "<p class='plant-scientific'>".htmlspecialchars($nomeCientifico)."</p>";
            }

            echo "
                    <p class='plant-description'>".nl2br(htmlspecialchars($descricao))."</p>
                </div>
            </div>";
        }

    } else {
        echo "<p style='text-align:center; font-size:1.2rem; color:#444;'>Nenhum resultado encontrado.</p>";
    }

    echo '</section>';
}
?>

</body>
