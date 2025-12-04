<?php
// Nenhuma lógica aqui — somente o formulário
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

    <?php require_once('./app/template/header.php'); ?>

    <h1 id="titulo">Cadastro de Planta</h1>

    <form id="formPlanta" enctype="multipart/form-data" class="form-container">

        <label for="nome">Nome comum:</label>
        <input type="text" id="nome" name="nome" class="texto" required>

        <label for="nome_cientifico">Nome científico:</label>
        <input type="text" id="nome_cientifico" name="nome_cientifico" class="texto">

        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required></textarea>

        <label for="caracteristicas">Características:</label>
        <textarea id="caracteristicas" name="caracteristicas" required></textarea>

        <label for="uso">Uso (opcional):</label>
        <textarea id="uso" name="uso"></textarea>

        <label for="imagem" class="upload-label">Escolher imagem</label>
        <input type="file" id="imagem" name="imagem" accept="image/*" hidden>

        <p id="file-name" class="file-name">Nenhum arquivo selecionado</p>

        <input type="submit" class="botao_cadastro" value="Cadastrar Planta">
    </form>

    <div id="mensagem"></div>

    <script>
        document.getElementById("formPlanta").addEventListener("submit", function(e){
  e.preventDefault();
  const form = this;
  const fd = new FormData(form);
  document.getElementById('mensagem').innerHTML = '<p>Enviando...</p>';

  fetch('/cadastrar_planta', {
    method: 'POST',
    body: fd
  })
  .then(async response => {
    const text = await response.text();
    if (!response.ok) {
      // mostra a mensagem de erro vinda do servidor (400/500)
      document.getElementById('mensagem').innerHTML = `<p class="erro">${text}</p>`;
      throw new Error(text);
    }
    // ok
    document.getElementById('mensagem').innerHTML = `<p class="sucesso">${text}</p>`;
    form.reset();
    document.getElementById('file-name').textContent = 'Nenhum arquivo selecionado';
  })
  .catch(err => {
    console.error('Fetch error:', err);
  });
});

    </script>

    <?php require_once('./app/template/footer.php'); ?>

</body>
</html>
