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
class Fahrer extends Page
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

        //Bestellungen, deren Pizzen alle Status fertig sind, den Fahrer anzeigen lassen und veränderte Stati in die Datenbank übernehmen
        // to do: fetch data for this view from the database
        // to do: return array containing data
        $totalOderSize = 0;
        $sqlGetAllIds = "SELECT ordering_id FROM ordering";
        $RecordSet = $this->_database->query($sqlGetAllIds);
        if (!$RecordSet) throw new Exception("Error in sqlStatement: " . $this->_database->error);
        $AllIDs = array();
        while ($Record = $RecordSet->fetch_assoc()) {
            $AllIDs[] = $Record["ordering_id"];
            $totalOderSize++;
        }

        $quantityOfArticles = array();
        for ($i = 0; $i < $totalOderSize; $i++) {
            $sqlCountArticlesForEach = "SELECT count(ordering_id) as anzOrder FROM ordering JOIN ordered_article using (ordering_id) where ordering_id = $AllIDs[$i]";
            $RecordSet2 = $this->_database->query($sqlCountArticlesForEach);
            if (!$RecordSet2) throw new Exception("Error in sqlStatement: " . $this->_database->error);
            $Record2 = $RecordSet2->fetch_assoc();
            $quantityOfArticles[] = $Record2["anzOrder"];
        }

        $articleId = array();
        $notDoneOrders = array();
        for ($i = 0; $i < $totalOderSize; $i++) {
            $sqlCountStatusDone = "SELECT count(status) as anzDone from ordered_article WHERE ordering_id = $AllIDs[$i] AND status = 3";
            $RecordSet3 = $this->_database->query($sqlCountStatusDone);
            if (!$RecordSet3) throw new Exception("Error in sqlStatement: " . $this->_database->error);
            $Record3 = $RecordSet3->fetch_assoc();
            $countStatusDoneForID = $Record3["anzDone"];

            if ($quantityOfArticles[$i] == $countStatusDoneForID) {
                $sqlArtId = "SELECT ordered_article.article_id FROM ordering JOIN ordered_article USING(ordering_id) WHERE ordering_id = $AllIDs[$i]";
                $sqlAdr = "SELECT address FROM ordering where ordering_id = $AllIDs[$i]";
                $articleString = " ";
                $anzArticle = 0;

                $RecordSet5 = $this->_database->query($sqlArtId);
                if (!$RecordSet5) throw new Exception("Error in sqlStatement: " . $this->_database->error);
                while($Record5 = $RecordSet5->fetch_assoc()){
                        $articleId = $Record5["article_id"];
                        $anzArticle++;
                }
                for($m = 0; $m<$anzArticle; $m++){
                    echo ("\n$articleId[$m]");
                    
                }

                for($j = 0; $j<$anzArticle; $j++){
                    if($articleId[$j] == "1")
                        $articleString = "$articleString, Pizza-Salami";
                    if($articleId[$j] == "2")
                        $articleString = "$articleString, Pizza-Vegetaria";
                    if($articleId[$j] == "3")
                        $articleString = "$articleString, Pizza-Spinat-Hühnchen";
                }

                $RecordSet6 = $this->_database->query($sqlAdr);
                if (!$RecordSet6) throw new Exception("Error in sqlStatement: " . $this->_database->error);
                $Record6 = $RecordSet6->fetch_assoc();
                $tmpString = $Record6["address"];
                echo("$tmpString $articleString");
                $notDoneOrders[] = "$tmpString, $articleString";

                $RecordSet7 = $this->_database->query("SELECT status FROM ordered_articles WHERE ordering_id = $AllIDs[$i]");
                if (!$RecordSet7) throw new Exception("Error in sqlStatement: " . $this->_database->error);
                $Record7 = $RecordSet7->fetch_assoc();
                $orderStatus = $Record7["status"];

            }
        }

        /*$sqlDeleteOrder = "DELETE FROM ordering where ordering.ordering_id = $AllIDs[$i] ";
        $this->_database->query($sqlDeleteOrder);

        $sqlDeleteArticles = "DELETE FROM ordered_article where ordered_article.ordering_id = $AllIDs[$i]";
        $this->_database->query($sqlDeleteArticles);*/

    return $notDoneOrders;
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
        $this->generatePageHeader('Fahrer'); //to do: set optional parameters

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
                    <input type="radio" id="fertig" name="status" value="3" hspace="10">
                    <input type="radio" id="unterwegs" name="status" value="4" hspace="10">
                    <input type="radio" id="geliefert" name="status" value="5" hspace="10">                  
                </form>
                </section> 
            </body>
        </html>
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
            $page = new Fahrer();
            $page->getViewData();
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
Fahrer::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already
?>
