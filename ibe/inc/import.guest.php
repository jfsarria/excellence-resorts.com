<?
/*
 * Revised: Sep 08, 2011
 */

$isMigration = true;
include "ws.migration.server.php";
$ARGS = array (
    "CODE"=>"getGuestByUsrPwdId",
    "EMAIL"=>isset($_EMAIL)?$_EMAIL:"",
    "PASSWORD"=>isset($_PWD)?$_PWD:"",
    "OLD_ID"=>(isset($_OLD_ID)&&$_OLD_ID!=0) ? $_OLD_ID : 0
);
//print "<pre>";print_r($ARGS);print "</pre>";
ob_start();
    include "ws.migration.submit.php";
$_MIGR = ob_get_clean();
//print "=> ".$_MIGR;

$_ARRAY = str2xml($_MIGR);
$_JSON = json_encode($_ARRAY);
$_GUEST = json_decode($_JSON, true);
if (count($_GUEST)!=0) {
    foreach ($_GUEST AS $KEY => $VAL) if (is_array($VAL)) $_GUEST[$KEY]="";
    //print "<pre>";print_r($_GUEST);print "</pre>";
    
    $_AGENT_ID = isset($_AGENT_ID)?$_AGENT_ID:0;
    $_PWD = isset($_PWD)?$_PWD:($_GUEST['PASSWORD']!=""?$_GUEST['PASSWORD']:"");

    $_GUEST['ID'] = dbNextId($db);
    $_GUEST['OWNER_ID'] = $_AGENT_ID==0 ? $_GUEST['ID'] : $_AGENT_ID;
    $_GUEST['PASSWORD'] = $_AGENT_ID==0 ? $_PWD : "~0~";

    //print "<pre>";print_r($_GUEST);print "</pre>";
    //exit;

    $result = $clsGuest->create($db, $_GUEST);

    if (is_array($result)) {
        $_GUEST = array();
    } else {
        // IMPORT GUEST RESERVATIONS FROM OLD SYSTEM
        $ARGS = array (
            "CODE"=>"getReservations",
            "ID"=> (isset($_OLD_ID)&&$_OLD_ID!=0) ? $_OLD_ID : $_GUEST['MIGRATED_ID']
        );
        ob_start();
            include "ws.migration.submit.php";
        $_MIGR = ob_get_clean();
        $_RESULT = $clsGlobal->jsonDecode($_MIGR);
        //print "<pre>";print_r($_RESULT);print "</pre>";
        $PROPERTIES = $clsGlobal->getPropertiesByIDs($db, array("asArray"=>true));

        foreach ($_RESULT as $RES_NUMBER=>$_DATA) {
            $clsAvailability->get_Property($db, array("RES_PROP_ID"=>$_DATA["RES_PROP_ID"]));
            $_DATA["ID"] = dbNextId($db);
            $_DATA["GUEST_ID"] = $_GUEST['ID']; 
            $_DATA["OWNER_ID"] = (isset($_AGENT_ID)&&$_AGENT_ID!=0) ? $_AGENT_ID : $_GUEST['ID'];
            $_DATA["SOURCE_ID"] = $_DATA["OWNER_ID"];
            $_DATA["RES_TABLE"] = "RESERVATIONS_".$PROPERTIES[$_DATA['RES_PROP_ID']]['CODE']."_".$_DATA['RES_YEAR'];

            include "import.reservation.php";
            //break;
        }
    }
} 

?>