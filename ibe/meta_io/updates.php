<?php

date_default_timezone_set("America/New_York");
include_once "fns.php";
$PROP = getMetaIOProp();
//print "<pre>";print_r($PROP);print "</pre>";
$dir = $_SERVER['DOCUMENT_ROOT'].'/ibe/meta_io/data/';
$files = scandir($dir);
$CODES = $PROP["CODE"];
$CODE = $CODES[0]=="XRC" ? "ER" : $CODES[0];

$DATA = array($CODE=>array());
$cnt = 0;
foreach($files as $filename) {
    if ($filename!="."&&$filename!="..") {
        $modified = filemtime($dir.$filename);
        $KEY = date("Y-m-d H:i:s ".(++$cnt),$modified);
        $DATA[$CODE][$KEY] = "http://".$_SERVER['HTTP_HOST']."/ibe/meta_io/data/".$filename;
    }
}
krsort($DATA[$CODE]);

$JSON = json_encode($DATA);
//print "<pre>";print_r($DATA);print "</pre>";

header('Content-type: application/json');
print $JSON;

?>
