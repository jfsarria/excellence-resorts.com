<?

$isConfirmationPage = true;

extract($_SESSION['AVAILABILITY']);

function printit($STR) {
    print str_replace(array("\n","\r\n"),array("<br>","<br>"),$STR);
}
?>

<style>
.ui-collapsible-box {
    padding-top: 0px;
}
</style>

<?
    $RES_ITEMS = $_SESSION['AVAILABILITY']['RES_ITEMS'];
?>
<div data-role="header" data-theme="x">
    <h1><? print _l("Reservation Confirmation","Número de confirmación",$RES_LANGUAGE) ?></h1>
</div>

<div data-role="content">	
    <b><? print _l("Number: ","Número: ",$RES_LANGUAGE)." ".$RESERVATION['RES_NUMBER'] ?></b>

    <div data-role="collapsible" data-collapsed="false" data-theme="c" data-content-theme="c">
        <h3><? print _l("Reservation Information","Información de la Reservación",$RES_LANGUAGE) ?></h3>
        <div class="ui-collapsible-box">
            Hotel: 
            <? 
                $CHILDREN = (isset($RES_ROOMS_CHILDREN_QTY) && (int)$RES_ROOMS_CHILDREN_QTY!=0) ? (int)$RES_ROOMS_CHILDREN_QTY : 0;
                $INFANTS = (isset($RES_ROOMS_INFANTS_QTY) && (int)$RES_ROOMS_INFANTS_QTY!=0) ? (int)$RES_ROOMS_INFANTS_QTY : 0;

                $OUTOUT = "";

                print $RES_ITEMS['PROPERTY']['NAME']."<br>".
                _l("Booking Date","Fecha de reserva",$RES_LANGUAGE).": "._fecha(date("l, F j, Y", strtotime($RES_DATE)),$RES_LANGUAGE)."<br>".
                _l("Number of Rooms","Número de Habitaciones",$RES_LANGUAGE).": {$RES_ROOMS_QTY}<br>".
                _l("Number of Adults","Número de Adultos",$RES_LANGUAGE).": {$RES_ROOMS_ADULTS_QTY}<br>";
            
                if ($CHILDREN!=0 || $INFANTS!=0) {
                    if ($CHILDREN!=0) print _l("Number of Children","Número de Niños",$RES_LANGUAGE).": ".($CHILDREN - $INFANTS)."<br>\n";
                    if ($INFANTS!=0) print _l("Number of Infants","Número de Bebes",$RES_LANGUAGE).": ".$INFANTS."<br>\n";
                }

                print "<br>".
                _l("Check In","Llegada",$RES_LANGUAGE).": "._fecha(date("F j, Y", strtotime($RES_CHECK_IN)),$RES_LANGUAGE)."<br>".
                _l("Check Out","Salida",$RES_LANGUAGE).": "._fecha(date("F j, Y", strtotime($RES_CHECK_OUT)),$RES_LANGUAGE)."<br>".
                _l("Total Stay","Total de la estancia",$RES_LANGUAGE).": {$RES_NIGHTS} "._l("night","noche",$RES_LANGUAGE)."".($RES_NIGHTS!=1?"s":"")."<br>";

                $iRooms = count($RESERVATION['ROOMS']);
                $_SESSION['AVAILABILITY']['TMP']['ROOMS'][0]['TXT'] = "";
                
                foreach ($RESERVATION['RES_ROOMS_SELECTED'] as $ind => $ROOM_ID) { 
                    $GUESTS_QTY = (int)$_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_GUESTS_QTY"];
                    $ADULTS = (int)$_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_ADULTS_QTY"];
                    $CHILDREN = isset($_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_CHILDREN_QTY"]) ? (int)$_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_CHILDREN_QTY"] : 0;
                    $INFANTS = isset($_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_INFANTS_QTY"]) ? (int)$_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_INFANTS_QTY"] : 0;
                    $_SESSION['AVAILABILITY']['TMP']['ROOMS'][$ind]['TXT'] = "<br><b>Room ".($ind+1).":</b><br>".$_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_ROOMS"][$ROOM_ID]["NAME"].",<br>";
                    if ($CHILDREN!=0 || $INFANTS!=0) {
                        $_SESSION['AVAILABILITY']['TMP']['ROOMS'][$ind]['TXT'] .= $ADULTS." "._l("Adult","Adulto",$RES_LANGUAGE).($ADULTS==1?"":"s").",<br>";
                        if ($CHILDREN - $INFANTS!=0) $_SESSION['AVAILABILITY']['TMP']['ROOMS'][$ind]['TXT'] .= ($CHILDREN - $INFANTS).(($CHILDREN - $INFANTS == 1)?" "._l("Child","Niño",$RES_LANGUAGE):" "._l("Children","Niños",$RES_LANGUAGE)).",<br>";
                        if ($INFANTS!=0) $_SESSION['AVAILABILITY']['TMP']['ROOMS'][$ind]['TXT'] .= $INFANTS." "._l("Infant","Bebé",$RES_LANGUAGE).($INFANTS==1?"":"s").",<br>";
                    } else {
                        $_SESSION['AVAILABILITY']['TMP']['ROOMS'][$ind]['TXT'] .= $GUESTS_QTY." "._l("Guest","Huesped",$RES_LANGUAGE).($GUESTS_QTY==1?"":_l("s","es",$RES_LANGUAGE)).",<br>";
                    }
                    if ($iRooms>1) {
                        $OUTOUT .= $_SESSION['AVAILABILITY']['TMP']['ROOMS'][$ind]['TXT'];
                        $OUTOUT .= "USD $".number_format($RESERVATION['RES_ROOM_CHARGE'][$ind])."<br>";
                    }
                    $_SESSION['AVAILABILITY']['TMP']['ROOMS'][$ind]['TXT'] = strip_tags($_SESSION['AVAILABILITY']['TMP']['ROOMS'][$ind]['TXT']);
                }

                $OUTOUT .= "
                    <b>"._l("Total cost","Costo total",$RES_LANGUAGE).": USD $".number_format($RESERVATION['RES_TOTAL_CHARGE'])."</b>
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

                $OUTOUT .= implode(", ",$CLASS_NAMES).(count($SPECIAL_NAMES)!=0?", ".implode(", ",$SPECIAL_NAMES):"");

                print printit($OUTOUT);
            ?>
        </div>
    </div>

    <div data-role="collapsible" data-collapsed="true" data-theme="c" data-content-theme="c">
        <h3><? print _l("Optional Preferences","Preferencias",$RES_LANGUAGE) ?></h3>
        <div class="ui-collapsible-box">
            <?
                $OUTOUT = "";

                $ROOM_NUM = 1;
                foreach ($RESERVATION['ROOMS'] as $ind => $PROOM) {
                    $ROOM_ID = $RESERVATION['RES_ROOMS_SELECTED'][$ind];
                    $ROOM = $_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_ROOMS"][$ROOM_ID];
                    if ($iRooms>1) {
                        $OUTOUT .= _l("Room Type","Habitación",$RES_LANGUAGE).": {$ROOM['NAME']} <br>";
                    }
                    $OUTOUT .= ""._l("Bed preference","Tipo de cama",$RES_LANGUAGE).": ".((isset($PROOM['GUEST_BEDTYPE'])&&$PROOM['GUEST_BEDTYPE']!=""&&isset($RES_ITEMS['PROPERTY']['BED_TYPES'])&&isset($RES_ITEMS['PROPERTY']['BED_TYPES'][$PROOM['GUEST_BEDTYPE']]))?_pref($RES_ITEMS['PROPERTY']['BED_TYPES'][$PROOM['GUEST_BEDTYPE']],$RES_LANGUAGE):_l("No preference","Sin preferencias",$RES_LANGUAGE))."";
                    if (isset($PROOM['GUEST_BABYCRIB']) && $PROOM['GUEST_BABYCRIB']!="") $OUTOUT .= "\n"._l("Baby Crib","Cuna para bebé",$RES_LANGUAGE).": "._l("Yes","Si",$RES_LANGUAGE);
                    $OUTOUT .= "
                        "._l("Smoking Preference","Habitación",$RES_LANGUAGE).": ".((isset($PROOM['GUEST_SMOKING'])&&$PROOM['GUEST_SMOKING']!="")?_pref($PROOM['GUEST_SMOKING'],$RES_LANGUAGE):_l("No preference","Sin preferencias",$RES_LANGUAGE))."
                        "._l("Special Occasion","Ocasión Especial",$RES_LANGUAGE).": ".((isset($PROOM['GUEST_SMOKING'])&&$PROOM['GUEST_OCCASION']!="")?_pref($PROOM['GUEST_OCCASION'],$RES_LANGUAGE):"No")."<br>
                    ";
                    ++$ROOM_NUM;
                } 

                print printit($OUTOUT);
            ?>
        </div>
    </div>

    <div data-role="collapsible" data-collapsed="true" data-theme="c" data-content-theme="c">
        <h3><? print _l("Guest Information","Información sobre el huésped",$RES_LANGUAGE) ?></h3>
        <div class="ui-collapsible-box">
            <?
                $GUEST = $_SESSION['AVAILABILITY']['RESERVATION']['GUEST'];
                $OUTOUT = _title($GUEST['TITLE'], $RES_LANGUAGE)." {$GUEST['FIRSTNAME']} {$GUEST['LASTNAME']}
                    "._l("Email","Correo Electrónico",$RES_LANGUAGE).": {$GUEST['EMAIL']}
                    "._l("Phone","Teléfono",$RES_LANGUAGE).": {$GUEST['PHONE']}
                    {$GUEST['ADDRESS']}
                    ".appendToString($GUEST['CITY'],", ").appendToString($GUEST['STATE']," ").appendToString($GUEST['ZIPCODE'],", ").$GUEST['COUNTRY']."
                ";

                print printit($OUTOUT);
            ?>
        </div>
    </div>

    <div data-role="collapsible" data-collapsed="true" data-theme="c" data-content-theme="c">
        <h3><? print _l("Hotel Information","Información sobre el hotel",$RES_LANGUAGE) ?></h3>
        <div class="ui-collapsible-box">
            <?
                $OUTOUT = "{$RES_ITEMS['PROPERTY']['NAME']}<br>{$RES_ITEMS['PROPERTY']['INFO_'.$RES_LANGUAGE]}";

                print printit($OUTOUT);
            ?>
        </div>
    </div>



</div>
