<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <!-- Título da aba do navegador -->
  <title>Herbário Digital</title>
  
  <!-- Importa o CSS principal do site -->
  <link rel="stylesheet" href="assets/css/base.css" />
  <link rel="stylesheet" href="assets/css/pesquisa.css" />
  <link rel="stylesheet" href="assets/css/cadastro.css" />

  <!-- Ícone da aba (favicon) -->
  <link rel="shortcut icon" type="imagex/png" href="assets/img/plant_icon.png" />
</head>

<body class="main">
    <!-- Importa o cabeçalho a partir do template -->
    <?php require_once('./app/template/header.php'); ?>

    <!-- Área principal do site -->
    <main class="text-white bg-white row g-4 d-flex justify-content-center">

        <!-- Container de título -->
        <div class="container">
            <h1><b>Herbário Digital</b></h1>
        </div>
            
  </main>
        <?php

require_once './app/router.php';
require_once './app/database.php';

use App\Router;

$router = new Router();

/*
|--------------------------------------------------------------------------
| ROTAS DO SITE
|--------------------------------------------------------------------------
*/

// Página inicial → catálogo
$router->get('/', function () {
    require './app/content/catalogo.php';
});

// Página de pesquisa
$router->get('/pesquisar', function () {
    require './app/content/pesquisa.php';
});

// Página de cadastro
$router->get('/cadastro', function () {
    require './app/content/cadastro.php';
});

// Rota para cadastrar planta (POST)
$router->post('/cadastrar_planta', [App\Controllers\PlantaController::class, 'store']);


/*
|--------------------------------------------------------------------------
| Executar o Router
|--------------------------------------------------------------------------
*/

echo $router->resolve();

?>

    </main>
</body>

</html>
