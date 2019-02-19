<?
/*
 * Revised: Aug 01, 2011
 */

$OUT = "";
$ID = isset($_GET['ID']) ? trim($_GET['ID']) : "";

if ($ID!="") {
    ob_start();
    print "<result>";
    $RSET = $clsGuest->getByKey($db, array("WHERE"=>" OWNER_ID='{$ID}' "));
    while ($row = $db->fetch_array($RSET['rSet'])) {
        $TA = $clsGlobal->cleanUp_rSet_Array($row);
        print "<guests>";
        foreach ($TA AS $KEY => $VAL) {
            if ($KEY!="PASSWORD") print "<{$KEY}>{$VAL}</{$KEY}>";
        }
        print "</guests>";
    }
    print "</result>";
    $OUT = ob_get_clean();
}

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ("Content-Type:application/json");

print ($OUT!="") ? json_encode(str2xml($OUT)) : "{}";


?>