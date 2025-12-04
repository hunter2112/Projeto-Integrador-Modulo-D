<?php
// Usa a classe Database do namespace App para interagir com o banco de dados.
use App\Database;

// Cria uma nova instância da classe Database, estabelecendo a conexão.
$db = new Database();
// Executa uma consulta SQL para selecionar todos os registros da tabela 'plantas'.
$result = $db->query("SELECT * FROM plantas");
?>

<body class="catalogo">
   
    <!-- Inclui o cabeçalho padrão da página. -->
    <?php require_once('./app/template/header.php'); ?>

<!-- Estrutura do Modal (Popup) que exibirá os detalhes da planta. Inicialmente, ele está oculto pela classe 'hidden'. -->
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
    <!-- Título principal da página do catálogo. -->
    <h1 id="titulo">Catálogo</h1>

    <!-- Seção que conterá a grade de cartões de plantas. -->
    <section class="plant-grid" id="plantas">

        <!-- Inicia um loop para percorrer cada linha (planta) retornada pela consulta ao banco. -->
        <?php while ($row = $result->fetch_assoc()): ?>
            
            <?php
                // Extrai os dados da linha atual para variáveis.
                // Nome comum
                $nome = $row['nome'];
                
                // Nome científico
                $nomeCientifico = $row['nome_cientifico'] ?? "";

                // Descrição
                $descricao = $row['descricao'] ?? "Sem descrição cadastrada.";

                // Gera um hash MD5 do nome da planta para criar um nome de arquivo de imagem único.

                $nomeNorm = iconv('UTF-8', 'ASCII//TRANSLIT', $nome);
                $nomeNorm = preg_replace('/[^A-Za-z0-9]/', '', $nomeNorm);
                $nomeNorm = strtolower($nomeNorm);
                $hash = md5($nomeNorm);
                $imgPath = "assets/img/plantas/{$hash}.jpg";
                // Verifica se o arquivo de imagem existe; se não, usa uma imagem padrão.
                if (!file_exists($imgPath)) {
                    $imgPath = "assets/img/default_plant.jpg";
                }
            ?>

            <!-- CARD DA PLANTA (com as mesmas classes e estrutura do HTML estático) -->
            <div class="plant-card"
     
     data-nome="<?= htmlspecialchars($nome) ?>"
     data-cientifico="<?= htmlspecialchars($nomeCientifico) ?>"
     data-descricao="<?= htmlspecialchars($descricao) ?>"
     data-caracteristicas="<?= htmlspecialchars($row['caracteristicas']) ?>"
     data-uso="<?= htmlspecialchars($row['uso']) ?>"
     data-imagem="<?= $imgPath ?>">

    <!-- Define a imagem de fundo do card. -->
    <div class="plant-image" style="background-image: url('<?= $imgPath ?>')"></div>

    <!-- Exibe o nome da planta. `htmlspecialchars` previne ataques XSS. -->
    <h3 class="plant-name"><?= htmlspecialchars($nome) ?></h3>

</div>


        <?php endwhile; // Fim do loop. ?>

    </section>

	<!-- Inclui o rodapé padrão da página. -->
	<?php require_once('./app/template/footer.php'); ?>

<script>
// Garante que o script só será executado após o carregamento completo do DOM.
document.addEventListener('DOMContentLoaded', () => {
  // Seleciona os elementos do modal que serão preenchidos com dados.
  const modal = document.getElementById('plant-modal');
  if (!modal) return; // não há modal — sai sem erro

  const modalImg = document.getElementById('modal-img');
  const modalNome = document.getElementById('modal-nome');
  const modalCientifico = document.getElementById('modal-cientifico');
  const modalDesc = document.getElementById('modal-descricao');
  const modalCarac = document.getElementById('modal-caracteristicas');
  const modalUso = document.getElementById('modal-uso');
  const closeBtn = modal.querySelector('.modal-close');

  // Usa "delegação de eventos" para ouvir cliques no corpo da página.
  // Isso garante que cliques em cards adicionados dinamicamente também funcionem.
  document.body.addEventListener('click', (ev) => {
    // Verifica se o elemento clicado (ou um de seus pais) é um '.plant-card'.
    const card = ev.target.closest('.plant-card');
    if (!card) return; // clique fora de card

    // Pega os dados dos atributos 'data-*' do card clicado.
    const imagem = card.dataset.imagem || '';
    const nome = card.dataset.nome || '';
    const cientifico = card.dataset.cientifico || '';
    const descricao = card.dataset.descricao || '';
    const caracteristicas = card.dataset.caracteristicas || '';
    const uso = card.dataset.uso || '';

    // Preenche os elementos do modal com os dados obtidos.
    if (modalImg) modalImg.src = imagem;
    if (modalNome) modalNome.textContent = nome;
    if (modalCientifico) modalCientifico.textContent = cientifico;
    if (modalDesc) modalDesc.textContent = descricao;
    if (modalCarac) modalCarac.textContent = caracteristicas;
    if (modalUso) modalUso.textContent = uso;

    // Mostra o modal removendo a classe 'hidden'.
    modal.classList.remove('hidden');
    // Previne qualquer comportamento padrão do clique (ex: seguir um link).
    ev.preventDefault();
  });

  // Adiciona a funcionalidade de fechar o modal ao clicar no botão 'X'.
  if (closeBtn) {
    closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
  }

  // Adiciona a funcionalidade de fechar o modal ao clicar na área externa (overlay).
  modal.addEventListener('click', (e) => {
    if (e.target === modal) modal.classList.add('hidden');
  });

  // Adiciona a funcionalidade de fechar o modal ao pressionar a tecla 'Escape'.
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
      modal.classList.add('hidden');
    }
  });
});
</script>

</body>
