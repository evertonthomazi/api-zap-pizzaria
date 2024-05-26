@extends('front.layout.app')

@section('css')
<style>
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

    .cart-item {
        display: flex;
        justify-content: space-between;
        padding: 10px;
        border-bottom: 1px solid #ccc;
        margin-bottom: 10px;
    }

    .cart-item img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        margin-right: 20px;
    }

    .cart-item-details {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .cart-item-details h3 {
        margin: 0;
    }

    .cart-item-details p {
        margin: 5px 0;
        color: #666;
    }

    .cart-item-price,
    .cart-item-quantity {
        display: flex;
        align-items: center;
        justify-content: space-between;
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
    }

    .cart-item-remove {
        color: #ff4500;
        cursor: pointer;
    }

    .total-price {
        font-size: 20px;
        font-weight: bold;
        text-align: right;
        margin-top: 20px;
    }

    .checkout-button {
        display: block;
        width: 100%;
        padding: 10px 20px;
        background-color: #ff4500;
        color: #fff;
        text-align: center;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 20px;
        font-size: 18px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <h2>Carrinho de Compras</h2>

    @if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(count($cart) > 0)
        @foreach($cart as $index => $item)
        <div class="cart-item">
            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
            <div class="cart-item-details">
                <h3>{{ $item['name'] }}</h3>
                <p>{{ $item['description'] }}</p>
                <p>Borda: {{ $item['crust'] }}</p>
                <p>Observação: {{ $item['observation'] }}</p>
                <div class="cart-item-price">
                    <span>Quantidade: {{ $item['quantity'] }}</span>
                    <span>R$ {{ number_format($item['total'], 2, ',', '.') }}</span>
                </div>
                <div class="cart-item-quantity">
                    <button class="decrement" data-index="{{ $index }}">-</button>
                    <input type="number" value="{{ $item['quantity'] }}" readonly>
                    <button class="increment" data-index="{{ $index }}">+</button>
                    <span class="cart-item-remove" data-index="{{ $index }}">Remover</span>
                </div>
            </div>
        </div>
        @endforeach
        <div class="total-price">
            Total: R$ {{ number_format(array_sum(array_column($cart, 'total')), 2, ',', '.') }}
        </div>
        <a href="#" class="checkout-button">Finalizar Compra</a>
    @else
        <p>Seu carrinho está vazio.</p>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.cart-item-remove').forEach(button => {
        button.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            fetch(`/cart/remove/${index}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => response.json())
              .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        });
    });

    document.querySelectorAll('.increment').forEach(button => {
        button.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            // Código para incrementar a quantidade e atualizar o carrinho via AJAX
        });
    });

    document.querySelectorAll('.decrement').forEach(button => {
        button.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            // Código para decrementar a quantidade e atualizar o carrinho via AJAX
        });
    });
</script>
@endsection
