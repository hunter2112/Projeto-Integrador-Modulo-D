<?php

namespace App;

class Config
{
    /**
     * Configurações da API do Google Cloud Vision
     * 
     * IMPORTANTE: Substitua 'SUA_API_KEY_AQUI' pela sua chave real
     * Obtenha sua chave em: https://console.cloud.google.com
     */
    const GOOGLE_VISION_API_KEY = 'AIzaSyBZm1EAVWVuguS9z13gnH-nfatNuDErbgY';

    /**
     * Configurações de upload de imagem
     */
    const MAX_FILE_SIZE = 4 * 1024 * 1024; // 4MB
    const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    
    /**
     * Pasta para salvar uploads temporários (opcional)
     */
    const UPLOAD_TEMP_DIR = __DIR__ . '/../uploads/temp/';
}