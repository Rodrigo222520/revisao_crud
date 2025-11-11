<?php
// Pequeno wrapper para integrações com APIs externas (webhooks, notificações, etc.)
// Altere API_ENDPOINT para o seu endpoint real.

if (!defined('API_ENDPOINT')) {
    define('API_ENDPOINT', 'https://example.com/webhook'); // Substitua pelo seu endpoint
}

function send_webhook(array $payload, string $endpoint = API_ENDPOINT, array $headers = []): array {
    $defaultHeaders = [
        'Content-Type: application/json',
        'Accept: application/json',
    ];
    $allHeaders = array_merge($defaultHeaders, $headers);

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeaders);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'ok' => $err === '' && $status >= 200 && $status < 300,
        'status' => $status,
        'error' => $err,
        'response' => $response,
    ];
}

function send_user_registration(string $email, $userId = null): array {
    $payload = [
        'event' => 'user.registered',
        'data' => [
            'id' => $userId,
            'email' => $email,
            'timestamp' => date('c'),
        ],
    ];
    return send_webhook($payload);
}

function send_task_created(array $task): array {
    $payload = [
        'event' => 'task.created',
        'data' => array_merge($task, ['timestamp' => date('c')]),
    ];
    return send_webhook($payload);
}
