<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos em Breve</title>
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
            text-align: center;
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
            transition: top 0.3s;
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
    </style>
</head>
<body>

    <div class="category-header" id="category-header">Categoria 1</div>

    <div class="container" id="product-container">
        <!-- Categorias e produtos serão adicionados aqui pelo JavaScript -->
    </div>

    <script>
        const categories = [
            'Categoria 1', 'Categoria 2', 'Categoria 3', 
            'Categoria 4', 'Categoria 5', 'Categoria 6', 'Categoria 7'
        ];

        const productTemplate = (categoryIndex, productIndex) => `
            <div class="product">
                <div class="image-blur"></div>
                <img src="https://via.placeholder.com/60" alt="Product Image">
                <div class="product-details">
                    <div class="product-title">Produto ${productIndex + 1}</div>
                    <div class="product-description">Descrição do produto ${productIndex + 1} da ${categories[categoryIndex]}</div>
                </div>
                <div class="product-price">R$ ${(productIndex + 1) * 10},00</div>
            </div>
        `;

        const container = document.getElementById('product-container');

        categories.forEach((category, categoryIndex) => {
            const categoryDiv = document.createElement('div');
            categoryDiv.classList.add('category');
            categoryDiv.id = `category-${categoryIndex}`;
            categoryDiv.innerHTML = `<h2>${category}</h2>`;

            for (let i = 0; i < 5; i++) {
                categoryDiv.innerHTML += productTemplate(categoryIndex, i);
            }

            container.appendChild(categoryDiv);
        });

        window.addEventListener('scroll', () => {
            let lastCategoryIndex = 0;
            const categories = document.querySelectorAll('.category');
            const header = document.getElementById('category-header');
            categories.forEach((category, index) => {
                const rect = category.getBoundingClientRect();
                if (rect.top <= 60) {
                    lastCategoryIndex = index;
                }
            });
            header.innerText = categories[lastCategoryIndex].querySelector('h2').innerText;
        });
    </script>

</body>
</html>
