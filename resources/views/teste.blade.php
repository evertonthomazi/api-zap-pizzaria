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

            qz.websocket.connect().then(() => {
                console.log("QZ Tray connected");

                const siteConfig = {
                    hostname: window.location.hostname,
                    port: window.location.port,
                    protocol: window.location.protocol.replace(":", "")
                };

                console.log("Configuring site permissions for:", siteConfig);

                // Define site permissions programmatically
                qz.security.setCertificatePromise(function(resolve, reject) {
                    // Resolve with the certificate data
                    resolve("YOUR_CERTIFICATE_HERE");
                });

                qz.security.setSignaturePromise(function(toSign) {
                    return function(resolve, reject) {
                        resolve("YOUR_SIGNATURE_HERE");
                    };
                });

                // This example will print on button click
                document.getElementById('printButton').addEventListener('click', printText);
            }).catch(err => {
                console.error("Failed to connect to QZ Tray:", err);
            });
        });

        function printText() {
            if (typeof qz === 'undefined') {
                console.error("QZ Tray is not loaded.");
                return;
            }

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
        }
    </script>
</head>
<body>
    <button id="printButton">Imprimir</button>
</body>
</html>
