<?
/*
 * Revised: Sep 16, 2016
 *          Mar 30, 2018
 */

$isMigration = false;
$OUT = "";
$_EMAIL = isset($_GET['EMAIL']) ? trim($_GET['EMAIL']) : "";
$_PWD = isset($_GET['PWD']) ? trim($_GET['PWD']) : "";
$_OLD_ID = isset($_REQUEST['OLD_ID']) ? (int)$_REQUEST['OLD_ID'] : 0;
$_AGENT_ID = isset($_REQUEST['AGENT_ID']) ? (int)$_REQUEST['AGENT_ID'] : 0;

if ($_EMAIL!="") {
    ob_start();
    if ($_PWD!="") {
        $RSET = $clsGuest->getByEmailPwd($db, array("EMAIL"=>$_EMAIL,"PASSWORD"=>$_PWD));
        if ($RSET['iCount']!=0) {
            while ($row = $db->fetch_array($RSET['rSet'])) {
                $GUEST = $clsGlobal->cleanUp_rSet_Array($row);
                print "<guest>";
                foreach ($GUEST AS $KEY => $VAL) {
                    if ($KEY!="PASSWORD") print "<{$KEY}>{$VAL}</{$KEY}>";
                }
                print "</guest>";
            }
        } else {
            // IMPORT GUEST FROM OLD SYSTEM
            // include_once "import.guest.php";
        }
    } else {
        $RSET = $clsGuest->getByEmail($db, array("EMAIL"=>$_EMAIL));
        if ($RSET['iCount']!=0) {
            while ($row = $db->fetch_array($RSET['rSet'])) {
                $GUEST = $clsGlobal->cleanUp_rSet_Array($row);
                print "<guest>";
                foreach ($GUEST AS $KEY => $VAL) {
                    if ($KEY!="PASSWORD") print "<{$KEY}>{$VAL}</{$KEY}>";
                }
                print "</guest>";
            }
        }    
    }
    $OUT = ob_get_clean();
} else if ($_OLD_ID!=0) {

    // M I G R A T E

    $RSET = $clsGuest->getByEmailOldId($db, array("MIGRATED_ID"=>$_OLD_ID));
    if ($RSET['iCount']==0) {
        // IMPORT GUEST THAT BELONGS TO TA FROM OLD SYSTEM USING OLD_ID
        include_once "import.guest.php";
    }
}

//print $OUT;

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header("Content-Type:application/json");

if (isset($isMigration)&&$isMigration) {
    print (count($_GUEST)!=0) ? json_encode($_GUEST) : "{}";
} else {
    print ($OUT!="") ? json_encode(str2xml($OUT)) : "{}";
}

?>