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

<script>
    var dataLayerObjMob = {
        'Page' : 'IBE',
        'Prop_ID' : '<?=$RES_ITEMS["PROPERTY"]["NAME"]?>',
        'Checkin_date' : '<?=$RES_CHECK_IN?>',
        'Checkout_date' : '<?=$RES_CHECK_OUT?>',
        'Number_of_rooms' : <?=$RES_ROOMS_QTY?>,
        'Guests' : <?=(int)$RES_ROOMS_ADULTS_QTY + (int)$RES_ROOMS_CHILDREN_QTY?>,
        'Country' : '<?=$RES_COUNTRY_CODE?>',
        'ibe_step' : 'step-1',
        "event": "checkout",
        "ibe_price" : <?=$RESERVATION['RES_TOTAL_CHARGE']?>,
        "ecommerce": {
            "checkout": {
                "actionField": {
                    "step": 1,
                    "option": "Availability and Rates"
                },
                "products": []
            }
        }                
    }; 


</script>

<div data-role="header" data-theme="x">
    <h1><? print _l("Reservation Summary","Información de Reserva",$RES_LANGUAGE) ?></h1>
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
                    <div class='qty'>"._l("Room","Habitación",$RES_LANGUAGE)." ".($ROOM_NUM+1).", ".$ADULTS." ".((int)$RES_PROP_ID==4 ? _l("Adult","Adulto",$RES_LANGUAGE) : _l("Guest","Huesped",$RES_LANGUAGE)).((int)$ADULTS!=1 ? _l("s",(int)$RES_PROP_ID==4 ? "s":"es",$RES_LANGUAGE):"");

                    if ($CHILDREN!=0 || $INFANTS!=0) {
                        if ($CHILDREN!=0) print ", ".($CHILDREN - $INFANTS)." "._l("Child","Niño",$RES_LANGUAGE).(($CHILDREN - $INFANTS)!=1 ? _l("ren","s",$RES_LANGUAGE) : "");
                        if ($INFANTS!=0) print ", ".$INFANTS." "._l("Infant","Bebé",$RES_LANGUAGE);
                    } 

                    print "
                    </div>
                    <div class='name'>{$ROOM_NAME}</div>
                    <div class='price'>$".(number_format($ROOM_TOTAL))." (USD)</div>
                </div>
            ";
            ?>
            <script>
                dataLayerObjMob.ecommerce.checkout.products[<?=$ROOM_NUM?>] = {
                    "name": '<?=$RES_ITEMS["PROPERTY"]["NAME"]?>',
                    "id": '<?=$RES_ITEMS["PROPERTY"]["ID"]?>',
                    "price": <?=$ROOM_TOTAL?>,
                    "brand": "Excellence Resorts",
                    "category": '<?=$RES_ITEMS[$ROOM_ID]['IS_VIP'] == 0 ? "Suite" : "Club" ?>',
                    "variant": '<?=$ROOM_NAME?>',
                    "quantity": 1,
                    "currencyCode": "USD",
                    "dimension1": '<?=$RES_CHECK_IN?>',
                    "dimension2": '<?=$RES_CHECK_OUT?>',
                    "dimension3": "RoomOnly",
                    'dimension4': "NA",
                    "metric1": <?=(int)$ADULTS + (int)$CHILDREN + (int)$INFANTS?>,
                    "metric3": <?=(int)$CHILDREN + (int)$INFANTS?>,
                    "metric4": <?=(int)$ADULTS?>,
                    "metric5": <?=$RES_NIGHTS?>
                }
                
            </script>
            <?      
        }
        ?>
        <div class='room'>
            <div class='price'><? print _l("TOTAL COST","COSTO TOTAL",$RES_LANGUAGE) ?> (USD): $<? print number_format($RESERVATION['RES_TOTAL_CHARGE']) ?></div>
            <a href="/mobile/availability.php?GET_INFO=1&ts=<? print strtotime("now") ?>" data-role="button" data-theme="x" data-ajax="false"><? print _l("Continue","Continuar",$RES_LANGUAGE) ?></a>
        </div>

        <div id='policy'>
            <strong><? print _l("Cancellation and Modification Policy","Política de Cancelación y Modificación",$RES_LANGUAGE) ?></strong><br>
            <? if (isset($RES_ITEMS['CANCELLATION_POLICY'])) print $RES_ITEMS['CANCELLATION_POLICY'] ?>
        </div>
    </div>

</div>

<script>
    console.log("dataLayer step 1", dataLayerObjMob);
    localStorage.setItem("dataLayerObjMob",JSON.stringify(dataLayerObjMob));    
    dataLayer.push(dataLayerObjMob);
</script>