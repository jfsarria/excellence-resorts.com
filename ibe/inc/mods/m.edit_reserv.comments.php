<?
/*
 * Revised: Jul 26, 2011
 */

ob_start();

if ($MODIFY!="") {
    $showEdit = true;

    if ($SUBMIT=="SUBMIT") {
        $isOk = false;

        if (!$isWEBSERVICE) {
            $RESERVATION = array(
                'COMMENTS' => $_DATA['RES_GUEST_COMMENTS'],
                'CC_COMMENTS' => $_DATA['CC_COMMENTS'],
                'HEAR_ABOUT_US' => $_DATA['RES_GUEST_HEAR_ABOUT_US']
            );
        } else {
            $RESERVATION = array();
            if (isset($_DATA['COMMENTS'])) $RESERVATION["COMMENTS"] = $_DATA['COMMENTS'];
            if (isset($_DATA['CC_COMMENTS'])) $RESERVATION["CC_COMMENTS"] = $_DATA['CC_COMMENTS'];
            if (isset($_DATA['HEAR_ABOUT_US'])) $RESERVATION["HEAR_ABOUT_US"] = $_DATA['HEAR_ABOUT_US'];
        }

        if ((isset($error) && sizeof($error) != 0) ) {
            include_once "inc/ibe.frm.err.php";
        } else {
            if (isset($RESERVATION['COMMENTS'])) $JSON['RESERVATION']['COMMENTS'] = $RESERVATION['COMMENTS'];
            if (isset($RESERVATION['CC_COMMENTS'])) $JSON['RESERVATION']['CC_COMMENTS'] = $RESERVATION['CC_COMMENTS'];
            if (isset($RESERVATION['HEAR_ABOUT_US'])) $JSON['RESERVATION']['HEAR_ABOUT_US'] = $RESERVATION['HEAR_ABOUT_US'];

            //print "<pre>";print_r($RESERVATION);print "</pre>";

            $RESERVATION['ID'] = $ID;
            $RESERVATION['ARRAY'] = $clsGlobal->jsonEncode($JSON);
            $RESERVATION['RES_TABLE'] = "RESERVATIONS_{$CODE}_{$YEAR}";

            $result = $clsReserv->modifyReservation($db, $RESERVATION); 

            if ((int)$result == 1) {
                include_once "inc/ibe.frm.ok.php";
                $showEdit = false;
            } else {
                print "<div id='s_notice' class='top_msg'>$result</div><br><br>";
            }
        }
    }

    if (!$isWEBSERVICE) {
        if ($showEdit) {
            include_once "inc/mods/".$_RESERVMOD[0]['rooms']['comments'];
        } else {
            print "
                <script>
                    document.location.href=\"{$THIS_PAGE}\"
                </script>
            ";
        }
    }
} else {
    ?>
    <fieldset>
        <legend>Comments</legend>
        <div class="fieldset">
            <div>Guests Comments and special requests:<br><? if (isset($RESERVATION['COMMENTS'])) print urldecode($RESERVATION['COMMENTS']) ?></div><br>
            <div>How did you hear about us?:<br><? if (isset($RESERVATION['HEAR_ABOUT_US'])) print urldecode($RESERVATION['HEAR_ABOUT_US']) ?></div><br>
            <div>Call Center Comments:<br><? if (isset($RESERVATION['CC_COMMENTS'])) print urldecode($RESERVATION['CC_COMMENTS']) ?></div><br>
            <? //if ($RESVIEW['STATUS_STR']=="booked") { ?>
            <div style='text-align:center;margin-top:10px'>
                <a href="<? print $THIS_PAGE."&MODIFY=COMMENTS"; ?>"><span class="button key">Modify</span></a>
            </div>
            <? //} ?>
        </div>
    </fieldset>
<? } 

$RESULT = ob_get_clean();

if (!$isWEBSERVICE) print $RESULT;

?>