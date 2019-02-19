<?
/*
 * Revised: Feb 04, 2017
 *
 * http://excellence-resorts.com/ibe/index.php?PAGE_CODE=ws.metaio_cron&PROPERTY=2
 *
 *
 */

date_default_timezone_set("America/New_York");
include "fns.php";

//ob_start();print_r($_GET);$PARAS=ob_get_clean();

$PROPERTY = isset($_GET['PROPERTY']) ? $_GET['PROPERTY'] : 1;
PRINT "<br>PROPERTY: $PROPERTY ==> ";

$run = false;
$reset = false;
$arg = array("PROPERTY"=>$PROPERTY);
$RSET = getCronStatus($db, $arg);
//print "<pre>";print_r($RSET);print "</pre>";

if ($RSET['iCount']==1) {
  $row = $db->fetch_array($RSET['rSet']);
  $COUNTRY = (int)$row["COUNTRY"];
  $ADULTS = (int)$row["ADULTS"];
  $MONTH = (int)$row["MONTH"];
  $DATE = $row["DATE"];
} else {
  $reset = true;
}

if (!$reset) {
  if ($COUNTRY==3 && $ADULTS==3 && $MONTH==13) {
    if ($DATE != $_TODAY) {
      $reset = true;
    }
  } else {
    $run = true;
    if ($COUNTRY==3 && $ADULTS==3) {
      ++$MONTH;
      $COUNTRY=1;
      $ADULTS=1;
    } else if ($COUNTRY!=3 && $ADULTS==3) {
      $ADULTS=1;
      ++$COUNTRY;
    } else {
      ++$ADULTS;
    }    
  }
}

if ($reset) {
  $run = true;
  $COUNTRY = 1;
  $ADULTS = 1;
  $MONTH = 1;
  $DATE = $_TODAY;  
}

// Property 1, Country 1, Adults 1, Month 1, from 2017-02-03, to 2017-03-02

//mail("jaunsarria@gmail.com","{$_SERVER['HTTP_HOST']} : $PROPERTY ==> $DATE :: $COUNTRY - $ADULTS - $MONTH",$PARAS);

if ($run) {
  print " Running...";
  $START = addUnitsToDate($DATE, "+".($MONTH-1), true, "months");
  $STOP = addUnitsToDate($START, "+1", true, "months");
  $STOP = addUnitsToDate($STOP, "-1", true, "days");
  print " :: $START - $STOP :: ";

  updateCronStatus($db, array(
    "PROPERTY" => $PROPERTY,
    "COUNTRY" => $COUNTRY,
    "ADULTS" => $ADULTS,
    "MONTH" => $MONTH,
    "DATE" => $DATE
  ));

  $API = "http://{$_SERVER['HTTP_HOST']}/ibe/meta_io/generate.php?PROP_ID=$PROPERTY&COUNTRY=$COUNTRY&ADULTS_QTY=$ADULTS&MONTH=$MONTH&START=$START&STOP=$STOP&RETURN=2";
  print $API;

  //file_put_contents($_SERVER["DOCUMENT_ROOT"]."/ibe/meta_io/debug2.txt",$API."\n",FILE_APPEND);

  $RESULT = file_get_contents($API);
  PRINT $RESULT;
}


