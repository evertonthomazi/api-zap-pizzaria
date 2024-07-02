@extends('front.layout.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Nunito', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            padding: 5px;
            padding-bottom: 156px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }

        .cart-items {
            background-color: #fff;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .cart-item {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
            position: relative;
            margin-bottom: 11px;
        }

        .cart-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            margin-right: 10px;
        }

        .cart-item-details {
            flex: 1;
        }

        .cart-item-details h3 {
            margin: 0;
        }

        .cart-item-details p {
            margin: 5px 0;
            color: #666;
        }

        .cart-item-price {
            font-size: 16px;
            font-weight: bold;
            text-align: right;
        }

        .cart-item-quantity {
            display: flex;
            align-items: center;
            margin-top: 5px;
        }

        .cart-item-quantity input {
            width: 50px;
            text-align: center;
        }

        .cart-item-quantity button {
            background-color: #ff4500;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            margin-right: 5px;
            margin-left: 5px;
        }

        .cart-item-remove {
            color: #ccc;
            cursor: pointer;
            margin-left: auto;
            position: absolute;
            top: 0px;
            right: 0px;
        }

        .cart-item-remove:hover {
            color: #ff4500;
        }

        .total-price {
            font-size: 15px;
            font-weight: bold;
            text-align: right;
        }

        .checkout-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #000;
            padding: 5px;
            box-shadow: 0 -1px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
            box-sizing: border-box;
            flex-direction: row-reverse;
        }

        .checkout-button {
            padding: 10px 20px;
            background-color: #ff4500;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 10px;
        }

        .header {
            background-color: #000;
            color: #fff;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            /* Adjusted to space items between */
        }

        .header i {
            cursor: pointer;
            margin-right: 10px;
        }

        body .carousel {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            background-color: #f0f0f0;
        }

        .carousel {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        .carousel-inner {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .carousel-item {
            min-width: 100%;
            transition: opacity 0.5s ease-in-out;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .product-card {
            background-color: #fff;
            padding: 5px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 300px;
            margin-bottom: 20px;
        }

        .product-card img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            width: 90px;
            height: 90px;
        }

        .btn-carousel {
            background: #27ae60;
            color: #000;
            text-decoration-line: none;
            padding: 10px;
            border-radius: 5px;
        }

        .prev,
        .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            border: none;
            padding: 15px;
            cursor: pointer;
            color: white;
            font-size: 18px;
            border-radius: 50%;
            user-select: none;
        }

        .prev {
            left: 10px;
        }

        .next {
            right: 10px;
        }

        .title-bebida {
            display: flex;
            justify-content: center;
        }

        .frete-options {
            display: flex;
            align-items: center;
        }

        .frete-options input[type="radio"] {
            margin-left: 10px;
        }

        .crust-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .crust-option {
            display: flex;
            align-items: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .crust-option input[type="radio"] {
            display: none;
        }

        .crust-option.selected {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            border-color: #ff4500;
        }

        .crust-option span {
            margin-left: 10px;
        }

        .crust-option i {
            display: none;
            color: #ff4500;
        }

        .crust-option.selected i {
            display: inline-block;
        }

        #dinheiro-options,
        #pix-options {
            padding: 20px;
            background: white;
            border: double;
            border-radius: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="header">
        <div>
            <a href="{{ route('checkout.home') }}" class="header">
                <i class="fas fa-arrow-left"></i>
                <span>Voltar</span>
            </a>
        </div>
    </div>
    <div class="container">
        <h2>Carrinho de Compras</h2>
        <div class="frete-options">
            <label>
                <input type="radio" name="frete" value="{{ session('taxa_entrega') }}" checked>
                Entrega (R$ {{ number_format(session('taxa_entrega'), 2, ',', '.') }})
            </label>
            <label>
                <input type="radio" name="frete" value="0.00">
                Retirar na loja (R$ 0,00)
            </label>
        </div>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (count($cart) > 0)
            <div class="cart-items">
                @foreach ($cart as $index => $item)
                    <div class="cart-item">
                        <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}">
                        <div class="cart-item-details">
                            <h3>{{ $item['name'] }}</h3>
                            <div class="cart-item-price">R$ {{ number_format($item['total'], 2, ',', '.') }}</div>
                            <div class="cart-item-quantity">
                                <button class="decrement" data-index="{{ $index }}">-</button>
                                <input type="number" value="{{ $item['quantity'] }}" readonly>
                                <button class="increment" data-index="{{ $index }}">+</button>
                            </div>
                        </div>
                        <span class="cart-item-remove" data-index="{{ $index }}"><i
                                class="fas fa-trash-alt"></i></span>
                    </div>
                @endforeach
            </div>
        @else
            <p>Seu carrinho está vazio.</p>
        @endif

        <form action="/checkout/finalizar" method="POST" id="payment-form">
            @csrf
            <h3 id="payment-section">Forma de Pagamentos</h3>
            <div class="crust-options">
                <label class="crust-option">
                    <input type="radio" name="payment" value="Dinheiro" class="payment-radio">
                    <i class="fas fa-check"></i>
                    <span class="pizza-price">Dinheiro</span>
                </label>
                <div id="dinheiro-options" style="display: none;">
                    <label>
                        <input type="checkbox" id="troco-checkbox"> Precisa de troco?
                    </label>
                    <div id="troco-field" style="display: none;">
                        <label for="troco-amount">Valor do troco:</label>
                        <input type="text" id="troco-amount" class="money" min="0" step="0.01"
                            placeholder="0.00" name="troco_amount">
                    </div>
                </div>
                <label class="crust-option">
                    <input type="radio" name="payment" value="Cartão de Crédito" class="payment-radio">
                    <i class="fas fa-check"></i>
                    <span class="pizza-price">Cartão de Crédito</span>
                </label>
                <label class="crust-option">
                    <input type="radio" name="payment" value="Cartão de Débito" class="payment-radio">
                    <i class="fas fa-check"></i>
                    <span class="pizza-price">Cartão de Débito</span>
                </label>
                <label class="crust-option">
                    <input type="radio" name="payment" value="Pix" class="payment-radio">
                    <i class="fas fa-check"></i>
                    <span class="pizza-price">Pix</span>
                </label>
                <div id="pix-options" style="display: none;">
                    <p>Use o seguinte código PIX para pagamento:</p>
                    <div style="display: flex; align-items: center;">
                        <span id="pix-code">11933361625</span>
                        <button type="button" id="copy-pix-btn" style="margin-left: 10px;">Copiar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="checkout-footer">
        @if (count($cart) > 0)
            <div class="total-price">
                <div class="taxa entrega">
                    <div>
                        Itens : {{ number_format(array_sum(array_column($cart, 'total')), 2, ',', '.') }}
                    </div>
                    Taxa entrega : R$ <span
                        id="taxa-entrega">{{ number_format(session('taxa_entrega'), 2, ',', '.') }}</span>
                </div>
                Total : R$ <span
                    id="total">{{ number_format(array_sum(array_column($cart, 'total')) + session('taxa_entrega'), 2, ',', '.') }}</span>
            </div>
            <button id="submit-button" class="checkout-button">Finalizar Compra</button>
        @else
            <a href="/checkout" class="checkout-button">Ir Para Cardápio</a>
        @endif
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('/assets/admin/vendor/jquery/jquery.min.js') }} "></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <script src="{{ asset('/assets/utils.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Função para adicionar ou remover a classe 'selected' e exibir opções de pagamento
            $('.crust-option').on('click', function() {
                $('.crust-option').removeClass('selected');
                $(this).addClass('selected');
                $(this).find('input[type="radio"]').prop('checked', true);
                const paymentMethod = $(this).find('input[type="radio"]').val();
                if (paymentMethod === 'Dinheiro') {
                    $('#dinheiro-options').show();
                    $('#pix-options').hide();
                } else if (paymentMethod === 'Pix') {
                    $('#pix-options').show();
                    $('#dinheiro-options').hide();
                } else {
                    $('#dinheiro-options').hide();
                    $('#pix-options').hide();
                }
            });

            // Função para exibir ou ocultar campo de troco
            $('#troco-checkbox').on('change', function() {
                if (this.checked) {
                    $('#troco-field').show();
                } else {
                    $('#troco-field').hide();
                }
            });

            // Função para copiar código PIX
            $('#copy-pix-btn').on('click', function() {
                const pixCode = $('#pix-code').text();
                navigator.clipboard.writeText(pixCode).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Copiado!',
                        text: 'Código PIX copiado para a área de transferência.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });
                }).catch(err => {
                    console.error('Erro ao copiar o PIX:', err);
                });
            });

            // Função para verificar se uma forma de pagamento foi selecionada ao clicar em "Finalizar Pedido"
            $('#submit-button').on('click', function(e) {
                if (!$('input[name="payment"]:checked').val()) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: $('#payment-section').offset().top
                    }, 500);
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção',
                        text: 'Por favor, selecione uma forma de pagamento.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                } else {
                    $('#payment-form').submit();
                }
            });

            // Funções já existentes para manipulação de itens no carrinho
            document.querySelectorAll('.cart-item-remove').forEach(button => {
                button.addEventListener('click', function() {
                    const index = this.getAttribute('data-index');
                    Swal.fire({
                        title: 'Tem certeza?',
                        text: "Você deseja remover este item do carrinho?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sim, remover!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const url = `/checkout/cart/remove/${index}`;
                            window.location.href = url;
                        }
                    });
                });
            });

            document.querySelectorAll('.increment').forEach(button => {
                button.addEventListener('click', function() {
                    const index = this.getAttribute('data-index');
                    const quantityInput = this.parentNode.querySelector('input');
                    const currentQuantity = parseInt(quantityInput.value);
                    const newQuantity = currentQuantity + 1;

                    quantityInput.value = newQuantity;
                    updateCartItemQuantity(index, newQuantity);
                });
            });

            document.querySelectorAll('.decrement').forEach(button => {
                button.addEventListener('click', function() {
                    const index = this.getAttribute('data-index');
                    const quantityInput = this.parentNode.querySelector('input');
                    const currentQuantity = parseInt(quantityInput.value);

                    if (currentQuantity > 1) {
                        const newQuantity = currentQuantity - 1;

                        quantityInput.value = newQuantity;
                        updateCartItemQuantity(index, newQuantity);
                    }
                });
            });

            function updateCartItemQuantity(index, quantity) {
                const urlUp = `/checkout/cart/update-quantity/${index}/${quantity}`;
                window.location.href = urlUp;
            }

            let currentIndex = 0;

            function showSlide(index) {
                const slides = document.querySelectorAll('.carousel-item');
                if (index >= slides.length) {
                    currentIndex = 0;
                } else if (index < 0) {
                    currentIndex = slides.length - 1;
                } else {
                    currentIndex = index;
                }

                slides.forEach((slide, i) => {
                    slide.style.opacity = i === currentIndex ? '1' : '0';
                });

                const carouselInner = document.querySelector('.carousel-inner');
                carouselInner.style.transform = `translateX(-${currentIndex * 100}%)`;
            }

            function moveSlide(direction) {
                showSlide(currentIndex + direction);
            }

            document.addEventListener('DOMContentLoaded', () => {
                showSlide(currentIndex);
            });

            document.querySelectorAll('input[name="frete"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const selectedValue = parseFloat(this.value);
                    const itemTotal = parseFloat({{ array_sum(array_column($cart, 'total')) }});
                    const totalPriceElement = document.getElementById('total');
                    const taxaEntregaElement = document.getElementById('taxa-entrega');

                    taxaEntregaElement.textContent = selectedValue.toFixed(2).replace('.', ',');
                    totalPriceElement.textContent = (itemTotal + selectedValue).toFixed(2).replace(
                        '.', ',');

                    // Enviar valor do frete via AJAX para atualizar a sessão
                    fetch('/checkout/update-taxa-entrega', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                taxa_entrega: selectedValue
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Taxa de entrega atualizada na sessão.');
                            } else {
                                console.error('Falha ao atualizar a taxa de entrega.');
                            }
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                        });
                });
            });
        });
    </script>
@endsection
