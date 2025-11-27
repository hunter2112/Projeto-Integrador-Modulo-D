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
            // Importa o sistema de rotas e banco de dados
            require_once('./app/router.php');
            require_once('./app/database.php');

            // Cria uma instância do roteador
            $router = new App\Router();

            /*
             |-----------------------------------------------------
             | Declaração das rotas GET do site
             |-----------------------------------------------------
            */

            // Rota principal (home) -> exibe o catálogo de plantas
            $router->get('/', function() {
                // Inclui o arquivo do catálogo
                require_once('./app/content/catalogo.php');
            });

            // Rota para pesquisa
            $router->get('/pesquisa', function() {
                require_once('./app/content/pesquisa.php');
            });

            $router->get('/pesquisar', function() {
                require_once('./app/content/pesquisar.php');
            });

             $router->get('/cadastro', function() {
                require_once('./app/content/cadastro.php');
            });


            $router->get('/pesquisar', [App\Controllers\PesquisaController::class, 'buscar']);

            $router->post('/cadastrar_planta', [App\CadastroController::class, 'salvar']);


            /*
             |-----------------------------------------------------
             | resolve()
             | Identifica a rota acessada e executa o callback
             |-----------------------------------------------------
            */
            echo $router->resolve();
        ?>
    </main>
</body>

</html>
