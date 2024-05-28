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



        .product-card {
            background-color: #fff;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            cursor: pointer;
            position: relative;
            /* Adicionado */
        }

        .product-card h3 {
            margin-top: 0;
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            margin-right: 10px;
        }

        .product-image img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .product-checkbox {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .swipper-container {
            display: none;
            width: 58%;
            padding: 20px;
            background: #ffd100;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            bottom: 63px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 999;
        }

        .swipper-slide {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
        }

        .swipper-close {
            position: absolute;
            top: -3px;
            right: -8px;
            cursor: pointer;
        }

        .swipper-close i {
            font-size: 24px;
            color: #888;
        }

        /* Estilos para o checkbox */
        /* Estilos para o checkbox */
        .custom-checkbox {
            position: relative;
            display: inline-block;
            width: 30px;
            height: 20px;
            cursor: pointer;
        }

        .custom-checkbox input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .custom-checkbox .checkbox-control {
            position: absolute;
            top: 0;
            left: 0;
            width: 20px;
            height: 20px;
            border: 2px solid #ccc;
            border-radius: 50%;
            /* Deixa o checkbox redondo */
            transition: background-color 0.3s, border-color 0.3s;
        }

        .custom-checkbox .checkbox-label {
            position: absolute;
            top: 50%;
            left: 25px;
            transform: translateY(-50%);
            color: #333;
            font-size: 14px;
        }

        .custom-checkbox input:checked+.checkbox-control {
            background-color: #00ff00;
            /* Altera a cor de fundo para verde */
            border-color: #00ff00;
            /* Altera a cor da borda para verde */
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
            /* Adiciona um efeito de brilho */
        }

        .custom-checkbox input:checked+.checkbox-control::before {
            content: '';
            /* Remove o marcador padrão */
        }

        /* Efeitos para o card selecionado */
        .product-card {
            background-color: #fff;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            cursor: pointer;
            position: relative;
            transition: transform 0.3s ease;
            /* Adiciona uma transição suave */
        }

        .product-card.selected {
            transform: scale(1.1);
            /* Faz o card aumentar de tamanho */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            /* Aumenta a sombra para criar a ilusão de elevação */
        }

        .product-card.selected:focus {
            outline: none;
            /* Remove a borda de foco */
        }

        .product-price {
            position: absolute;
            bottom: 0px;
            right: 7px;
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

        .footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }

        .footer .total-price {
            font-weight: bold;
        }

        .footer button {
            padding: 10px 20px;
            background-color: #ffd100;
            border: none;
            border-radius: 5px;
            color: #333;
            font-weight: bold;
            cursor: pointer;
        }

        .footer button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
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
            margin-top: 20px;
            flex-direction: column;
            position: relative;
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
            position: absolute;
            bottom: -20px;
            right: 2px;
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
        <h2>Escolha até dois sabores</h2>
        <div class="product-list">
            @foreach ($products as $product)
                <div class="product-card" data-product-id="{{ $product->id }}">
                    <div class="product-image">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}">
                    </div>
                    <div class="product-details">
                        <div class="product-title">{{ $product->name }}</div>
                        <div class="product-description">{{ $product->description }}</div>
                    </div>
                    <h3 style="display: none">{{ $product->name }}</h3>
                    <p style="display: none">{{ $product->description }}</p>
                    <div class="product-price">R$ {{ number_format($product->price, 2, ',', '.') }}</div>
                    <input type="checkbox" class="product-checkbox">
                </div>
            @endforeach
        </div>
        <h2>Bordas e Observações</h2>
        @if (count($crusts) > 0)
            <div class="crust-options">
                @foreach ($crusts as $crust)
                    <div class="crust-option">
                        <input type="radio" name="crust" value="{{ $crust->id }}" data-price="{{ $crust->price }}"
                            {{ $loop->first ? 'checked' : '' }}>
                        {{ $crust->name }} <span class="crust-price">+ R$
                            {{ number_format($crust->price, 2, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
            <div class="swipper-container" id="swipper-container">
                <div class="swipper-close" id="swipper-close">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="swiper-wrapper">
                    <!-- Swiper slides will be appended here -->
                </div>
            </div>


            <div class="observation">
                <i class="fa fa-pencil"></i><small>Pizza 1</small>
                <textarea id="observation1" rows="2" maxlength="140" placeholder="Alguma Observação?"></textarea>
                <div class="char-count" id="char-count1">0/140</div>
            </div>


            <div class="observation">
                <i class="fa fa-pencil"></i><small>Pizza 2</small>
                <textarea id="observation2" rows="2" maxlength="140" placeholder="Alguma Observação?"></textarea>
                <div class="char-count" id="char-count2">0/140</div>
            </div>

    </div>
    @endif
    <div class="sobe" style="margin-top: 116px;"></div>
    <div class="footer">
        <div class="total-price">Total: R$ <span id="totalPrice">0.00</span></div>
        <button id="addToCartButton" disabled>Adicionar ao Carrinho</button>
    </div>
@endsection

@section('scripts')
    <script>
        const productCards = document.querySelectorAll('.product-card');
        const swipperContainer = document.getElementById('swipper-container');
        const swipperClose = document.getElementById('swipper-close');
        const totalPriceElement = document.getElementById('totalPrice');
        const addToCartButton = document.getElementById('addToCartButton');

        let selectedProducts = [];
        var selectedCrust = null;

        document.getElementById('observation1').addEventListener('input', function() {
            const charCount = this.value.length;
            document.getElementById('char-count1').innerText = charCount + '/140';
            document.getElementById('observation-input1').value = this.value;
        });

        document.getElementById('observation2').addEventListener('input', function() {
            const charCount = this.value.length;
            document.getElementById('char-count2').innerText = charCount + '/140';
            document.getElementById('observation-input2').value = this.value;
        });


        productCards.forEach(card => {
            card.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const checkbox = this.querySelector('.product-checkbox');
                const productName = this.querySelector('.product-title').textContent;

                if (checkbox.checked) {
                    checkbox.checked = false;
                    const index = selectedProducts.indexOf(productId);
                    if (index > -1) {
                        selectedProducts.splice(index, 1);
                    }
                    updateObservationText(productId, '');
                } else {
                    if (selectedProducts.length >= 2) {
                        alert('Você só pode selecionar até dois sabores.');
                        return;
                    }
                    checkbox.checked = true;
                    selectedProducts.push(productId);
                    updateObservationText(productId, productName);
                }

                updateSwiper();

                // Adiciona/Remove a classe 'selected' para aplicar o efeito ao card selecionado
                this.classList.toggle('selected', checkbox.checked);

                updateTotalPrice();
                updateAddToCartButton();
            });
        });

        function updateObservationText(productId, productName) {
            const observationId = productId === selectedProducts[0] ? 'observation1' : 'observation2';
            document.getElementById(observationId).previousElementSibling.innerHTML = productName;
        }
        // Adicione um evento de clique para os elementos .crust-option
        const crustOptions = document.querySelectorAll('.crust-option');
        crustOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Seleciona o input de rádio dentro do elemento .crust-option clicado
                const radioInput = this.querySelector('input[type="radio"]');
                // Verifica se o input já está marcado
                if (!radioInput.checked) {
                    // Marca o input
                    radioInput.checked = true;
                    // Atualiza o preço total
                    selectedCrust = radioInput.value;
                    updateTotalPrice();
                }
            });
        });

        function updateSwiper() {
            const swiperWrapper = document.querySelector('.swiper-wrapper');
            swiperWrapper.innerHTML = '';
            selectedProducts.forEach(productId => {
                const productName = document.querySelector(`.product-card[data-product-id="${productId}"] h3`)
                    .textContent;
                const slide = document.createElement('div');
                slide.classList.add('swiper-slide');
                slide.textContent = "Metade : " + productName;
                swiperWrapper.appendChild(slide);
            });

            if (selectedProducts.length > 0) {
                swipperContainer.style.display = 'block';
            } else {
                swipperContainer.style.display = 'none';
            }
        }

        function updateTotalPrice() {
            let maxPrice = 0;
            selectedProducts.forEach(productId => {
                const productPrice = parseFloat(document.querySelector(
                    `.product-card[data-product-id="${productId}"] .product-price`).textContent.replace(
                    'R$ ', '').replace(',', '.'));
                if (productPrice > maxPrice) {
                    maxPrice = productPrice;
                }
            });

            // Adicione o preço da borda selecionada ao preço total
            if (selectedCrust) {
                const crustPrice = parseFloat(document.querySelector(`input[name="crust"][value="${selectedCrust}"]`)
                    .dataset.price);
                maxPrice += crustPrice;
            }

            totalPriceElement.textContent = maxPrice.toFixed(2);
        }

        function updateAddToCartButton() {
            if (selectedProducts.length === 2) {
                addToCartButton.disabled = false;
            } else {
                addToCartButton.disabled = true;
            }
        }

        swipperClose.addEventListener('click', function() {
            swipperContainer.style.display = 'none';
            productCards.forEach(card => {
                card.querySelector('.product-checkbox').checked = false;
                card.classList.remove('selected');
            });
            selectedProducts = [];
            updateTotalPrice();
            updateAddToCartButton();
        });

        document.getElementById('addToCartButton').addEventListener('click', function() {
            const productIds = selectedProducts;
            let crustId = selectedCrust;
            if (crustId === null) {
                crustId = 1; // Defina 1 como o valor padrão se nenhum tipo de borda for selecionado
            }
            const observation1 = document.getElementById('observation1').value;
            const observation2 = document.getElementById('observation2').value;

            const formData = new FormData();
            formData.append('product_ids', JSON.stringify(productIds));
            formData.append('crust_id', crustId);
            formData.append('observation1', observation1);
            formData.append('observation2', observation2);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route('cart.add2') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao adicionar produto ao carrinho');
                    }
                    return response.json();
                })
                .then(data => {
                    // Redirecionar para a página de checkout, se necessário
                    window.location.href = '{{ route('checkout.home') }}';
                })
                .catch(error => {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'success',
                        title: "Adicionado com Sucesso",
                    })

                    setTimeout(() => {
                        window.location.href = '{{ route('checkout.home') }}';
                    }, 3000);
                });
        });
    </script>
@endsection
