@extends('front.layout.app')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f8f8f8;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.carousel {
    position: relative;
    width: 80%;
    max-width: 900px;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.carousel-track {
    display: flex;
    transition: transform 0.3s ease-in-out;
}

.carousel-item {
    min-width: calc(100% / 3); /* Ajusta a largura dos itens para caber 3 por vez */
    box-sizing: border-box;
    padding: 20px;
    background-color: white;
    text-align: center;
}

.carousel-item img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}

.carousel-item h2 {
    margin: 10px 0;
}

.carousel-item p {
    color: #666;
}

.carousel-button {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(0, 0, 0, 0.5);
    border: none;
    color: white;
    font-size: 18px;
    padding: 10px;
    cursor: pointer;
    border-radius: 50%;
}

.carousel-button.prev {
    left: 10px;
}

.carousel-button.next {
    right: 10px;
}

</style>
endsection
@section('content')


<section class="carrinho">
    <div class="cart">
        <h1>Seu Carrinho</h1>
        <div class="cart-item">
            <img src="https://cdn-icons-png.freepik.com/256/2454/2454219.png?ga=GA1.1.969032829.1714322674&semt=ais_hybrid" alt="Produto 1">
            <div class="item-details">
                <h2>Produto 1</h2>
                <p>Descrição do produto 1.</p>
                <span class="price">$10.00</span>
                <div class="quantity">
                    <button class="decrement">-</button>
                    <span class="quantity-number">1</span>
                    <button class="increment">+</button>
                </div>
            </div>
        </div>
        <div class="cart-item">
            <img src="https://cdn-icons-png.freepik.com/256/2454/2454219.png?ga=GA1.1.969032829.1714322674&semt=ais_hybrid" alt="Produto 2">
            <div class="item-details">
                <h2>Produto 2</h2>
                <p>Descrição do produto 2.</p>
                <span class="price">$20.00</span>
                <div class="quantity">
                    <button class="decrement">-</button>
                    <span class="quantity-number">1</span>
                    <button class="increment">+</button>
                </div>
            </div>
        </div>
        
        <div class="carousel">
        <div class="carousel-track">
            <div class="carousel-item">
                <img src="https://cdn-icons-png.freepik.com/256/2405/2405479.png?semt=ais_hybrid" alt="Bebida 1">
                <h2>Bebida 1</h2>
                <p>Descrição da bebida 1.</p>
            </div>
            <div class="carousel-item">
                <img src="https://cdn-icons-png.freepik.com/256/4072/4072195.png?ga=GA1.1.969032829.1714322674&semt=ais_hybrid" alt="Bebida 2">
                <h2>Bebida 2</h2>
                <p>Descrição da bebida 2.</p>
            </div>
            <div class="carousel-item">
                <img src="https://cdn-icons-png.freepik.com/256/2405/2405451.png?ga=GA1.1.969032829.1714322674&semt=ais_hybrid" alt="Bebida 3">
                <h2>Bebida 3</h2>
                <p>Descrição da bebida 3.</p>
            </div>
            <div class="carousel-item">
                <img src="https://cdn-icons-png.freepik.com/256/2405/2405479.png?semt=ais_hybrid" alt="Bebida 1">
                <h2>Bebida 1</h2>
                <p>Descrição da bebida 1.</p>
            </div>
            <div class="carousel-item">
                <img src="https://cdn-icons-png.freepik.com/256/4072/4072195.png?ga=GA1.1.969032829.1714322674&semt=ais_hybrid" alt="Bebida 2">
                <h2>Bebida 2</h2>
                <p>Descrição da bebida 2.</p>
            </div>
            <div class="carousel-item">
                <img src="https://cdn-icons-png.freepik.com/256/2405/2405451.png?ga=GA1.1.969032829.1714322674&semt=ais_hybrid" alt="Bebida 3">
                <h2>Bebida 3</h2>
                <p>Descrição da bebida 3.</p>
            </div>
        </div>
        <button class="carousel-button prev">←</button>
        <button class="carousel-button next">→</button>
    </div>

    <div class="total">
            <h2>Total: $30.00</h2>
        </div>
        <button class="checkout">Finalizar Compra</button>
    </div>


</section>

@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
      const track = document.querySelector('.carousel-track');
        const items = Array.from(track.children);
        const nextButton = document.querySelector('.next');
        const prevButton = document.querySelector('.prev');
        const itemWidth = items[0].getBoundingClientRect().width;
        const itemsPerView = 3;
        let currentIndex = 0;

        function updateCarousel() {
            const totalWidth = itemWidth * itemsPerView;
            track.style.transform = `translateX(-${currentIndex * totalWidth}px)`;
        }

        nextButton.addEventListener('click', () => {
            if (currentIndex < items.length / itemsPerView - 1) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            updateCarousel();
        });

        prevButton.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = Math.ceil(items.length / itemsPerView) - 1;
            }
            updateCarousel();
        });

        updateCarousel();
</script>
@endsection
