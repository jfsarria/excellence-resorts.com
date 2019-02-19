<?php
  
  include "classes.php";

  include "RESERVAR-MXFPM.php";
  //include "RESERVAR-MXFPM-2.php";
  //print "ok";exit;

//  ob_start();
//    include "Examples/1. New Booking/RESERVAR.xml";
//  $DATA = ob_get_clean();
//  print "==> $DATA";

  $api = new navision_cls();
  $newBooking = $api->execute($DATA);
  $result = $newBooking->GetProcessResult;
  $json = $api->str2json($result);
  
  print header('Content-Type: application/json');print $json;
