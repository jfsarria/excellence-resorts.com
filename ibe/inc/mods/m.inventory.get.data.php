<?
/*
 * Revised: Jan 06, 2013
 *          Jul 07, 2014
 *          Dec 13, 2017
 */

// DO NOT REMOVE
// THIS FILE IS USED BY THE INVENTORY IN TWO PLACES, AVAILABILITY AND INVENTORY

$getStopSale = isset($getStopSale) ? $getStopSale : false;

$INVENTORY = array();
$OVERRIDE = array();
$BLACKOUT = array();
$STOPSALE = array();

$_ARG =  array (
    "ROOM_IDs"=>$ROOM_IDs,
    "FROM"=>$FROM,
    "TO"=>$TO,
    "CODE"=>$CODE,
    "YEAR"=>(isset($YEAR))?$YEAR:date("Y"),
    "YEARS"=>(isset($YEARS))?$YEARS:array(date("Y"))
);
//$YEARS = array(2011, date("Y")); // override and include all years since 2011
$YEARS = array();

$YEAR_START = (int)substr($FROM,0,4);
if ($YEAR_START<2011) $YEAR_START = 2011;

$YEAR_END = (int)substr($TO,0,4);
if ($YEAR_END>date("Y")+2) $YEAR_END = date("Y")+2;

for ($YEAR=$YEAR_START;$YEAR<=$YEAR_END;++$YEAR) array_push($YEARS, $YEAR);

if (isset($YEARS)) $_ARG["YEARS"] = $YEARS;
//print "DATA: <pre>";print_r($_ARG);print "</pre>";

if (count($ROOM_IDs)>0) {
    $I_RSET = $clsInventory->getInventory($db, $_ARG);
    $_ARG = array("ROOM_IDs"=>$ROOM_IDs);
    $R_RSET = $clsInventory->getRooms($db, $_ARG);
    $O_RSET = $clsInventory->getAddAllocation($db, $_ARG);
    $C_RSET = $clsInventory->getBlackOut($db, $_ARG);

    //mail("jaunsarria@gmail.com","getBlackOut From","m.inventory.get.data.php");
    //ob_start();print_r($C_RSET['rSet']);$output = ob_get_clean();
    //mail("jaunsarria@gmail.com","C_RSET",$output);

    if (!is_string($I_RSET))  while ($row = $db->fetch_array($I_RSET['rSet'])) $INVENTORY[$row['ROOM_ID']][substr($row['RES_DATE'],0,10)] = $row['SOLD'];
    while ($row = $db->fetch_array($O_RSET['rSet'])) $OVERRIDE[$row['ROOM_ID']][substr($row['RES_DATE'],0,10)] = $row['QTY'];
    while ($row = $db->fetch_array($C_RSET['rSet'])) $BLACKOUT[$row['ROOM_ID']][substr($row['DATE_CLOSED'],0,10)] = 1;

    if ($getStopSale) {
        $_DATA['RES_COUNTRY_GROUP'] = $clsGlobal->getCountryGroupByCode($db, array("CODE"=>$_DATA['RES_COUNTRY_CODE']));
        $STOPSALE = $clsInventory->makeStopSale($db, $_ARG, $_DATA);
        //ob_start();print_r($STOPSALE);print_r($BLACKOUT);$output = ob_get_clean();
        //mail("jaunsarria@gmail.com","STOPSALE",$output);
    }
}

/*
print "<!--";
print "INVENTORY:<pre>";print_r($INVENTORY);print "</pre>";
print "OVERRIDE:<pre>";print_r($OVERRIDE);print "</pre>";
print "BLACKOUT:<pre>";print_r($BLACKOUT);print "</pre>";
print "-->";
*/

?>
