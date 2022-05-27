<?php
declare(strict_types=1);
// UTF-8 marker äöüÄÖÜß€

//Mission: Bestellungen, deren Pizzen alle Status fertig sind, von der Datenbank entfernen, sodass es nicht mehr angezeigt wird auf der Seite
//Veränderte Stati in die Datenbank rein
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
class Bäcker extends Page
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
        $sqlStatement = "Select article.name, ordered_article.ordered_article_id, ordering_id, status "
                        . "From ordered_article join article "
                        . "on article.article_id = ordered_article.article_id";
        $RecordSet = $this->_database->query($sqlStatement);
        if(!$RecordSet) throw new Exception("Error in sqlStatement: " . $this->_database->error);
        $DataArray = array();
        while($Record = $RecordSet->fetch_assoc()){
            $DataArray[] = $Record["name"];
            $DataArray[] = $Record["ordered_article_id"];
            $DataArray[] = $Record["ordering_id"];
            $DataArray[] = $Record["status"];
        }
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
        $this->generatePageHeader('Bäcker'); //to do: set optional parameters
        //header("Content-type: text/html");
        $title = "Bäcker";
        echo <<< EOT
        <!DOCTYPE html>
        <html lang="de">
            <head>
                <meta charset="UTF-8"  />
                <!-- für später: CSS include -->
                <!-- <link rel="stylesheet" href="XXX.css"/> -->
                <!-- für später: JavaScript include -->
                <!-- <script src="XXX.js"></script> -->
                <title>Pizzabäcker</title>
            </head>
        <body>
            <section>
                <h1>Pizzabäcker (bestellte Pizzen)</h1>
                    <form name="fortschritte[]" accept-charset="UTF-8" method="post" action="bäcker.php/">
                            <p>Bestellt/Im Ofen/Fertig</p>
        EOT;
                            for($i = 0; $i < count($Data); $i+=4){
                                $PizzaName = $Data[$i];
                                $OrderedArticleID = $Data[$i+1];
                                $OrderingID = $Data[$i+2];
                                $ProcessStatus = $Data[$i+3];
                                echo <<< EOT2
                                    <p>
                                        {$PizzaName}{$OrderingID}
                                EOT2;
                                        if($ProcessStatus == "1"){
                                            echo <<< EOT2
                                                <input type="radio"  name="{$PizzaName}{$OrderedArticleID}" id="blub" value="1" checked/>
                                            EOT2;
                                        }
                                        else{
                                            echo <<< EOT2
                                                <input type="radio" name="{$PizzaName}{$OrderedArticleID}" id="blub" value="1"/>
                                            EOT2;
                                        }

                                        if($ProcessStatus == "2"){
                                            echo <<< EOT2
                                                <input type="radio" name="{$PizzaName}{$OrderedArticleID}"id="blub" value="2" checked/>
                                            EOT2;
                                        }
                                        else{
                                            echo <<< EOT2
                                                <input type="radio" name="{$PizzaName}{$OrderedArticleID}" id="blub"value="2" />
                                            EOT2;
                                        }

                                        if($ProcessStatus >= 3){
                                            echo <<< EOT2
                                                <input type="radio" name="{$PizzaName}{$OrderedArticleID}" id="blub"value="3" checked />
                                            EOT2;
                                        }
                                        else{
                                            echo <<< EOT2
                                                <input type="radio" name="{$PizzaName}{$OrderedArticleID}"id="blub" value="3" />
                                            EOT2;
                                        }
                                        echo <<< EOT2
                                        </p>
                                        EOT2;
                            }
        echo <<< EOT
                        <input type="submit" value="abschicken" />
                        <input type="reset" value="löschen aller"/>
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


        if(isset($_POST)) {
            $totalOderSize = 0;
            $numOfOrderedArticle = 0;


            //Statusänderung
            $orderedArticleID = array();
            $orderedArticleStatus = array();
            $sqlGetOrdArtID = "SELECT ordered_article_id,status FROM ordered_article";
            $RecordSet4 = $this->_database->query($sqlGetOrdArtID);
            if (!$RecordSet4) throw new Exception("Error in sqlStatement: " . $this->_database->error);
            while ($Record4 = $RecordSet4->fetch_assoc()) {
                $orderedArticleID[] = $Record4["ordered_article_id"];
                $orderedArticleStatus = $Record4["status"];
                $numOfOrderedArticle++;
            }

            $allStatFromPost = array();
            foreach($_POST as $key => $value){
                $allStatFromPost[] = $value;
            }

            for($i = 0; $i<$numOfOrderedArticle; $i++) {
                $sqlUpdateAllStat = "UPDATE ordered_article SET status = $allStatFromPost[$i] where ordered_article_id = $orderedArticleID[$i]";
                $this->_database->query($sqlUpdateAllStat);
            }


            //Statusabfrage und Datenlöschung
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

            for ($i = 0; $i < $totalOderSize; $i++) {
                $sqlCountStatusDone = "SELECT count(status) as anzDone from ordered_article WHERE ordering_id = $AllIDs[$i] AND status = 3";
                $RecordSet3 = $this->_database->query($sqlCountStatusDone);
                if (!$RecordSet3) throw new Exception("Error in sqlStatement: " . $this->_database->error);
                $Record3 = $RecordSet3->fetch_assoc();
                $countStatusDoneForID = $Record3["anzDone"];

                if ($quantityOfArticles[$i] == $countStatusDoneForID) {
                    $sqlDeleteOrder = "DELETE FROM ordering where ordering.ordering_id = $AllIDs[$i] ";
                    $this->_database->query($sqlDeleteOrder);

                    $sqlDeleteArticles = "DELETE FROM ordered_article where ordered_article.ordering_id = $AllIDs[$i]";
                    $this->_database->query($sqlDeleteArticles);
                }
            }

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
            $page = new Bäcker();
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
Bäcker::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already
?>
