<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Herbário Digital</title>
    <link rel="shortcut icon" type="imagex/png" href="assets/img/plant_icon.png"/>

    <link rel="stylesheet" href="./assets/css/styles.css">

    <!-- Bootstrap css -->    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</head>

<body>
    <!-- Importar Cabecalho -->
    <?php require_once('./app/template/header.php'); ?>

    <main class="text-white bg-white row g-4 justify-content-md-center"> 
        <section class="section-style-2"> 

            <?php
                require_once('./app/template/title.php');
                require_once('./app/app.php');
            ?>    
        
        </section>
    </main>

    <?php require_once('./app/template/footer.php'); ?>
</body>

</html>