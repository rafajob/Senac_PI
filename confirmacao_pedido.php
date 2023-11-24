<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <title>Confirmação de Pedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
        }

        h1 {
            font-size: 1.8em;
        }

        form {
            display: inline-block;
            text-align: left;
            max-width: 400px; /* Ajuste a largura máxima conforme necessário */
            margin: 0 auto; /* Centralizar o formulário na tela */
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 15px;
        }

        button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%; /* Ocupar 100% da largura */
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            text-align: center;
        }

        @media only screen and (max-width: 600px) {
            /* Adicione regras de estilo específicas para dispositivos móveis aqui */

            form {
                max-width: 100%;
            }

            button {
                font-size: 1em; /* Ajuste o tamanho da fonte para telas menores */
            }
        }
    </style>



</head>

<body>
    <h1>Confirmação de Pedido</h1>

    <?php
 
    include 'conexao.php';

 
    if (isset($_GET['restaurante']) && isset($_GET['prato'])) {
        $restauranteId = $_GET['restaurante'];
        $pratoId = $_GET['prato'];

        
        $nomePrato = "{$pratoId}";

        try {
           
            $stmt = $pdo->prepare("SELECT nome_restaurante FROM restaurantes WHERE id_restaurante = :restauranteId");
            $stmt->bindParam(':restauranteId', $restauranteId, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $nomeRestaurante = ($row) ? $row['nome_restaurante'] : "Restaurante não encontrado";

            // Verifica envio do formulario
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['nomeUsuario'])) {
   
                $nomeUsuario = $_GET['nomeUsuario'];
                $horaAlmoco = isset($_GET['horaAlmoco']) ? $_GET['horaAlmoco'] : '';
                $opcaoRefeicao = isset($_GET['opcaoRefeicao']) ? $_GET['opcaoRefeicao'] : '';
                $observacoes = isset($_GET['observacoes']) ? $_GET['observacoes'] : '';
				
                // Mostra mensadem de confirmação
                echo "<p>Olá, " . htmlspecialchars($nomeUsuario) . "! Seu pedido no restaurante <strong>{$nomeRestaurante}</strong> foi solicitado.</p>";
                echo "<p>Detalhes do pedido:</p>";
                echo "<ul>";
                echo "<li>Restaurante: {$nomeRestaurante}</li>";
                echo "<li>Prato: {$nomePrato}</li>";
                echo "<li>Hora do Almoço: {$horaAlmoco}</li>";
                echo "<li>Opção de Refeição: {$opcaoRefeicao}</li>";
                echo "<li>Observações: {$observacoes}</li>";
                echo "</ul>";

				echo "<script>nomeUsuario = '" . $nomeUsuario . "';</script>";

            } else {
                
				echo "<script>";
				echo "var nomeUsuario = '';";
				echo "var nomeRestaurante = '" . $nomeRestaurante . "';";
				echo "var nomePrato = '" . $nomePrato . "';";
				echo "var horaAlmoco = '';";
				echo "var opcaoRefeicao = '';";
				echo "var observacoes = '';";
				echo "</script>";
                ?>
                <form method="get" action="confirmacao_pedido.php">
                    <label for="nomeUsuario">Seu Nome:</label>
                    <input type="text" id="nomeUsuario" name="nomeUsuario" required>

                    <label for="horaAlmoco">Hora do Almoço:</label>
                    <input type="time" id="horaAlmoco" name="horaAlmoco">

                    <label for="opcaoRefeicao">Opção de Refeição:</label>
                    <select id="opcaoRefeicao" name="opcaoRefeicao">
                        <option value="retirada">Retirada</option>
                        <option value="consumo Local">Consumo no Local</option>
                    </select>

                    <label for="observacoes">Observações:</label>
                    <textarea id="observacoes" name="observacoes" rows="4"></textarea>

                    <input type="hidden" name="restaurante" value="<?= $restauranteId ?>">
                    <input type="hidden" name="prato" value="<?= $pratoId ?>">

                    <button type="submit">Confirmar Pedido</button>
                </form>
            <?php
            }
        } catch (PDOException $e) {

            error_log("Error querying the database: " . $e->getMessage());

            
            echo "Erro ao consultar o banco de dados. Por favor, tente novamente mais tarde.";
            
            exit();
        }
    } else {
        echo "<p>Erro: Parâmetros de pedido ausentes.</p>";
    }

 
    $pdo = null;
    ?>
<script>
    // Função para enviar os dados via AJAX
    function enviarDados(nomeUsuario, nomeRestaurante, nomePrato, horaAlmoco, opcaoRefeicao, observacoes) {
        var url = "processar_pedido.php";
        var dadosPedido = {
            nomeUsuario: nomeUsuario,
            nomeRestaurante: nomeRestaurante,
            nomePrato: nomePrato,
            horaAlmoco: horaAlmoco,
            opcaoRefeicao: opcaoRefeicao,
            observacoes: observacoes,
        };

        // Enviar dados via AJAX
        $.post(url, dadosPedido, function (resposta) {
            console.log("Resposta do servidor: " + resposta);
            alert("Pedido processado com sucesso!");
        })
        .fail(function (erro) {
            console.error("Erro ao processar o pedido: " + erro.statusText);
        });

        console.log("Enviando dados...");
        return true;
    }

    // Associar a função enviarDados ao evento de envio do formulário
    $('form').submit(function() {
        var nomeUsuario = $('#nomeUsuario').val();
        var nomeRestaurante = '<?php echo $nomeRestaurante; ?>';
        var nomePrato = '<?php echo $nomePrato; ?>';
        var horaAlmoco = $('#horaAlmoco').val();
        var opcaoRefeicao = $('#opcaoRefeicao').val();
        var observacoes = $('#observacoes').val();

        return enviarDados(nomeUsuario, nomeRestaurante, nomePrato, horaAlmoco, opcaoRefeicao, observacoes);
    });
</script>

</body>

</html>
