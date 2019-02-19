<?
/*
 * Revised: Sep 11, 2011
 */

// /ibe/index.php?PAGE_CODE=ws.getJSON&ID=113887&CODE=XPC&YEAR=2011

$ID = isset($_ID) ? $_ID : (isset($_GET['RES_ID']) ? trim($_GET['RES_ID']) : (isset($_GET['ID']) ? $_GET['ID'] : ""));
$CODE = isset($_CODE) ? $_CODE : (isset($_GET['CODE']) ? trim($_GET['CODE']) : "");
$YEAR = isset($_YEAR) ? $_YEAR : (isset($_GET['YEAR']) ? trim($_GET['YEAR']) : "");
$RESENDING = isset($_GET['RESENDING']) ? (int)$_GET['RESENDING'] : 0;
$ARRAY = array();
$JSON = "{}";

if ($ID!="" && $CODE!="" && $YEAR!="") {
    $RSET = $clsReserv->getReservationById($db, array(
        "ID"=>$ID,
        "RES_TABLE"=>"RESERVATIONS_{$CODE}_{$YEAR}",
        "FIELDS"=>"ARRAY, _fn_getStatusStr(STATUS, CHECK_IN) AS STATUS_STR"
    ));
    if ($RSET['iCount']>0) {
        $row = $db->fetch_array($RSET['rSet']);
        $ARRAY = $clsGlobal->jsonDecode($row['ARRAY']);
        $ARRAY['RESERVATION']['STATUS_STR'] = $row['STATUS_STR'];
        if (isset($ARRAY['RESERVATION']['FORWHOM']) && isset($ARRAY['RESERVATION']['FORWHOM']['RES_GUEST_ID'])) {
            $ARRAY['RESERVATION']['GUEST'] = $clsGuest->get($db, array("ID"=>$ARRAY['RESERVATION']['FORWHOM']['RES_GUEST_ID']));
        }
        $ARRAY['DAYS_LEFT'] = dateDiff($_TODAY, $ARRAY['RES_CHECK_IN'], "D", false);
        $ARRAY['IS_TRANSFER_ACTIVE'] = $clsTransfer->isActive($db, array("PROP_ID"=>$ARRAY['RES_PROP_ID']));

        include_once "mods/m.reserv.payment.er.server.php";
        $CCPS = file_get_contents($B_WEBSERVER."ws/get.php?RES_ID=".$ARRAY['RESERVATION']['RES_NUMBER']."&sortBy=UID");
        $CCPS = json_decode($CCPS, true);
        $ARRAY['CAN_CANCEL_TRANSFER'] = (int)$CCPS['STATUS']==0 ? 1 : 0;

        $JSON = $clsGlobal->jsonEncode($ARRAY);
    }
}

if (!isset($isGetJSON)) {
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header ("Content-Type:application/json");

    print $JSON;
}

?>