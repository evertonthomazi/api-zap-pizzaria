@extends('front.layout.app')

@section('css')
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
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

    .container {
        padding: 20px;
    }

    .pizza-name {
        font-size: 24px;
        font-weight: bold;
    }

    .pizza-details {
        font-size: 16px;
        color: #666;
        margin-bottom: 20px;
    }

    .pizza-price {
        font-size: 20px;
        color: #ff4500;
    }

    .crust-options {
        margin: 20px 0;
    }

    .crust-option {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
    }

    .crust-option input {
        margin-right: 10px;
    }

    .observation {
        display: flex;
        align-items: center;
        margin-top: 20px;
    }

    .observation i {
        margin-right: 10px;
    }

    .observation textarea {
        flex-grow: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        resize: none;
    }

    .char-count {
        text-align: right;
        margin-top: 5px;
        color: #666;
    }

    .footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px;
        border-top: 1px solid #ccc;
        position: fixed;
        bottom: 0;
        width: 100%;
        background-color: #fff;
    }

    .quantity {
        display: flex;
        align-items: center;
    }

    .quantity input {
        width: 50px;
        text-align: center;
        margin: 0 10px;
    }

    .btn-add {
        padding: 10px 20px;
        background-color: #ff4500;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="header">
    <i class="fas fa-arrow-left" onclick="history.back()"></i>
    <span>Checkout</span>
</div>

<div class="container">
    <div class="pizza-name">{{ $product->name }}</div>
    <div class="pizza-details">{{ $product->description }}</div>
    <div class="pizza-price">R$ {{ number_format($product->price, 2, ',', '.') }}</div>

    @if(in_array($product->category->name, ['Pizzas']))
    <div class="crust-options">
        <div class="crust-option">
            <input type="radio" name="crust" value="Tradicional" checked>
            Tradicional
        </div>
        <div class="crust-option">
            <input type="radio" name="crust" value="Cheddar">
            Cheddar <span class="pizza-price">+ R$ 5,00</span>
        </div>
        <div class="crust-option">
            <input type="radio" name="crust" value="Catupiry">
            Catupiry <span class="pizza-price">+ R$ 5,00</span>
        </div>
    </div>
    @endif

    <div class="observation">
        <i class="fa fa-pencil"></i>
        <textarea id="observation" rows="2" maxlength="140" placeholder="Alguma Observação?"></textarea>
    </div>
    <div class="char-count" id="char-count">0/140</div>
</div>

<div class="footer">
    <div class="quantity">
        <button onclick="changeQuantity(-1)">-</button>
        <input type="number" id="quantity" value="1" min="1">
        <button onclick="changeQuantity(1)">+</button>
    </div>
    <div>
        <span class="pizza-price" id="total-price">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
        <button class="btn-add">Adicionar ao Carrinho</button>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.getElementById('observation').addEventListener('input', function() {
        const charCount = this.value.length;
        document.getElementById('char-count').innerText = charCount + '/140';
    });

    function changeQuantity(amount) {
        const quantityInput = document.getElementById('quantity');
        let quantity = parseInt(quantityInput.value) + amount;
        quantity = quantity < 1 ? 1 : quantity;
        quantityInput.value = quantity;
        updateTotalPrice();
    }

    const productPrice = {{ $product->price }};
    const crustPrices = {
        'Tradicional': 0,
        'Cheddar': 5,
        'Catupiry': 5
    };

    document.querySelectorAll('input[name="crust"]').forEach(crustInput => {
        crustInput.addEventListener('change', updateTotalPrice);
    });

    document.getElementById('quantity').addEventListener('input', updateTotalPrice);

    function updateTotalPrice() {
        const selectedCrust = document.querySelector('input[name="crust"]:checked')?.value || 'Tradicional';
        const quantity = parseInt(document.getElementById('quantity').value);
        const totalPrice = (productPrice + crustPrices[selectedCrust]) * quantity;
        document.getElementById('total-price').innerText = 'R$ ' + totalPrice.toFixed(2).replace('.', ',');
    }

    updateTotalPrice();
</script>
@endsection
