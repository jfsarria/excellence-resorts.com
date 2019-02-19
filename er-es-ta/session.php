<?php

/**
 * @author NODOXI
 * @copyright 2011
 */

session_start();
switch($_GET['action']){
  case "delete":
    session_destroy();
    break;
  case "set":
    if(!isset($_GET['ID'])||!isset($_GET['LASTNAME'])||!isset($_GET['NAME']))
      die('Not enough params');
    $account = array();
    $account['ID'] = $_GET['ID'];
    $account['NAME'] = $_GET['NAME']; 
    $account['LASTNAME'] = $_GET['LASTNAME'];  
    
    $_SESSION['account'] = serialize($account);
    print "true";
    break;
  default:
    print json_encode(unserialize($_SESSION['account']));
    break;
}
session_write_close();
?>