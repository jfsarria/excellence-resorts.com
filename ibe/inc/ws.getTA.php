<?
/*
 * Revised: Aug 30, 2011
 *          Jul 08, 2014
 */

$isMigration = false;
$OUT = "";
$_EMAIL = isset($_GET['EMAIL']) ? trim($_GET['EMAIL']) : "";
$_PWD = isset($_GET['PWD']) ? trim($_GET['PWD']) : "";
$_ID = isset($_GET['ID']) ? trim($_GET['ID']) : "";

if (($_EMAIL!="" && $_PWD!="") || $_ID!="") {
    ob_start();
    if ($_ID!="") {
      $RSET = $clsTA->getById($db, array("ID"=>$_ID));
    } else {
      $RSET = $clsTA->getByEmailPwd($db, array("EMAIL"=>$_EMAIL,"PASSWORD"=>$_PWD));
    }
    if ($RSET['iCount']!=0) {
        while ($row = $db->fetch_array($RSET['rSet'])) {
            $TA = $clsGlobal->cleanUp_rSet_Array($row);
            print "<agent>";
            foreach ($TA AS $KEY => $VAL) {
                if ($KEY!="PASSWORD") print "<{$KEY}>{$VAL}</{$KEY}>";
            }
            print "</agent>";
        }
    } else {
        // IMPORT GUEST FROM OLD SYSTEM
        include_once "import.ta.php";

    }
    $OUT = ob_get_clean();
}

//print $OUT;

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header("Content-Type:application/json");

if (isset($isMigration)&&$isMigration) {
    print (count($_AGENT)!=0) ? json_encode($_AGENT) : "{}";
} else {
    print ($OUT!="") ? json_encode(str2xml($OUT)) : "{}";
}

?>