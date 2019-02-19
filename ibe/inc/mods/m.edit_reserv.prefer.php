 <?
/*
 * Revised: Mar 23, 2012
 */

ob_start();

if ($MODIFY!="") {
    $showEdit = true;

    if ($SUBMIT=="SUBMIT") {
        $isOk = false;

        $ORIGINAL_RES = $JSON;
            
        /* ROOMS */
        foreach ($RESERVATION['RES_ROOMS_SELECTED'] AS $ROOM_KEY => $ROOM_ID) {
            //if (isset($_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_ROOM_KEY"])) $IROOM['ROOM_KEY'] = $_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_ROOM_KEY"];
            $IROOM = array();
            if (isset($_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_TITLE"])) $IROOM['GUEST_TITLE'] = ($_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_TITLE"]!="") ? $_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_TITLE"] : $GUEST['TITLE'];
            if (isset($_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_FIRSTNAME"])) $IROOM['GUEST_FIRSTNAME'] = ($_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_FIRSTNAME"]!="") ? $_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_FIRSTNAME"] : $GUEST['FIRSTNAME'];
            if (isset($_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_LASTNAME"])) $IROOM['GUEST_LASTNAME'] = ($_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_LASTNAME"]!="") ? $_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_LASTNAME"] : $GUEST['LASTNAME'];
            if (isset($_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_REPEATED"])) $IROOM['GUEST_REPEATED'] = $_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_REPEATED"];
            if (isset($_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_BEDTYPE"])) $IROOM['GUEST_BEDTYPE'] = $_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_BEDTYPE"];
            if (isset($_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_BABYCRIB"])) $IROOM['GUEST_BABYCRIB'] = $_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_BABYCRIB"];
            if (isset($_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_SMOKING"])) $IROOM['GUEST_SMOKING'] = $_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_SMOKING"];
            if (isset($_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_OCCASION"])) $IROOM['GUEST_OCCASION'] = $_DATA["RES_GUEST_ROOM_{$ROOM_KEY}_OCCASION"];

            $RESERVATION['ROOMS'][$ROOM_KEY] = $IROOM;
            $IROOM = array_merge($JSON['RESERVATION']['ROOMS'][$ROOM_KEY], $IROOM);
            $JSON['RESERVATION']['ROOMS'][$ROOM_KEY] = $IROOM;
        }
        /* OTHER */
        $FIELDS = array('ARRIVAL_TIME','ARRIVAL_AMPM','AIRLINE','FLIGHT','ARRIVAL','ARRIVAL_AP');
        foreach ($FIELDS as $ind=>$FIELD) {
            if (isset($_DATA["RES_GUEST_{$FIELD}"])) {
                $RESERVATION[$FIELD] = $_DATA["RES_GUEST_{$FIELD}"];
                $JSON['RESERVATION'][$FIELD]  = $RESERVATION[$FIELD];
            }
        }

        if ((isset($error) && sizeof($error) != 0) ) {
            include_once "inc/ibe.frm.err.php";
        } else {
            $RESERVATION['ID'] = $ID;
            $RESERVATION['ARRAY'] = $clsGlobal->jsonEncode($JSON);
            $RESERVATION['RES_TABLE'] = "RESERVATIONS_{$CODE}_{$YEAR}";

            $result = $clsReserv->modifyReservation($db, $RESERVATION); 

            foreach ($RESERVATION['RES_ROOMS_SELECTED'] AS $ROOM_KEY => $ROOM_ID) {
                $OPTS = $RESERVATION['ROOMS'][$ROOM_KEY];
                $OPTS['ROOM_KEY'] = $ROOM_KEY;
                $OPTS['RES_ID'] = $JSON['RESERVATION']['RES_ID'];
                $OPTS['RES_NUM'] = $JSON['RESERVATION']['RES_NUMBER'];
                $OPTS['RES_TABLE'] = "RESERVATIONS_{$CODE}_{$YEAR}_ROOM_OPTS";
                $result = $clsReserv->modifyReservationRoomOpts($db, $OPTS); 
            }

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
            include_once "inc/mods/".$_RESERVMOD[0]['rooms']['optionals'];
        } else {
            /*
             * Send emails if Air changed
             */
            $isOnlyPrefer = true;
            ob_start();
                include "inc/ws.sendConfirmation.php";
            ob_get_clean();

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
        <legend>Special preferences</legend>
        <div class="fieldset">
        <? 
        foreach ($RESERVATION['ROOMS'] as $ind => $PROOM) {
            if (count($RESERVATION['ROOMS'])>1) print "<div>Name: {$PROOM['GUEST_TITLE']} {$PROOM['GUEST_FIRSTNAME']} {$PROOM['GUEST_LASTNAME']}</div>";
            print "
                <div>Repeat Guest: ".(isset($PROOM['GUEST_REPEATED']) && is_array($PROOM['GUEST_REPEATED'])?$clsGlobal->getPropertiesNamesFromArray($PROOM['GUEST_REPEATED'], $RES_ITEMS['PROPERTIES']):"No")."</div>
                <div>Bed Type: ".((isset($PROOM['GUEST_BEDTYPE'])&&$PROOM['GUEST_BEDTYPE']!=""&&isset($RES_ITEMS['PROPERTY']['BED_TYPES'][$PROOM['GUEST_BEDTYPE']]))?$RES_ITEMS['PROPERTY']['BED_TYPES'][$PROOM['GUEST_BEDTYPE']]:"No")."</div>
            ";
            if (isset($PROOM['GUEST_BABYCRIB']) && (int)$PROOM['GUEST_BABYCRIB']=="1") {
                print "
                    <div>Baby Crib: Yes</div>
                ";
            }
            print "
                <div>Smoking Preference: ".((isset($PROOM['GUEST_SMOKING'])&&$PROOM['GUEST_SMOKING']!="")?$PROOM['GUEST_SMOKING']:"No")."</div>
                <div>Special Occasion: ".((isset($PROOM['GUEST_SMOKING'])&&$PROOM['GUEST_OCCASION']!="")?$PROOM['GUEST_OCCASION']:"No")."</div>
                <br>
            ";
        } 
        print "
            <div>Hotel Arrival Time: ".(($RESERVATION['ARRIVAL_TIME']!="")?$RESERVATION['ARRIVAL_TIME']." ".$RESERVATION['ARRIVAL_AMPM']:"--")."</div>
            <div>Airline: ".(($RESERVATION['AIRLINE']!="")?$RESERVATION['AIRLINE']:"--")."</div>
            <div>Flight: ".(($RESERVATION['FLIGHT']!="")?$RESERVATION['FLIGHT']:"--")."</div>
            <div>Arrival Time: ".((isset($RESERVATION['ARRIVAL'])&&$RESERVATION['ARRIVAL']!="")?$RESERVATION['ARRIVAL']." ".$RESERVATION['ARRIVAL_AP']:"--")."</div>
        ";
        ?>
        </div>
        <? //if ($RESVIEW['STATUS_STR']=="booked") { ?>
        <div style='text-align:center;margin-top:10px'>
            <a href="<? print $THIS_PAGE."&MODIFY=OPTIONALS"; ?>"><span class="button key">Modify</span></a>
        </div>
        
        <br>
        <div style='text-align:center;margin-top:10px'>
            <a href="<? print $THIS_PAGE."&MODIFY=INVENTORY"; ?>"><span class="button key">Reset Inventory</span></a>
        </div>

        <? //} ?>
    </fieldset>
<? } 

$RESULT = ob_get_clean();

if (!$isWEBSERVICE) print $RESULT;        

?>
