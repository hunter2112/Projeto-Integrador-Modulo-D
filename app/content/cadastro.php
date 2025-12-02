<?php
// Este arquivo é responsável apenas por renderizar o formulário de cadastro.
// Nenhuma lógica de banco de dados é executada aqui.
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <!-- Título que aparece na aba do navegador -->
    <title>Cadastro de Planta - Herbário Digital</title>

    <!-- Links para as folhas de estilo CSS -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/cadastro.css">
    <!-- Ícone da página (favicon) -->
    <link rel="shortcut icon" type="image/png" href="assets/img/plant_icon.png">
</head>

<body class="cadastro">

    <!-- Inclui o cabeçalho padrão da página -->
    <?php require_once('./app/template/header.php'); ?>

    <!-- Título principal da página de cadastro -->
    <h1 id="titulo">Cadastro de Planta</h1>

    <!-- Formulário de cadastro de plantas.
         enctype="multipart/form-data" é essencial para permitir o envio de arquivos (imagens). -->
    <form id="formPlanta" enctype="multipart/form-data" class="form-container">

    <label for="nome">Nome comum:</label>
    <input type="text" id="nome" name="nome" class="texto" required>

    <label for="nome_cientifico">Nome científico:</label>
    <input type="text" id="nome_cientifico" name="nome_cientifico" class="texto" required>

    <label for="descricao">Descrição:</label>
    <textarea id="descricao" name="descricao" required></textarea>

    <label for="caracteristicas">Características:</label>
    <textarea id="caracteristicas" name="caracteristicas" required></textarea>

    <label for="uso">Uso (opcional):</label>
    <textarea id="uso" name="uso"></textarea>

    <!-- Label estilizado que funciona como um botão para o input de arquivo -->
    <label for="imagem" class="upload-label">Escolher imagem</label>
    <!-- Input de arquivo real, que fica escondido. O 'accept' filtra os tipos de arquivo permitidos. -->
    <input type="file" id="imagem" name="imagem" accept="image/*" hidden>

    <!-- Parágrafo para exibir o nome do arquivo selecionado -->
    <p id="file-name" class="file-name">Nenhum arquivo selecionado</p>

    <!-- Botão para submeter o formulário -->
    <input type="submit" class="botao_cadastro" value="Cadastrar Planta">
</form>

<!-- Div onde as mensagens de sucesso ou erro serão exibidas -->
<div id="mensagem"></div>

<script>
// Adiciona um "ouvinte" ao campo de upload de imagem.
document.getElementById("imagem").addEventListener("change", e => {
    // Quando um arquivo é selecionado, atualiza o texto do parágrafo #file-name.
    // Usa um operador ternário para verificar se algum arquivo foi selecionado.
    document.getElementById("file-name").textContent =
        e.target.files.length ? e.target.files[0].name : "Nenhum arquivo selecionado";
});

// Adiciona um "ouvinte" para o evento de submissão do formulário.
document.getElementById("formPlanta").addEventListener("submit", function(e) {
    // Previne o comportamento padrão do formulário, que é recarregar a página.
    e.preventDefault();

    // Cria um objeto FormData com todos os dados do formulário, incluindo a imagem.
    const formData = new FormData(this);

    // Envia os dados para a rota '/cadastrar_planta' usando o método POST (AJAX).
    fetch("/cadastrar_planta", {
        method: "POST",
        body: formData
    })
    // Converte a resposta do servidor para texto.
    .then(r => r.text())
    // Se a requisição for bem-sucedida:
    .then(msg => {
        // Exibe a mensagem de sucesso retornada pelo servidor.
        document.getElementById("mensagem").innerHTML = `<p class="sucesso">${msg}</p>`;
        // Limpa todos os campos do formulário.
        this.reset();
        // Reseta o nome do arquivo exibido.
        document.getElementById("file-name").textContent = "Nenhum arquivo selecionado";
    })
    // Se ocorrer um erro na requisição:
    .catch(err => {
        // Exibe uma mensagem de erro.
        document.getElementById("mensagem").innerHTML = `<p class="erro">Erro: ${err}</p>`;
    });
});
</script>

    <!-- Inclui o rodapé padrão da página -->
    <?php require_once('./app/template/footer.php'); ?>

</body>
</html>
