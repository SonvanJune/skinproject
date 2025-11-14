<?php

function getFileSizeFormatted($path)
{
    if (!file_exists($path)) {
        return 'File not found';
    }

    $fileSize = filesize($path);

    if ($fileSize >= 1073741824) {
        // >= 1 GB
        return round($fileSize / 1073741824, 2) . ' GB';
    } elseif ($fileSize >= 1048576) {
        // >= 1 MB
        return round($fileSize / 1048576, 2) . ' MB';
    } elseif ($fileSize >= 1024) {
        // >= 1 KB
        return round($fileSize / 1024, 2) . ' KB';
    } else {
        // < 1 KB
        return $fileSize . ' bytes';
    }
}

function getPaypalInformation($type)
{
    $key = 'DEFAULT_ENCRYPT_KEY';
    $filePath = resource_path('paypal/information.php');
    if (!file_exists($filePath)) {
        return $type ? null : ['client_id' => null, 'client_secret' => null, 'app_id' => null];
    }
    $data = include $filePath;

    $ciphertextCI = base64_decode($data['client_id']);
    $ivCI = substr(hash('sha256', $key), 0, openssl_cipher_iv_length('aes-256-cbc'));
    $clientId = openssl_decrypt($ciphertextCI, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $ivCI);

    $ciphertextCS = base64_decode($data['client_secret']);
    $ivCS = substr(hash('sha256', $key), 0, openssl_cipher_iv_length('aes-256-cbc'));
    $clientSecret = openssl_decrypt($ciphertextCS, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $ivCS);

    $ciphertextAI = base64_decode($data['app_id']);
    $ivAI = substr(hash('sha256', $key), 0, openssl_cipher_iv_length('aes-256-cbc'));
    $app_id = openssl_decrypt($ciphertextAI, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $ivAI);

    switch ($type) {
        case 'client_id':
            return $clientId;
        case 'client_secret':
            return $clientSecret;
        case 'app_id':
            return $app_id;
        default:
            return '';
    }
}
