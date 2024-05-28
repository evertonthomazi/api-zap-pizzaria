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
            box-sizing: border-box;
        }

        .quantity {
            display: flex;
            align-items: center;
        }

        .quantity input {
            width: 50px;
            text-align: center;
            margin-left: 5px;
            margin-right: 5px;
        }


        .quantity button {
            background-color: #ff4500;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            margin-right: 5px;
            margin-left;: 5px
        }

        .btn-add {
            position: absolute;
            right: 6px;
            height: 50%;
        }
    </style>
@endsection

@section('content')
    <div class="header">
        <i class="fas fa-arrow-left" onclick="history.back()"></i>
        <span>Voltar</span>
    </div>

    <div class="container">
        <div class="pizza-name">{{ $product->name }}</div>
        <div class="pizza-details">{{ $product->description }}</div>
        <div class="pizza-price">R$ {{ number_format($product->price, 2, ',', '.') }}</div>

        @if (in_array($product->category->name, ['Pizzas Clássicas', 'Pizzas Especiais', 'Pizzas Doces']))


            @if (count($crusts) > 0)
                <div class="crust-options">
                    @foreach ($crusts as $crust)
                        <div class="crust-option">
                            <input type="radio" name="crust" value="{{ $crust }}"
                                {{ $loop->first ? 'checked' : '' }}>
                            {{ $crust->name }} <span class="pizza-price">+ R$
                                {{ number_format($crust->price, 2, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="observation">
            <i class="fa fa-pencil"></i>
            <textarea id="observation" rows="2" maxlength="140" placeholder="Alguma Observação?"></textarea>
        </div>
        <div class="char-count" id="char-count">0/140</div>
    </div>

    <div class="footer">
        <form action="{{ route('cart.add') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="crust" id="selected-crust" value="Tradicional">
            <input type="hidden" name="crustPrice" id="selected-crust-price" value="0.00">
            <input type="hidden" name="observation" id="observation-input">
            <div class="quantity">
                <button type="button" class="decrement" onclick="changeQuantity(-1)">-</button>
                <input type="number" id="quantity" name="quantity" value="1" min="1">
                <button type="button" class="increment" onclick="changeQuantity(1)">+</button>


                <button type="submit" class="btn-add">Adicionar ao Carrinho</button>
            </div>
            <span class="pizza-price" id="total-price">R$ {{ number_format($product->price, 2, ',', '.') }}</span>

        </form>

    </div>
@endsection

@section('scripts')
    <script>
        var globalCrustPrice = parseFloat('{{ $crusts->first()->price }}');
        document.getElementById('observation').addEventListener('input', function() {
            const charCount = this.value.length;
            document.getElementById('char-count').innerText = charCount + '/140';
            document.getElementById('observation-input').value = this.value;
        });

        function changeQuantity(amount) {
            const quantityInput = document.getElementById('quantity');
            let quantity = parseInt(quantityInput.value) + amount;
            quantity = quantity < 1 ? 1 : quantity;
            quantityInput.value = quantity;
            updateTotalPrice(globalCrustPrice);
        }

        const productPrice = {{ $product->price }};
        document.querySelectorAll('input[name="crust"]').forEach(crustInput => {
            crustInput.addEventListener('change', function() {
                // Atualiza o valor da borda selecionada
                const selectedCrust = this.value;
                const jsonObject = JSON.parse(selectedCrust);
                document.getElementById('selected-crust').value = jsonObject.name;
                document.getElementById('selected-crust-price').value = jsonObject.price;

                // Recupera o preço da borda selecionada do banco de dados
                const crustPrice = parseFloat(jsonObject.price);
                globalCrustPrice = crustPrice;
                // Atualiza o preço total com base na borda selecionada
                updateTotalPrice(crustPrice);
            });
        });

        // Função para atualizar o preço total com base na quantidade e na borda selecionada
        function updateTotalPrice(crustPrice) {
            console.log(crustPrice);
            const productPrice = parseFloat('{{ $product->price }}');
            const selectedCrust = document.querySelector('input[name="crust"]:checked')?.value || 'Tradicional';
            const quantity = parseInt(document.getElementById('quantity').value);
            const totalPrice = (productPrice + crustPrice) * quantity;
            document.getElementById('total-price').innerText = 'R$ ' + totalPrice.toFixed(2).replace('.', ',');
        }

        document.getElementById('quantity').addEventListener('input', function() {
            // Recupera o preço da borda selecionada do banco de dados
            const crustPrice = parseFloat('{{ $crust->price }}');
           
            updateTotalPrice(crustPrice);
        });

        // Chama a função inicialmente para configurar o preço total com base na borda padrão e na quantidade inicial
        updateTotalPrice(parseFloat('{{ $crusts->first()->price }}'));
    </script>
@endsection
