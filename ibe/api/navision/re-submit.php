<?
# http://excellence-resorts.com/ibe/index.php?PAGE_CODE=ws.navisionCall&YEAR=2017&PROP_CODE=XOB

#print "<pre>";print_r($_SERVER);print "</pre>";EXIT;

global $wpdb;
$isCron = true;

$YEAR = isset($_GET['YEAR'])?$_GET['YEAR']:date("Y");
$PROP_CODE = isset($_GET['PROP_CODE'])?$_GET['PROP_CODE']:"FPM";

if (empty($YEAR) || empty($PROP_CODE)) {
  exit;
}

#$LIMIT = 1;

$SQLSTR = "SELECT ID, NUMBER, NAVISION_STATUS FROM RESERVATIONS_{$PROP_CODE}_{$YEAR} WHERE CHECK_IN >= '".DATE("Y-m-d")."' AND NAVISION_STATUS='RESERVAR'";

if (isset($LIMIT)) {
  $SQLSTR .= " LIMIT ".$LIMIT;
}

print "<pre>".$SQLSTR."</pre><hr>";

//EXIT;

$RSET = dbQuery($db, array('query' => $SQLSTR));
print "Count: ".$RSET['iCount']."<br><br>";
while ($RECORD = $db->fetch_array($RSET['rSet'])) {
    $NUMBER = $RECORD['NUMBER'];

    PRINT $NUMBER." :: ";

    $exec = 'wget --background -q "http://'.$_SERVER["SERVER_NAME"].'/ibe/index.php?PAGE_CODE=ws.navisionCall&NUM='.$NUMBER.'" -O /dev/null';
    print exec($exec);print " :: $exec<BR>";
}