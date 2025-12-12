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
            <button type="button" class="btn-remover" onclick="limparImagem()">✖ Remover</button>
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
</div>

<style>
.reconhecimento-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
}

.reconhecimento-header {
    text-align: center;
    margin-bottom: 30px;
}

.reconhecimento-header h1 {
    color: #2d5016;
    margin-bottom: 10px;
}

.upload-area {
    border: 3px dashed #4a7c2c;
    border-radius: 15px;
    padding: 60px 20px;
    text-align: center;
    background: #f0f8f0;
    cursor: pointer;
    transition: all 0.3s;
}

.upload-area:hover {
    background: #e8f5e8;
    border-color: #2d5016;
}

.upload-icon {
    font-size: 60px;
    margin-bottom: 20px;
}

.btn-upload, .btn-analisar {
    background: #4a7c2c;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 15px;
    transition: background 0.3s;
}

.btn-upload:hover, .btn-analisar:hover {
    background: #2d5016;
}

.preview-area {
    text-align: center;
    margin-top: 30px;
}

.preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.btn-remover {
    background: #dc3545;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.btn-remover:hover {
    background: #c82333;
}

.preview-img {
    max-width: 100%;
    max-height: 400px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.loading-area {
    text-align: center;
    padding: 40px;
}

.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #4a7c2c;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.resultados-area {
    margin-top: 30px;
}

.resultado-secao {
    background: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.labels-lista {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.label-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: #f0f8f0;
    border-radius: 8px;
}

.label-badge {
    background: #4a7c2c;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 14px;
}

.labels-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px;
}

.label-grid-item {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 8px;
    text-align: center;
}

.especies-lista {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.especie-item {
    padding: 12px;
    background: #e3f2fd;
    border-radius: 8px;
    border-left: 4px solid #2196f3;
}

.cores-lista {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.cor-item {
    width: 80px;
    height: 80px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    border: 2px solid #ddd;
}

.instrucoes {
    background: #fff9e6;
    padding: 20px;
    border-radius: 10px;
    margin-top: 30px;
    border-left: 4px solid #ffc107;
}

.aviso {
    background: white;
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
}
</style>

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