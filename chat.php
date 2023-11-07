<?php
// Chave privada e pessoal de acesso a API
include_once("keys/apikey.php");

try {
    $entrada = "Qual a capital do Brasil?";

    // ChatGPT
    $url = 'https://api.openai.com/v1/chat/completions';

    $data = [
        'messages' => [
            [
                'role' => 'system',
                'content' => 'Você é um assistente que responde a perguntas.'
            ],
            [
                'role' => 'user',
                'content' => $entrada
            ]
        ]
    ];

    $api = curl_init($url);
    curl_setopt($api, CURLOPT_POST, 1);
    curl_setopt($api, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($api, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($api, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ]);

    echo ("ChatGPT está processando sua resposta...<br><br>");

    $response = curl_exec($api);
    curl_close($api);
    $result = json_decode($response, true);

    if (isset($result['choices'][0]['message']['content'])) {
        $resposta = $result['choices'][0]['message']['content'];
        echo "Resposta: " . $resposta;
    } else {
        echo "Não foi possível obter uma resposta.";
    }
} catch (Exception $e) {
    die("Erro: " . $e->getMessage() . "<br>");
}
