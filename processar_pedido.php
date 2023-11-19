<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Mensagens</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
        }

        h1 {
            color: #4CAF50;
        }

        .message-panel {
            border: 1px solid #ccc;
            padding: 20px;
            margin: 20px auto;
            max-width: 600px;
            text-align: left;
        }

        .message {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <h1>Painel de Mensagens</h1>

    <div class="message-panel">
        <?php
        // Função para exibir mensagens
        function exibirMensagem($mensagem)
        {
            echo "<div class='message'>";
            echo "<p>$mensagem</p>";
            echo "</div>";
        }

        // Verifica se a requisição é do tipo POST ou AJAX
        if ($_SERVER["REQUEST_METHOD"] == "POST" || !empty($_POST)) {
            // Verifica se os dados necessários foram recebidos
            if (
                isset($_POST['nomeUsuario']) &&
                isset($_POST['nomeRestaurante']) &&
                isset($_POST['nomePrato'])
            ) {
                // Obtenha os dados do pedido
                $nomeUsuario = $_POST['nomeUsuario'];
                $nomeRestaurante = $_POST['nomeRestaurante'];
                $nomePrato = $_POST['nomePrato'];
                $horaAlmoco = isset($_POST['horaAlmoco']) ? $_POST['horaAlmoco'] : '';
                $opcaoRefeicao = isset($_POST['opcaoRefeicao']) ? $_POST['opcaoRefeicao'] : '';
                $observacoes = isset($_POST['observacoes']) ? $_POST['observacoes'] : '';

                // Exibe as informações em um painel de mensagens
                exibirMensagem("<strong>Nome do Cliente:</strong> $nomeUsuario");
                exibirMensagem("<strong>Restaurante:</strong> $nomeRestaurante");
                exibirMensagem("<strong>Prato:</strong> $nomePrato");
                exibirMensagem("<strong>Hora do Almoço:</strong> $horaAlmoco");
                exibirMensagem("<strong>Opção de Refeição:</strong> $opcaoRefeicao");
                exibirMensagem("<strong>Observações:</strong> $observacoes");
				exibirMensagem("<hr>");

                // Armazena as mensagens na variável de sessão
                $novaMensagem = array(
                    'nomeUsuario' => $nomeUsuario,
                    'nomeRestaurante' => $nomeRestaurante,
                    'nomePrato' => $nomePrato,
                    'horaAlmoco' => $horaAlmoco,
                    'opcaoRefeicao' => $opcaoRefeicao,
                    'observacoes' => $observacoes
                );
                $_SESSION['mensagens'][] = $novaMensagem;
            } else {
                // Se os dados não são válidos, exibe uma mensagem de erro
                exibirMensagem("<strong>Erro:</strong> Dados do pedido inválidos.");
            }
        } 

        // Exibe as mensagens armazenadas na variável de sessão
        if (isset($_SESSION['mensagens'])) {
            $mensagensAntigas = $_SESSION['mensagens'];
            foreach ($mensagensAntigas as $mensagem) {
                exibirMensagem("<strong>Nome do Cliente:</strong> " . $mensagem['nomeUsuario']);
                exibirMensagem("<strong>Restaurante:</strong> " . $mensagem['nomeRestaurante']);
                exibirMensagem("<strong>Prato:</strong> " . $mensagem['nomePrato']);
                exibirMensagem("<strong>Hora do Almoço:</strong> " . $mensagem['horaAlmoco']);
                exibirMensagem("<strong>Opção de Refeição:</strong> " . $mensagem['opcaoRefeicao']);
                exibirMensagem("<strong>Observações:</strong> " . $mensagem['observacoes']);
				exibirMensagem("<hr>");
          }
        }
		  // Adicione JavaScript para recarregar a página após um curto intervalo (por exemplo, 2 segundos)
                echo "<script>
                    setTimeout(function(){
                        location.reload();
                    }, 2000); // 2000 milissegundos = 2 segundos
                  </script>";
        ?>
    </div>
</body>

</html>
