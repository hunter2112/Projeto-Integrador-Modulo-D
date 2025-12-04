<?php
// Usa a classe Database do namespace App para interagir com o banco de dados.
use App\Database;
?>

<body class="pesquisa">

    <!-- Inclui o cabeçalho padrão da página. -->
    <?php require_once('./app/template/header.php'); ?>

<!-- Estrutura do Modal (Popup) que exibirá os detalhes da planta. Inicialmente, ele está oculto. -->
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


    <!-- Título principal da página de pesquisa. -->
    <h1 id="titulo">Pesquisar Plantas</h1>

    <!-- Formulário de pesquisa que envia os dados via método GET para a própria página. -->
    <form action="/pesquisa" method="GET" class="form-pesquisa">

        <!-- Menu dropdown para selecionar o critério de busca (nome ou uso). -->
        <select name="tipo" class="dropdown" required>
            <option value="nome" <?= ($_GET['tipo'] ?? '') === 'nome' ? 'selected' : '' ?>>Pesquisar por Nome</option>
            <option value="uso" <?= ($_GET['tipo'] ?? '') === 'uso' ? 'selected' : '' ?>>Pesquisar por Uso</option>
        </select>

        <input type="text" name="q" placeholder="Digite o termo..."
               value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" required>

        <button class="btn-pesquisar" type="submit">Buscar</button>
    </form>


<?php
// Verifica se os parâmetros 'q' (query) e 'tipo' foram enviados via GET (ou seja, se o formulário foi submetido).
if (isset($_GET['q'], $_GET['tipo'])) {

    // Pega os valores do formulário.
    $tipo = $_GET['tipo'];
    // Adiciona '%' no início e no fim do termo de busca para usar com o operador LIKE do SQL.
    $q = '%' . $_GET['q'] . '%';

    // Cria uma nova instância da classe Database.
    $db = new Database();

    // Mapeia o valor do 'tipo' para o nome real da coluna no banco de dados.
    // Isso é uma medida de segurança para evitar injeção de SQL, garantindo que apenas colunas válidas sejam usadas na consulta.
    $colunas = [
        'nome' => 'nome',
        'uso'  => 'uso'
    ];

    if (!isset($colunas[$tipo])) {
        echo "<p style='color:red;'>Tipo inválido.</p>";
        return;
    }

    $coluna = $colunas[$tipo];

    // Prepara e executa a consulta SQL usando um prepared statement para segurança.
    $sql = "SELECT * FROM plantas WHERE $coluna LIKE ?";
    $result = $db->prepare($sql, "s", [$q]);

    // Inicia a seção que exibirá os resultados.
    echo '<section class="plant-grid" style="margin-top: 40px;">';

    // Verifica se a consulta retornou algum resultado.
    if ($result->num_rows > 0) {

        // Loop para percorrer cada planta encontrada.
        while ($row = $result->fetch_assoc()) {

            // Extrai os dados da planta.
            $nome = $row['nome'];
            $nomeCientifico = $row['nome_cientifico'] ?? "";
            $descricao = $row['descricao'] ?? "Sem descrição cadastrada.";

            // Gera o caminho da imagem com base em um hash do nome, ou usa uma imagem padrão.
            $nomeNorm = iconv('UTF-8', 'ASCII//TRANSLIT', $nome);
            $nomeNorm = preg_replace('/[^A-Za-z0-9]/', '', $nomeNorm);
            $nomeNorm = strtolower($nomeNorm);
            $hash = md5($nomeNorm);
            $imgPath = "assets/img/plantas/{$hash}.jpg";

            if (!file_exists($imgPath)) {
                $imgPath = "assets/img/default_plant.jpg";
            }

            // Monta e exibe o HTML do cartão da planta, usando htmlspecialchars para prevenir XSS.
            // Os dados são armazenados em atributos 'data-*' para serem usados pelo JavaScript do modal.
            echo "
<div class='plant-card' style='margin:auto;max-width:500px'
     data-nome='".htmlspecialchars($nome)."'
     data-cientifico='".htmlspecialchars($nomeCientifico)."'
     data-descricao='".htmlspecialchars($descricao)."'
     data-caracteristicas='".htmlspecialchars($row['caracteristicas'])."'
     data-uso='".htmlspecialchars($row['uso'])."'
     data-imagem='$imgPath'>

    <div class='plant-image' style=\"background-image: url('$imgPath')\"></div>
    <h3 class='plant-name'>".htmlspecialchars($nome)."</h3>

</div>";

            if (!empty($nomeCientifico)) {
                
            }
        }

    } else {
        // Se nenhum resultado for encontrado, exibe uma mensagem.
        echo "<p style='text-align:center; font-size:1.2rem; color:#444;'>Nenhum resultado encontrado.</p>";
    }

    echo '</section>';
}
?>

</body>
<script>
// Seleciona todos os cartões de planta e os elementos do modal.
// NOTA: Este script é quase idêntico ao de 'catalogo.php'.
const cards = document.querySelectorAll(".plant-card");
const modal = document.getElementById("plant-modal");

const modalImg = document.getElementById("modal-img");
const modalNome = document.getElementById("modal-nome");
const modalCientifico = document.getElementById("modal-cientifico");
const modalDesc = document.getElementById("modal-descricao");
const modalCarac = document.getElementById("modal-caracteristicas");
const modalUso = document.getElementById("modal-uso");

// Adiciona um evento de clique a cada cartão de resultado.
cards.forEach(card => {
    card.addEventListener("click", () => {

        // Quando um cartão é clicado, preenche o modal com os dados dos atributos 'data-*' do cartão.
        modalImg.src = card.dataset.imagem;
        modalNome.textContent = card.dataset.nome;
        modalCientifico.textContent = card.dataset.cientifico;
        modalDesc.textContent = card.dataset.descricao;
        modalCarac.textContent = card.dataset.caracteristicas;
        modalUso.textContent = card.dataset.uso;

        // Exibe o modal removendo a classe 'hidden'.
        modal.classList.remove("hidden");
    });
});

// Adiciona um evento de clique ao botão 'X' para fechar o modal.
document.querySelector(".modal-close").onclick = () => {
    modal.classList.add("hidden");
};

// Adiciona um evento para fechar o modal se o usuário clicar na área externa (overlay).
modal.onclick = e => {
    if (e.target === modal) modal.classList.add("hidden");
};
</script>
