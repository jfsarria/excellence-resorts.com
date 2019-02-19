<?
/*
 * Revised: Nov 01, 2016
 */

global $wpdb;

$TODAY = date("Y-m-d");
$query = "SELECT * FROM CURRENCY ORDER BY DATE DESC LIMIT 6";
//print "<p class='s_notice top_msg'>$query</p>";
$RSET = dbQuery($db, array('query' => $query));
$CURRENCY = array();
while ($row = $db->fetch_array($RSET['rSet'])) {
    $CURRENCY[$row['CODE']] = (double)$row['QUOTE'];
}

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ("Content-Type:application/json");

print json_encode($CURRENCY);
