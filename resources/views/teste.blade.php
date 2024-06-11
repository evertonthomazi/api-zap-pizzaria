<!DOCTYPE html>
<html>
<head>
    <title>Impressão Automática com QZ Tray</title>
    <script src="https://demo.qz.io/js/qz-tray.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            qz.api.setSha256Type(function(data) {
                return crypto.subtle.digest("SHA-256", new TextEncoder("utf-8").encode(data)).then(function(hash) {
                    return btoa(String.fromCharCode.apply(null, new Uint8Array(hash)));
                });
            });

            qz.api.setPromiseType(function(fn) { return new Promise(fn); });

            // Configure certificates and signature
            qz.security.setCertificatePromise(function(resolve, reject) {
                resolve("-----BEGIN CERTIFICATE-----\n...YOUR CERTIFICATE...\n-----END CERTIFICATE-----");
            });

            qz.security.setSignaturePromise(function(toSign) {
                return function(resolve, reject) {
                    var pk = "-----BEGIN PRIVATE KEY-----\n...YOUR PRIVATE KEY...\n-----END PRIVATE KEY-----";
                    var sign = forge.md.sha256.create();
                    sign.update(toSign, 'utf8');
                    var pki = forge.pki;
                    var privateKey = pki.privateKeyFromPem(pk);
                    resolve(btoa(privateKey.sign(sign)));
                };
            });

            // Connect to QZ Tray
            if (!qz.websocket.isActive()) {
                qz.websocket.connect().then(() => {
                    console.log("QZ Tray connected");
                }).catch(err => {
                    console.error("Failed to connect to QZ Tray:", err);
                });
            }
        });

        function printText() {
            if (typeof qz === 'undefined') {
                console.error("QZ Tray is not loaded.");
                return;
            }

            if (!qz.websocket.isActive()) {
                qz.websocket.connect().then(function() {
                    return qz.printers.find(); // Encontra a impressora padrão
                }).then(function(printer) {
                    var config = qz.configs.create(printer); // Configuração da impressora
                    var data = [
                        { type: 'raw', format: 'plain', data: 'Hello World!\n' },
                        { type: 'raw', format: 'plain', data: '\x1B\x69' } // Comando de corte de papel
                    ];
                    return qz.print(config, data);
                }).catch(function(e) {
                    console.error(e);
                }).finally(function() {
                    qz.websocket.disconnect();
                });
            } else {
                qz.printers.find().then(function(printer) {
                    var config = qz.configs.create(printer); // Configuração da impressora
                    var data = [
                        { type: 'raw', format: 'plain', data: 'Hello World!\n' },
                        { type: 'raw', format: 'plain', data: '\x1B\x69' } // Comando de corte de papel
                    ];
                    return qz.print(config, data);
                }).catch(function(e) {
                    console.error(e);
                }).finally(function() {
                    qz.websocket.disconnect();
                });
            }
        }
    </script>
</head>
<body>
    <button id="printButton" onclick="printText()">Imprimir</button>
</body>
</html>
