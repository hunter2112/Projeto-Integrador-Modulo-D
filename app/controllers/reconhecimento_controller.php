<?php

namespace App;

class ReconhecimentoController
{
    /**
     * Processa o upload e reconhecimento da imagem
     */
    public static function analisar()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            // Pegar API Key do .env
            $apiKey = EnvLoader::get('GOOGLE_VISION_API_KEY');
            
            if (empty($apiKey)) {
                throw new \Exception('API Key não configurada no arquivo .env');
            }

            // Validar se foi enviado um arquivo
            if (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception('Nenhuma imagem foi enviada ou ocorreu um erro no upload.');
            }

            $arquivo = $_FILES['imagem'];

            // Validar tipo de arquivo
            $tiposPermitidos = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!in_array($arquivo['type'], $tiposPermitidos)) {
                throw new \Exception('Tipo de arquivo não permitido. Use JPG, PNG ou WEBP.');
            }

            // Validar tamanho (máximo 4MB)
            if ($arquivo['size'] > 4 * 1024 * 1024) {
                throw new \Exception('Arquivo muito grande. Tamanho máximo: 4MB.');
            }

            // Ler o arquivo e converter para base64
            $imagemConteudo = file_get_contents($arquivo['tmp_name']);
            $imagemBase64 = base64_encode($imagemConteudo);

            // Chamar a API do Google Cloud Vision
            $resultado = self::chamarGoogleVisionAPI($imagemBase64, $apiKey);

            // Processar e retornar resultados
            $dadosProcessados = self::processarResultados($resultado);

            echo json_encode([
                'sucesso' => true,
                'dados' => $dadosProcessados
            ], JSON_UNESCAPED_UNICODE);

        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode([
                'sucesso' => false,
                'erro' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        
        exit;
    }

    /**
     * Chama a API do Google Cloud Vision
     */
    private static function chamarGoogleVisionAPI($imagemBase64, $apiKey)
    {
        $url = 'https://vision.googleapis.com/v1/images:annotate?key=' . $apiKey;

        $dados = [
            'requests' => [
                [
                    'image' => [
                        'content' => $imagemBase64
                    ],
                    'features' => [
                        ['type' => 'LABEL_DETECTION', 'maxResults' => 15],
                        ['type' => 'WEB_DETECTION', 'maxResults' => 10],
                        ['type' => 'IMAGE_PROPERTIES'],
                        ['type' => 'OBJECT_LOCALIZATION', 'maxResults' => 5]
                    ]
                ]
            ]
        ];

        $opcoes = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => json_encode($dados),
                'timeout' => 30,
                'ignore_errors' => true
            ]
        ];

        $contexto = stream_context_create($opcoes);
        $resposta = @file_get_contents($url, false, $contexto);

        if ($resposta === false) {
            throw new \Exception('Erro ao comunicar com a API do Google Cloud Vision. Verifique sua conexão e API Key.');
        }

        $resultado = json_decode($resposta, true);

        // Verificar erros da API
        if (isset($resultado['error'])) {
            $mensagemErro = $resultado['error']['message'] ?? 'Erro desconhecido da API';
            throw new \Exception('Erro Google API: ' . $mensagemErro);
        }

        if (isset($resultado['responses'][0]['error'])) {
            throw new \Exception('Erro da API: ' . $resultado['responses'][0]['error']['message']);
        }

        if (!isset($resultado['responses'][0])) {
            throw new \Exception('Resposta inválida da API Google Vision.');
        }

        return $resultado['responses'][0];
    }

    /**
     * Processa os resultados da API
     */
    private static function processarResultados($resultado)
    {
        $processado = [
            'labels' => [],
            'labelsPlanta' => [],
            'webEntities' => [],
            'cores' => [],
            'objetos' => []
        ];

        // Processar labels (etiquetas)
        if (isset($resultado['labelAnnotations'])) {
            foreach ($resultado['labelAnnotations'] as $label) {
                $processado['labels'][] = [
                    'descricao' => $label['description'],
                    'confianca' => round($label['score'] * 100, 1)
                ];
            }

            // Filtrar labels relacionadas a plantas
            $palavrasChavePlanta = [
                'plant', 'flower', 'leaf', 'tree', 'botanical', 'flora', 
                'vegetation', 'herb', 'planta', 'flor', 'folha', 'árvore',
                'vegetal', 'botânica', 'erva', 'petal', 'stem', 'root',
                'grass', 'shrub', 'seed', 'blossom', 'foliage'
            ];

            foreach ($resultado['labelAnnotations'] as $label) {
                $desc = strtolower($label['description']);
                foreach ($palavrasChavePlanta as $palavra) {
                    if (strpos($desc, $palavra) !== false) {
                        $processado['labelsPlanta'][] = [
                            'descricao' => $label['description'],
                            'confianca' => round($label['score'] * 100, 1)
                        ];
                        break;
                    }
                }
            }
        }

        // Processar web entities (possíveis identificações)
        if (isset($resultado['webDetection']['webEntities'])) {
            foreach ($resultado['webDetection']['webEntities'] as $entity) {
                if (!empty($entity['description'])) {
                    $processado['webEntities'][] = [
                        'descricao' => $entity['description'],
                        'confianca' => isset($entity['score']) ? round($entity['score'] * 100, 1) : null
                    ];
                }
            }
        }

        // Processar cores dominantes
        if (isset($resultado['imagePropertiesAnnotation']['dominantColors']['colors'])) {
            foreach (array_slice($resultado['imagePropertiesAnnotation']['dominantColors']['colors'], 0, 6) as $cor) {
                $r = $cor['color']['red'] ?? 0;
                $g = $cor['color']['green'] ?? 0;
                $b = $cor['color']['blue'] ?? 0;
                
                $processado['cores'][] = [
                    'rgb' => sprintf('rgb(%d, %d, %d)', $r, $g, $b),
                    'hex' => sprintf('#%02x%02x%02x', $r, $g, $b),
                    'porcentagem' => round($cor['score'] * 100, 1)
                ];
            }
        }

        // Processar objetos localizados
        if (isset($resultado['localizedObjectAnnotations'])) {
            foreach ($resultado['localizedObjectAnnotations'] as $objeto) {
                $processado['objetos'][] = [
                    'nome' => $objeto['name'],
                    'confianca' => round($objeto['score'] * 100, 1)
                ];
            }
        }

        return $processado;
    }

    /**
     * Renderiza a página de reconhecimento
     */
    public static function pagina()
    {
        require __DIR__ . '/../content/reconhecimento.php';
    }
}