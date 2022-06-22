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
class Driver extends Page
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
        $statusFromOrder = array();
        $counter = 0;
        for ($i = 0; $i < $totalOderSize; $i++) {
            $sqlCountStatusDone = "SELECT count(status) as anzDone from ordered_article WHERE ordering_id = $AllIDs[$i] AND status > 1";
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
                    $articleId[] = $Record5["article_id"];
                    $anzArticle++;
                }

                $sqlStatementPizzaWithId = "Select article_id, name From article";
                $RecordSet = $this->_database->query($sqlStatementPizzaWithId);
                $pizzaList = array();
                $pizzaString = "Pizza-";
                while($Record = $RecordSet->fetch_assoc()){
                    $pizzaList[$Record["article_id"]] = "$pizzaString" . $Record["name"];
                }

                for($j = 0; $j<$anzArticle; $j++){
                    $pizzaName = $pizzaList[$articleId[$j]];
                    $articleString = "$articleString, $pizzaName";
                }

                $RecordSet6 = $this->_database->query($sqlAdr);
                if (!$RecordSet6) throw new Exception("Error in sqlStatement: " . $this->_database->error);
                $Record6 = $RecordSet6->fetch_assoc();
                $tmpString = $Record6["address"];
                $notDoneOrders[] = "$tmpString, $articleString";

                $RecordSet7 = $this->_database->query("SELECT DISTINCT status FROM ordered_article WHERE ordering_id = $AllIDs[$i]");
                if (!$RecordSet7) throw new Exception("Error in sqlStatement: " . $this->_database->error);

                while($Record7 = $RecordSet7->fetch_assoc()){
                    $statusFromOrder[] = $Record7["status"];
                }
                $notDoneOrders[] = $statusFromOrder[$counter];
                $notDoneOrders[] = $AllIDs[$i];
                $counter++;
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


        echo <<< EOT
        <!DOCTYPE html>
        <html lang="de" xmlns="http://www.w3.org/1999/html">
            <head>
                <meta charset="UTF-8" />
                <!-- für später: CSS include -->
                <!-- <link rel="stylesheet" href="XXX.css"/> -->
                <!-- für später: JavaScript include -->
                <!-- <script src="XXX.js"></script> -->
                <title>Fahrer</title>
            </head>
         <body>
            <section>
                <h1>Fahrer (auslieferbare Bestellungen)</h1>
                    <form name="lieferstatus[]" accept-charset="UTF-8" method="post" action="driver.php">
                            
                           <p><b> Bestellt\n   Im Ofen  \n Fertig</b></p>

        EOT;
        for($i = 0; $i<count($Data); $i = $i+3){
            $orderDisplay = $Data[$i];
            $orderStatus = $Data[$i+1];
            $orderID = $Data[$i+2];

            echo <<< EOT
                <p><b>$orderDisplay</b></p>
                EOT;

            if($orderStatus == "2"){echo <<< EOT
                <input type="radio"  name="$orderID$orderStatus" value="$orderID,2," checked/>
            EOT;
            }else{ echo <<< EOT
                <input type="radio" name="$orderID$orderStatus" value="$orderID,2,"/>
            EOT;
            }

            if($orderStatus == "3"){ echo <<< EOT
                 <input type="radio"  name="$orderID$orderStatus" value="$orderID,3," checked/>
            EOT;
            }else{ echo <<< EOT
                <input type="radio" name="$orderID$orderStatus" value="$orderID,3,"/>
            EOT;
            }

            if($orderStatus == "4"){ echo <<< EOT
                 <input type="radio"  name="$orderID$orderStatus" value="$orderID,4," checked/>
            EOT;
            }else{ echo <<< EOT
                <input type="radio" name="$orderID$orderStatus" value="$orderID,4,"/>
            EOT;
            }
        }

        echo <<< EOT
                        <p></p>
                        <input name="checkInput" value="true" hidden /> 
                        <input type="submit" value="abschicken" />
                        <input type="reset" value="löschen" />
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

        if(isset($_POST["checkInput"])){
            $allStatFromPost = "";
            $counter = 0;
            foreach($_POST as $key => $value){
                if($value != "checkInput") {
                    $allStatFromPost = "$allStatFromPost$value";
                    $counter++;
                }
            }
            //Nice to know ->String Splitter in Php, explode()
            $data = explode(",",$allStatFromPost);//Splits into array with deli and given string
            $counter = 0;
            for($i = 0; $i<count($data)-1; $i = $i + 2){
                $orderID = $data[$i];//Why the fuck werden daraus Buchstaben??????Scheiß autoType man
                $orderStatus = $data[$i+1];
                $counter = $i + 1;
                if($data[$i+1] < "4"){
                    $sqlUpdateArticleStatus = "UPDATE ordered_article SET status = $data[$counter] WHERE ordering_id = $data[$i]";
                    $this->_database->query($sqlUpdateArticleStatus);
                }else{
                    $sqlDeleteOrder = "DELETE FROM ordering where ordering.ordering_id = $data[$i]";
                    $this->_database->query($sqlDeleteOrder);

                    $sqlDeleteArticles = "DELETE FROM ordered_article where ordered_article.ordering_id = $data[$i]";
                    $this->_database->query($sqlDeleteArticles);
                }
            }
            header("Location: driver.php");
            die;
        }
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
            $page = new Driver();
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
Driver::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already
?>
