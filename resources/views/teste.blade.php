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

    <!-- Conteúdo do Cupom -->
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
MIIECzCCAvOgAwIBAgIGAZAJcaxLMA0GCSqGSIb3DQEBCwUAMIGiMQswCQYDVQQG
EwJVUzELMAkGA1UECAwCTlkxEjAQBgNVBAcMCUNhbmFzdG90YTEbMBkGA1UECgwS
UVogSW5kdXN0cmllcywgTExDMRswGQYDVQQLDBJRWiBJbmR1c3RyaWVzLCBMTEMx
HDAaBgkqhkiG9w0BCQEWDXN1cHBvcnRAcXouaW8xGjAYBgNVBAMMEVFaIFRyYXkg
RGVtbyBDZXJ0MB4XDTI0MDYxMDIyMzYwM1oXDTQ0MDYxMDIyMzYwM1owgaIxCzAJ
BgNVBAYTAlVTMQswCQYDVQQIDAJOWTESMBAGA1UEBwwJQ2FuYXN0b3RhMRswGQYD
VQQKDBJRWiBJbmR1c3RyaWVzLCBMTEMxGzAZBgNVBAsMElFaIEluZHVzdHJpZXMs
IExMQzEcMBoGCSqGSIb3DQEJARYNc3VwcG9ydEBxei5pbzEaMBgGA1UEAwwRUVog
VHJheSBEZW1vIENlcnQwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDu
fHmWDOKwYxRSUCzNUIP8xdqS4PPOYK1AqpW0Yhta6ZIH3f0+n8ZJAjnu0jA7KVSA
3T87o2PUQ62jPvObneJW/fQOTImaiU3VIbqHs4zpoZqRpNrHxQ0NyACZQ/rsExHz
GMteIg8QJpeL1uP9AdbZ67ZUalug19ici91L29Qqjpu8eHN6FZKC8ZIyebGwDeXU
M/U33hMX+q2S3zQQZza5AS7IRlg0Cv/9hkbuMOQQF4qlw61iURVjblfyo4N1UHkX
eMJ3R8NPGUlYbbImKatB/keFtHGZR6H5mVgLQgR8IJpX3nT0pY+P9QyATApWETYl
bKhUEvL1jYjTF85YrD8PAgMBAAGjRTBDMBIGA1UdEwEB/wQIMAYBAf8CAQEwDgYD
VR0PAQH/BAQDAgEGMB0GA1UdDgQWBBTEmKBpEee/npV3G9nKTyxW4+kL0zANBgkq
hkiG9w0BAQsFAAOCAQEAqkLS/nf9tC3K3UryQaAbKZwieSCDkmlmIc8k6HzqeavP
CfpemcSdkf5gJqrEgPwDOM72ElbbwC54ub+aitJ0rWq0bnZycqa12Ne48uCqs+LI
k+OAA4hi0Mpf2MinUUGn/AxZwEv26PqiQ+Pjd2e2h+ahgwinH8OQ9llOB+1tT4au
U0TiStgpwoec+IP0SgH9iMyrDq8SKHwdWsbjyjiOYp8Y4JjqNbwHMSdkblfjFVfi
vuadDBFOnoorktRDXPcdg57qW+snoprn8gFB9I5UXyHo4bJUpdLiVhV7cd/wRyGj
JkbrcOPOFG10t1Og3zGcS5zftQ8OJXtzIdXbIeMYeg==
-----END CERTIFICATE-----`;
            const privateKey = `-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDufHmWDOKwYxRS
UCzNUIP8xdqS4PPOYK1AqpW0Yhta6ZIH3f0+n8ZJAjnu0jA7KVSA3T87o2PUQ62j
PvObneJW/fQOTImaiU3VIbqHs4zpoZqRpNrHxQ0NyACZQ/rsExHzGMteIg8QJpeL
1uP9AdbZ67ZUalug19ici91L29Qqjpu8eHN6FZKC8ZIyebGwDeXUM/U33hMX+q2S
3zQQZza5AS7IRlg0Cv/9hkbuMOQQF4qlw61iURVjblfyo4N1UHkXeMJ3R8NPGUlY
bbImKatB/keFtHGZR6H5mVgLQgR8IJpX3nT0pY+P9QyATApWETYlbKhUEvL1jYjT
F85YrD8PAgMBAAECggEAGk9FIpadU5Q6o7IQnz3osbQqHtYOT5A+zMmE5neESQ1H
fPKyg3wg5eEG/x/VDlHK7sR5u1yVIM+ukjnZqitK0woFKKas742CNqcX6uyV+RhI
8xRaxnsoq4KK/l2Piwe3jTgJB4N+YevAAD9eu4S+bSAh50IshZwKW4raiQnhaChI
4bedX3Q+BJzqfZP7tg7XieTjyk+U9hrFgHsb3DAljwKRrBy98Hm3AGJjSiIZFNRa
JqnyyXAQRTgdbgFIFGzr73mYqpTD7rpVZ0X5CpNS8BGumorez+7gDn6nyLR1kBiQ
b0n/MmfugKhGvzF1xbyY5vFjnOnssP8P3RP/w8g2QQKBgQD826xgCC3GmZH3bh8G
f6ZBX5AI+Ab9q8rCpwMysA8Stl6Jz4KjhtC8ufj35As6dQ568iSUG4sqV8Ea7l7Y
OfVDHpXM5B7QXRFFXhk8/XRbBJp9n4/ux50yyHtUowYaVppSmE6K7U7lLCAZayNt
0E5OVJt0YOmHdH/45krLqt5pnwKBgQDxcxXmA5ZylRG9GGVjGM2z3hH7bqv7g9lk
9r5SRe3/Ev1DybpbMWHwMXHX+rscuDtOVn6vNzv6lGiqWja3EYoUCYt/F+OsN9i0
rYQ/UcTEwPGuIZNuiYPpKM56HhS+bvTOKY758rDsguR6ttMIDLlAfmCD0dLljvEQ
Tz1vvk4UkQKBgGYqnEtxAo3sHv5KkF6f1R00742wKaL1dsePk8s3N9/nr+se3ToV
juGygtYmXiMQiPlEPWNafbBuJKgtCEV1pZOpF/3hblHiSMgubSQnhIwCICoB/rYM
EYgjWTGpbR3XQCN/Rrz7hZUzbwTWNUp5kCo2JVmwjqscd1iqNC52q8chAoGBAOvi
7qMAIEyjXGgsXGkbQ2QVceX9sqIPpyTfdwLz9Nc9mxdODvWm4jMCa2GBQKqHRtF6
6VuPD9b4hOThLBFgXlDDHni1QyXujZW+67Pc0+sRQUxI2zujt67jwg1GNCf0SNDd
gySoOLdoDYXh9Xwoyhe7H9nI9Ux2gQbZE4GHH8sxAoGBAJ3CNK8/ocGu8BZ0MThr
JoB6Khy/xEkOGVbtSUxbuERTjlATp/PU0q3AmF2N1LsHuEH0xzomQWIMZ5KmyuqJ
yNDqIWi3DpYNIcgv1hXoxfPcCqZFRkYbl97a4bPCfrBIeT+O6dHqO0jp09pTB2xL
6YMha+Thd8ItMg+TM9lqAOKK
-----END PRIVATE KEY-----`;

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

            if (!qz.websocket.isActive()) {
                qz.websocket.connect().then(() => {
                    return qz.printers.find(); // Encontrar a impressora padrão
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
        });
    </script>
</body>
</html>
