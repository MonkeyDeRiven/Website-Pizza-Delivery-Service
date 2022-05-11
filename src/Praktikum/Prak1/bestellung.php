<?php
    header("Content-type: text/html");
    $title = "Bestellung";
    echo<<< EOT
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8" />
    <!-- für später: CSS include -->
    <!-- <link rel="stylesheet" href="XXX.css"/> -->
    <!-- für später: JavaScript include -->
    <!-- <script src="XXX.js"></script> -->
    <title>Text des Titels</title>
</head>
<body>
    <h1>Bestellung</h1>
    <label for="Speisekarte" hidden>Speisekarte</label>
    <section id="Speisekarte">
        <h2>Speisekarte</h2>
        <img src="pizza-magherita.png" alt="Bild aus technischen Gründen nicht verfügbar!" width="200" height="200">
        <p>Magheriata</p>
        <p>4.00€</p>

        <img src="pizza-magherita.png" alt="Bild aus technischen Gründen nicht verfügbar!" width="200" height="200">
        <p>Salami</p>
        <p>4.50€</p>

        <img src="pizza-magherita.png" alt="Bild aus technischen Gründen nicht verfügbar!" width="200" height="200">
        <p>Hawaii</p>
        <p>5.50€</p>
    </section>

    <label for="Warenkorb" hidden>Ihr Warenkorb</label>
    <section id="Warenkorb">
    <h3>Warenkorb</h3>
        <form accept-charset="UTF-8" method="post" action="https://echo.fbi.h-da.de" id="Bestellformular">
            <label for="Bestellung" hidden>Bestellung</label>
            <select name = "Bestellung[]" id="Bestellung" size="3" tabindex="1" multiple>
                <option value="Magherita" selected>Pizza-Magherita </option>
                <option value="Salami"> Pizza-Salami </option>
                <option value="Hawaii"> Pizza-Hawaii </option>
            </select>
            <p id="PreisInsgesamt">14.50€</p>
            <input name="Adresse" type="text" id ="address" placeholder="Hier Adresse eingeben" value="" required>

            <button type="submit" tabindex="4" accesskey="o" form="Bestellformular">Bestellen</button>
            <button type="button" tabindex="2" accesskey="d">Alle Löschen</button>
            <button type="button" tabindex="3" accesskey="a">Auswahl Löschen</button>
        </form>
    </section>
</body>
</html>
EOT;
    ?>


