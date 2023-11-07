<?php
// Chave privada e pessoal de acesso a API
include_once("keys/apikey.php");

try {
    $prompt = "São se sagra campeão da Copa do Brasil, salve tricolor!";
    
    // Cria textos aleatorios de acordo como prompt enviado 
    $url = 'https://api.openai.com/v1/engines/davinci/completions';

    $data = [
        'prompt' => $prompt,
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

    echo ("Calma! Da Vinci está gerando seu texto artístico aleatório...<br><br>");

    $response = curl_exec($api);
    curl_close($api);
    $result = json_decode($response, true);

    if (isset($result['choices'][0]['text'])) {
        $retorno = $result['choices'][0]['text'];
        echo ($retorno . "<br>");
    } else {
        echo ("Não foi possível gerar o texto!" . "<br>");
    }
} catch (Exception $e) {
    die("Erro: " . $e->getMessage() . "<br>");
}
