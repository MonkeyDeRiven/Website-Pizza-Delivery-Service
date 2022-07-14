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
class Baker extends Page
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
            . "on article.article_id = ordered_article.article_id "
            . "Where status < 3";

        $RecordSet = $this->_database->query($sqlStatement);
        if(!$RecordSet) throw new Exception("Error in sqlStatement: " . $this->_database->error);
        $DataArray = array();;
        while($Record = $RecordSet->fetch_assoc()){
            $DataArray[] = $Record["name"];
            $DataArray[] = $Record["ordered_article_id"];
            $DataArray[] = $Record["ordering_id"];
            $DataArray[] = $Record["status"];
        }
        $RecordSet->free();
        return $DataArray;
    }

    private function deleteFinishedOrders(array $orderList){
        $orderStartIndex = 0;
        $orderEndIndex = 0;
        $isOrderDone = true;
        for($i = 1; $i < count($orderList)-1; $i++){
            if($orderList[$i] != $orderList[$i-1]){

            }
        }
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
        $this->generatePageHeader('Bäcker', "", false); //to do: set optional parameters
        //header("Content-type: text/html");
        echo <<< EOT
        <body>
            <section>
                <h1>Pizzabäcker (bestellte Pizzen)</h1>
        EOT;
        if(count($Data) == 0){
            echo <<< EOT
                <p>Momentan sind keine weitere Bestellunge vorhanden!</p>
            EOT;

        }
        else {
            echo <<< EOT
                <form name="fortschritte[]" id="bakerForm" accept-charset="UTF-8" method="post" action="Baker.php">
                <div class="orderStatusBaker">
                    <p class="bakerStatusLinePizzaName"></p>
                    <p class="orderStatusItem">Bestellt</p>
                    <p class="orderStatusItem">Im Ofen</p>
                    <p class="orderStatusItem">Fertig</p>
                </div>
                <div class="containerAllOrders">
            EOT;
        }

        for($i = 0; $i < count($Data); $i+=4){
            $PizzaName = $Data[$i];
            $OrderedArticleID = $Data[$i+1];
            $OrderingID = $Data[$i+2];
            $ProcessStatus = $Data[$i+3];
            echo <<< EOT2
                    <div class="orderStatusBaker">
                        <p class="bakerStatusLinePizzaName">{$PizzaName}{$OrderingID}</p>
            EOT2;
            if($ProcessStatus == "0"){
                echo <<< EOT2
                    <div class="orderStatusItem">
                        <input onclick="document.forms['bakerForm'].submit();" type="radio"  name="{$PizzaName} {$OrderedArticleID}" value="0" checked/>
                    </div>
                EOT2;
            }
            else{
                echo <<< EOT2
                    <div class="orderStatusItem">
                        <input onclick="document.forms['bakerForm'].submit();" type="radio" name="{$PizzaName} {$OrderedArticleID}" value="0"/>
                    </div>
                EOT2;
            }
            if($ProcessStatus == "1"){
                echo <<< EOT2
                    <div class="orderStatusItem">
                        <input class="orderStatusItem" onclick="document.forms['bakerForm'].submit();" type="radio" name="{$PizzaName} {$OrderedArticleID}" value="1" checked/>
                    </div>
                EOT2;
            }
            else{
                echo <<< EOT2
                    <div class="orderStatusItem">
                        <input class="orderStatusItem" onclick="document.forms['bakerForm'].submit();" type="radio" name="{$PizzaName} {$OrderedArticleID}" value="1" />
                    </div>
                EOT2;
            }
            if($ProcessStatus >= "2"){
                echo <<< EOT2
                    <div class="orderStatusItem">
                        <input class="orderStatusItem" onclick="document.forms['bakerForm'].submit();" type="radio" name="{$PizzaName} {$OrderedArticleID}" value="2" checked />
                    </div>
                EOT2;
            }
            else{
                echo <<< EOT2
                    <div class="orderStatusItem">
                        <input class="orderStatusItem" onclick="document.forms['bakerForm'].submit();" type="radio" name="{$PizzaName} {$OrderedArticleID}" value="2" />
                    </div>
                    EOT2;
            }

            echo <<< EOT2
                    </div>
            EOT2;
        }
        echo <<< EOT
                        </div>
                        <input name="checkInput" value="true" hidden />  
                        <input type="reset" value="löschen aller" />
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

        /*  if(isset($_POST["checkInput"])) {
              $totalOderSize = 0;
              $numOfOrderedArticle = 0;

              //Statusänderung
              $orderedArticleID = array();
              $orderedArticleStatus = array();
              $sqlGetOrdArtID = "SELECT ordered_article_id,status FROM ordered_article Where status < 2";
              $RecordSet4 = $this->_database->query($sqlGetOrdArtID);
              if (!$RecordSet4) throw new Exception("Error in sqlStatement: " . $this->_database->error);
              while ($Record4 = $RecordSet4->fetch_assoc()) {
                  $orderedArticleID[] = $Record4["ordered_article_id"];
                  $orderedArticleStatus = $Record4["status"];
                  $numOfOrderedArticle++;
              }

              $allStatFromPost = array();
              foreach($_POST as $key => $value){
                  if($value != "checkInput") {
                      $allStatFromPost[] = $value;
                  }
              }

              for($i = 0; $i<$numOfOrderedArticle; $i++) {
                  $sqlUpdateAllStat = "UPDATE ordered_article SET status = $allStatFromPost[$i] where ordered_article_id = $orderedArticleID[$i]";
                  $this->_database->query($sqlUpdateAllStat);
              }
              header('Location: Baker.php');
              die();
          }*/
        // to do: call processReceivedData() for all members
        $getOrderedArticleStatus = $this->_database->prepare("SELECT ordered_article_id, status FROM ordered_article WHERE status < 2");
        $updateOrderedArticleStatus = $this->_database->prepare("UPDATE ordered_article SET status = ? WHERE ordered_article_id = ?");
        $getOrderedArticleStatus->execute();

        $RecordSet = $getOrderedArticleStatus->get_result();
        $statusArray = array();
        $orderedArticleIdArray = array();
        while($Record = $RecordSet->fetch_assoc()){
            $statusArray[] = $Record["status"];
            $orderedArticleIdArray[] = $Record["ordered_article_id"];
        }


        if(isset($_POST["checkInput"])){

            foreach ($_POST as $key => $value){
                $currenOrderedArticleId = preg_split("/_/",$key)[1];
                for($i = 0; $i <count($statusArray); $i++){
                    if($currenOrderedArticleId == $orderedArticleIdArray[$i]){
                        if($statusArray[$i] != $value){
                            $updateOrderedArticleStatus->bind_param("ii", $value, $currenOrderedArticleId);
                            $updateOrderedArticleStatus->execute();
                            return;
                        }
                    }
                }
            }
            header('Location: Baker.php');
            die();
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
            $page = new Baker();
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
Baker::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already
?>