<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

set_time_limit(0); 
ini_set('memory_limit', '-1'); //overrides the default PHP memory limit.

// excellence-resorts.com/ibe/export.php?CODE=XPC&YEAR=2012

// select * into outfile '/tmp/some_file.csv' FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"' LINES TERMINATED BY '\n' from tabla_db;

/*
    define("APP_DB_SERVER", "localhost");
    define("APP_DB_NAME", "admin_ibe");
    define("APP_DB_USER", "john-all");
    define("APP_DB_PASS", "JohnS-@mt09@uer");
*/

/*
    RESERVATIONS_XPC_2012: 2955 Records
*/

$host = 'localhost';
$db = 'admin_ibe';
$user = 'john-all';
$pass = 'JohnS-@mt09@uer';

$table = "RESERVATIONS_".$_GET['CODE']."_".$_GET['YEAR'];
$file = $table;
$filename = $file."_".date("Y-m-d_H-i",time());

//$fields = "username, name, email, address, phone, mobile"; // the fields in the table you want. seperated with comma. Example ($fields = "field1, field2, field3";)
//$fields = "id,number,guest_id,owner_id,source_id,parent_id,check_in,check_out,nights,rooms,adults,children,total,fees,supplement,arrival_time,arrival_ampm,airline,flight,comments,cc_comments,hear_about_us,language,method,notes,cancelled,created,created_by,modified,modified_by,status,emailed,cc_type,cc_number,cc_name,cc_code,cc_exp,cc_bill_address,cc_bill_city,cc_bill_state,cc_bill_country,cc_bill_zipcode,cc_bill_email,geo_ip,geo_country_code,geo_country_name,geo_state_code,geo_city,geo_zipcode,class_names,special_names";
//$fields = "id,number,guest_id,owner_id,source_id,parent_id,check_in,check_out";
$fields = "number,check_in,check_out,nights,rooms,total,fees,supplement,hear_about_us,cancelled,created,created_by,geo_ip,geo_country_code,geo_country_name";

$link = mysql_connect($host, $user, $pass) or die("Can not connect." . mysql_error());
mysql_select_db($db) or die("Can not connect.");

$arr = explode(",",$fields);
$cnt = count($arr);
$csv_output = $fields."\n";

$sql = "SELECT $fields FROM ".$table."";
$values = mysql_query($sql);
//$csv_output .= "\"".$sql."\"\n";
$records = mysql_fetch_row($values);
print $sql."<br>".count($records);
exit;
$isOK = true;
$line = 1;
while ($rowr = $records && $isOK) {
    for ($j=0;$j<$cnt;$j++) {
        $value = str_replace('"', '""', $rowr[$j]);
        $csv_output .= '"' . $value . '"' . ",";
    }
    $csv_output .= "\n";

    //if ($line % 100 == 0) {
        /*
    if ($line == 100) {
        $myFile = $_SERVER['DOCUMENT_ROOT']."/ibe/test/".$file.".csv";
        $fh = fopen($myFile, 'a') or die("can't open file");
        fwrite($fh, $csv_output);
        fclose($fh);
        $line = 0;
        $csv_output = "";
        $isOK = false;
    }
    */
    ++$line;
}

/*
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header("Content-disposition: filename=".$filename.".csv");
*/

print $csv_output;

print "Done";

exit;
?>