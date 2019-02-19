<?php

// wget -O /var/www/vhosts/iskullny.com/stagingfinest/ibe/api/navision/navision.log http://staging.finestresorts.com/ibe/index.php?PAGE_CODE=ws.navisionCall
// */10 * * * * wget -O /var/www/vhosts/iskullny.com/finestresorts/ibe/api/navision/navision.log http://www.finestresorts.com/ibe/index.php?PAGE_CODE=ws.navisionCall
// http://secure-belovedhotels.com/ibe/index.php?PAGE_CODE=ws.navisionCall

//file_put_contents($_SERVER['DOCUMENT_ROOT']."/ibe/api/navision/test.txt","hahahahaha");
//print "All good! cool stuff";exit;

/*

wget --background -q "http://www.finestresorts.com/ibe/index.php?PAGE_CODE=ws.navisionCall&NUM=516117763576476" -O /dev/null

* m.navision.php
* confirmation.php

*/

error_reporting(E_ERROR);

global $wpdb;

$isCron = true;
$FORCE = isset($_GET['FORCE'])?$_GET['FORCE']:"";
$RES_NUM = isset($_GET['NUM'])?$_GET['NUM']:"";
$RES_ID = isset($_GET['RES_ID'])?$_GET['RES_ID']:"";
$INTERVAL = "DATE_ADD(MODIFIED, INTERVAL 30 MINUTE)";
$WHERE = !empty($RES_NUM) ? " NUMBER='$RES_NUM' " : " CURRENT_DATE() >= $INTERVAL";
$WHERE = !empty($RES_NUM) ? " NUMBER='$RES_NUM' " : "";
$WHERE = !empty($RES_ID) ? " ID='$RES_ID' " : $WHERE;

$AND = empty($WHERE) ? "" : " AND ";

$WHERE .= empty($FORCE) ? " $AND NAVISION_STATUS <> '' " : "";

$PROP_CODES = array();
$SQLSTR = "SELECT `CODE` FROM PROPERTIES";
$RSET = dbQuery($db, array('query' => $SQLSTR));
while ($RECORD = $db->fetch_array($RSET['rSet'])) {
    $PROP_CODES[] = $RECORD['CODE'];
}

print "<pre>";print_r($PROP_CODES);print "</pre>";

foreach ($PROP_CODES as $PROP_CODE) {
	$YEARS = array(DATE("Y")-1, DATE("Y"));
	foreach ($YEARS as $YEAR) {
		$RES_TABLE = "RESERVATIONS_{$PROP_CODE}_{$YEAR}";

    $SQLSTR = "
      SELECT ID, NUMBER, ARRAY, NAVISION_STATUS, NAVISION_SENT, NAVISION_RESULT, NAVISION_CANCEL, MODIFIED, CURRENT_DATE() AS HOY, $INTERVAL AS MODIFIED_INTERVAL
      FROM {$RES_TABLE}
      WHERE $WHERE ";

    $SQLSTR .= !empty($FORCE) ? "  ORDER BY ID DESC LIMIT 1 " : " ORDER BY NUMBER ";

    print $SQLSTR . PHP_EOL . "<BR>";
    $RSET = dbQuery($db, array('query' => $SQLSTR));
    print "Reservations: ".$RSET['iCount'] . PHP_EOL . "<BR>";
    if ($RSET['iCount']>0) {
      while ($RECORD = $db->fetch_array($RSET['rSet'])) {
        $RES_ID = $RECORD['ID'];
        print $RECORD['NUMBER'] . PHP_EOL . "<BR>";
        try {
          include $_SERVER['DOCUMENT_ROOT']."/ibe/api/navision/make.php";
        } catch(Exception $e) { }
      }
    }

	}
}

