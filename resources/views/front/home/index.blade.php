<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
/>
<link rel="stylesheet" href="../style.css">
    <title>Meu Site em Breve</title>
    <!-- Adicione links para bibliotecas externas via CDN aqui, se necessário -->
    <!-- Exemplo: <link rel="stylesheet" href="https://example.com/style.css"> -->
    <!-- Exemplo: <script src="https://example.com/script.js"></script> -->
    
</head>
<body>
    <header>
        <a href="#" class="logo">Pizza!  <i class="fa-solid fa-pizza-slice"></i> </a>
        <nav class="navbar">
            <a href="#">HOME</a>
            <a href="#dishes">PIZZA</a>
            <a href="#">HOME</a>
            <a href="#">HOME</a>
            <a href="#">HOME</a>
        </nav>

        <div class="icons">
            <i class="fas fa-bars" id="menu-bars"></i>
            <i class="fas fa-search" id="search-icon"></i>
            <a href="#" class="fas fa-heart"></a>
            <a href="carrinho.balde.php" class="fas fa-shopping-cart"></a>
        </div>
    </header>

    <form action="" id="search-form">
        <input type="search" placeholder="Procurar..." name="" id="search-box">
        <label for="search-box" class="fas fa-search"></label>
        <i class="fas fa-times" id="close"></i>
    </form>

    <section class="home" id="home">

        <div class="swiper mySwiper home-slider">

            <div class="swiper-wrapper wrapper">
                <div class="swiper-slide slide">
                    <div class="content">
                        <span>Pizzaria Bologne</span>
                        <h3>Monte do seu jeito!</h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                        <a href="#" class="btn">Proximo</a>
                    </div>
                    <div class="image">
                        <img src="https://img.freepik.com/fotos-premium/pizza-isolada-em-fundo-branco-ia-generativa_74760-6895.jpg" alt="">
                    </div>
                </div>


                <div class="swiper-slide slide">
                    <div class="content">
                        <span>Pizzaria Bologne</span>
                        <h3>Monte do seu jeito!</h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                        <a href="#" class="btn">Proximo</a>
                    </div>
                    <div class="image">
                        <img src="https://i.pinimg.com/736x/30/74/75/3074756e1c21d6a7e8e14ee339df13e7.jpg" alt="">
                    </div>
                </div>

                <div class="swiper-slide slide">
                    <div class="content">
                        <span>Pizzaria Bologne</span>
                        <h3>Monte do seu jeito!</h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                        <a href="#" class="btn">Proximo</a>
                    </div>
                    <div class="image">
                        <img src="https://img.freepik.com/fotos-premium/uma-pizza-com-calabresa-em-um-fundo-branco_900101-25781.jpg" alt="">
                    </div>
                </div>


            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <section class="dishes" id="dishes">
        <h3 class="sub-heading">Pizzas</h3>
        <h1 class="heading">Bejamim Pizzas</h1>

        <div class="box-container">
            <div class="box">
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-eye"></a>
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSaK09xx0uhqzD_twr3sIWov8d-CRqyu8bAzKVeJczCyQ&s" alt="">
                <h3>Delyveri</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <span>Valor $$$</span>
                <a href="#" class="btn"> add ao carrinho</a>
            </div>

            <div class="box">
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-eye"></a>
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSaK09xx0uhqzD_twr3sIWov8d-CRqyu8bAzKVeJczCyQ&s" alt="">
                <h3>Delyveri</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <span>Valor $$$</span>
                <a href="#" class="btn"> add ao carrinho</a>
            </div>

            <div class="box">
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-eye"></a>
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSaK09xx0uhqzD_twr3sIWov8d-CRqyu8bAzKVeJczCyQ&s" alt="">
                <h3>Delyveri</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <span>Valor $$$</span>
                <a href="#" class="btn"> add ao carrinho</a>
            </div>

            <div class="box">
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-eye"></a>
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSaK09xx0uhqzD_twr3sIWov8d-CRqyu8bAzKVeJczCyQ&s" alt="">
                <h3>Delyveri</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <span>Valor $$$</span>
                <a href="#" class="btn"> add ao carrinho</a>
            </div>

            <div class="box">
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-eye"></a>
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSaK09xx0uhqzD_twr3sIWov8d-CRqyu8bAzKVeJczCyQ&s" alt="">
                <h3>Delyveri</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <span>Valor $$$</span>
                <a href="#" class="btn"> add ao carrinho</a>
            </div>

            <div class="box">
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-eye"></a>
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSaK09xx0uhqzD_twr3sIWov8d-CRqyu8bAzKVeJczCyQ&s" alt="">
                <h3>Delyveri</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <span>Valor $$$</span>
                <a href="#" class="btn"> add ao carrinho</a>
            </div>
        </div>
    </section>




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


    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="../script.js"></script>
</body>
</html>
