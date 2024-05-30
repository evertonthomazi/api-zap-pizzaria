<!-- resources/views/carrinho/resumo.blade.php -->

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Resumo do Carrinho</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .item {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
        }
        .item img {
            max-width: 100px;
            margin-right: 20px;
            float: left;
        }
        .item-details {
            overflow: hidden;
        }
        .item-details h2 {
            margin-top: 0;
            margin-bottom: 10px;
        }
        .item-details p {
            margin: 0;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
        /* Estilo para o modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            text-align: center;
        }
    </style>
</head>
<body>

<h1>Resumo do Carrinho</h1>

@foreach ($cart as $item)
    <div class="item">
        <div class="item-details">
            <h2>{{ $item['name'] }}</h2>
            <p>{{ $item['description'] }}</p>
            <p>Quantidade: {{ $item['quantity'] }}</p>
            <p>Preço Unitário: R$ {{ $item['price'] }}</p>
            <p>Observação: {{ $item['observation'] ?? 'Nenhuma' }}</p>
            <p>Total: R$ {{ $item['total'] }}</p>
        </div>
        <div style="clear:both;"></div>
    </div>
@endforeach

<!-- Total Geral -->
<div class="total">Total Geral: R$ {{ number_format(array_sum(array_column($cart, 'total')), 2, ',', '.') }}</div>

<!-- Modal de Agradecimento -->
<div id="myModal" class="modal">
  <div class="modal-content">
    <h2>Obrigado pelo seu pedido!</h2>
    <p>Seu pedido foi recebido com sucesso. Retornaremos em breve para continuar a conversa pelo WhatsApp.</p>
  </div>
</div>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Altere a tag <script> do html2canvas para uma versão mais antiga -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>

<script>
    // Função para exibir o modal de agradecimento
    function showModal() {
        $('#myModal').css('display', 'block');
    }

    // Função para enviar a imagem via AJAX e exibir o modal
    function sendImageAndShowModal() {
        // Tirar o print da página
        html2canvas(document.body).then(function(canvas) {
            // Converter o canvas em uma imagem
            var imgData = canvas.toDataURL('image/png');
            var token = $('meta[name="csrf-token"]').attr('content');
            // Enviar a imagem via AJAX
            $.ajax({
                type: 'POST',
                url: '{{ route("checkout.enviaImagen") }}',
                data: {
                    _token: token,
                    imagem: imgData
                },
                success: function(response) {
                    // Tratar a resposta, se necessário
                    console.log('Imagem enviada com sucesso!');
                    // Exibir o modal de agradecimento
                    showModal();
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao enviar imagem:', error);
                }
            });
        });
    }

    // Chamada da função ao carregar a página
    $(document).ready(function() {
        sendImageAndShowModal();
    });
</script>

</html>
