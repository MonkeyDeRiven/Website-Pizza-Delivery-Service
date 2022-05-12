<?php
    header("Content-type: text/html");
    $title = "Kunde";
    echo <<< EOT
        <!DOCTYPE html>
        <html lang="de">
            <head>
                <meta charset="UTF-8" />
                <!-- für später: CSS include -->
                <!-- <link rel="stylesheet" href="XXX.css"/> -->
                <!-- für später: JavaScript include -->
                <!-- <script src="XXX.js"></script> -->
                <title>Kunde</title>
            </head>
        <body>
            <section>
                <h1>Kunde (Lieferstatus)</h1>
                    <p>Salami: bestellt</p>
                    <p>Tonno: in ofen</p>
                    <p>Hawai: fertig</p>
                </section>
                <input type="button" value="neue Bestellung" onclick="window.location.href = 'http://localhost/Praktikum/Prak1/bestellung.php';"/>
            </body>
        </html>
EOT;

?>
