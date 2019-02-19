<?
/*
 * Revised: Feb 04, 2014
 *          Nov 01, 2015
 */

$PROP_ID = isset($_GET['PROP_ID']) ? $_GET['PROP_ID'] : "";
$CHECK_IN = isset($_GET['CHECK_IN']) ? $_GET['CHECK_IN'] : "";
$YEAR = isset($_GET['YEAR']) ? $_GET['YEAR'] : "";
$PEOPLE = isset($_GET['PEOPLE']) ? $_GET['PEOPLE'] : "";
$TRIP = isset($_GET['TRIP']) ? $_GET['TRIP'] : "ROUNDT";
$_FORMAT = isset($_GET['ContentType']) ? $_GET['ContentType'] : "json";

$RSET = $clsTransfer->getCarByProp($db, array(
  "PROP_ID"=>$PROP_ID,
  "PEOPLE"=>$PEOPLE
));
$CARS = array();
while ($row = $db->fetch_array($RSET['rSet'])) {
    $CAR = $clsGlobal->cleanUp_rSet_Array($row);
    //print "<pre>";print_r($CAR);print "</pre>";
    $ind = 0;
    if ((int)$CAR['PRICE_1_YEAR']==(int)$YEAR) $ind = 1;
    if ((int)$CAR['PRICE_2_YEAR']==(int)$YEAR) $ind = 2;
    if ((int)$CAR['PRICE_3_YEAR']==(int)$YEAR) $ind = 3;
    if ($ind!=0) {
        $PRICE = $TRIP=="ROUNDT" ? $CAR['PRICE_'.$ind.'_ROUNDT'] : $CAR['PRICE_'.$ind.'_ONEWAY'];
        $ITEM = array(
            'ID' => $row['ID'],
            'NAME_EN' => $CAR['NAME_EN'],
            'NAME_SP' => $CAR['NAME_SP'],
            'DESCR_EN' => $CAR['DESCR_EN'],
            'DESCR_SP' => $CAR['DESCR_SP'],
            'PRICE' => $PRICE,
            'MAX_PAX' => $CAR['MAX_PAX']
        );
        $ITEM['IMAGES'] = array();
        $IMAGES = $clsGlobal->get_Uploads($db, array("PARENT_ID"=>$row['ID'],"TYPE"=>"image","LOCATION"=>$_SERVER["SERVER_NAME"]."/ibe/ups/transfers/"));
        foreach ($IMAGES as $KEY=>$IMG) $ITEM['IMAGES'][] = $IMG;

        // OVERRIDE PRICE IS SEASONAL PRICE EXISTS
        $SSET = $clsTransferCarSeason->getPrice($db, array(
            "PROP_ID" => $PROP_ID,
            "CAR_ID" => $row['ID'],
            "CHECK_IN" => $CHECK_IN
        ));
        while ($srow = $db->fetch_array($SSET['rSet'])) {
            $OCAR = $clsGlobal->cleanUp_rSet_Array($srow);
            $PRICE = $TRIP=="ROUNDT" ? $OCAR['PRICE_ROUNDT'] : $OCAR['PRICE_ONEWAY'];
            $ITEM['PRICE'] = $PRICE;
        }
        $CARS[] = $ITEM;
    }
}

usort($CARS, function($a, $b) {
    return $a['PRICE'] - $b['PRICE'];
});

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

//print "<pre>";print_r($CARS);print "</pre>";

if ($_FORMAT == "xml") {
    header ("Content-Type:text/xml");
    print '<?xml version="1.0" encoding="UTF-8"?>';
}

if ($_FORMAT == "json") {
    header ("Content-Type:application/json");
    ////$OUT = json_encode(str2xml($OUT));
    print json_encode($CARS);
}

die();

?>
