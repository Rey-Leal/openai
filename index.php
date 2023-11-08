<!DOCTYPE html>
<html lang="en">

<head>
    <title>OpenAI</title>
    <?php include_once('head.php'); ?>
</head>

<body>
    <nav>
        <div class="nav-wrapper light-blue darken-2 white-text">
            <img src="content/images/icone.ico" width="65" height="65" />
            <p class="brand-logo">OpenAI</p>
        </div>
    </nav>
    <br>
    <a class="btn-large waves-effect waves-dark light-blue darken-2" href="chat.php">ChatGPT - Respostas de perguntas</a>
    <a class="btn-large waves-effect waves-dark light-blue darken-2" href="davinci.php">Da Vinci - Gerador de textos aleatórios</a>
</body>

</html>

<?php
include_once("keys/apikey.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $entrada = $_POST["pergunta"];;

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
        if ($response === false) {
            echo ("Erro na solicitação cURL: " . curl_error($api));
        } else {
            $result = json_decode($response, true);

            echo ("<pre>");
            print_r($result);
            echo ("</pre>");

            if (isset($result['choices'][0]['text'])) {
                $resposta = $result['choices'][0]['text'];
                echo ($resposta . "<br>");
            } else {
                echo ("Não foi possível gerar o texto!" . "<br>");
            }
        }
        curl_close($api);
    } catch (Exception $e) {
        die("Erro: " . $e->getMessage() . "<br>");
    }
}
?>