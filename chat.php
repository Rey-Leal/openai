<?php
// Chave privada e pessoal de acesso a API
include_once("keys/apikey.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $entrada = $_POST["pergunta"];;

        // ChatGPT - Respostas via Machine Learning 
        $url = 'https://api.openai.com/v1/chat/completions';

        $data = array(
            "messages" => array(
                array(
                    "role" => "system",
                    "content" => "Você é um assistente que responde a perguntas sobre fatos."
                ),
                array(
                    "role" => "user",
                    "content" => "$entrada"
                )
            ),
            "max_tokens" => 150,
            "temperature" => 0.7, // Criatividade de respostas
            "top_p" => 1.0, // Diversidade de respostas
            "n" => 1, // Quantidade de respostas
            "model" => "gpt-3.5-turbo-0613"
        );

        $api = curl_init();
        curl_setopt($api, CURLOPT_URL, $url);
        curl_setopt($api, CURLOPT_POST, 1);
        curl_setopt($api, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($api, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($api, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey"
        ));

        echo ("ChatGPT está processando sua resposta...<br><br>");

        $response = curl_exec($api);
        if ($response === false) {
            echo ("Erro na solicitação cURL: " . curl_error($api));
        } else {
            $result = json_decode($response, true);

            echo ("<pre>");
            print_r($result);
            echo ("</pre>");

            if (isset($result['choices'][0]['message']['content'])) {
                $resposta = $result['choices'][0]['message']['content'];
                echo ($resposta . "<br>");
            } else {
                echo ("Não foi possível obter uma resposta." . "<br>");
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
    <title>OpenAI - ChatGPT</title>
</head>

<body>
    <h2>OpenAI - ChatGPT</h2>
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