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

        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            grid-gap: 20px;
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
            position: relative; /* Adicionado */
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
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            bottom: 20px;
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
            top: 10px;
            right: 10px;
            cursor: pointer;
        }

        .swipper-close i {
            font-size: 24px;
            color: #888;
        }
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
        border-radius: 3px;
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

    .custom-checkbox input:checked + .checkbox-control {
        background-color: #ff4500;
        border-color: #ff4500;
    }

    .custom-checkbox input:checked + .checkbox-control::before {
        content: '\2713';
        font-size: 12px;
        color: #fff;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    </style>
@endsection

@section('content')
    <div class="container">
        <h2>Escolha até dois sabores</h2>
        <div class="product-list">
            @foreach ($products as $product)
                <div class="product-card" data-product-id="{{ $product->id }}">
                    <div class="product-image">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}">
                    </div>
                    <h3>{{ $product->name }}</h3>
                    <p>{{ $product->description }}</p>
                    <input type="checkbox" class="product-checkbox">
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
    </div>
@endsection

@section('scripts')
    <script>
        const productCards = document.querySelectorAll('.product-card');
        const swipperContainer = document.getElementById('swipper-container');
        const swipperClose = document.getElementById('swipper-close');

        let selectedProducts = [];

        productCards.forEach(card => {
            card.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const checkbox = this.querySelector('.product-checkbox');

                if (checkbox.checked) {
                    // Se o checkbox já estiver marcado, desmarca-o
                    checkbox.checked = false;
                    const index = selectedProducts.indexOf(productId);
                    if (index > -1) {
                        selectedProducts.splice(index, 1);
                    }
                } else {
                    // Verifica se já foram selecionados dois produtos
                    if (selectedProducts.length >= 2) {
                        alert('Você só pode selecionar até dois sabores.');
                        return;
                    }
                    checkbox.checked = true;
                    selectedProducts.push(productId);
                }

                // Atualiza o Swiper
                updateSwiper();
            });
        });

        function updateSwiper() {
            const swiperWrapper = document.querySelector('.swiper-wrapper');
            swiperWrapper.innerHTML = '';
            selectedProducts.forEach(productId => {
                const productName = document.querySelector(`.product-card[data-product-id="${productId}"] h3`).textContent;
                const slide = document.createElement('div');
                slide.classList.add('swiper-slide');
                slide.textContent = productName;
                swiperWrapper.appendChild(slide);
            });

            if (selectedProducts.length > 0) {
                swipperContainer.style.display = 'block';
            } else {
                swipperContainer.style.display = 'none';
            }
        }

        swipperClose.addEventListener('click', function() {
    swipperContainer.style.display = 'none';
    // Desmarca todos os checkboxes
    productCards.forEach(card => {
        card.querySelector('.product-checkbox').checked = false;
    });
    // Limpa a lista de produtos selecionados
    selectedProducts = [];
});
</script>
@endsection
       
