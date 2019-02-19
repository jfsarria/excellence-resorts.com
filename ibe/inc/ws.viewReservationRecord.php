<?
// http://www.excellence-resorts.com/ibe/index.php?PAGE_CODE=ws.viewReservationRecord&TABLE=RESERVATIONS_XPM_2012&NUMBER=2104400400149
// http://www.locateandshare.com/ibe/index.php?PAGE_CODE=ws.viewReservationRecord&TABLE=RESERVATIONS_XRC_2012&NUMBER=1101395497970

$query = "SHOW COLUMNS FROM {$_REQUEST['TABLE']}";
$arg = array('query' => $query);
$FSET = dbQuery($db, $arg);

$query = "SELECT * FROM {$_REQUEST['TABLE']} WHERE NUMBER = '{$_REQUEST['NUMBER']}'";
$arg = array('query' => $query);
$RSET = dbQuery($db, $arg);

if ($RSET['iCount']!=0) {
    print "<table>";
    $r = $db->fetch_array($RSET['rSet']);
    while ($f = $db->fetch_array($FSET['rSet'])) {
        $Field = $f['Field'];
        print "<tr><td valign='top'>".$Field."</td><td>".$r[$Field]."</td></tr>";
        //print_r($r);
    }
    print "</table>";
}

?>