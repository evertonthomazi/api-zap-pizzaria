@extends('front.layout.app')

@section('css')
    <style>
         @import url('https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap');
        body {
            font-family: 'Nunito', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
           
        }

        .container {
            padding: 20px;
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
            font-size: 20px;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }

        .checkout-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #000;
            padding: 20px;
            box-shadow: 0 -1px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
            box-sizing: border-box;
            /* Adicionado */
        }


        .checkout-button {
            padding: 10px 20px;
            background-color: #ff4500;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
        }

        .header {
            background-color: #000;
            color: #fff;
            padding: 10px;
            display: flex;
            align-items: center;
        }

        .header i {
            cursor: pointer;
            margin-right: 10px;
        }
    </style>
@endsection


@section('content')
    <div class="header">
        <a href="{{ route('checkout.home') }}" class="header">
            <i class="fas fa-arrow-left"></i>
            <span>Voltar</span>
        </a>
    </div>
    <div class="container">
        <h2>Carrinho de Compras</h2>

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
                            <p>Borda: {{ $item['crust'] }}</p>
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
    </div>
    <div class="checkout-footer">
        @if (count($cart) > 0)
            <a href="/checkout/finalizar" class="checkout-button">Finalizar Compra</a>
            <div class="total-price">
                Total: R$ {{ number_format(array_sum(array_column($cart, 'total')), 2, ',', '.') }}
            </div>
        @else
            <a href="/checkout" class="checkout-button">Ir Para Cardápio</a>
        @endif
    </div>
@endsection


@section('scripts')
    <script>
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
                // Adicionar lógica para incrementar a quantidade e atualizar o carrinho via AJAX
            });
        });

        document.querySelectorAll('.decrement').forEach(button => {
            button.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                // Adicionar lógica para decrementar a quantidade e atualizar o carrinho via AJAX
            });
        });
    </script>
    <script>
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
    </script>
@endsection
