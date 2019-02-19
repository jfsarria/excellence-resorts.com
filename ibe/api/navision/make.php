<?php

//print "Good"; exit;

//error_reporting(E_ALL ^ E_STRICT);

ob_start();

$FORCE = isset($FORCE) ? $FORCE : "";
$isCron = isset($isCron) ? $isCron : false;
$JSON = $clsGlobal->jsonDecode($RECORD['ARRAY']);
$BOOK = $JSON;//$_SESSION['AVAILABILITY'];
//print "BOOK:<pre>";print_r($BOOK);print "</pre>";
/*
ob_start();
  print $RECORD['ARRAY'] . " == <pre>";print_r($BOOK);print "</pre>";
$_RESULT = ob_get_clean();
mail("juan.sarria@everlivesolutions.com","navision debug",$_RESULT);
*/

$NAVISION_STATUS = $RECORD['NAVISION_STATUS'];
if (!empty($FORCE) && empty($NAVISION_STATUS)) {
  $NAVISION_STATUS = $FORCE;
}

$isCancelled = $NAVISION_STATUS=="ELIMINAR";
$isUpdate = $NAVISION_STATUS=="UPDATE";

include_once $_SERVER["DOCUMENT_ROOT"] . "/ibe/api/navision/classes.php";

$api = new navision_cls();
//print "Good $RES_ID - $RES_TABLE"; exit;
#print "<pre>";print_r($BOOK);print "</pre>";

$RESERVATION = $BOOK['RESERVATION'];
$RES_NUMBER = $RESERVATION['RES_NUMBER'];
$RES_CHECK_IN = $api->switchDate($BOOK['RES_CHECK_IN']);
$RES_CHECK_OUT = $api->switchDate($BOOK['RES_CHECK_OUT']);
$RES_COUNTRY_CODE = !empty($BOOK['RES_COUNTRY_CODE']) ? $BOOK['RES_COUNTRY_CODE'] : "US";
$ROOMS = $RESERVATION['ROOMS'];
$isOK = true;

$isRebooMod = isset($BOOK['RES_REBOOKING']) && is_array($BOOK['RES_REBOOKING']) && isset($BOOK['RES_REBOOKING']['RES_NUM']) && !empty($BOOK['RES_REBOOKING']['RES_NUM']) && isset($BOOK['RES_REBOOKING']['ROOMS']) && (int)$BOOK['RES_REBOOKING']['ROOMS'] == (int)$BOOK['RES_ROOMS_QTY'];

if ($isRebooMod && (int)$BOOK['RES_REBOOKING']['PROP_ID'] != (int)$BOOK['RES_PROP_ID']) {
  $isRebooMod = false;  // CANCEL PREVIOUS IF REBOOK IS FOR DIFFERENT PROPERTY
}

//$clsGlobal->sendEmail(array("IS_INTERNAL"=>1,"SUBJECT"=>"NAVISION START","MESSAGE"=>($isCancelled?"IS cancelled":"NO cancelled")." ".($isUpdate?"IS update":"NO update"),"FROM"=>"juan.sarria@everlivesolutions.com","TO"=>"juan.sarria@everlivesolutions.com"));
//$clsGlobal->sendEmail(array("IS_INTERNAL"=>1,"SUBJECT"=>"NAVISION $RES_NUMBER SENT","MESSAGE"=>($isRebooMod?"IS REBOOKING":"No Rebooking"),"FROM"=>"juan.sarria@everlivesolutions.com","TO"=>"juan@townsquaredigital.com"));
//mail("juan.sarria@everlivesolutions.com","1","");

$PROCESS_TYPE = "RESERVAR";

if ($isCancelled || $isUpdate || $isRebooMod) {
  $PROCESS_TYPE = $isCancelled ? "ELIMINAR" : $PROCESS_TYPE;
  $RAW_XML = $RECORD['NAVISION_RESULT'];
  $XML = str_replace(array("Ã‘","Ñ"),array("N","N"),$RAW_XML);
  $NAVISION_RESULT = simplexml_load_string($XML);
  $isOK = empty($XML) ? false : $isOK;

  /*
	ob_start();
    print "ARRAY:<BR>" . isset($RECORD) && isset($RECORD['NAVISION_RESULT']) ? $RECORD['NAVISION_RESULT']."<BR>" : "No Record<br>";
		print "RAW_XML:<br>".$RAW_XML."<BR>";
    print "XML:<br>".$XML."<BR>";
		PRINT "NAVISION_RESULT:<BR>";print_r($NAVISION_RESULT);
	$_RESULT = ob_get_clean();
	$clsGlobal->sendEmail(array("IS_INTERNAL"=>1,"SUBJECT"=>"NAVISION RESULT: $RES_NUMBER","MESSAGE"=>$_RESULT,"FROM"=>"juan.sarria@everlivesolutions.com","TO"=>"juan.sarria@everlivesolutions.com"));
  */
}

$PROP_TYPE = "EX";
$RES_PROP_ID = $BOOK['RES_PROP_ID'];
if ($RES_PROP_ID==1) {
  $HOTEL = "MXXRC";
} else if ($RES_PROP_ID==2) {
  $HOTEL = "MXXPM";
} else if ($RES_PROP_ID==3) {
  $HOTEL = "RDXPC";
} else if ($RES_PROP_ID==4) {
  $HOTEL = "MXLAH";
  $PROP_TYPE = "";
} else if ($RES_PROP_ID==5) {
  $HOTEL = "MXFPM";
  $PROP_TYPE = "";
} else if ($RES_PROP_ID==6) {
  $HOTEL = "RDXEC";
} else if ($RES_PROP_ID==7) {
  $HOTEL = "JMXOB";
}

$EXTRAS = array();
if (isset($BOOK["RES_ITEMS"]["TRANSFER"]) && isset($RESERVATION['TRANSFER_CAR']) && (int)$RESERVATION['TRANSFER_CAR']!=0 && (int)$RESERVATION['TRANSFER_FEE']!=0) {
      $CAR_ID = $RESERVATION['TRANSFER_CAR'];
      $EXTRAS = array(
        "COD_EXTRA" => "TRF" . ($RESERVATION['TRANSFER_TYPE']=="ROUNDT"?"RT":"OW") . $BOOK["RES_ITEMS"]["TRANSFER"][$CAR_ID]["TYPE"],
        "TIPO_EXTRA" => "SERVICE",
        "CANTIDAD_EXTRA" => "1",
        "PRECIO_EXTRA" => $RESERVATION['TRANSFER_FEE'],
        "CARGO_UNICO" => "YES",
        "FECHA_DESDE_EXTRA" => $RES_CHECK_IN, // NEW
        "FECHA_HASTA_EXTRA" => $api->addDaysAndSwitchDate($BOOK['RES_CHECK_IN'], 1), // NEW
        "PRECIO_POR_PERSONA" => "NO",
        "PRECIO_POR_CANTIDAD" => "YES"
      );
}

$HEAR_ABOUT_US = isset($RESERVATION["HEAR_ABOUT_US"]) ? $RESERVATION["HEAR_ABOUT_US"] : "";
$COMMENTS = isset($RESERVATION["COMMENTS"]) ? $api->hyphenize($RESERVATION["COMMENTS"]) : "";
$OBSERVACIONES = array($COMMENTS,$HEAR_ABOUT_US);

//if (isset($RESERVATION["CC_COMMENTS"])) {
//  $OBSERVACIONES[] = $RESERVATION["CC_COMMENTS"];
//}

//if ($isFirstTime) {

  $HAB = array();

  foreach ($RESERVATION['RES_ROOMS_SELECTED'] as $IND => $ROOM_ID) {

      $ROOM_IND = $IND+1;
      
      $RESERVANH = "";
      if (($isCancelled || $isUpdate || $isRebooMod) && $isOK) {

        $_HAB = $NAVISION_RESULT->RESERVA->HAB_LIST->HAB;
        $_HAB = isset($_HAB[$IND]) ? $_HAB[$IND] : $_HAB; // Analize what to do in case or rebooking more habs than previous res
        $RESERVANH = $_HAB->ANO."#".$_HAB->RESERVA_NH."#".$_HAB->DESGLOSE;
        //$RESERVANH .= $isRebooMod ? "#REBOOKING" : "";
        //ob_start();print_r($_HAB);print "RESERVANH: ".$RESERVANH;$DEBUG = ob_get_clean();$clsGlobal->sendEmail(array("IS_INTERNAL"=>1,"SUBJECT"=>"NAVISION $RES_NUMBER DEBUG","MESSAGE"=>$DEBUG,"FROM"=>"juan.sarria@everlivesolutions.com","TO"=>"juan@townsquaredigital.com"));

      } else {
        //$clsGlobal->sendEmail(array("IS_INTERNAL"=>1,"SUBJECT"=>"NAVISION $RES_NUMBER NO REBOOKING","MESSAGE"=>"NO REBOOKING:: ".($isOK?"isOK":"NO OK"),"FROM"=>"juan.sarria@everlivesolutions.com","TO"=>"juan@townsquaredigital.com"));
      }

      $PRECIOS = array();
      $OCUPANTES = array();
      $PROMOCION = array();

      $ROOM = $BOOK["RES_ROOM_{$ROOM_IND}_ROOMS"][$ROOM_ID];

      $cnt = 0;
      foreach ($ROOM["NIGTHS"] as $NIGTH_DATE => $NIGTH_DATA) {
        if (is_array($NIGTH_DATA)) {
          $EX_GUESTS = (int)$BOOK["RES_ROOM_{$ROOM_IND}_ADULTS_QTY"]; // For reservations before the fix
          //ob_start();print_r($NIGTH_DATA);$DEBUG = ob_get_clean();mail("juan.sarria@everlivesolutions.com","NIGTH_DATA","PROP_TYPE :: " . $PROP_TYPE . " --- " . $DEBUG);
          if ($PROP_TYPE=="EX" && isset($NIGTH_DATA['CLASS']['RATE']['QTY'])) {
              $EX_GUESTS = (int)$NIGTH_DATA['CLASS']['RATE']['QTY'];
          }
          $PRECIOS["PRECIOS-".(++$cnt)] = array(
              "P_FEC" => $api->switchDate($NIGTH_DATE), 
              "P_PRE" => $NIGTH_DATA['RATE']['FINAL'] * ($PROP_TYPE=="EX" ? $EX_GUESTS : 1)
          );
          if (isset($NIGTH_DATA['CLASS']['SPECIAL'])&&$NIGTH_DATA['CLASS']['SPECIAL']!="X") {
              if (isset($NIGTH_DATA['CLASS']['SPECIAL']['ACCESS_CODE'])) {
                  $ACCESS_CODE = trim($NIGTH_DATA['CLASS']['SPECIAL']['ACCESS_CODE']);
                  if (!empty($ACCESS_CODE)) {
                    $PROMOCION["COD_PROMOCION-".$cnt] = $NIGTH_DATA['CLASS']['SPECIAL']['ACCESS_CODE'];
                  }
              }
          }
        }
      }

      $BEDTYPE_ID = $ROOMS[$IND]["GUEST_BEDTYPE"];
      $BEDTYPE = (int)$BEDTYPE_ID!=0 ? $BOOK["RES_ITEMS"]["PROPERTY"]["BED_TYPES"][$BEDTYPE_ID] : "";
      $OBS_TXT = array_merge($OBSERVACIONES, array( 
        $ROOMS[$IND]["GUEST_SMOKING"],
        $ROOMS[$IND]["GUEST_OCCASION"],
        $BEDTYPE,
      ));
      if (isset($ROOMS[$IND]["GUEST_BABYCRIB"])&&(int)$ROOMS[$IND]["GUEST_BABYCRIB"]==1) {
        $OBS_TXT[] = "Baby Crib";
      }
      if (isset($ROOMS[$IND]["GUEST_REPEATED"])) {
        $REPEATED = array();
        foreach ($ROOMS[$IND]["GUEST_REPEATED"] as $PID) {
          $REPEATED[] = $BOOK["RES_ITEMS"]["PROPERTIES"][$PID]["NAME"];
        }
        $OBS_TXT[] = "Repeated guest: ".implode(", ",$REPEATED);
      }


        $CHILDREN = isset($BOOK["RES_ROOM_{$ROOM_IND}_CHILDREN_QTY"]) ? (int)$BOOK["RES_ROOM_{$ROOM_IND}_CHILDREN_QTY"] : 0;
        $INFANTS = isset($BOOK["RES_ROOM_{$ROOM_IND}_INFANTS_QTY"]) ? (int)$BOOK["RES_ROOM_{$ROOM_IND}_INFANTS_QTY"] : 0;

        $NINOS = $CHILDREN - $INFANTS;

        $OCUPANTE = $RESERVATION["ROOMS"][$IND];
        $OCUPANTE = array(
            "TIPO" => "AD",
            "NOMBRE" => isset($OCUPANTE["GUEST_FIRSTNAME"])&&$OCUPANTE["GUEST_FIRSTNAME"]!="null"&&!empty($OCUPANTE["GUEST_FIRSTNAME"]) ? $OCUPANTE["GUEST_FIRSTNAME"] : $RESERVATION['GUEST']['FIRSTNAME'],
            "APELLIDO1" => isset($OCUPANTE["GUEST_LASTNAME"])&&$OCUPANTE["GUEST_LASTNAME"]!="null"&&!empty($OCUPANTE["GUEST_LASTNAME"]) ? $OCUPANTE["GUEST_LASTNAME"] : $RESERVATION['GUEST']['LASTNAME'],
            "APELLIDO2" => "",
            "DNI" => "",
            "EDAD" => "0"
        );

        $ADULTS_QTY = $BOOK["RES_ROOM_{$ROOM_IND}_ADULTS_QTY"];
        $QTY = 0;
        for ($AD=1; $AD<=$ADULTS_QTY; ++$AD) {
            $OCUPANTES["OCUPANTES-".(++$QTY)] = $OCUPANTE;
            $OCUPANTE["NOMBRE"] = "";
        }

        $OCUPANTE["TIPO"] = "NI";
        for ($NI=1; $NI<=$NINOS; ++$NI) {
            $OCUPANTES["OCUPANTES-".(++$QTY)] = $OCUPANTE;
        }

        $OCUPANTE["TIPO"] = "CU";
        for ($CU=1; $CU<=$INFANTS; ++$CU) {
            $OCUPANTES["OCUPANTES-".(++$QTY)] = $OCUPANTE;
        }

        $HAB['HAB-'.$ROOM_IND] = ARRAY (
          "RESERVANH" => $RESERVANH,
          "TIPO_HAB" => !empty($BOOK["RES_ITEMS"][$ROOM_ID]['CLAVE']) ? $BOOK["RES_ITEMS"][$ROOM_ID]['CLAVE'] : "JUNIORTR",
          "REG" => "AI",
          "FECHA_ENTRADA" => $RES_CHECK_IN,
          "FECHA_SALIDA" => $RES_CHECK_OUT,
          "HOR_LLEG" => "",
          "HOR_SAL" => "",
          "AD" => $ADULTS_QTY,
          "JR" => "0",
          "NI" => $NINOS,
          "BB" => $INFANTS,
          "PROMOCION_LIST" => $PROMOCION,
          "COD_TARIFA" => "",
          "DIVISA_PREC" => "USD",
          "TIPO_PREC" => "M",
          "PRECIOS_LIST" => $PRECIOS,
          "OCUPANTES_LIST" => $OCUPANTES,
          "EXTRAS_LIST" => ARRAY (
            "EXTRAS" => $EXTRAS
          ),
          "OBSERVACIONES" => ARRAY (
              "TEXTO" => implode(" - ",$OBS_TXT)
          )
        );

        $EXTRAS = array(); // clear extras for rooms but first one
  }

  $DATA = ARRAY (
    "PROCESS_TYPE" => $PROCESS_TYPE,
    "ID" => $RES_NUMBER,
    "HOTEL" => $HOTEL,
    "LOCALIZADOR" => $RES_NUMBER,
    "ORIGEN" => "WEB_XCL",
    "RESERVA" => ARRAY (
        "ID_RVA" => $RES_NUMBER,
        "FVENTA" => $api->switchDate($BOOK['RES_DATE']),
        "HOTEL" => $HOTEL,
        "FI" => $RES_CHECK_IN,
        "FF" => $RES_CHECK_OUT,
        "TTOO" => $api->get_navision_var("TTOO", $RES_PROP_ID, $RES_COUNTRY_CODE, $BOOK['RES_SRC']),
        "AGENCIA" => $api->get_navision_var("AGENCIA", $RES_PROP_ID, $RES_COUNTRY_CODE, $BOOK['RES_SRC']),
        "CLIENTE" => $api->get_navision_var("CLIENTE", $RES_PROP_ID, $RES_COUNTRY_CODE, $BOOK['RES_SRC']), // NEW
        "CANAL" => $BOOK['RES_SRC'] == "CC" ? "CALL CENTER USA" : "WEB HOTEL", // NEW
        "CATALOGO" => ARRAY (),
        "BONO" => $RES_NUMBER,
        "PAGADO" => ARRAY (),
        "DNI_PAGO" => ARRAY (),
        "NOMBRE_PAGO" => $RESERVATION['GUEST']['FIRSTNAME'],
        "AP1_PAGO" => $RESERVATION['GUEST']['LASTNAME'],
        "AP2_PAGO" => ARRAY (),
        "TELEFONO_PAGO" => $RESERVATION['GUEST']['PHONE'],
        "EMAIL_PAGO" => !empty($RESERVATION['GUEST']['EMAIL']) ? $RESERVATION['GUEST']['EMAIL'] : $RESERVATION['PAYMENT']['CC_BILL_EMAIL'],
        "DIRECCION_PAGO" => $RESERVATION['GUEST']['ADDRESS'], // NEW
        "CIUDAD_PAGO" => $RESERVATION['GUEST']['CITY'], // NEW
        "ESTADO_PAGO" => $RESERVATION['GUEST']['STATE'], // NEW
        "CP_PAGO" => $RESERVATION['GUEST']['ZIPCODE'], // NEW
        "PAIS_PAGO" => $RESERVATION['GUEST']['COUNTRY'], // NEW
        "HAB_LIST" => $HAB
    )
  );

//}

print "NAVISION:<pre>";print_r($DATA);print "</pre>";
$SENT = is_array($DATA) ? $api->XMLParser($DATA) : $DATA;
//print "<hr>SENT:<BR>".htmlentities($SENT);
print "<hr>SENT:<BR>".htmlentities($SENT);

$args = array (
	"RES_TABLE"=>$RES_TABLE,
	"ID"=>$RES_ID,
	"NAVISION_SENT"=>$SENT,
);

$subject = "";
$call_navision = true;
//$call_navision = false;

//$clsGlobal->sendEmail(array("IS_INTERNAL"=>1,"SUBJECT"=>"NAVISION - make.php","MESSAGE"=>$RES_ID,"FROM"=>"juan.sarria@everlivesolutions.com","TO"=>"juan.sarria@everlivesolutions.com"));

if ($call_navision && $isOK) {

    //mail("juan.sarria@everlivesolutions.com","$RES_NUMBER: Sent to Navision",$SENT);

    $result = $api->execute($DATA, $SENT);
    print "<br>result:<pre>";print_r($result);print "</pre>";
    $XML = $result->GetProcessResult;
    $XML = $api->removeXMLversion($XML);
    $ARR = simplexml_load_string($XML);
    //$JSON = json_encode($ARR);

    print "<pre>";print_r($ARR);print "</pre>";
	
	if (!empty($XML) && stristr($XML,"RESP_RESERVAR_ERROR")===FALSE && stristr($XML,"RESP_ANULAR_ERROR")===FALSE && stristr($XML,"<ERROR>")===FALSE) {
		$args["NAVISION_STATUS"] = "";
		$args["NAVISION_ERROR"] = "";
		if ($isCancelled) {
			$args["NAVISION_CANCEL"] = $XML;
		} else {
			$args["NAVISION_RESULT"] = $XML;
		}
	} else {
		$args["NAVISION_ERROR"] = $XML;
	}

    print htmlentities($XML);

} else {
	$subject = " NOT CALLED : ";
    print "*** $subject ***";
	$args["NAVISION_STATUS"] = "";
}

$result = $clsReserv->modifyReservation($db, $args);

$OUTPUT = ob_get_clean();

//print $OUTPUT;

$subject .= $isCron ? " CRON $NAVISION_STATUS : $RES_NUMBER" : "";
$OUTPUT .= $isCron ? " [ $SQLSTR ] " : "";
$OUTPUT .= "<hr>".$api->SOAPClient;

$clsGlobal->sendEmail(array("IS_INTERNAL"=>1,"SUBJECT"=>"NAVISION".$subject,"MESSAGE"=>$OUTPUT,"FROM"=>"jaunsarria@gmail.com","TO"=>"nisenbaummirek@gmail.com",'IS_INTERNAL'=>1));
//$clsGlobal->sendEmail(array("IS_INTERNAL"=>1,"SUBJECT"=>"NAVISION".$subject,"MESSAGE"=>$OUTPUT,"FROM"=>"juan.sarria@everlivesolutions.com","TO"=>"nisenbaummirek@gmail.com,juan.sarria@everlivesolutions.com",'IS_INTERNAL'=>1));
