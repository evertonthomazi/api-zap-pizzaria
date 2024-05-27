@extends('front.layout.app')

@section('css')
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        

        .category-header {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 10;
            padding: 10px;
            display: flex;
            overflow-x: auto;
            white-space: nowrap;
        }

        .category-header div {
            display: inline-block;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .category-header div.active {
            background-color: #ff4500;
            color: #fff;
        }

        .container {
            margin-top: 60px;
            padding: 20px;
        }

        .category {
            margin-bottom: 30px;
        }

        .product {
            background-color: #fff;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .product .image-blur {
            background-color: #ccc;
            width: 60px;
            height: 60px;
            margin-right: 10px;
            filter: blur(5px);
        }

        .product img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            margin-right: 10px;
        }

        .product-details {
            flex-grow: 1;
        }

        .product-title {
            font-size: 16px;
            font-weight: bold;
        }

        .product-description {
            font-size: 14px;
            color: #666;
        }

        .product-price {
            font-size: 16px;
            color: #ff4500;
            text-align: right;
        }

        .cart-footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #000;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            z-index: 1000;
            box-sizing: border-box;
        }

        .cart-icon {
            display: flex;
            align-items: center;
            position: relative;
        }

        .cart-icon i {
            font-size: 24px;
        }

        .cart-count {
            background-color: red;
            color: #fff;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 14px;
            position: absolute;
            top: -10px;
            right: -10px;
        }

        .view-cart a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
        }

        .animate {
            animation: shake 0.5s;
        }

        @keyframes shake {
            0% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            50% {
                transform: translateX(5px);
            }

            75% {
                transform: translateX(-5px);
            }

            100% {
                transform: translateX(0);
            }
        }
    </style>
@endsection

@section('content')
    <div class="category-header" id="category-header">
        <!-- Adicionando o item "Home" -->
        <div data-category-id="home">Home</div>
      
        @foreach ($categories as $category)
            <div data-category-id="{{ $category->id }}">{{ $category->name }}</div>
        @endforeach
    </div>
    <div class="container" id="product-container">
        <div class="category" id="category-home">
            <h2>Monte Sua Pizza Meio a Meio</h2>
            <div class="product" data-product-id="perso">
                <img src="https://maissaborgranjalisboa.onezap.link/wp-content/uploads/2022/03/meio-a-meio-scaled.jpg" alt="">
                <div class="product-details">
                    <div class="product-title">Escolha até 2 Sabores</div>
                    <div class="product-description">Prevalece o valor da Maior</div>
                </div>
                <div class="product-price"></div>
            </div>
        </div>
        @foreach ($categories as $category)
            <div class="category" id="category-{{ $category->id }}">
                <h2>{{ $category->name }}</h2>
                @foreach ($category->products as $product)
                    <div class="product" data-product-id="{{ $product->id }}">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}">
                        <div class="product-details">
                            <div class="product-title">{{ $product->name }}</div>
                            <div class="product-description">{{ $product->description }}</div>
                        </div>
                        <div class="product-price">R$ {{ number_format($product->price, 2, ',', '.') }}</div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    @if (count($cart) > 0)
    <footer class="cart-footer" onclick="redirectToCart()">
        <div class="cart-icon">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count">{{ count($cart) }}</span>
            
        </div>
        <button class="btn">Finalizar Pedido</button>
        <div class="view-cart" >
            Total: R$ {{ number_format(array_sum(array_column($cart, 'total')), 2, ',', '.') }}
        </div>
    </footer>
@endif

@endsection

@section('scripts')
    <script>
        function redirectToCart() {
    window.location.href = "{{ route('cart.show') }}";
}

        const categoryHeader = document.getElementById('category-header');
        const categories = document.querySelectorAll('.category-header div');
        const categoryElements = document.querySelectorAll('.category');
        const products = document.querySelectorAll('.product');

        products.forEach(product => {
            product.addEventListener('click', () => {
                const productId = product.getAttribute('data-product-id');
               
                if(productId === 'perso'){
                    const url = '/checkout/adicionar-2-sabores/';
                window.location.href = url;
                }else{
                    const url = '/checkout/adicionar-produto/' + productId;
                window.location.href = url;
                }
              
            });
        });

        categories.forEach(category => {
            category.addEventListener('click', () => {
                const categoryId = category.getAttribute('data-category-id');
                const categoryElement = document.getElementById('category-' + categoryId);

                window.scrollTo({
                    top: categoryElement.offsetTop - categoryHeader.offsetHeight,
                    behavior: 'smooth'
                });

                categories.forEach(cat => cat.classList.remove('active'));
                category.classList.add('active');
                categoryHeader.scrollLeft = category.offsetLeft - categoryHeader.offsetWidth / 2 + category
                    .offsetWidth / 2;
            });
        });

        window.addEventListener('scroll', () => {
            let lastCategoryIndex = 0;

            categoryElements.forEach((category, index) => {
                const rect = category.getBoundingClientRect();
                if (rect.top <= categoryHeader.offsetHeight) {
                    lastCategoryIndex = index;
                }
            });

            categories.forEach(cat => cat.classList.remove('active'));
            const activeCategory = categories[lastCategoryIndex];
            activeCategory.classList.add('active');
            categoryHeader.scrollLeft = activeCategory.offsetLeft - categoryHeader.offsetWidth / 2 + activeCategory
                .offsetWidth / 2;
        });

        document.addEventListener('DOMContentLoaded', function() {
            const cartIcon = document.querySelector('.cart-icon');
            setInterval(() => {
                cartIcon.classList.add('animate');
                setTimeout(() => {
                    cartIcon.classList.remove('animate');
                }, 1000);
            }, 3000);
        });
    </script>
@endsection
