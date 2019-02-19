<?
    include_once "m.reserv.payment.er.server.php";

    $SOURCE_STR = $RESVIEW['SOURCE_STR']; //$clsReserv->getSourceStr($RESVIEW);
    $MADE_BY = ($SOURCE_STR=="CC") ? $clsUsers->getAgentName($db, array("ID"=>$RESVIEW['SOURCE_ID'])) : $RESVIEW['CONTACT_FIRSTNAME']." ".$RESVIEW['CONTACT_LASTNAME'];

    $CCPS = file_get_contents($B_WEBSERVER."ws/get.php?RES_ID=".$RESVIEW['NUMBER']."&sortBy=UID");
    $CCPS = json_decode($CCPS, true);

    global $_TODAY, $_TRANSFER_DAYS, $_PICKUP_DAYS;;
    $IS_TRANSFER_ACTIVE = $clsTransfer->isActive($db, array("PROP_ID"=>$RES_PROP_ID));
    $DAYS_LEFT = dateDiff($_TODAY, $RES_CHECK_IN, "D", false);
    $CAN_CANCEL_TRANSFER = (int)$CCPS['STATUS']==0;
    $CAN_BOOK_TRANSFER = $CAN_CANCEL_TRANSFER && $DAYS_LEFT >= $_TRANSFER_DAYS && $IS_TRANSFER_ACTIVE==1;
    $CAN_ADD_TRANSFER = $CAN_BOOK_TRANSFER && $DAYS_LEFT >= $_TRANSFER_DAYS+1;
    $CAN_BOOK_PICKUP = $DAYS_LEFT >= $_PICKUP_DAYS;
    //print $CAN_ADD_TRANSFER ? " YES " : " NO ";
?>
<fieldset>
    <div class="fieldset">
        <div><b><? print $RES_ITEMS['PROPERTY']['NAME'] ?></b></div>
        <div class='resMadeBy'>Reservation source <? print $SOURCE_STR ?>, made by <? if ($SOURCE_STR=="CC") print "Agent "; print $MADE_BY ?></div>
        <div>
            <span class='resNumber'>
                Reservation # <b><? print $RESVIEW['NUMBER'] ?></b>
            </span>
            &nbsp;&nbsp;
            Status: <span class='resStatus_<? print str_replace(" ","_",$RESVIEW['STATUS_STR']) ?>'><? print ucwords($RESVIEW['STATUS_STR']) ?></span>
            <? 
            if (isset($RESERVATION['NOTES'])&&trim($RESERVATION['NOTES'])!="") { 
                if (isset($RESERVATION['NOTES'])) print "<div>Notes: {$RESERVATION['NOTES']}</div>";
            }
            ?>
        </div>
        <div><b><? print (int)$CCPS['STATUS']==1 ? "Charged" : "Pending Charge" ?></b></div>
        <? if ($RESVIEW['STATUS_STR']=="booked") { ?>
        <div><?=$DAYS_LEFT?> days left until CheckIn</div>
        <? } ?>
    </div>
</fieldset>
