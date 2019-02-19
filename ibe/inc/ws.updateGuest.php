<?
/*
 * Revised: Aug 05, 2011
 */

$result = array();

if (isset($_DATA['ID'])) $GUEST["ID"] = $_DATA['ID'];
if (isset($_DATA['TITLE'])) $GUEST["TITLE"] = $_DATA['TITLE'];
if (isset($_DATA['FIRSTNAME'])) $GUEST["FIRSTNAME"] = $_DATA['FIRSTNAME'];
if (isset($_DATA['LASTNAME'])) $GUEST["LASTNAME"] = $_DATA['LASTNAME'];
if (isset($_DATA['EMAIL'])) $GUEST["EMAIL"] = $_DATA['EMAIL'];
if (isset($_DATA['PASSWORD'])) $GUEST["PASSWORD"] = $_DATA['PASSWORD'];
if (isset($_DATA['LANGUAGE'])) $GUEST["LANGUAGE"] = $_DATA['LANGUAGE'];
if (isset($_DATA['ADDRESS'])) $GUEST["ADDRESS"] = $_DATA['ADDRESS'];
if (isset($_DATA['CITY'])) $GUEST["CITY"] = $_DATA['CITY'];
if (isset($_DATA['STATE'])) $GUEST["STATE"] = $_DATA['STATE'];
if (isset($_DATA['COUNTRY'])) $GUEST["COUNTRY"] = $_DATA['COUNTRY'];
if (isset($_DATA['ZIPCODE'])) $GUEST["ZIPCODE"] = $_DATA['ZIPCODE'];
if (isset($_DATA['PHONE'])) $GUEST["PHONE"] = $_DATA['PHONE'];

if ((int)$GUEST["ID"]!=0) {
    $isOk = false;

    if (isset($GUEST['EMAIL']) && trim($GUEST["EMAIL"])!="") {
        $TRSET = $clsGuest->getByKey($db, array("WHERE"=>"EMAIL = '{$GUEST['EMAIL']}'"));
        if ($TRSET['iCount']==1) {
            $TROW = $db->fetch_array($TRSET['rSet']);
            if ($TROW['ID']==$GUEST['ID']) $isOk = true;
        } else if ($TRSET['iCount']==0) $isOk = true;
    } else $isOk = true;

    if ($isOk) {
        $result = $clsGuest->modify($db, $GUEST); 

        if ((int)$result == 1) {
            $result = $GUEST;
            $showEdit = false;

            // Send confirmation email if reservation parameters are given
            if ( isset($_GET['CODE']) && isset($_GET['YEAR']) ) {
                include "ws.sendConfirmation.php";
            }
        } else {
            $result['error'] = $result;
        }
    } else {
        $result['error'] = "Email Already Taken";
    }
} else {
    $result['error'] =  "Missing Guest ID";
}

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ("Content-Type:application/json");

print json_encode($result);

?>