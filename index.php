<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Herbário Digital</title>
  <!--Faz com que as configuações de estilo sejam "puxadas" do arquivo "styles.css"-->
  <link rel="stylesheet" href="assets/css/base.css" />
  <!--Link que adiciona um ícone à guia da página-->
  <link rel="shortcut icon" type="imagex/png" href="assets/img/plant_icon.png" />
</head>

<body class="main">
    <!-- Importar Cabecalho -->
    <?php require_once('./app/template/header.php'); ?>

    <main class="text-white bg-white row g-4 d-flex justify-content-center">

        <div class="container">
            <h1><b>Herbário Digital</b></h1>
        </div>
            
        <?php
            require_once('./app/router.php');
            require_once('./app/database.php');
            $router     = new App\Router();
            $db         = new App\Database();

            $router->get('/', function() {
                echo "<h3>Catalogo</h3>";
            });

            $router->get('/pesquisa:uso', function() {
                echo '<h1> Pesquisa por Uso </h1>';
            });

            $router->get('/pesquisa:nome', function() {
                echo '<h1> Pesquisa por Nome </h1>';
            });

            echo $router->resolve();
        ?>
    </main>

    <?php require_once('./app/template/footer.php'); ?>
</body>

</html>