<?php
// Chave privada e pessoal de acesso a API
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenAI - Da Vinci</title>
</head>

<body>
    <h2>OpenAI - Da Vinci</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">        
        <input type="text" id="pergunta" name="pergunta" placeholder="Digite sua pergunta"><br><br>
        <input type="submit" value="Enviar">
    </form>

    <?php
    // Exibir a resposta aqui após o processamento
    if (isset($resposta)) {
        echo "<strong>Resposta:</strong> " . $resposta . "<br>";
    }
    ?>
</body>

</html>