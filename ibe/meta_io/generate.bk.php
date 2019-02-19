<?
/*
if (isset($_REQUEST['RETURN'])&&(int)$_REQUEST['RETURN']==1) {
  header ("Content-Type:application/json");
  die(json_encode($_GET)) ;
} else {
  print $DEBUG;
  exit;
}
*/

error_reporting(E_ALL);
ini_set('display_errors', '1');

ob_start();

//print "_SERVER:<pre>";print_r($_SERVER);print "</pre>";exit;

date_default_timezone_set("America/New_York");
include $_SERVER["DOCUMENT_ROOT"]."/ibe/meta_io/fns.php";

$COUNTRIES = array("","US","CA","GB");

$PROP_ID = isset($_GET['PROP_ID']) ? $_GET['PROP_ID'] : 1;
$COUNTRY = isset($_GET['COUNTRY']) ? $_GET['COUNTRY'] : 1;
$COUNTRY_CODE = $COUNTRIES[$COUNTRY]=="GB" ? "RW" : $COUNTRIES[$COUNTRY]; 
$ADULTS_QTY = isset($_GET['ADULTS_QTY']) ? (int)$_GET['ADULTS_QTY'] : 1;
$PROP = getMetaIOProp($PROP_ID);
$PROP_CODE = $PROP["CODE"][0];
$PREV_FILE = "";
$FILES = array();
$DEBUG = "";


$TODAY = isset($_GET['START']) ? $_GET['START'] : addUnitsToDate(date("Y-m-d"), "+12", true, "months"); //date("Y-m-d");
$CHECK_IN = isset($_GET['STOP']) ? $_GET['STOP'] : addUnitsToDate($TODAY, "+1", true, "days");
$RES_NIGHTS = strtotime($CHECK_IN." 01:01:01 GMT") - strtotime($TODAY." 01:01:01 GMT");
$RES_NIGHTS = floor($RES_NIGHTS/(24*60*60)) + 1;

$API = "http://{$_SERVER['HTTP_HOST']}/ibe/index.php?PAGE_CODE=ws.availability&ACTION=SUBMIT&RES_LANGUAGE=EN&RES_PROP_ID={$PROP_ID}&RES_CHECK_IN={$TODAY}&RES_CHECK_OUT={$CHECK_IN}&RES_NIGHTS={$RES_NIGHTS}&RES_ROOMS_QTY=1&RES_ROOM_1_ADULTS_QTY={$ADULTS_QTY}&RES_ROOM_1_CHILDREN_QTY=0&RES_COUNTRY_CODE={$COUNTRY_CODE}";
print "<hr>$API<hr>";
$DEBUG .= "\n\n$API \n";

$RJSON = file_get_contents($API);
//mail("jaunsarria@gmail.com","8 :: RJSON ","$API \n $RJSON");

$RESULT = json_decode($RJSON, true);

foreach ($RESULT['RES_ROOM_1_ROOMS'] as $ROOM_ID => $ROOM) {
    $DEBUG .= "\nupdating ROOM_ID: $ROOM_ID :: AVAILABLE_NIGHTS: {$ROOM['AVAILABLE_NIGHTS']} - exists: ".(isset($ROOM["NIGTHS"])?"YES":"NO")." - total: ".(isset($ROOM["TOTAL"])?"YES":"NO");
    foreach ($ROOM['NIGTHS'] as $NIGHT_DATE => $NIGHT_DATA) {
        $DEBUG .= "$NIGHT_DATE: ";
        $KEY = str_replace("-","",$NIGHT_DATE);
        $CURRENT_FILE = "data/".$PROP_CODE."-".substr($NIGHT_DATE, 0, 7).".json";
        $DEBUG .= "$CURRENT_FILE ";

        if ($PREV_FILE != $CURRENT_FILE) {
            $PREV_FILE = $CURRENT_FILE;
            if (!file_exists($CURRENT_FILE)) {
                //print "<br>Creating $CURRENT_FILE file...<br>";
                $empty = array("calendar" => array());
                $JSON = json_encode($empty);
                file_put_contents($CURRENT_FILE, $JSON);
            }
        }
        if (!isset($FILES[$CURRENT_FILE])) {
            $JSON = file_get_contents($CURRENT_FILE);
            $FILES[$CURRENT_FILE] = json_decode($JSON, true);
        }

        $DATA = $FILES[$CURRENT_FILE];

        $ROOM_DATA = isset($DATA['calendar'][$KEY]["Adults ".$ADULTS_QTY][$ROOM_ID]) ? $DATA['calendar'][$KEY]["Adults ".$ADULTS_QTY][$ROOM_ID] : array("name"=>"","price"=>array());
        $ROOM_DATA["name"] = $ROOM['NAME'];
        if (!is_array($ROOM_DATA["price"])) { $ROOM_DATA["price"] = array(); } 

        $ROOM_DATA["price"][$COUNTRY_CODE] = is_array($NIGHT_DATA) ? (int)$NIGHT_DATA['RATE']['FINAL']*$ADULTS_QTY : 0;
        $DATA['calendar'][$KEY]["Adults ".$ADULTS_QTY][$ROOM_ID] = $ROOM_DATA;
        ksort($DATA['calendar'][$KEY]["Adults ".$ADULTS_QTY]);

        $FILES[$CURRENT_FILE] = $DATA;
    }

}

//mail("jaunsarria@gmail.com","8 :: getMetaIORooms $PROP_ID - {$COUNTRY_CODE} - $ADULTS_QTY - {$_GET['START']} - {$_GET['STOP']} :: $TODAY - $CHECK_IN ",$DEBUG);

//print "<pre>";print_r($FILES);print "</pre>";

saveMetaIO($FILES, true);

$DEBUG = ob_get_clean();

if (isset($_REQUEST['RETURN'])&&(int)$_REQUEST['RETURN']==1) {
  header ("Content-Type:application/json");
  die(json_encode($_GET)) ;
} else {
  print $DEBUG;
  exit;
}
