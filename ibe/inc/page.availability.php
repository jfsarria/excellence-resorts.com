<?
/*
 * Revised: Jan 06, 2013
 *          Oct 30, 2016
 *          Sep 12, 2018 Cajica 
 */

if (!is_array($_DATA)) $_DATA = array();

if (!$isWEBSERVICE) print $clsGlobal->buildWebserviceParameters("ws.availability", $_DATA);

$_DATA['RES_SRC'] = isset($_DATA['RES_SRC']) ? $_DATA['RES_SRC'] : "";
$_DATA['RES_LANGUAGE'] = (isset($_DATA['RES_LANGUAGE'])&&$_DATA['RES_LANGUAGE']!="") ? $_DATA['RES_LANGUAGE'] : "";
$_DATA['RES_DATE'] = (isset($_DATA['RES_DATE'])&&$_DATA['RES_DATE']!="") ? $_DATA['RES_DATE'] : $_TODAY;
$_DATA['RES_IN_THE_FUTURE'] = (isset($_DATA['RES_IN_THE_FUTURE'])&&$_DATA['RES_IN_THE_FUTURE']!="") ? (int)$_DATA['RES_IN_THE_FUTURE'] : 0;
$_DATA['RES_PROP_ID'] = (isset($_DATA['RES_PROP_ID'])&&(int)$_DATA['RES_PROP_ID']!=0) ? (int)$_DATA['RES_PROP_ID'] : 1;
$_DATA['RES_USERTYPE'] = (isset($_DATA['RES_USERTYPE'])) ? $_DATA['RES_USERTYPE'] : array('1'=>1);
$_DATA['RES_SPECIAL_CODE'] = (isset($_DATA['RES_SPECIAL_CODE'])) ? $_DATA['RES_SPECIAL_CODE'] : "";
$_DATA['RES_COUNTRY_CODE'] = (isset($_DATA['RES_COUNTRY_CODE'])) ? $_DATA['RES_COUNTRY_CODE'] : "US";
$_DATA['RES_STATE_CODE'] = (isset($_DATA['RES_STATE_CODE'])) ? $_DATA['RES_STATE_CODE'] : "";
$_DATA['RES_CHECK_IN'] = (isset($_DATA['RES_CHECK_IN'])&&$_DATA['RES_CHECK_IN']!="") ? $_DATA['RES_CHECK_IN'] : $_TODAY;
$_DATA['RES_NIGHTS'] = (isset($_DATA['RES_NIGHTS'])) ? (int)$_DATA['RES_NIGHTS'] : 2;
$_DATA['RES_ROOMS_QTY'] = (isset($_DATA['RES_ROOMS_QTY'])) ? (int)$_DATA['RES_ROOMS_QTY'] : 1;
//$_DATA['RES_CHECK_OUT'] = (isset($_DATA['RES_CHECK_OUT'])&&$_DATA['RES_CHECK_OUT']!="") ? $_DATA['RES_CHECK_OUT'] : addDaysToDate($_TODAY, $_DATA['RES_NIGHTS']);
$_DATA['RES_CHECK_OUT'] = addDaysToDate($_DATA['RES_CHECK_IN'], $_DATA['RES_NIGHTS']);

$_DATA['RES_REBOOKING'] = array();
$RES_REBOOKING_FIELDS = array('RES_ID','RES_NUM','RES_CODE','RES_YEAR','PROP_ID','CHECK_IN','ROOMS','TOTAL_CHARGE','NIGHTS','TO_WHOM','GUEST_ID','TA_ID','CC_COMMENTS','TRANSFER_FEE','TRANSFER_CAR');
foreach ($RES_REBOOKING_FIELDS as $i => $FIELD) {
    $_DATA['RES_REBOOKING'][$FIELD] = (isset($_REQUEST["REBOOK_{$FIELD}"])) ? $_REQUEST["REBOOK_{$FIELD}"] : "";
    unset($_DATA["REBOOK_{$FIELD}"]);
}

//print "<pre>";print_r($_DATA);print "</pre>";

$_DATA['RES_PROP_ID'] = ($_DATA['RES_REBOOKING']['PROP_ID']!="" && $ACTION!="SUBMIT") ? $_DATA['RES_REBOOKING']['PROP_ID'] : $_DATA['RES_PROP_ID'];

if ($ACTION=="SUBMIT") {
    // GET DATA FOR WEBSERVICE
    if ($isWEBSERVICE) {
        $P_RSET = $clsGlobal->getPropertyById($db,array("PROPERTY_ID"=>$_DATA['RES_PROP_ID']));
        $_PROPERTY = $db->fetch_array($P_RSET['rSet']);
    }

    // LOAD PROPERTY SPECIFIC AVAILABILITY CALCULATIONS
    if ($_DATA['RES_PROP_ID'] == 4 || $_DATA['RES_PROP_ID'] == 5) {
        include_once "cls/ibe.availability.override.cls.php";
    }

    // GET GEO INFORMATION
    $IP = (isset($_REQUEST['RES_IP'])) ? $_REQUEST['RES_IP'] : $_SERVER["REMOTE_ADDR"];
    $_DATA['GEO_IP'] = $IP;

    //ob_start();print_r($_DATA);$output = ob_get_clean();    mail("jaunsarria@gmail.com","_DATA",$output);

    ////if (isset($_DATA['GET_GEO'])) {
        include("geo/geoipcity.inc");
        include("geo/geoipregionvars.php");
        $gi = geoip_open($_SERVER['DOCUMENT_ROOT']."/ibe/geo/GeoLiteCity.dat",GEOIP_STANDARD);
            // http://www.locateandshare.com/ibe/geo/sample_city.php
            $geo = geoip_record_by_addr($gi,$IP);
            $_GEO_LITE = array();
            $_GEO_LITE['RES_GEO_IP'] = $IP;
            $_GEO_LITE['RES_GEO_COUNTRY_CODE'] = isset($geo->country_code) ? $geo->country_code : "US";
            $_GEO_LITE['RES_GEO_COUNTRY_NAME'] = isset($geo->country_name) ? $geo->country_name : "United States";
            $_GEO_LITE['RES_GEO_CITY'] = isset($geo->city) ? $geo->city : "New York";
            $_GEO_LITE['RES_GEO_ZIPCODE'] = isset($geo->postal_code) ? $geo->postal_code : "10004";

						//mail("juan.sarria@everlivesolutions.com","ER GEO /ibe/inc/page.availability.php $IP","$IP - ".$_GEO_LITE['RES_GEO_COUNTRY_CODE']);

        geoip_close($gi);
    ////}

    if (isset($_DATA['GET_GEO'])) {
        foreach ($_GEO_LITE as $KEY => $VAL) {
            $_DATA[$VAL] = $_GEO_LITE[$VAL];
        }
    }

    // DEBUGGING CODE
    $_DATA["DEBUGGING"] = array(
        "GEO_LITE" => $_GEO_LITE,
        "COUNTRY_CODE" => array()
    );

    // GET AVAILABILITY
    $_SESSION['AVAILABILITY'] = $clsAvailability->get_Availability($db, $_DATA);
    $_SESSION['AVAILABILITY']['SEARCH'] = $_DATA;

    // GET AND APPLY INVENTORY ALLOTMENT OVERRIDE
    $ROOM_IDs = array();
    for ($ROOM=1; $ROOM <= (int)$_SESSION['AVAILABILITY']['RES_ROOMS_QTY']; ++$ROOM) {
        foreach ($_SESSION['AVAILABILITY']["RES_ROOM_{$ROOM}_ROOMS"] AS $ROOM_ID => $DATA) {
            array_push($ROOM_IDs, $ROOM_ID);
        }
    }

    $FROM = $_SESSION['AVAILABILITY']['RES_CHECK_IN'];
    $TO = $_SESSION['AVAILABILITY']['RES_CHECK_OUT'];
    $CODE = ($isWEBSERVICE) ? $_PROPERTY['CODE'] : $_SESSION['AUTHENTICATION']['PROPERTIES'][$_DATA['RES_PROP_ID']]['CODE'];

    $YEAR_START = date("Y", strtotime($FROM));
    if ($YEAR_START<date("Y")) $YEAR_START = date("Y");

    $YEAR_END = date("Y", strtotime($TO));
    if ($YEAR_END>date("Y")+1) $YEAR_END = date("Y")+1;

    $YEARS = array($YEAR_START,$YEAR_END);

    //ob_start();print_r($_DATA);$output = ob_get_clean();
    //mail("jaunsarria@gmail.com","getBlackOut Calling","page.availability.php: $output");

    $getStopSale = true;
    include "inc/mods/m.inventory.get.data.php";
    $MAX_ROOMS = array();
    if (isset($R_RSET)) while ($row = $db->fetch_array($R_RSET['rSet'])) $MAX_ROOMS[$row['ID']] = (int)$row['MAX_ROOMS'];

    // APPLY INVENTORY
    for ($ROOM=1; $ROOM <= (int)$_SESSION['AVAILABILITY']['RES_ROOMS_QTY']; ++$ROOM) {
        foreach ($_SESSION['AVAILABILITY']["RES_ROOM_{$ROOM}_ROOMS"] AS $ROOM_ID => $DATA) {
            if (is_array($DATA) && isset($DATA["NIGTHS"])) {
                $ROOMS_LEFT = 0;
                foreach ($DATA["NIGTHS"] AS $DATE => $ARRAY) {
                    if (is_array($_SESSION['AVAILABILITY']["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['NIGTHS'][$DATE])) {
                        $isSTOPSALE = (isset($STOPSALE[$ROOM_ID][$DATE])) ? true : false;
                        $isCLOSED = (isset($BLACKOUT[$ROOM_ID][$DATE])) ? true : false;
                        $SOLD = (isset($INVENTORY[$ROOM_ID][$DATE])) ? $INVENTORY[$ROOM_ID][$DATE] : 0;
                        $PLUSQTY = (isset($OVERRIDE[$ROOM_ID][$DATE])) ? $OVERRIDE[$ROOM_ID][$DATE] : 0;
                        $LEFT = ($MAX_ROOMS[$ROOM_ID] + $PLUSQTY) - $SOLD;
                        $isSOLD = ($LEFT<=0) ? true : false;
                        if ($isCLOSED || $isSOLD || $isSTOPSALE) {
                            $_SESSION['AVAILABILITY']["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['NIGTHS'][$DATE] = $isSTOPSALE ? "STOP_SALE" : ($isCLOSED ? "CLOSED" :($LEFT==0?"SOLD":"OVER SOLD"));
                            --$_SESSION['AVAILABILITY']["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['AVAILABLE_NIGHTS'];
                        } else {
                            $_SESSION['AVAILABILITY']["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['NIGTHS'][$DATE]["INVENTORY"] = array(
                                "ROOMS"=>$MAX_ROOMS[$ROOM_ID],
                                "OVERRIDE"=>$PLUSQTY,
                                "LEFT"=>$LEFT,
                                "SOLD"=>$SOLD
                            );
                            // DISCOUNT THE JUST TAKEN ROOM TO AVOID OVER BOOKING
                            if (isset($INVENTORY[$ROOM_ID][$DATE])) {
                                ++$INVENTORY[$ROOM_ID][$DATE];
                            } else {
                                $INVENTORY[$ROOM_ID][$DATE] = 1;
                            }
                        }
                        if ($ROOMS_LEFT==0||$LEFT<$ROOMS_LEFT) {
                            $ROOMS_LEFT = $LEFT;
                        }

                    }
                }
                $_SESSION['AVAILABILITY']["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['TOTAL']['ROOMS_LEFT'] = $ROOMS_LEFT;
            }
        }
    }

} else {
    if (isset($_SESSION['AVAILABILITY'])) unset($_SESSION['AVAILABILITY']);
}

if ($isWEBSERVICE) {
    unset($_SESSION['AVAILABILITY']['ACTION']);
    unset($_SESSION['AVAILABILITY']['SEARCH']);
} else {
    include "page.availability.callcenter.hdr.php";
    ?>

    <form id="editfrm" method="post" enctype="multipart/form-data" action="?#results">
        <input type="hidden" name="ACTION" id="ACTION" VALUE="">
        <div class="aclear"></div>
        <? include_once "inc/tpl.modules.php"; ?>
        <? if ($_DATA['RES_LANGUAGE']!="") { ?>
        <div id="getAvailabilityBtn" style='text-align:center'>
            <a onclick="$('#REBOOK_PROP_ID').val('');$('#ACTION').val('SUBMIT');$('#editfrm').submit()"><span class="button key">Get Availability and Rates</span></a>
        </div>
        <? } ?>
    </form>

    <?
    if (isset($_SESSION['AVAILABILITY'])) {
        $_AVAILABILITY = $_SESSION['AVAILABILITY'];
        include_once "page.availability.list.php";
    }   
}
?>