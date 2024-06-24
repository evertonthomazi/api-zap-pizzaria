@extends('front.layout.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        

        .form-container {
            width: 80%;
            max-width: 500px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 5px;
            font-size: 13px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .header {
            background-color: #000;
            color: #fff;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            /* Adjusted to space items between */
        }

        .header i {
            cursor: pointer;
            margin-right: 10px;
        }

        .container {
            margin: 0px 15px 0px 15px;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: stretch;
            flex-direction: column;
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
    </style>
@endsection

@section('content')
    <div class="header">
        <div>
            <a href="{{ route('checkout.home') }}" class="header">
                <i class="fas fa-arrow-left"></i>
                <span>Voltar</span>
            </a>
        </div>

    </div>
    <div class="container">
        <div>
            <h3>Meu Cadastro</h3>
            <form action="/checkout/pagamento" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nome</label>
                    <input type="text" id="name" class="title-case" name="name" placeholder="Nome Completo" value="{{ $customer->name }}" required>
                </div>
                <input type="hidden" name="id" value="{{ $customer->id }}">
                <div class="form-group">
                    <label for="phone">Telefone</label>
                    <input type="text" id="phone" name="phone" placeholder="Telefone" value="{{ $customer->phone }}" required>
                </div>
                <div class="form-group">
                    <label for="zipcode">CEP</label>
                    <input type="text" id="zipcode" name="zipcode" placeholder="CEP" value="{{ $customer->zipcode }}" required>
                </div>
                <div class="form-group">
                    <label for="public_place">Logradouro</label>
                    <input type="text" id="public_place" name="public_place" readonly placeholder="Rua" value="{{ $customer->public_place }}" required>
                </div>
                <div class="form-group">
                    <label for="number">Número</label>
                    <input type="text" id="number" name="number" placeholder="Número" value="{{ $customer->number }}" required>
                </div>
                <div class="form-group">
                    <label for="complement">Complemento</label>
                    <input type="text" id="complement" name="complement" placeholder="Complemento" value="{{ $customer->complement }}">
                </div>
                <div class="form-group">
                    <label for="neighborhood">Bairro</label>
                    <input type="text" id="neighborhood" name="neighborhood" readonly placeholder="Bairro" value="{{ $customer->neighborhood }}" required>
                </div>
                <div class="form-group" style="display: none;">
                    <label for="city">Cidade</label>
                    <input type="text" id="city" name="city" placeholder="Cidade" value="{{ $customer->city }}" required>
                </div>
                <div class="form-group" style="display: none;">
                    <label for="state">Estado</label>
                    <input type="text" id="state" name="state" placeholder="Estado" value="{{ $customer->state }}" required>
                </div>
                <div class="checkout-footer">
                    <button type="submit" class="btn checkout-button">Pagamento</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('/assets/admin/vendor/jquery/jquery.min.js') }} "></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script src="{{ asset('/assets/utils.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#zipcode').mask('00000-000');

            $('#zipcode').on('input', function() {
                var cep = $(this).val().replace(/\D/g, '');
                if (cep.length === 8) {
                    $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {
                        if (!("erro" in dados)) {
                            $('#public_place').val(dados.logradouro);
                            $('#neighborhood').val(dados.bairro);
                            $('#city').val(dados.localidade);
                            $('#state').val(dados.uf);
                        } else {
                            alert("CEP não encontrado.");
                        }
                    });
                }
            });

            var phoneMaskBehavior = function(val) {
                    return val.replace(/\D/g, '').length === 9 ? '00000-0000' : '0000-00009';
                },
                phoneOptions = {
                    onKeyPress: function(val, e, field, options) {
                        field.mask(phoneMaskBehavior.apply({}, arguments), options);
                    }
                };

            $('#phone').mask(phoneMaskBehavior, phoneOptions);
        });
    </script>
@endsection
