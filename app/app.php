<?php
    require_once('./app/router.php');
    $router     = new App\Router();

    $router->get('/', function() {
        echo '<h1> Herbario Digital </h1>';
    });

    echo $router->resolve();

?>