<?
/*
 * Revised: Sep 02, 2012
 */

$DONE = false;
$RESERVATIONS = array();
$IDs = isset($_GET['IDs']) ? explode(",",$_GET['IDs']) : array();
$CHECK_IN = isset($_GET['CHECK_IN']) ? trim($_GET['CHECK_IN']) : "";

$arg = Array(
    "YEARS" => Array(date("Y")-1,date("Y")),
    "VIEWBY" => "activity",
    "GROUPED" => 1
);

if (count($IDs)>1) {
    array_shift($IDs);
    $WHERE = array();
    foreach ($IDs as $i=>$ID) array_push($WHERE,"view.NUMBER = '{$ID}'");
    $arg["WHERE"] = implode(" OR ",$WHERE);
    $DONE = true;
} else if ($CHECK_IN!="") {
    //$arg["WHERE"] = "CHECK_IN = '$CHECK_IN'";
    $arg["WHERE"] = "(CHECK_IN >= '".DATE("Y-m-d")."' AND CHECK_IN <= '$CHECK_IN')";
    $DONE = true;
}

if ($DONE) {
    $RSET = getReservations($db, $arg);
    while ($row = $db->fetch_array($RSET['rSet'])) {
        if (!isset($RESERVATIONS[$row['NUMBER']])) {
            $RESERVATIONS[$row['NUMBER']] = $clsGlobal->cleanUp_rSet_Array($row);
        }
    }
}

function getReservations($db, $arg) {
    extract($arg);
    global $clsReserv;
    #print "<pre>";print_r($arg);print "<pre>";
    $qry = array();
    $TABLES = array();
    $RSET = mysql_list_tables(constant("APP_DB_NAME"));
    while ($row = mysql_fetch_row($RSET)) {
        $VIEWNAME = $row[0];
        if (strstr($VIEWNAME,"V_SEARCH_")) {
            $TPL = explode("_",$VIEWNAME);
            if (count($TPL)==4 && in_array($TPL[3], $YEARS)!==FALSE) {
                array_push($TABLES,$VIEWNAME);
            }
        }
    }
    arsort($TABLES);
    foreach ($TABLES as $i=>$VIEWNAME) {
        #print "\n".$VIEWNAME."<br>";
        $clsReserv->searchReservationQuery($db, array(
            "GROUPED"=>$GROUPED,
            "VIEWNAME"=>$VIEWNAME,
            "TABLENAME"=>str_replace("V_SEARCH_","RESERVATIONS_",$VIEWNAME),
            "WHERE"=>"(".$WHERE.")"
        ), $qry);
    }

    $query = implode(" UNION ",$qry)." ORDER BY NUMBER DESC";
    $arg = array('query' => $query);
    //print "<p class='s_notice top_msg'>$query</p>";
    $result = dbQuery($db, $arg);
    return $result;
}

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header("Content-Type:application/json");

print json_encode($RESERVATIONS);

?>