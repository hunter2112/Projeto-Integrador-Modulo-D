<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Herbário Digital</title>

    <link rel="stylesheet" href="./assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <a href="/cadastro">Cadastro</a>
        <a href="/pesquisa">Pesquisa</a>
        <a href="/catalogo">Catálogo</a>
    </header>

    <?php
        require_once('./app/router.php');
        $router     = new App\Router();

        $router->get('/', function() {
            echo '<h1> Herbario Digital </h1>';
        });

        echo $router->resolve();

    ?>
</body>

</html>