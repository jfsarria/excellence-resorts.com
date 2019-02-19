<?
/*
 * Revised: Jan 14, 2013
 *          Dec 22, 2016
 */

//$HAS_TRANSFER = isset($RESERVATION['TRANSFER_FEE']) && (int)$RESERVATION['TRANSFER_FEE']!=0 && isset($RESERVATION['TRANSFER_CAR']) && (int)$RESERVATION['TRANSFER_CAR']!=0;

$TOTAL_CHARGE = $RESERVATION['RES_TOTAL_CHARGE'];
$HAS_TRANSFER = isset($RESERVATION['TRANSFER_CAR']) && (int)$RESERVATION['TRANSFER_CAR']!=0;

$OUTOUT = ((int)$RES_PROP_ID==4) ? "<div style='font:normal 12px Arial;color:#333333;'>" : "<div style='font:normal 12px Georgia;color:#000000;'>";

if (!$isCancelled) {
    $OUTOUT .= "
        <div style='font-size:16px'><b>".($isRebooking ? "Re-booking " : "").""._l("Reservation Confirmation Number","Número de confirmación",$RES_LANGUAGE).": {$RES_NUMBER}</b></div>
    ";
}

$OUTOUT .= "
$DEAR
";

if (!$isCancelled) {
    if ($isPreStay) {
        $EMAIL_PRESTAY = html_entity_decode($SETUP['EMAIL_PRESTAY_'.$RES_LANGUAGE]);
        $OUTOUT .= "\n".$EMAIL_PRESTAY."\n";
    } else {
        $OUTOUT .= "
            "._l("Thank you for choosing","Gracias por su preferencia",$RES_LANGUAGE)." {$STYLED_PROP_NAME}
        ";
    }
}

if ($isCancelled) {
    $EMAIL_CAN = html_entity_decode($SETUP['EMAIL_CAN_'.$RES_LANGUAGE]);
    $OUTOUT .= "\n".$EMAIL_CAN."\n";
} else if ($isRebooking) {
    $EMAIL_REB = html_entity_decode($SETUP['EMAIL_REB_'.$RES_LANGUAGE]);
    $OUTOUT .= "\n".$EMAIL_REB."\n";
} 

if (!$isCancelled && !$isPreStay) {
    $EMAIL_HDR = "\n".html_entity_decode($SETUP['EMAIL_HDR_'.$RES_LANGUAGE]);
    $OUTOUT .= $EMAIL_HDR;
}

if (!$isCancelled) {
    $OUTOUT .= "
        "._l("Sincerely","Atentamente",$RES_LANGUAGE).",
        {$RES_ITEMS['PROPERTY']['NAME']}
    ";
}

$OUTOUT .= "
    <hr>
    <b>"._l("Reservation Information","Información de la Reservación",$RES_LANGUAGE).":</b>

    Hotel: ".adjustPropertyName($RES_ITEMS['PROPERTY']['NAME'], $RES_ITEMS['PROPERTY']['ID'])."
    "._l("Booking Date","Fecha de reserva",$RES_LANGUAGE).": "._fecha(date("l, F j, Y", strtotime($RES_DATE)),$RES_LANGUAGE)."
    "._l("Number of Rooms","Número de habitaciones",$RES_LANGUAGE).": {$RES_ROOMS_QTY}
    "._l("Number of Adults","Número de Adultos",$RES_LANGUAGE).": {$RES_ROOMS_ADULTS_QTY}
";

$iRooms = count($RESERVATION['ROOMS']);

$CHILDREN = (isset($RES_ROOMS_CHILDREN_QTY) && (int)$RES_ROOMS_CHILDREN_QTY!=0) ? (int)$RES_ROOMS_CHILDREN_QTY : 0;
$INFANTS = (isset($RES_ROOMS_INFANTS_QTY) && (int)$RES_ROOMS_INFANTS_QTY!=0) ? (int)$RES_ROOMS_INFANTS_QTY : 0;
$KIDS_QTY = max(0, $CHILDREN - $INFANTS);

if ($CHILDREN!=0 || $INFANTS!=0) {
    if ($CHILDREN!=0) {
      $OUTOUT .= _l("Number of Children","Número de Niños",$RES_LANGUAGE).": ".$KIDS_QTY;
      if ($iRooms==1) {
        $CHILD_AGES = array();
        for ($CHILD=1; $CHILD<=$CHILDREN; ++$CHILD) {
          $AGE = (int)$_SESSION['AVAILABILITY']["RES_ROOM_1_CHILD_AGE_".$CHILD];
          if ($AGE > 3) { $CHILD_AGES[] = $AGE; }
        }
        $age_txt = _l("age","edad",$RES_LANGUAGE).($KIDS_QTY!=1?_l("s","es",$RES_LANGUAGE):"");
        if (count($CHILD_AGES)!=0) $OUTOUT .= " ($age_txt ".implode(", ",$CHILD_AGES).")";
      }
      $OUTOUT .= "\n";
    }
    if ($INFANTS!=0) $OUTOUT .= _l("Number of Infants","Número de Bebes",$RES_LANGUAGE)." (age 0 to 3yrs): ".$INFANTS."\n";
}

//    <!-- Number of Infants: 0 -->

$OUTOUT .= "
    "._l("Check In","Entrada",$RES_LANGUAGE).": "._fecha(date("F j, Y", strtotime($RES_CHECK_IN)),$RES_LANGUAGE)."
    "._l("Check Out","Salida",$RES_LANGUAGE).": "._fecha(date("F j, Y", strtotime($RES_CHECK_OUT)),$RES_LANGUAGE)."
    "._l("Total Stay","Total de la estancia",$RES_LANGUAGE).": {$RES_NIGHTS} "._l("night","noche",$RES_LANGUAGE)."".($RES_NIGHTS!=1?"s":"")."

";

if ($iRooms>1) {
    foreach ($RESERVATION['RES_ROOMS_SELECTED'] as $ind => $ROOM_ID) { 
        $GUESTS_QTY = (int)$_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_GUESTS_QTY"];
        $ADULTS = (int)$_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_ADULTS_QTY"];
        $CHILDREN = isset($_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_CHILDREN_QTY"]) ? (int)$_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_CHILDREN_QTY"] : 0;
        $INFANTS = isset($_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_INFANTS_QTY"]) ? (int)$_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_INFANTS_QTY"] : 0;
        $KIDS_QTY = max(0, $CHILDREN - $INFANTS);
        $OUTOUT .= "Room ".($ind+1).", ".$_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_ROOMS"][$ROOM_ID]["NAME"].", ";
        if ($CHILDREN!=0 || $INFANTS!=0) {
            $OUTOUT .= $ADULTS." Adult".($ADULTS==1?"":"s").", ";
            if ($KIDS_QTY!=0) {
              $CHILD_AGES = array();
              for ($CHILD=1; $CHILD<=$KIDS_QTY; ++$CHILD) $CHILD_AGES[] = (int)$_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_CHILD_AGE_".$CHILD];
              $age_txt = _l("age","edad",$RES_LANGUAGE).($KIDS_QTY!=1?_l("s","es",$RES_LANGUAGE):"");
              $AGES = (count($CHILD_AGES)!=0) ? " ($age_txt ".implode(", ",$CHILD_AGES).")" : "";
              $OUTOUT .= $KIDS_QTY." ".(($KIDS_QTY == 1)?" Child":" Children").$AGES.", ";
            }
            if ($INFANTS!=0) $OUTOUT .= $INFANTS." Infant".($INFANTS==1?"":"s").", ";
        } else {
            $OUTOUT .= $GUESTS_QTY." Guest".($GUESTS_QTY==1?"":"s").", ";
        }
        $OUTOUT .= "USD $".number_format($RESERVATION['RES_ROOM_CHARGE'][$ind])."<br>";
    }
}

$OUTOUT .= "
    <b>"._l("Total cost","Costo total",$RES_LANGUAGE).": USD $".number_format($TOTAL_CHARGE)."</b>
";

$CLASS_NAMES = array();
$SPECIAL_NAMES = array();
foreach ($RESERVATION['RES_ROOMS_SELECTED'] as $ind => $ROOM_ID) { 
    $ROOM = $_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_ROOMS"][$ROOM_ID];

    //if (is_array($ROOM["CLASS_NAMES"])) $CLASS_NAMES = array_merge_recursive($CLASS_NAMES,$ROOM["CLASS_NAMES"]);
    //if (is_array($ROOM["SPECIAL_NAMES"])) $SPECIAL_NAMES = array_merge_recursive($SPECIAL_NAMES,$ROOM["SPECIAL_NAMES"]);

    if (is_array($ROOM["CLASS_NAMES"])) {
        foreach ($ROOM["CLASS_NAMES"] as $KEY=>$REF) if (isset($RES_ITEMS[$KEY]['NAME_'.$RES_LANGUAGE])) array_push($CLASS_NAMES, $RES_ITEMS[$KEY]['NAME_'.$RES_LANGUAGE]);
    }
    if (is_array($ROOM["SPECIAL_NAMES"])) {
        foreach ($ROOM["SPECIAL_NAMES"] as $KEY=>$REF) if (isset($RES_ITEMS[$KEY]['NAME_'.$RES_LANGUAGE]))  array_push($SPECIAL_NAMES, $RES_ITEMS[$KEY]['NAME_'.$RES_LANGUAGE]);
    }
}
$CLASS_NAMES = array_unique($CLASS_NAMES);
$SPECIAL_NAMES = array_unique($SPECIAL_NAMES);

$OUTOUT .= "
    ".implode(", ",$CLASS_NAMES).(count($SPECIAL_NAMES)!=0?", ".implode(", ",$SPECIAL_NAMES):"")."
";


IF ($HAS_TRANSFER) {
  $ROUNDT = $RES_LANGUAGE=='EN' ? "Round Trip" : "Viaje Redondo";
  $ONEWAY = $RES_LANGUAGE=='EN' ? "One Way" : "De ida al hotel";
  $TOTAL_CHARGE += $RESERVATION['TRANSFER_FEE'];
  $OUTOUT .= "
      <b>"._l("Transfer","Trasportación",$RES_LANGUAGE).": ".($RESERVATION['TRANSFER_TYPE']=="ROUNDT"?$ROUNDT:$ONEWAY)."</b>
      "._l("Transfer Fee","Costo del transporte",$RES_LANGUAGE).": USD $".number_format($RESERVATION['TRANSFER_FEE'])."

      <b>"._l("TOTAL CHARGE","CARGO TOTAL",$RES_LANGUAGE).": USD $".number_format($TOTAL_CHARGE)."</b>
  ";
}

if (isset($RESERVATION['CURRENCY_CODE'])&&!empty($RESERVATION['CURRENCY_CODE'])&&$RESERVATION['CURRENCY_CODE']!="USDUSD") {
    $CURRENCY_SYMBOL = array("USDUSD"=>"$","USDAUD"=>"$","USDBRL"=>"R$","USDCAD"=>"$","USDEUR"=>"€","USDGBP"=>"£","USDMXN"=>"$");
    $TOTAL_CONVERSION = ceil($TOTAL_CHARGE * (double)$RESERVATION['CURRENCY_QUOTE']);
    /*
    $OUTOUT .= "
      <b>"._l("Equivalent cost at the time of reservation","Costo equivalente en el momento de la reserva",$RES_LANGUAGE).": (".str_replace("USD","",$RESERVATION['CURRENCY_CODE']).") ".$CURRENCY_SYMBOL[$RESERVATION['CURRENCY_CODE']]." ".number_format($TOTAL_CONVERSION)."</b>
    ";
    */
}

$OUTOUT .= "
    <hr>
    <b>"._l("Optional Preferences","Preferencias",$RES_LANGUAGE).":</b>
";

$ROOM_NUM = 1;
foreach ($RESERVATION['ROOMS'] as $ind => $PROOM) {
    $ROOM_ID = $RESERVATION['RES_ROOMS_SELECTED'][$ind];
    $ROOM = $_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_ROOMS"][$ROOM_ID];
    if ($iRooms>1) {
        $OUTOUT .= "
            "._l("Room Type","Habitación",$RES_LANGUAGE).": {$ROOM['NAME']}
            "._l("Guest","Huesped",$RES_LANGUAGE).": {$PROOM['GUEST_FIRSTNAME']} {$PROOM['GUEST_LASTNAME']}
        ";
    }
    if (isset($PROOM['GUEST_REPEATED'])) {
        $OUTOUT .= ""._l("Repeat Guest","Anteriormente Hospedado",$RES_LANGUAGE).": ";
        $HOTELS = array();
        foreach ($PROOM['GUEST_REPEATED'] as $HID) {
            if (isset($RES_ITEMS['PROPERTIES'][$HID])) array_push($HOTELS, $RES_ITEMS['PROPERTIES'][$HID]['NAME']);
        }
        $OUTOUT .= implode(", ",$HOTELS)."\n";
    }
    $OUTOUT .= ""._l("Bed preference","Tipo de cama",$RES_LANGUAGE).": ".((isset($PROOM['GUEST_BEDTYPE'])&&$PROOM['GUEST_BEDTYPE']!=""&&isset($RES_ITEMS['PROPERTY']['BED_TYPES'])&&isset($RES_ITEMS['PROPERTY']['BED_TYPES'][$PROOM['GUEST_BEDTYPE']]))?_pref($RES_ITEMS['PROPERTY']['BED_TYPES'][$PROOM['GUEST_BEDTYPE']],$RES_LANGUAGE):_l("No preference","Sin preferencias",$RES_LANGUAGE))."";
    if (isset($PROOM['GUEST_BABYCRIB']) && (int)$PROOM['GUEST_BABYCRIB']==1) $OUTOUT .= "\n"._l("Baby Crib","Cuna para bebé",$RES_LANGUAGE).": "._l("Yes","Si",$RES_LANGUAGE);
    $OUTOUT .= "
        "._l("Smoking Preference","Habitación",$RES_LANGUAGE).": ".((isset($PROOM['GUEST_SMOKING'])&&$PROOM['GUEST_SMOKING']!="")?_pref($PROOM['GUEST_SMOKING'],$RES_LANGUAGE):_l("No preference","Sin preferencias",$RES_LANGUAGE))."
        "._l("Special Occasion","Ocasión Especial",$RES_LANGUAGE).": ".((isset($PROOM['GUEST_SMOKING'])&&$PROOM['GUEST_OCCASION']!="")?_pref($PROOM['GUEST_OCCASION'],$RES_LANGUAGE):"No")."
    ";
    ++$ROOM_NUM;
} 


$EMAIL_ARR = html_entity_decode($SETUP['EMAIL_ARR_'.$RES_LANGUAGE]);
$OUTOUT .= "\n".$EMAIL_ARR."\n";

if (!$HAS_TRANSFER && ($RESERVATION['AIRLINE']!="" || $RESERVATION['FLIGHT']!="")) {
    $EMAIL_AIR = html_entity_decode($SETUP['EMAIL_AIR_'.$RES_LANGUAGE]);
    $OUTOUT .= "\n".$EMAIL_AIR."\n";
}

$OUTOUT .= "
    <b>"._l("COMMENTS/SPECIAL REQUESTS","COMENTARIOS / SOLICITUDES ESPECIALES",$RES_LANGUAGE)."</b>
    ".urldecode($RESERVATION['COMMENTS'])."
    <hr>
";

$OUTOUT .= "<b>"._l("Room".($iRooms!=1?"s":"")." information","Información sobre la".($iRooms!=1?"s":"")." habitaci".($iRooms!=1?"ones":"ón").":",$RES_LANGUAGE).":</b>";

$ind = 1;
foreach ($RESERVATION['RES_ROOMS_SELECTED_NAMES'] as $ROOM_ID => $ROOM_NAME) {
    if (isset($RES_ITEMS[$ROOM_ID])) {
        $OUTOUT .= "
            ".$RES_ITEMS[$ROOM_ID]["NAME_".$RES_LANGUAGE]."

            ".$RES_ITEMS[$ROOM_ID]["DESCR_".$RES_LANGUAGE]."

            ".$RES_ITEMS[$ROOM_ID]["INCLU_".$RES_LANGUAGE]."
        ";
    }
    ++$ind;
}

$OUTOUT .= "
    <hr>
    <b>"._l("Guest Information","Información sobre el huésped",$RES_LANGUAGE).":</b>
    "._title($GUEST['TITLE'], $RES_LANGUAGE)." {$GUEST['FIRSTNAME']} {$GUEST['LASTNAME']}
    "._l("Email","Correo Electrónico",$RES_LANGUAGE).": {$GUEST['EMAIL']}
    "._l("Phone","Teléfono",$RES_LANGUAGE).": {$GUEST['PHONE']}
    {$GUEST['ADDRESS']}
    ".appendToString($GUEST['CITY'],", ").appendToString($GUEST['STATE']," ").appendToString($GUEST['ZIPCODE'],", ").$GUEST['COUNTRY']."
";

$INFO = $SETUP['INFO_'.$RES_LANGUAGE];

$OUTOUT .= "
    <hr>
    <b>"._l("Hotel Information","Información sobre el hotel",$RES_LANGUAGE).":</b>
    {$RES_ITEMS['PROPERTY']['NAME']}

    {$INFO}
    <hr>
";

if ($RESERVATION['RES_GUESTMETHOD']=="CC") {
    $OUTOUT .= "
        <b>"._l("Payment information","Información de pago",$RES_LANGUAGE).":</b>
        "._l("Card Type","Tipo de tarjeta de crédito",$RES_LANGUAGE).": {$RESERVATION['PAYMENT']['CC_TYPE']}
        "._l("Card Number","Número de tarjeta",$RES_LANGUAGE).": [CC_NUMBER]
        "._l("Expiration Date","Fecha de vencimiento",$RES_LANGUAGE).": {$RESERVATION['PAYMENT']['CC_EXP']}
        "._l("Card Holder","Titular de la Tarjeta",$RES_LANGUAGE).": {$RESERVATION['PAYMENT']['CC_NAME']}
    ";
    $EMAIL_CCDETAILS = html_entity_decode($SETUP['EMAIL_CCDETAILS_'.$RES_LANGUAGE]);
    $OUTOUT .= "\n".$EMAIL_CCDETAILS."\n<hr>";
} else {
    $OUTOUT .= "
        <b>"._l("Payment information","Información de pago",$RES_LANGUAGE).":</b>
        "._l("WIRE","Transferencia Electrónica",$RES_LANGUAGE)."
        <hr>
    ";
}

$EMAIL_RES = html_entity_decode($SETUP['EMAIL_RES_'.$RES_LANGUAGE]);
$OUTOUT .= $EMAIL_RES."\n";

if ($isTA && count($TA)>0) {
    $OUTOUT .= "
        <hr>
        <b>"._l("Travel Agent Information","Información del Agente de Viajes",$RES_LANGUAGE).":</b>

        {$TA['AGENCY_NAME']}

        {$TA['FIRSTNAME']} {$TA['LASTNAME']}
        "._l("Email","Correo Electrónico",$RES_LANGUAGE).": {$TA['EMAIL']}
        "._l("Phone","Teléfono",$RES_LANGUAGE).": {$TA['AGENCY_PHONE']}
        {$TA['AGENCY_ADDRESS']}
        {$TA['AGENCY_CITY']}, {$TA['AGENCY_STATE']} {$TA['AGENCY_ZIPCODE']}
        {$TA['AGENCY_COUNTRY']}
    ";
}

$OUTOUT .= "</div>";

$OUTOUT = str_replace(array("\n","\r\n"),array("<br>","<br>"),$OUTOUT);

$OUTOUT = str_replace(array("Á","á","É","é","Í","í","Ñ","ñ","Ó","ó","Ú","ú","Ü","ü"),array("&#193;","&#225;","&#201;","&#233;","&#205;","&#237;","&#209;","&#241;","&#211;","&#243;","&#218;","&#250;","&#220;","&#252;"),$OUTOUT);

print $OUTOUT;

?>

