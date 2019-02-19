<?

#print "<pre>";print_r($_SERVER);print "</pre>";EXIT;

global $wpdb;
$isCron = true;

$YEAR = isset($_GET['YEAR'])?$_GET['YEAR']:"2017";
$PROP_CODE = isset($_GET['PROP_CODE'])?$_GET['PROP_CODE']:"FPM";
#$LIMIT = 1;

$SQLSTR = "SELECT ID, NUMBER, NAVISION_STATUS FROM RESERVATIONS_{$PROP_CODE}_{$YEAR} WHERE CHECK_IN >= '".DATE("Y-m-d")."' AND NAVISION_ERROR<>'' AND NAVISION_SENT LIKE '%>RESERVAR<%' AND (NAVISION_SENT LIKE '%<RESERVANH/>%' OR NAVISION_SENT LIKE '%<RESERVANH></RESERVANH>%') AND NAVISION_RESULT=''";

if (isset($LIMIT)) {
  $SQLSTR .= " LIMIT ".$LIMIT;
}

print "<pre>".$SQLSTR."</pre><hr>";

EXIT;

$RSET = dbQuery($db, array('query' => $SQLSTR));
print "Count: ".$RSET['iCount']."<br><br>";
while ($RECORD = $db->fetch_array($RSET['rSet'])) {
    $ID = $RECORD['ID'];
    $NUMBER = $RECORD['NUMBER'];
    $NAVISION_STATUS = $RECORD['NAVISION_STATUS'];
    PRINT $NUMBER." :: ";
    if (empty($NAVISION_STATUS)) {
      $query = "UPDATE RESERVATIONS_{$PROP_CODE}_{$YEAR} SET NAVISION_STATUS = 'RESERVAR' WHERE ID = '$ID'";
      dbExecute($db, array('query' => $query));
    }
    $exec = 'wget --background -q "http://'.$_SERVER["SERVER_NAME"].'/ibe/index.php?PAGE_CODE=ws.navisionCall&NUM='.$NUMBER.'" -O /dev/null';
    print exec($exec);print " :: $exec<BR>";
}