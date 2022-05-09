<?php
    header("Content-type: text/html");
    $title = "Bestellung";
    ?>
<!DOCTYPE html>
<html lang="de">
    <?php
        echo <<< EOT
            <!-- HEREDOC! Hier steht HTML-Code -->
            <head>
                <meta charset="UTF-8" />
                <title>$title</title>
            </head>
        EOT;
    ?>
    <body>
        <?php
            echo <<< EOT
                <section>
                    <h1>Bestellung</h1>
                </section>
            EOT;
        ?>
    </body>
</html>

