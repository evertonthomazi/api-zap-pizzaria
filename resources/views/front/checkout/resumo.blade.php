<!-- resources/views/carrinho/resumo.blade.php -->

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Resumo do Carrinho</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap');

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            position: relative;
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
            background-color: rgba(0, 0, 0, 0.4);
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

        /* Estilo para o card de observações */
        .observation-card {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .observation-card h3 {
            margin-top: 0;
        }

        .observation {
            margin-bottom: 5px;
        }

        .row {

            display: flex;
            flex-direction: row;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            text-align: center;
            color: white;
            font-size: 20px;
            font-family: Arial, sans-serif;
        }

        .loading-overlay .loading-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        .icons a {
            cursor: pointer;
            text-align: center;
            font-size: 28px;
            color: var(--green);
            -webkit-text-stroke-width: 1px;
            -webkit-text-stroke-color: #5cc011;

        }
    </style>
</head>

<body>
    <div class="loading-overlay">
        <div class="loading-text">Por favor, aguarde...</div>
    </div>
    <h1>Resumo do Carrinho</h1>

    @foreach ($cart as $item)
        <div class="item">
            <div class="item-details">
                @php
                    // Dividir o nome da pizza e as observações
                    $itemDetails = explode('/', $item['name']);
                    $pizzaName = trim($itemDetails[0]); // Primeiro item é o nome da pizza
                    $flavorsCount = count($itemDetails) - 1; // Calcula a quantidade de sabores
                    $flavors = '';
                    for ($i = 1; $i <= $flavorsCount; $i++) {
                        $flavors .= $itemDetails[$i];
                        if ($i < $flavorsCount) {
                            $flavors .= ', ';
                        }
                    }
                    $observations = explode(' / ', $item['observation']); // Divide as observações
                @endphp
                <h2>{{ $item['name'] }}</h2>
                <div class="row">

                    <div class="col-md-6">
                        @if ($flavorsCount > 0)
                            <p>Tipo: {{ $flavorsCount + 1 }} Sabores</p>
                        @endif

                        <p>Quantidade: {{ $item['quantity'] }}</p>
                        <p>Preço Unitário: R$ {{ $item['price'] }}</p>
                        <p>Total: R$ {{ number_format($item['total'], 2, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6">
                        @if (!empty($observations))
                            <div>
                                <h3>Observações:</h3>
                                @foreach ($observations as $index => $observation)
                                    @php
                                        $obsPizzaName = trim($itemDetails[$index] ?? ''); // Nome da pizza
                                        $obsText = trim($observation); // Observação
                                    @endphp
                                    @if (!empty($obsText))
                                        <p class="observation">{{ $obsPizzaName }}: {{ $obsText }}</p>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>


            </div>
            <div style="clear:both;"></div>
        </div>
    @endforeach

    <!-- Total Geral -->
    <div class="total">Total Geral: R$
        {{ number_format(array_sum(array_column($cart, 'total')) + session('taxa_entrega'), 2, ',', '.') }}</div>

    <!-- Modal de Agradecimento -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <h2>Obrigado pelo seu pedido!</h2>
            <p>Seu pedido foi recebido com sucesso. retorna para o WhatsApp.</p>
            <div class="icons">
                <a href="https://api.whatsapp.com/send?phone=5511986123660"><i class="fa-brands fa-whatsapp"></i> clique
                    aqui!</a>
            </div>
        </div>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>

<script>
    // Função para exibir o modal de agradecimento
    function showModal() {
        $('#myModal').css('display', 'block');
        $('.loading-overlay').css('display', 'none');
    }

    // Função para enviar a imagem via AJAX e exibir o modal
    function sendImageAndShowModal() {
        // Mostrar o overlay de carregamento
        $('.loading-overlay').css('display', 'block');

        // Tirar o print da página
        html2canvas(document.body).then(function(canvas) {
            // Converter o canvas em uma imagem
            var imgData = canvas.toDataURL('image/png');
            var token = $('meta[name="csrf-token"]').attr('content');
            // Enviar a imagem via AJAX
            $.ajax({
                type: 'POST',
                url: '{{ route('checkout.enviaImagen') }}',
                data: {
                    _token: token,
                    imagem: imgData
                },
                success: function(response) {
                 
                    showModal();
                },
                error: function(xhr, status, error) {
                 
                    $('.loading-overlay').css('display', 'none');
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
