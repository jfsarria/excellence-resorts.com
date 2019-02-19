<?
/*
 * Revised: Jan 06, 2013
 */

ob_start();

$retVal = array();
$showEdit = true;
$TOTAL_CHARGE = 0;
$SUPPLEMENT = 0;
$FEES = 0;
if (isset($JSON)) {
    $TOTAL_CHARGE = $JSON['RESERVATION']['RES_TOTAL_CHARGE'];
    $arg = array (
        "CHECK_IN"=>$JSON['RES_CHECK_IN'],
        "TOTAL_CHARGE"=>$TOTAL_CHARGE,
        "NIGHTS"=>$JSON['RES_NIGHTS']
    );
    $FEES = isset($JSON['RESERVATION']['FEES']) ? $JSON['RESERVATION']['FEES'] : $clsReserv->calculateFees($db, $arg);
}

if ($SUBMIT=="SUBMIT") {

    if ($MODIFY=="CANCEL" || $MODIFY=="NOSHOW" || $MODIFY=="SUPPLEMENT") {
        $NOTES = isset($_DATA['NOTES']) ? $_DATA['NOTES'] : ucwords(strtolower($MODIFY));
        $FEES = isset($_DATA['FEES']) ? $_DATA['FEES'] : $FEES;
        $SUPPLEMENT = isset($_DATA['SUPPLEMENT']) ? $_DATA['SUPPLEMENT'] : $SUPPLEMENT;

        $RESERVATION = array (
            "ID"=>$ID,
            "RES_TABLE"=>"RESERVATIONS_{$CODE}_{$YEAR}",
            "NOTES"=>$NOTES
        );
        if ($MODIFY=="CANCEL" || $MODIFY=="NOSHOW") {
            $RESERVATION["FEES"] = $FEES;
            $RESERVATION["STATUS"] = $MODIFY=="CANCEL"?0:2;
            $RESERVATION["CANCELLED"] = $_TODAY;
        }
        if ($MODIFY=="SUPPLEMENT") {
            $RESERVATION["SUPPLEMENT"] = $SUPPLEMENT;
        }
        if (isset($JSON)) {
            $JSON['RESERVATION']['NOTES'] = $NOTES;
            if ($MODIFY=="CANCEL") $JSON['RESERVATION']['CANCELLED'] = $_TODAY;
            if ($MODIFY=="CANCEL" || $MODIFY=="NOSHOW") $JSON['RESERVATION']['FEES'] = $FEES;
            if ($MODIFY=="SUPPLEMENT") $JSON['RESERVATION']['SUPPLEMENT'] = $SUPPLEMENT;
            $RESERVATION["ARRAY"] = $clsGlobal->jsonEncode($JSON);
        }
        $result = $clsReserv->modifyReservation($db, $RESERVATION);

        if ((int)$result == 1) {
            $showEdit = false;
            include_once "inc/ibe.frm.ok.php";

            // CANCEL CCPS
            // https://www.locateandshare.com/ws/record.php?TRANS_TYPE=2&RES_ID=3100154552102&MSG=
            ob_start();
                include "m.reserv.payment.er.server.php";
                if ($MODIFY=="CANCEL" || $MODIFY=="NOSHOW") {
                    $CCDATA = array (
                        "TRANS_TYPE"=>"2",
                        "RES_ID"=>$RESVIEW['NUMBER'],
                        "MSG"=>$NOTES
                    );
                }
                if ($MODIFY=="SUPPLEMENT") {
                    $CCDATA = array (
                        "ONLY_PENDING"=>1,
                        "UPDATE"=>$RESVIEW['NUMBER'],
                        "MSG"=>$NOTES,
                        "card_amount"=>(int)$TOTAL_CHARGE + (int)$SUPPLEMENT
                    );
                }
                include "m.reserv.payment.er.submit.php";
            $OUT = ob_get_clean();
            //print "==> ". $OUT;

            if ($MODIFY=="CANCEL" || $MODIFY=="NOSHOW") {
                /*
                 * RESTORE ROOM INVENTORY
                 */
                for ($Y=(int)$YEAR;$Y<=(int)$YEAR+1;++$Y) {
                    $clsReserv->deleteReservationRoomInventory($db, array (
                        "ID"=>$ID,
                        "RES_TABLE"=>"RESERVATIONS_{$CODE}_{$Y}_ROOM_INVENTORY"
                    ));
                }
                $retVal['fees'] = $FEES;

                /*
                 * Send cancelation confirmation email
                 */
                if (isset($ARRAY)) unset($ARRAY);
                ob_start();
                    include "inc/ws.sendConfirmation.php";
                ob_get_clean();
            }
            if ($MODIFY=="SUPPLEMENT") {
                $retVal['supplement'] = $SUPPLEMENT;
            }
            
            $retVal['notes'] = $NOTES;

        } else {
            print "<div id='s_notice' class='top_msg'>$result</div><br><br>";
            $retVal['err'] = $result;
        }
    }
}

if (!$isWEBSERVICE) {
    if ($showEdit) {
        if ($MODIFY=="CANCEL" || $MODIFY=="NOSHOW") {
            include_once "inc/mods/m.reserv.cancel.php";
        } else if ($MODIFY=="SUPPLEMENT") {
            include_once "inc/mods/m.reserv.supplement.php";
        } else {
            include_once "inc/mods/m.reserv.rebook.php";
        }
    } else {
        if ($MODIFY!="RESERV") {
            print "
                <script>
                    document.location.href=\"{$THIS_PAGE}\"
                </script>
            ";
        }
    }
}

$RESULT = ob_get_clean();

if (!$isWEBSERVICE) print $RESULT;

?>
