<?php
header('Content-Type: text/html; charset=utf-8');

// carregar classes/rotas/DB (ajuste caminhos se necessário)
require_once __DIR__ . '/app/router.php';
require_once __DIR__ . '/app/database.php';
require_once __DIR__ . '/app/controllers/cadastro_controller.php';

use App\Router;
use App\CadastroController;

// descobrir caminho atual (normalizado, sem querystring)
$rawPath = $_SERVER['REQUEST_URI'] ?? '/';
$path = explode('?', $rawPath)[0];

// criar roteador e registrar rotas
$router = new Router();

// rota: página inicial (ex.: catálogo ou home)
$router->get('/', function () {
    // escolhi mostrar o catálogo por padrão na home

});

// rota: pesquisa (renderiza resultados)
$router->get('/pesquisa', function () {
    require __DIR__ . '/app/content/pesquisa.php';
});

// rota: página de cadastro (somente formulário)
$router->get('/cadastro', function () {
    require __DIR__ . '/app/content/cadastro.php';
});

// rota: processar cadastro (POST)
$router->post('/cadastrar_planta', [CadastroController::class, 'salvar']);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Herbário Digital</title>

  <link rel="stylesheet" href="assets/css/base.css" />
  <link rel="stylesheet" href="assets/css/pesquisa.css" />
  <link rel="stylesheet" href="assets/css/cadastro.css" />
  <link rel="stylesheet" href="assets/css/catalogo.css" />
  <link rel="shortcut icon" type="image/png" href="assets/img/site_logo2.png" />
</head>
<body class="main">

  <?php require_once __DIR__ . '/app/template/header.php'; ?>

  <main class="content-wrap">

<div class="img_titulo">
    <img src="assets/img/site_title.png" alt="Herbário Digital">
</div>
    
    

    <!-- Mostrar o FORMULÁRIO DE PESQUISA APENAS na HOME (path == '/') -->
    <?php if ($path === '/'): ?>
      <form action="/pesquisa" method="GET" class="form-pesquisa">
        <select id="tipoPesquisaHome" name="tipo" class="dropdown" required>
          <option value="nome">Pesquisar por Nome</option>
          <option value="uso">Pesquisar por Uso</option>
          <option value="catalogo">Mostrar todas</option>
        </select>

        <input type="text" id="campoBuscaHome" name="q" placeholder="Digite o termo...">

        <button class="btn-pesquisar" type="submit">Buscar</button>
      </form>
    <?php endif; ?>

    <!-- Aqui o roteador inclui o conteúdo da rota solicitada -->
    <section class="route-content">
      <?php
        // Executa a rota adequada (isto vai incluir catalogo.php, pesquisa.php ou cadastro.php)
        echo $router->resolve();
      ?>
    </section>

  </main>

  <?php require_once __DIR__ . '/app/template/footer.php'; ?>

  <!-- Script: desabilitar campo quando escolher Mostrar todas (apenas para o form na home) -->
  <script>
    (function(){
      const tipoHome = document.getElementById("tipoPesquisaHome");
      const campoHome = document.getElementById("campoBuscaHome");
      if (!tipoHome || !campoHome) return;

      function atualizarCampoHome() {
        if (tipoHome.value === "catalogo") {
          campoHome.disabled = true;
          campoHome.value = "";
        } else {
          campoHome.disabled = false;
        }
      }

      atualizarCampoHome();
      tipoHome.addEventListener("change", atualizarCampoHome);
    })();
  </script>

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

  <script src="scripts/underline.js"></script>

  <script>
    document.getElementById("hamburgerBtn").addEventListener("click", function () {
    document.getElementById("navLinks").classList.toggle("show");
});
</script>

</body>
</html>
