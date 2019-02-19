<?
  // Maybe you could try changing session.use_only_cookies to on in php.ini.
  // http://www.php.net/manual/en/function.session-set-save-handler.php
  // https://forums.powweb.com/showthread.php?t=77977

  // http://www.php.net/manual/en/function.session-set-save-handler.php
?>

<?
  //include "FileSessionHandler.php";
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
  /*
  echo "test page with fopen()<br />";
  // try to open up a file
  $write_handle = fopen("slowdown_test_file.txt", "w");
  if ($write_handle === false)  echo "<br>Error: Couldn't open slowdown_test_file.txt for writing.<br>";
  $file_update_time = time();
  $write_result = fwrite($write_handle, $file_update_time);
  fclose($write_handle);
  */
  $_SESSION['TEST'] = "Cool";


?>