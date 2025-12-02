<?php
header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
<!-- Define o documento como HTML5 e o idioma como Português do Brasil -->
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <!-- Título da aba do navegador -->
  <title>Herbário Digital</title>
  
  <!-- Importa as folhas de estilo CSS para a aplicação -->
  <link rel="stylesheet" href="assets/css/base.css" />
  <link rel="stylesheet" href="assets/css/pesquisa.css" />
  <link rel="stylesheet" href="assets/css/cadastro.css" />
  <link rel="stylesheet" href="assets/css/catalogo.css" />

  <!-- Ícone da aba (favicon) -->
  <link rel="shortcut icon" type="imagex/png" href="assets/img/plant_icon.png" />
</head>

<body class="main">
    <!-- Inclui o cabeçalho da página a partir de um arquivo de template. -->
    <?php require_once('./app/template/header.php'); ?>

    <!-- Área principal do site -->
    <main class="text-white bg-white row g-4 d-flex justify-content-center">

        <!-- Container de título -->
        <div class="container">
            <!-- Título principal da página -->
            <h1><b>Herbário Digital</b></h1>
        </div>
            
  </main>
        <?php

// Inclui os arquivos essenciais para o funcionamento da aplicação.
// router.php: Gerencia as rotas (URLs) da aplicação.
require_once './app/router.php';
// database.php: Provavelmente contém a lógica de conexão com o banco de dados.
require_once './app/database.php';
// cadastro_controller.php: Controla a lógica de negócio para o cadastro de plantas.
require_once './app/controllers/cadastro_controller.php';

// Utiliza os namespaces para evitar conflitos de nomes de classes.
use App\Router;
use App\CadastroController;

// Cria uma nova instância do roteador.
$router = new Router();

// Define a rota para a página inicial ('/').
// Quando acessada via método GET, ela inclui o conteúdo da página de catálogo.
$router->get('/', function () {
    require './app/content/catalogo.php';
});

// Define a rota para a página de pesquisa ('/pesquisa').
// Quando acessada via método GET, ela inclui o conteúdo da página de pesquisa.
$router->get('/pesquisa', function () {
    require './app/content/pesquisa.php';
});

// Define a rota para a página de cadastro ('/cadastro').
// Quando acessada via método GET, ela inclui o conteúdo da página de cadastro.
$router->get('/cadastro', function () {
    require './app/content/cadastro.php';
});

// Define a rota para o processamento do formulário de cadastro ('/cadastrar_planta').
// Quando a requisição é do tipo POST, chama o método 'salvar' da classe 'CadastroController'.
$router->post('/cadastrar_planta', [CadastroController::class, 'salvar']);

// Executa o roteador para encontrar e renderizar a rota correspondente à URL atual.
echo $router->resolve();
?>

    </main>

</body>

<!-- Inclui o rodapé da página a partir de um arquivo de template. -->
<?php require_once('./app/template/footer.php'); ?>
</html>
