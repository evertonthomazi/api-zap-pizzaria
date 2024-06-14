<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cupom Fiscal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 80mm; /* Largura de uma impressora térmica padrão */
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 10px;
        }

        .item {
            border-bottom: 1px dashed #000;
            padding: 5px 0;
            page-break-inside: avoid; /* Evita quebra de página dentro desses elementos */
        }

        .item:last-child {
            border-bottom: none;
        }

        .item-details h2 {
            margin: 0;
            font-size: 14px;
            page-break-inside: avoid; /* Evita quebra de página dentro desses elementos */
        }

        .item-details p {
            margin: 2px 0;
            font-size: 12px;
            page-break-inside: avoid; /* Evita quebra de página dentro desses elementos */
        }

        .total {
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
            text-align: right;
        }

        .footer {
            border-top: 1px dashed #000;
            padding-top: 5px;
        }

        .row {
            display: flex;
            justify-content: space-between;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                width: 80mm; /* Largura de uma impressora térmica padrão */
            }

            .item, .header, .footer {
                page-break-inside: avoid; /* Evita quebra de página dentro desses elementos */
            }

            .item-details h2, .item-details p {
                page-break-inside: avoid; /* Evita quebra de página dentro desses elementos */
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Nome da Loja</h1>
        <p>Endereço da Loja</p>
        <p>Telefone: (XX) XXXX-XXXX</p>
    </div>

    @foreach ($cart as $item)
        <div class="item">
            <div class="item-details">
                @php
                    $itemDetails = explode('/', $item['name']);
                    $pizzaName = trim($itemDetails[0]);
                    $flavorsCount = count($itemDetails) - 1;
                    $flavors = '';
                    for ($i = 1; $i <= $flavorsCount; $i++) {
                        $flavors .= $itemDetails[$i];
                        if ($i < $flavorsCount) {
                            $flavors .= ', ';
                        }
                    }
                    $observations = explode(' / ', $item['observation']);
                @endphp
                <h2>{{ $item['name'] }}</h2>
                <div class="row">
                    <div class="col-md-6">
                        @if ($flavorsCount > 0)
                            <p>Tipo: {{ $flavorsCount + 1 }} Sabores</p>
                        @endif
                        <p>Quantidade: {{ $item['quantity'] }}</p>
                        <p>Preço Unitário: R$ {{ $item['price'] }}</p>
                        <p>Total: R$ {{ number_format($item['total'], 2, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6">
                        @if (!empty($observations))
                            <div>
                                <h3>Observações:</h3>
                                @foreach ($observations as $index => $observation)
                                    @php
                                        $obsPizzaName = trim($itemDetails[$index] ?? '');
                                        $obsText = trim($observation);
                                    @endphp
                                    @if (!empty($obsText))
                                        <p class="observation">{{ $obsPizzaName }}: {{ $obsText }}</p>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div style="clear:both;"></div>
        </div>
    @endforeach

    <div class="total">Total Geral: R$
        {{ number_format(array_sum(array_column($cart, 'total')) + session('taxa_entrega'), 2, ',', '.') }}
    </div>

    <div class="footer">
        <p>Obrigado pela preferência!</p>
    </div>

    <!-- Script do QZ Tray -->
    <script src="https://demo.qz.io/js/qz-tray.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/node-forge@0.9.1/dist/forge.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            qz.api.setSha256Type(function(data) {
                return crypto.subtle.digest("SHA-256", new TextEncoder("utf-8").encode(data)).then(function(hash) {
                    return btoa(String.fromCharCode.apply(null, new Uint8Array(hash)));
                });
            });

            qz.api.setPromiseType(function(fn) { return new Promise(fn); });


            const certificate = `-----BEGIN CERTIFICATE-----
MIIECzCCAvOgAwIBAgIGAZAJrz41MA0GCSqGSIb3DQEBCwUAMIGiMQswCQYDVQQG
EwJVUzELMAkGA1UECAwCTlkxEjAQBgNVBAcMCUNhbmFzdG90YTEbMBkGA1UECgwS
UVogSW5kdXN0cmllcywgTExDMRswGQYDVQQLDBJRWiBJbmR1c3RyaWVzLCBMTEMx
HDAaBgkqhkiG9w0BCQEWDXN1cHBvcnRAcXouaW8xGjAYBgNVBAMMEVFaIFRyYXkg
RGVtbyBDZXJ0MB4XDTI0MDYxMDIzNDMxOFoXDTQ0MDYxMDIzNDMxOFowgaIxCzAJ
BgNVBAYTAlVTMQswCQYDVQQIDAJOWTESMBAGA1UEBwwJQ2FuYXN0b3RhMRswGQYD
VQQKDBJRWiBJbmR1c3RyaWVzLCBMTEMxGzAZBgNVBAsMElFaIEluZHVzdHJpZXMs
IExMQzEcMBoGCSqGSIb3DQEJARYNc3VwcG9ydEBxei5pbzEaMBgGA1UEAwwRUVog
VHJheSBEZW1vIENlcnQwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDq
8ZuheqU85Vat2csxtzQO37Mef2Rai4iO7W6m9IeuywiR7NxiohygvT+xOuezJySu
EuAlGSnaMHmwFwCDrGlvuMBzvRVkjEWMHweIWylE9GCFVOCmUvNL4KIs2f3cYG6Z
bIM6Rs/Ki+JMWpsZbOp2Oy98xhC4MJvQsyYX2rQAiXBwSufWTsZnyV4kQj6Tm7Re
QkyzqRPZpTt1uTqdInCf9PKmXGH9BlQXqgka/l6D2FZoFUPzSCX5YoX7awJcNC5A
YGdiIPGJyqksNMpW4W1YtXtSbDRWe65UMyp9S9oFUpwlrqIuAY7GGBC5Wi/iqUnj
waDaz9BrBJN/pMIrWVRDAgMBAAGjRTBDMBIGA1UdEwEB/wQIMAYBAf8CAQEwDgYD
VR0PAQH/BAQDAgEGMB0GA1UdDgQWBBS+S9OyeCKrPf4GW3yJ5k+Uxx8DQzANBgkq
hkiG9w0BAQsFAAOCAQEA4OOOHuoznrahGlOydgh+mcv3YEwTydkcjGlDQ6YhnCB2
40EVqt9ADEY6BlYEqqlNvAEGWwpMtDgUwxdTBX6nvhxxp941oQwJtXd/NSziOZ8I
wpGZjc3QWQgk/rAqcltOk8xSExgdzi4hElFT6lELmh+itDIXFwaiI7wkz/mjLnOG
PV/+wFT6xKaMYUMyCNia6RrfEf/WdRN4pdiHDMzrXJ9hjp0VZrRkWTeAa2IenrzD
n5DFvMUoGXaSf51mqJIDrIS/5H7+zJjAD2iudlKQ827qGNFCUeLSUZvmUH6juRg4
ZPPP+n6fYOs9Ja4AO8OqVEKhOby+amKHlIUvqCepBg==
-----END CERTIFICATE-----
`;
            const privateKey = `-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDq8ZuheqU85Vat
2csxtzQO37Mef2Rai4iO7W6m9IeuywiR7NxiohygvT+xOuezJySuEuAlGSnaMHmw
FwCDrGlvuMBzvRVkjEWMHweIWylE9GCFVOCmUvNL4KIs2f3cYG6ZbIM6Rs/Ki+JM
WpsZbOp2Oy98xhC4MJvQsyYX2rQAiXBwSufWTsZnyV4kQj6Tm7ReQkyzqRPZpTt1
uTqdInCf9PKmXGH9BlQXqgka/l6D2FZoFUPzSCX5YoX7awJcNC5AYGdiIPGJyqks
NMpW4W1YtXtSbDRWe65UMyp9S9oFUpwlrqIuAY7GGBC5Wi/iqUnjwaDaz9BrBJN/
pMIrWVRDAgMBAAECggEACv9ONUvOtOqt7lYAgI4+cz0eCZ0VmctoPqXMhSdzER0o
hIlyyMclJzz/NeY3oZeBIbN5JyNmEHQubgNVnymQhziTAZfl4B6qGWSmb0fdHFYt
YnBWUlLAWAU8HMoDa6+z3Z31B+L3/QIj5fEOdi02owbNh+ikMGBOGtcX6baaBbWn
lsnP6OUpaDsdFR/cR9WSsQ1XEhPV991pv5F4/pObhBiPXBJ2tnPImWC8ozAKKodm
PaH68qzPbH5fna9ehPN7S2qhZueaU+KxWPtiPF3xuxyLJAoAIW0fwIknJ/Bkn5lt
7dnBTkNhvQOotyzSHzlg7a46r3OqE/0odSdBTHTmCQKBgQD/jdZzYNba7kfDDDPJ
p6pjXu04FRhry5hDbLZFbi8vE7squ3N+HOa13sCQDBwtKbF/5bwiK/fdiT5AJU26
/QD1qyO2i0w7sWaRY2wqMjNQsOhq6APtbYV4WOVaQjcwIo5zIT9Annk1Wtnllx9Y
uPAmgNsk1CCk/yW9gYFnX1u9fwKBgQDrWpAodZn/jbaur2LDnRf3Sn3EP76iWXyF
A24BnQ4s2UzhijuGTllHR9l6ENuSFIGfwzoRSLXyJvG4WqnnkjdmoO0tVNBS3AFo
EESdmtwOtukcRdDwMlJhrsmKmNyX3GtoXHJYL0/TMlLqKsCy8QSmENO0jJDxpYPy
weHybHNTPQKBgHE7+Ozw2OLzOAr96iiNSVt0oWn7NvH0qYgesHj9RJz4gjOCEb+s
sLGffPWW2BlO4x6Hvh/qGY8pE9M6dEHJf1ZbuZxWBrZgedEsG65qxgAYEzPjkHHw
2HYl9aJTur5yYWo0LiHE1nJEyk4H81TNxWNaL0AXWkuh3qgkrSEPyMejAoGBALc1
y6zDR3tJjSFVBHQ0cTifM2I3ISZutOEyt0roQOWmzxNvsUCu8w7rHp/ISbTg47tD
lYto58I2kMqrQUI0b44jzh4QvQ8TlsC0kUWDDLUjrDnHG4wwEDNchiWvM2HAQ5h9
BsIhKmnE7gi9+T4TI0RHKxaMqjSCXUH3rfUyeWAdAoGBAKq/EaFCN8zFt2PpALjW
keAL+zlvBE59lWSeNN07zE00+nsWl7REALqPl67WpjBxqMIgT/5GgjLJraY7P5Rr
kURHj8kZZVgfy01YsMIGjcs78yuv4IPU/dOWnO+3rrLy7lWiavctiKXCCseuAmrW
lCWS1aShIjjGPf8srZzgy0y7
-----END PRIVATE KEY-----
`;

         
            qz.security.setCertificatePromise(function(resolve, reject) {
                resolve(certificate);
            });

            qz.security.setSignaturePromise(function(toSign) {
                return function(resolve, reject) {
                    var sign = forge.md.sha256.create();
                    sign.update(toSign, 'utf8');
                    var pki = forge.pki;
                    var privateKeyPEM = pki.privateKeyFromPem(privateKey);
                    resolve(btoa(privateKeyPEM.sign(sign)));
                };
            });

            function connectAndPrint() {
                qz.websocket.connect().then(() => {
                    return qz.printers.find("Microsoft Print to PDF"); // Nome da impressora virtual
                }).then(printer => {
                    if (!printer) {
                        throw new Error("No printer found");
                    }
                    var config = qz.configs.create(printer); // Configuração da impressora
                    var data = [
                        { type: 'raw', format: 'plain', data: document.body.innerHTML },
                    ];
                    return qz.print(config, data);
                }).catch(err => {
                    console.error("Failed to connect to QZ Tray:", err);
                });
            }

            // Verifica se o QZ Tray está ativo
            if (!qz.websocket.isActive()) {
                connectAndPrint();
            } else {
                connectAndPrint();
            }
        });
    </script>
</body>
</html>