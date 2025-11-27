<?php
use App\Database;
?>

<body class="pesquisa">
    <?php require_once('./app/template/header.php'); ?>

    <h1 id="titulo">Pesquisar Plantas</h1>

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
if (isset($_GET['q'], $_GET['tipo'])) {

    $tipo = $_GET['tipo'];
    $q = '%' . $_GET['q'] . '%';

    $db = new Database();

    $colunas = [
        'nome' => 'nome',
        'uso'  => 'uso'
    ];

    if (!isset($colunas[$tipo])) {
        echo "<p style='color:red;'>Tipo de pesquisa inválido.</p>";
        return;
    }

    $coluna = $colunas[$tipo];

    $sql = "SELECT id, nome, nome_cientifico, imagem FROM plantas WHERE $coluna LIKE ?";
    $result = $db->prepare($sql, "s", [$q]);

    echo '<section class="plant-grid">';

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            // Corrigido → barra no caminho
            $img = $row['imagem'] ? "./assets/img/" . $row['imagem'] : "./assets/img/plantas/placeholder.jpg";

            echo "
            <div class='plant-card'>
                <div class='plant-image' style=\"background-image: url('$img')\"></div>
                <div class='plant-info'>
                    <h3 class='plant-name'>{$row['nome']}</h3>
                    <p class='plant-scientific'>{$row['nome_cientifico']}</p>
                </div>
            </div>";
        }

    } else {
        echo "<p style='text-align:center; color:#444; font-size:1.2rem;'>Nenhum resultado encontrado.</p>";
    }

    echo '</section>';
}
?>

</body>
