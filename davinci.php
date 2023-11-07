<?php
// Chave privada e pessoal de acesso a API
include_once("keys/apikey.php");

try {
    $entrada = "São Paulo se sagra campeão da Copa do Brasil, salve tricolor!";

    // Da Vinci - Cria textos aleatorios de acordo como prompt enviado 
    $url = 'https://api.openai.com/v1/engines/davinci/completions';

    $data = [
        'prompt' => $entrada,
        'max_tokens' => 150,
    ];

    $api = curl_init($url);
    curl_setopt($api, CURLOPT_POST, 1);
    curl_setopt($api, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($api, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($api, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ]);

    echo ("Da Vinci está gerando seu texto artístico aleatório...<br><br>");

    $response = curl_exec($api);
    curl_close($api);
    $result = json_decode($response, true);

    if (isset($result['choices'][0]['text'])) {
        $resposta = $result['choices'][0]['text'];
        echo ($resposta . "<br>");
    } else {
        echo ("Não foi possível gerar o texto!" . "<br>");
    }
} catch (Exception $e) {
    die("Erro: " . $e->getMessage() . "<br>");
}
