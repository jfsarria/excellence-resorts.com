<?
$ROOM = 1;
$RES_TOTAL_CHARGE = 0;
$_SESSION['AVAILABILITY']['RESERVATION']['RES_ROOM_CHARGE'] = array();
foreach ($_SESSION['AVAILABILITY']['RESERVATION']['RES_ROOMS_SELECTED'] as $IND=>$ROOM_ID) {
    $RES_ROOM_CHARGE = (int)$_SESSION['AVAILABILITY']["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['TOTAL']['FINAL'];
    $RES_TOTAL_CHARGE += $RES_ROOM_CHARGE;
    array_push($_SESSION['AVAILABILITY']['RESERVATION']['RES_ROOM_CHARGE'],$RES_ROOM_CHARGE);
    ++$ROOM;
}
$_SESSION['AVAILABILITY']['RESERVATION']['RES_TOTAL_CHARGE'] = $RES_TOTAL_CHARGE;

$TYPES = array_flip($_SESSION['AVAILABILITY']['RESERVATION']['RES_ROOMS_SELECTED']);
foreach ($TYPES as $ROOM_ID=>$IND) {
    $TYPES[$ROOM_ID] = $_SESSION['AVAILABILITY']['RES_ITEMS'][$ROOM_ID]['NAME_'.$_SESSION['AVAILABILITY']['RES_LANGUAGE']];
}
$_SESSION['AVAILABILITY']['RESERVATION']['RES_ROOMS_SELECTED_NAMES'] = $TYPES;

extract($_SESSION['AVAILABILITY']);

?>

<div data-role="header" data-theme="x">
    <h1><? print _l("Reservation Summary","Informaci�n de Reserva",$RES_LANGUAGE) ?></h1>
    <a href="#" data-rel="back" data-direction="reverse" data-role="button" data-icon="back" data-iconpos="notext"></a>
</div>


<div data-role="content">	

    <? 
        $data_collapsed = "false";
        include "reservation.summary.top.php" 
    ?>

    <div id="room_summary" class="ui-collapsible-content ui-body-c ui-corner-all ui-collapsible-box">
        <?
        $cnt = 1;
        foreach ($RESERVATION['RES_ROOMS_SELECTED'] as $ROOM_NUM => $ROOM_ID) {
            $ROOM_NAME = $RESERVATION['RES_ROOMS_SELECTED_NAMES'][$ROOM_ID];
            $ROOM_TOTAL = (int)$RESERVATION['RES_ROOM_CHARGE'][$ROOM_NUM];
            $ADULTS = (isset($_SESSION['AVAILABILITY']["RES_ROOM_".($ROOM_NUM+1)."_ADULTS_QTY"]) && (int)$_SESSION['AVAILABILITY']["RES_ROOM_".($ROOM_NUM+1)."_ADULTS_QTY"]!=0) ? (int)$_SESSION['AVAILABILITY']["RES_ROOM_".($ROOM_NUM+1)."_ADULTS_QTY"] : 0;
            $CHILDREN = (isset($_SESSION['AVAILABILITY']["RES_ROOM_".($ROOM_NUM+1)."_CHILDREN_QTY"]) && (int)$_SESSION['AVAILABILITY']["RES_ROOM_".($ROOM_NUM+1)."_CHILDREN_QTY"]!=0) ? (int)$_SESSION['AVAILABILITY']["RES_ROOM_".($ROOM_NUM+1)."_CHILDREN_QTY"] : 0;
            $INFANTS = (isset($_SESSION['AVAILABILITY']["RES_ROOM_".($ROOM_NUM+1)."_INFANTS_QTY"]) && (int)$_SESSION['AVAILABILITY']["RES_ROOM_".($ROOM_NUM+1)."_INFANTS_QTY"]!=0) ? (int)$_SESSION['AVAILABILITY']["RES_ROOM_".($ROOM_NUM+1)."_INFANTS_QTY"] : 0;
            print "
                <div class='room divider'>
                    <div class='qty'>"._l("Room","Habitaci�n",$RES_LANGUAGE)." ".($ROOM_NUM+1).", ".$ADULTS." ".((int)$RES_PROP_ID==4 ? _l("Adult","Adulto",$RES_LANGUAGE) : _l("Guest","Huesped",$RES_LANGUAGE)).((int)$ADULTS!=1 ? _l("s",(int)$RES_PROP_ID==4 ? "s":"es",$RES_LANGUAGE):"");

                    if ($CHILDREN!=0 || $INFANTS!=0) {
                        if ($CHILDREN!=0) print ", ".($CHILDREN - $INFANTS)." "._l("Child","Ni�o",$RES_LANGUAGE).(($CHILDREN - $INFANTS)!=1 ? _l("ren","s",$RES_LANGUAGE) : "");
                        if ($INFANTS!=0) print ", ".$INFANTS." "._l("Infant","Beb�",$RES_LANGUAGE);
                    } 

                    print "
                    </div>
                    <div class='name'>{$ROOM_NAME}</div>
                    <div class='price'>$".(number_format($ROOM_TOTAL))." (USD)</div>
                </div>
            ";
        }
        ?>
        <div class='room'>
            <div class='price'><? print _l("TOTAL COST","COSTO TOTAL",$RES_LANGUAGE) ?> (USD): $<? print number_format($RESERVATION['RES_TOTAL_CHARGE']) ?></div>
            <a href="/mobile/availability.php?GET_INFO=1&ts=<? print strtotime("now") ?>" data-role="button" data-theme="x" data-ajax="false"><? print _l("Continue","Continuar",$RES_LANGUAGE) ?></a>
        </div>

        <div id='policy'>
            <strong><? print _l("Cancellation and Modification Policy","Pol�tica de Cancelaci�n y Modificaci�n",$RES_LANGUAGE) ?></strong><br>
            <? if (isset($RES_ITEMS['CANCELLATION_POLICY'])) print $RES_ITEMS['CANCELLATION_POLICY'] ?>
        </div>
    </div>

</div>

