<?php
    header("Content-type:text/html");
    $title = "Fahrer";
    $bestellungen[] = "Schulz, Kasinostraße 5, 15,50€\nPizza-Hawaii, Pizza-Salami ";
    $labelForRdBtn = "fertig\t unterwegs\t geliefert";

echo <<< EOT
        <!DOCTYPE html>
        <html lang="de" xmlns="http://www.w3.org/1999/html">
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
                <form name="bestellstatus[]" accept-charset="UTF-8" method="get" action="https://echo.fbi.h-da.de/">                    <h1>Fahrer(auslieferbarer Bestellungen)</h1>
                    <b>$bestellungen[0]</b><br></br>
                    $labelForRdBtn<br></br>
                    <input type="radio" id="fertig" name="status" value="FERTIG" hspace="10">
                    <input type="radio" id="unterwegs" name="status" value="FERTIG" hspace="10">
                    <input type="radio" id="geliefert" name="status" value="GELIFERT" hspace="10">                   
                </section>  
                </form>
            </body>
        </html>
EOT;

?>
