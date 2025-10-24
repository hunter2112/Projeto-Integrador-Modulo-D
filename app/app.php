<?php

    require_once('./app/router.php');
    require_once('./app/database.php');
    $router     = new App\Router();
    $db = new App\Database();

    $router->get('/', function() {
        echo '<h1> Herbario Digital </h1>';
    });

    echo $router->resolve();

?>