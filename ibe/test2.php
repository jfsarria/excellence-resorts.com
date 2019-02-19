<?
  include "FileSessionHandler.php";
?>

<?
  $call_starttime = explode(' ', microtime());
  session_start();
  print "<pre>";print_r($call_starttime);print "</pre>";
  $call_finishtime = explode(' ', microtime());
  print "<pre>";print_r($call_finishtime);print "</pre>";
  $call_duration = round(($call_finishtime[0]+$call_finishtime[1]) - ($call_starttime[0]+$call_starttime[1]), 3);
  print "session_start: $call_duration <br>";
?>

<?php
  print "==> ".$_SESSION['TEST'];
?>