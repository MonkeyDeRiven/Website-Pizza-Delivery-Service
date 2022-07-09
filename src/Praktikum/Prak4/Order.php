<?php
declare(strict_types=1);
// UTF-8 marker äöüÄÖÜß€
/**
 * Class PageTemplate for the exercises of the EWA lecture
 * Demonstrates use of PHP including class and OO.
 * Implements Zend coding standards.
 * Generate documentation with Doxygen or phpdoc
 *
 * PHP Version 7.4
 *
 * @file     PageTemplate.php
 * @package  Page Templates
 * @author   Bernhard Kreling, <bernhard.kreling@h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 * @version  3.1
 */

// to do: change name 'PageTemplate' throughout this file
require_once './Page.php';

/**
 * This is a template for top level classes, which represent
 * a complete web page and which are called directly by the user.
 * Usually there will only be a single instance of such a class.
 * The name of the template is supposed
 * to be replaced by the name of the specific HTML page e.g. baker.
 * The order of methods might correspond to the order of thinking
 * during implementation.
 * @author   Bernhard Kreling, <bernhard.kreling@h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 */
class Order extends Page
{
    // to do: declare reference variables for members
    // representing substructures/blocks

    /**
     * Instantiates members (to be defined above).
     * Calls the constructor of the parent i.e. page class.
     * So, the database connection is established.
     * @throws Exception
     */
    protected function __construct()
    {
        parent::__construct();
        // to do: instantiate members representing substructures/blocks
    }

    /**
     * Cleans up whatever is needed.
     * Calls the destructor of the parent i.e. page class.
     * So, the database connection is closed.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is returned in an array e.g. as associative array.
     * @return array An array containing the requested data.
     * This may be a normal array, an empty array or an associative array.
     * @throws Exception
     */
    protected function getViewData():array
    {
        // to do: fetch data for this view from the database
        // to do: return array containing data
        $sqlStatement = "SELECT article.picture, article.name, article.price FROM article";


        $RecordSet = $this->_database->query($sqlStatement);
        if(!$RecordSet) throw new Exception("Error in sqlStatement: " . $this->_database->error);
        $DataArray = array();
        while($Record = $RecordSet->fetch_assoc()){
            $DataArray[] = $Record["picture"];
            $DataArray[] = $Record["name"];
            $DataArray[] = $Record["price"];
        }
        $RecordSet->free();
        return $DataArray;
    }

    /**
     * First the required data is fetched and then the HTML is
     * assembled for output. i.e. the header is generated, the content
     * of the page ("view") is inserted and -if available- the content of
     * all views contained is generated.
     * Finally, the footer is added.
     * @return void
     */
    protected function generateView():void
    {
        $Data = $this->getViewData();
        $this->generatePageHeader('Bestellung', "", false); //to do: set optional parameters
        //header("Content-type: text/html");
        echo <<< EOT
            <body>
            <section>
                <h1>Bestellung</h1>
        EOT;
        $HCount = 2;
        for($i = 0; $i < count($Data); $i+=3){
            $PizzaPicture = $Data[$i];
            $PizzaName = $Data[$i+1];
            $PizzaPrice = $Data[$i+2];

            echo <<< EOT
                        <article>
                            <h{$HCount}>$PizzaName</h{$HCount}>
                            <p id="$PizzaName">
                                <button onclick=addPizzaToCart("$PizzaName","$PizzaPrice")><img src="$PizzaPicture" height="100" width="100" /></button>
                                {$PizzaPrice} €
                            </p>
                        </article>
                    EOT;
            $HCount++;
        }
        echo <<< EOT
                <section>
                <h{$HCount}>Warenkorb</h{$HCount}>
                </section>
                <form accept-charset="UTF-8" method="post" action="Order.php" id="orderFormular">
                    <select id="shoppingCart" name="shoppingCart[]" multiple ></select>
                    <p id="totalPrice">Gesamtpreis: 0.00 €</p>
                    <input name="Address" type="text" id ="address" placeholder="Hier Adresse eingeben" value="" required oninput="enableSubmitButton()">
                    <button onclick="submitOrder()" disabled="true" id="submitButton">bestellen</button>
                    <button id="notSubmit" type="button" onclick="deleteAllSelectedOptions()" >Auswahl löschen</input>
                    <button type="reset" onclick="resetShoppingCart()" >löschen</button>
                   
                </form>
                </section>
            </body>
        EOT;

        $this->generatePageFooter();
    }

    /**
     * Processes the data that comes via GET or POST.
     * If this page is supposed to do something with submitted
     * data do it here.
     * @return void
     */

    protected function processReceivedData():void
    {
        parent::processReceivedData();


        // to do: call processReceivedData() for all members
        if (isset($_POST["Address"])){
            $addr = $this->_database->real_escape_string($_POST["Address"]);
            $timestamp = date('Y-m-d H:i:s');

            try {$this->_database->begin_transaction();
                $sqlStatement = "INSERT INTO ordering (address, ordering_time) VALUES('$addr', '$timestamp')";
                $this->_database->query($sqlStatement);

                $numberOfOrderedPizzas = count($_POST["shoppingCart"]);
                $orderedPizzas = $_POST["shoppingCart"];

                $sqlStatementSel = "SELECT ordering_id from ordering where ordering_time = '$timestamp'";
                $orderId = 0;
                $RecordSet = $this->_database->query($sqlStatementSel);
                if (!$RecordSet) throw new Exception("Error in sqlStatement: " . $this->_database->error);

                while ($Record = $RecordSet->fetch_assoc()) {
                    $orderId = $Record["ordering_id"];
                }

                $sqlStatementPizzaWithId = "Select name, article_id From article";
                $RecordSet = $this->_database->query($sqlStatementPizzaWithId);

                $pizzaIdList = array();
                while ($Record = $RecordSet->fetch_assoc()) {
                    $pizzaIdList[$Record["name"]] = $Record["article_id"];
                }

                for ($i = 0; $i < $numberOfOrderedPizzas; $i++) {
                    $articleId = $pizzaIdList[$orderedPizzas[$i]];
                    $sqlStatement = "INSERT INTO ordered_article (ordering_id, article_id, status) VALUES($orderId, $articleId,0)";

                    $this->_database->query($sqlStatement);
                }
                $_SESSION["orderID"] = $orderId;
                $this->_database->commit();
            }catch(Exception $e){
                $this->_database->rollback();
            }

            header('Location: Customer.php');
            die;
        }
    }

    /**
     * This main-function has the only purpose to create an instance
     * of the class and to get all the things going.
     * I.e. the operations of the class are called to produce
     * the output of the HTML-file.
     * The name "main" is no keyword for php. It is just used to
     * indicate that function as the central starting point.
     * To make it simpler this is a static function. That is you can simply
     * call it without first creating an instance of the class.
     * @return void
     */
    public static function main():void
    {
        try {
            session_start();
            $page = new Order();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            //header("Content-type: text/plain; charset=UTF-8");
            //header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

// This call is starting the creation of the page.
// That is input is processed and output is created.
Order::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already
?>
