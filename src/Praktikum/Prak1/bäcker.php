<?php
    header("Content-type: text/html");
    $title = "Bäcker";
    echo <<< EOT
    <!DOCTYPE html>
    <html lang="de">
        <head>
            <meta charset="UTF-8" />
            <!-- für später: CSS include -->
            <!-- <link rel="stylesheet" href="XXX.css"/> -->
            <!-- für später: JavaScript include -->
            <!-- <script src="XXX.js"></script> -->
            <title>Pizzabäcker</title>
        </head>
    <body>
        <section>
            <h1>Pizzabäcker (bestellte Pizzen)</h1>
                <form name="fortschritte[]" accept-charset="UTF-8" method="get" action="https://echo.fbi.h-da.de/">
                    <table style="text-align: center">
                        <tr>
                            <td></td>
                            <td>Bestellt</td>
                            <td>Im Ofen</td>
                            <td>Fertig</td>
                        </tr>
                        <tr>
                            <td>Salami</td>
                            <td><input type="radio" name="Salami1" value="bestellt" checked/></td>
                            <td><input type="radio" name="Salami1" value="im ofen" /></td>
                            <td><input type="radio" name="Salami1" value="fertig" /></td>
                        </tr>
                        <tr>
                            <td>Tonno</td>
                            <td><input type="radio" name="Tonno1" value="bestellt" checked /></td>
                            <td><input type="radio" name="Tonno1" value="im ofen" /></td>
                            <td><input type="radio" name="Tonno1" value="fertig" /></td>
                        </tr>
                        <tr>
                            <td>Margherita</td>
                            <td><input type="radio" name="Margherita1" value="bestellt" checked /></td>
                            <td><input type="radio" name="Margherita1" value="im ofen" /></td>
                            <td><input type="radio" name="Margherita1" value="fertig" /></td>
                        </tr>
                    </table>
                    <input type="submit" value="abschicken" />
                    <input type="reset" value="löschen aller"/>
                </form>
            </section>
        </body>
    </html>
EOT;


?>
