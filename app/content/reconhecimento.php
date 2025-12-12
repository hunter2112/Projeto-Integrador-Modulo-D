<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/recon.css">
</head>
<body>
    <div class="reconhecimento-container">
    <div class="reconhecimento-header">
        <h1>Reconhecimento de Plantas</h1>
        <p>Faça upload de uma foto para identificar a planta usando inteligência artificial</p>
    </div>

    <!-- Área de Upload -->
    <div id="upload-area" class="upload-area">
        <div class="upload-icon">📷</div>
        <h3>Carregar Foto da Planta</h3>
        <p>Clique ou arraste uma imagem aqui</p>
        <input type="file" id="input-imagem" accept="image/*" style="display: none;">
        <button type="button" class="btn-upload" onclick="document.getElementById('input-imagem').click();">
            Selecionar Imagem
        </button>
    </div>

    <!-- Preview da Imagem -->
    <div id="preview-area" class="preview-area" style="display: none;">
        <div class="preview-header">
            <h3>Imagem Carregada</h3>
            <button type="button" class="btn-remover" onclick="limparImagem()">Remover</button>
        </div>
        <img id="preview-img" src="" alt="Preview" class="preview-img">
        <button type="button" class="btn-analisar" onclick="analisarImagem()">
             Identificar Planta
        </button>
    </div>

    <!-- Loading -->
    <div id="loading-area" class="loading-area" style="display: none;">
        <div class="spinner"></div>
        <p>Analisando imagem...</p>
    </div>

    <!-- Resultados -->
    <div id="resultados-area" class="resultados-area" style="display: none;">
        <h2>Resultados da Análise</h2>
        
        <!-- Labels de Planta -->
        <div id="labels-planta-section" class="resultado-secao" style="display: none;">
            <h3>Características Identificadas</h3>
            <div id="labels-planta-lista" class="labels-lista"></div>
        </div>

        <!-- Possíveis Espécies -->
        <div id="especies-section" class="resultado-secao" style="display: none;">
            <h3>Possíveis Identificações</h3>
            <div id="especies-lista" class="especies-lista"></div>
        </div>

        <!-- Todas as Labels -->
        <div id="labels-section" class="resultado-secao">
            <h3>Todas as Identificações</h3>
            <div id="labels-lista" class="labels-grid"></div>
        </div>

        <!-- Cores Dominantes -->
        <div id="cores-section" class="resultado-secao" style="display: none;">
            <h3>Cores Predominantes</h3>
            <div id="cores-lista" class="cores-lista"></div>
        </div>
    </div>

<script>
let imagemSelecionada = null;

// Drag and drop
const uploadArea = document.getElementById('upload-area');
uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.style.background = '#e8f5e8';
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.style.background = '#f0f8f0';
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.style.background = '#f0f8f0';
    const arquivo = e.dataTransfer.files[0];
    if (arquivo && arquivo.type.startsWith('image/')) {
        processarImagem(arquivo);
    }
});

// Seleção de arquivo
document.getElementById('input-imagem').addEventListener('change', function(e) {
    const arquivo = e.target.files[0];
    if (arquivo) {
        processarImagem(arquivo);
    }
});

function processarImagem(arquivo) {
    imagemSelecionada = arquivo;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('preview-img').src = e.target.result;
        document.getElementById('upload-area').style.display = 'none';
        document.getElementById('preview-area').style.display = 'block';
        document.getElementById('resultados-area').style.display = 'none';
    };
    reader.readAsDataURL(arquivo);
}

function limparImagem() {
    imagemSelecionada = null;
    document.getElementById('input-imagem').value = '';
    document.getElementById('upload-area').style.display = 'block';
    document.getElementById('preview-area').style.display = 'none';
    document.getElementById('resultados-area').style.display = 'none';
}

async function analisarImagem() {
    if (!imagemSelecionada) return;

    // Mostrar loading
    document.getElementById('preview-area').style.display = 'none';
    document.getElementById('loading-area').style.display = 'block';
    document.getElementById('resultados-area').style.display = 'none';

    try {
        const formData = new FormData();
        formData.append('imagem', imagemSelecionada);

        const response = await fetch('/api/reconhecer', {
            method: 'POST',
            body: formData
        });

        const resultado = await response.json();

        if (!resultado.sucesso) {
            throw new Error(resultado.erro || 'Erro ao analisar imagem');
        }

        exibirResultados(resultado.dados);

    } catch (erro) {
        alert('Erro: ' + erro.message);
        document.getElementById('preview-area').style.display = 'block';
    } finally {
        document.getElementById('loading-area').style.display = 'none';
    }
}

function exibirResultados(dados) {
    document.getElementById('resultados-area').style.display = 'block';

    // Labels de planta
    if (dados.labelsPlanta && dados.labelsPlanta.length > 0) {
        document.getElementById('labels-planta-section').style.display = 'block';
        const lista = document.getElementById('labels-planta-lista');
        lista.innerHTML = dados.labelsPlanta.map(label => `
            <div class="label-item">
                <span>${label.descricao}</span>
                <span class="label-badge">${label.confianca}%</span>
            </div>
        `).join('');
    }

    // Web entities (espécies)
    if (dados.webEntities && dados.webEntities.length > 0) {
        document.getElementById('especies-section').style.display = 'block';
        const lista = document.getElementById('especies-lista');
        lista.innerHTML = dados.webEntities.slice(0, 5).map(entity => `
            <div class="especie-item">
                ${entity.descricao}
                ${entity.confianca ? `<small>(${entity.confianca}%)</small>` : ''}
            </div>
        `).join('');
    }

    // Todas as labels
    const lista = document.getElementById('labels-lista');
    lista.innerHTML = dados.labels.slice(0, 12).map(label => `
        <div class="label-grid-item">
            <div style="font-weight: bold;">${label.descricao}</div>
            <div style="font-size: 12px; color: #666;">${label.confianca}%</div>
        </div>
    `).join('');

    // Cores
    if (dados.cores && dados.cores.length > 0) {
        document.getElementById('cores-section').style.display = 'block';
        const lista = document.getElementById('cores-lista');
        lista.innerHTML = dados.cores.map(cor => `
            <div class="cor-item" style="background: ${cor.rgb};" title="${cor.porcentagem}%"></div>
        `).join('');
    }

    // Scroll suave até resultados
    document.getElementById('resultados-area').scrollIntoView({ behavior: 'smooth', block: 'start' });
}
</script>
</body>
</html>
