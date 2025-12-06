<?php
use App\Database;
?>

<body class="pesquisa">

<?php require_once('./app/template/header.php'); ?>

<!-- MODAL -->
<div id="plant-modal" class="modal hidden">
    <div class="modal-content">
        <span class="modal-close">&times;</span>

        <div class="modal-left">
            <img id="modal-img" src="">
        </div>

        <div class="modal-right">
            <h2 id="modal-nome"></h2>
            <h3 id="modal-cientifico"></h3>

            <p><strong>Descrição:</strong></p>
            <p id="modal-descricao"></p>

            <p><strong>Características:</strong></p>
            <p id="modal-caracteristicas"></p>

            <p><strong>Uso:</strong></p>
            <p id="modal-uso"></p>
        </div>
    </div>
</div>


<form action="/pesquisa" method="GET" class="form-pesquisa">
    <select id="tipoPesquisa" name="tipo" class="dropdown" required>
        <option value="nome"      <?= ($_GET['tipo'] ?? '') === 'nome' ? 'selected' : '' ?>>Pesquisar por Nome</option>
        <option value="uso"       <?= ($_GET['tipo'] ?? '') === 'uso' ? 'selected' : '' ?>>Pesquisar por Uso</option>
        <option value="catalogo"  <?= ($_GET['tipo'] ?? '') === 'catalogo' ? 'selected' : '' ?>>Mostrar Todas</option>
    </select>

    <input type="text" id="campoBusca" name="q"
           placeholder="Digite o termo..."
           value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">

    <button class="btn-pesquisar" type="submit">Buscar</button>
</form>

<?php

// só prosseguir se existe tipo
if (!isset($_GET['tipo'])) return;

$tipo = $_GET['tipo'];
$qRaw = trim($_GET['q'] ?? '');

// caso Mostrar Todas → ignora campo q
$db = new Database();

if ($tipo === "catalogo") {

    $sql = "SELECT * FROM plantas";
    $result = $db->query($sql);

} else {

    if ($qRaw === "") {
        echo "<p style='text-align:center;color:#b00;font-size:1.2rem;'>Digite algum termo.</p>";
        return;
    }

    $colunas = [
        "nome" => "nome",
        "uso"  => "uso"
    ];

    if (!isset($colunas[$tipo])) {
        echo "<p style='text-align:center;color:#b00;'>Tipo inválido.</p>";
        return;
    }

    $coluna = $colunas[$tipo];

    $sql = "SELECT * FROM plantas WHERE $coluna LIKE ?";
    $result = $db->prepare($sql, "s", ["%$qRaw%"]);
}

// EXIBIÇÃO DOS CARDS
echo '<section class="plant-grid" style="margin-top:40px;">';

if ($result->num_rows === 0) {
    echo "<p style='text-align:center;font-size:1.2rem;color:#444;'>Nenhum resultado encontrado.</p>";
} else {

    while ($row = $result->fetch_assoc()) {

        $nome = $row["nome"];
        $nomeCientifico = $row["nome_cientifico"];
        $descricao = $row["descricao"];

        // Normalização igual ao catálogo e cadastro
        $nomeNorm = iconv('UTF-8', 'ASCII//TRANSLIT', $nome);
        $nomeNorm = preg_replace('/[^A-Za-z0-9]/', '', $nomeNorm);
        $nomeNorm = strtolower($nomeNorm);
        $hash = md5($nomeNorm);

        $imgPath = "assets/img/plantas/$hash.jpg";
        if (!file_exists($imgPath)) {
            $imgPath = "assets/img/default_plant.jpg";
        }

        echo "
        <div class='plant-card'
            data-nome='".htmlspecialchars($nome)."'
            data-cientifico='".htmlspecialchars($nomeCientifico)."'
            data-descricao='".htmlspecialchars($descricao)."'
            data-caracteristicas='".htmlspecialchars($row['caracteristicas'])."'
            data-uso='".htmlspecialchars($row['uso'])."'
            data-imagem='$imgPath'>

            <div class='plant-image' style=\"background-image:url('$imgPath')\"></div>
            <h3 class='plant-name'>".htmlspecialchars($nome)."</h3>
        </div>";
    }
}

echo "</section>";
?>

<script>
// DELEGAÇÃO → funciona sempre
document.body.addEventListener("click", e => {
    const card = e.target.closest(".plant-card");
    if (!card) return;

    modalImg.src              = card.dataset.imagem;
    modalNome.textContent     = card.dataset.nome;
    modalCientifico.textContent = card.dataset.cientifico;
    modalDesc.textContent     = card.dataset.descricao;
    modalCarac.textContent    = card.dataset.caracteristicas;
    modalUso.textContent      = card.dataset.uso;

    modal.classList.remove("hidden");
});

document.querySelector(".modal-close").onclick = () => modal.classList.add("hidden");

modal.addEventListener("click", e => {
    if (e.target === modal) modal.classList.add("hidden");
});

// desativar input ao escolher catálogo
const tipo = document.getElementById("tipoPesquisa");
const campo = document.getElementById("campoBusca");

function atualizarCampo() {
    campo.disabled = tipo.value === "catalogo";
    if (campo.disabled) campo.value = "";
}

atualizarCampo();
tipo.addEventListener("change", atualizarCampo);
</script>

</body>
