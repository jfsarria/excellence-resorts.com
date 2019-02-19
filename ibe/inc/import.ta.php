<?
/*
 * Revised: Oct 16, 2011
 */

$isMigration = true;
include "ws.migration.server.php";
$ARGS = array (
    "CODE"=>"getAgentByUsrPwd",
    "EMAIL"=>isset($_EMAIL)?$_EMAIL:"",
    "PASSWORD"=>isset($_PWD)?$_PWD:"",
    "OLD_ID"=>(isset($_OLD_ID)&&$_OLD_ID!=0) ? $_OLD_ID : 0
);
ob_start();
    include "ws.migration.submit.php";
$_MIGR = ob_get_clean();
//print "=>".$_MIGR;

$_ARRAY = str2xml($_MIGR);
$_JSON = json_encode($_ARRAY);
$_AGENT = json_decode($_JSON, true);
if (count($_AGENT)!=0) {
    foreach ($_AGENT AS $KEY => $VAL) if (is_array($VAL)) $_AGENT[$KEY]="";
    //print "<pre>";print_r($_AGENT);print "<pre>";

    $_AGENT_ID = isset($_AGENT_ID)?$_AGENT_ID:0;
    $_PWD = isset($_PWD)?$_PWD:($_AGENT['PASSWORD']!=""?$_AGENT['PASSWORD']:"");

    $_AGENT['ID'] = dbNextId($db);
    $_AGENT['OWNER_ID'] = $_AGENT['ID'];
    $_AGENT['PASSWORD'] = $_PWD;

    $result = $clsTA->create($db, $_AGENT);

    if (is_array($result)) {
        $_AGENT = array();
    } else {
        // IMPORT TA RESERVATIONS FROM OLD SYSTEM
        $ARGS = array (
            "CODE"=>"getReservations",
            "ID"=>$_AGENT['MIGRATED_ID']
        );
        ob_start();
            include "ws.migration.submit.php";
        $_MIGR = ob_get_clean();
        //print $_MIGR;
        $_RESULT = $clsGlobal->jsonDecode($_MIGR);
        //print "<pre>";print_r($_RESULT);print "</pre>";
        //http://www.locateandshare.com/ibe/index.php?PAGE_CODE=ws.getGuest&OLD_ID=22369&AGENT_ID=106646
        //$WSURL = ((strstr($B_SERVER_NAME,"excellence-resorts")!==FALSE) ? "https://secure-excellence-resorts.com" : "http://www.locateandshare.com")."/ibe/index.php?";
        $WSURL = "https://excellence-resorts.com"."/ibe/index.php?";
        //print $WSURL;
        foreach ($_RESULT as $RES_NUM=>$DATA) {
            $ARGS = array (
                "PAGE_CODE"=>"ws.getGuest",
                "OLD_ID"=>$DATA['GUEST_ID'],
                "AGENT_ID"=>$_AGENT['ID']
            );
            //print "$RES_NUM <pre>";print_r($ARGS);print "</pre>";
            ob_start();
                include "ws.migration.submit.php";
            $_MIGR = ob_get_clean();
            //print $_MIGR;
        }

    }
} 

?>