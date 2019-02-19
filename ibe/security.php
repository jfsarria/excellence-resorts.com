<?php
// ws.makeReservation

/*
mobile/reservation.submit.php
ibe/inc/ws.sendGuestPwd.php
ibe/inc/ws.sendTAPwd.php
*/

if ($_PAGE_CODE=="ws.makeReservation") {

        $HTTP_REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
        $HTTP_REFERER = isset($_REQUEST['HTTP_REFERER']) ? $_REQUEST['HTTP_REFERER'] : $HTTP_REFERER;
        if (
                stristr($HTTP_REFERER,"locateandshare") === FALSE &&
                stristr($HTTP_REFERER,"precisemagic") === FALSE &&
                stristr($HTTP_REFERER,"belovedhotels") === FALSE &&
                stristr($HTTP_REFERER,"excellence") === FALSE
            ) 
        {
            $isTrust = false;
        }

}
?>