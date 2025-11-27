<?php
// Nenhuma consulta necessária aqui, apenas exibição do formulário
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Planta - Herbário Digital</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/cadastro.css">
    <link rel="shortcut icon" type="image/png" href="assets/img/plant_icon.png">
</head>

<body class="cadastro">

    <!-- Cabeçalho -->
    <?php require_once('./app/template/header.php'); ?>

    <h1 id="titulo">Cadastro de Planta</h1>

    <form id="formPlanta" enctype="multipart/form-data" class="form-container">

        <!-- Nome comum -->
        <label for="nome">Nome comum:</label>
        <input type="text" id="nome" name="nome" class="texto" required>

        <!-- Nome científico -->
        <label for="nome_cientifico">Nome científico:</label>
        <input type="text" id="nome_cientifico" name="nome_cientifico" class="texto" required>

        <!-- Descrição -->
        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required></textarea>

        <!-- Características -->
        <label for="caracteristicas">Características:</label>
        <textarea id="caracteristicas" name="caracteristicas" required></textarea>

        <!-- Uso -->
        <label for="uso">Uso (opcional):</label>
        <textarea id="uso" name="uso"></textarea>

        <!-- Upload da imagem -->
        <label for="imagem" class="upload-label">Escolher imagem</label>
        <input type="file" id="imagem" name="imagem" accept="image/*" hidden>

        <p id="file-name" class="file-name">Nenhum arquivo selecionado</p>

        <!-- Botão -->
        <input type="submit" class="botao_cadastro" value="Cadastrar Planta">
    </form>

    <div id="mensagem">
