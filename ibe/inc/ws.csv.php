<?

// http://locateandshare.com/ibe/index.php?PAGE_CODE=ws.csv&CODE=XPC&YEAR=2012

$CODE = isset($_GET['CODE']) ? $_GET['CODE'] : "";
$YEAR = isset($_GET['YEAR']) ? $_GET['YEAR'] : "";
$FIELDS = "number,check_in,check_out,nights,rooms,total,fees,supplement,hear_about_us,cancelled,created,created_by,geo_ip,geo_country_code,geo_country_name";
$filename = "RESERVATIONS_{$CODE}_{$YEAR}";

if ($CODE!="" && $YEAR!="") {

    $RSET = $clsReserv->getCSV($db, array("CODE"=>$CODE,"YEAR"=>$YEAR,"FIELDS"=>$FIELDS));
    //print "Count: ".$RSET['iCount'];

    $csv_output = $FIELDS."\n";
    $arr = explode(",",$FIELDS);
    $cnt = count($arr);

    while ($row = $db->fetch_array($RSET['rSet'])) {
        for ($j=0;$j<$cnt;$j++) {
            $value = str_replace('"', '""', $row[$j]);
            $csv_output .= '"' . $value . '"' . ",";
        }
        $csv_output .= "\n";
    }
} 

header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header("Content-disposition: filename=".$filename.".csv");

print $csv_output;

?>