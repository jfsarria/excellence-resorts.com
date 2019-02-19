<?
/*
 * Revised: Jan 06, 2013
 *          Mar 10, 2014
 *          Jun 24, 2014
 */

ob_start();

$retVal = array();
$showEdit = true;
$TRANSFER = array();
$TOTAL_CHARGE = 0;
$SUPPLEMENT = isset($JSON['RESERVATION']['SUPPLEMENT']) ? (int)$JSON['RESERVATION']['SUPPLEMENT'] : 0;
$TRANSFER_FEE = 0;
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

    if ($MODIFY=="CANCEL" || $MODIFY=="NOSHOW" || $MODIFY=="SUPPLEMENT" || $MODIFY=="TRANSFER" || $MODIFY=="TRANSFER_CANCEL") {
        $NOTES = isset($_DATA['NOTES']) ? $_DATA['NOTES'] : ucwords(strtolower($MODIFY));
        $FEES = isset($_DATA['FEES']) ? $_DATA['FEES'] : $FEES;
        $SUPPLEMENT = isset($_DATA['SUPPLEMENT']) ? $_DATA['SUPPLEMENT'] : $SUPPLEMENT;

        $RESERVATION = array (
            "ID"=>$ID,
            "RES_TABLE"=>"RESERVATIONS_{$CODE}_{$YEAR}"

        );
        if ($MODIFY=="CANCEL" || $MODIFY=="NOSHOW") {
            $RESERVATION["FEES"] = $FEES;
            $RESERVATION["STATUS"] = $MODIFY=="CANCEL"?0:2;
            $RESERVATION["CANCELLED"] = $_TODAY;
        }
        if ($MODIFY=="SUPPLEMENT") {
            $TRANSFER_FEE = (int)$_DATA['RES_GUEST_TRANSFER_FEE'];
            $RESERVATION["SUPPLEMENT"] = $SUPPLEMENT;
        }
        if ($MODIFY=="TRANSFER" || $MODIFY=="TRANSFER_CANCEL") {
            if (isset($_DATA['SUPPLEMENT'])) $TRANSFER['SUPPLEMENT'] = $_DATA['SUPPLEMENT'];
            if (isset($_DATA['RES_GUEST_AIRLINE'])) $TRANSFER['AIRLINE'] = $_DATA['RES_GUEST_AIRLINE'];
            if (isset($_DATA['RES_GUEST_FLIGHT'])) $TRANSFER['FLIGHT'] = $_DATA['RES_GUEST_FLIGHT'];
            if (isset($_DATA['RES_GUEST_ARRIVAL'])) $TRANSFER['ARRIVAL'] = $_DATA['RES_GUEST_ARRIVAL'];
            if (isset($_DATA['RES_GUEST_ARRIVAL_AP'])) $TRANSFER['ARRIVAL_AP'] = $_DATA['RES_GUEST_ARRIVAL_AP'];
            if (isset($_DATA['RES_GUEST_DEPARTURE_AIRLINE'])) $TRANSFER['DEPARTURE_AIRLINE'] = $_DATA['RES_GUEST_DEPARTURE_AIRLINE'];
            if (isset($_DATA['RES_GUEST_DEPARTURE_FLIGHT'])) $TRANSFER['DEPARTURE_FLIGHT'] = $_DATA['RES_GUEST_DEPARTURE_FLIGHT'];
            if (isset($_DATA['RES_GUEST_DEPARTURE'])) $TRANSFER['DEPARTURE'] = $_DATA['RES_GUEST_DEPARTURE'];
            if (isset($_DATA['RES_GUEST_DEPARTURE_AP'])) $TRANSFER['DEPARTURE_AP'] = $_DATA['RES_GUEST_DEPARTURE_AP'];
            if (isset($_DATA['RES_GUEST_TRANSFER_TYPE'])) $TRANSFER['TRANSFER_TYPE'] = $_DATA['RES_GUEST_TRANSFER_TYPE'];
            //if ($MODIFY=="TRANSFER_CANCEL") {
            	//$TRANSFER['TRANSFER_CAR'] = 0;
            	//$TRANSFER['TRANSFER_FEE'] = 0;
            //} else {
	            if (isset($_DATA['RES_GUEST_TRANSFER_CAR'])) $TRANSFER['TRANSFER_CAR'] = $_DATA['RES_GUEST_TRANSFER_CAR'];
	            if (isset($_DATA['RES_GUEST_TRANSFER_FEE'])) $TRANSFER['TRANSFER_FEE'] = $_DATA['RES_GUEST_TRANSFER_FEE'];
            //}
            $TRANSFER_FEE = isset($TRANSFER['TRANSFER_FEE']) ? (int)$TRANSFER['TRANSFER_FEE'] : 0;
            $TRANSFER_CAR = isset($TRANSFER['TRANSFER_CAR']) ? (int)$TRANSFER['TRANSFER_CAR'] : 0;
            $TRANSFER['CC_COMMENTS'] = isset($_DATA['CC_COMMENTS']) ? $_DATA['CC_COMMENTS'] : "";            
            //print "TRANSFER<pre>";print_r($TRANSFER);print "<pre>";            
            $RESERVATION = array_merge($RESERVATION, $TRANSFER);   
        } else {
          $RESERVATION["NOTES"] = $NOTES;
        }
        if (isset($JSON)) {
            if ($MODIFY!="TRANSFER"&&$MODIFY!="TRANSFER_CANCEL") $JSON['RESERVATION']['NOTES'] = $NOTES;
            if ($MODIFY=="CANCEL") $JSON['RESERVATION']['CANCELLED'] = $_TODAY;
            if ($MODIFY=="CANCEL" || $MODIFY=="NOSHOW") $JSON['RESERVATION']['FEES'] = $FEES;
            if ($MODIFY=="SUPPLEMENT") $JSON['RESERVATION']['SUPPLEMENT'] = $SUPPLEMENT;
            if ($MODIFY=="TRANSFER"||$MODIFY=="TRANSFER_CANCEL") $JSON['RESERVATION'] = array_merge($JSON['RESERVATION'], $TRANSFER); 
            $RESERVATION["ARRAY"] = $clsGlobal->jsonEncode($JSON);
        }
        //print "RESERVATION<pre>";print_r($RESERVATION);print "<pre>";
        $result = $clsReserv->modifyReservation($db, $RESERVATION);

        if ((int)$result == 1) {
            //print "CCPS ";//$RESVIEW['NUMBER'];
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
                if ($MODIFY=="SUPPLEMENT" || $TRANSFER_FEE!=0 || (isset($_DATA['ALREADY_CHARGED']) && (int)$_DATA['ALREADY_CHARGED']==0)) {
                    $CCDATA = array (
                        "ONLY_PENDING"=>1,
                        "UPDATE"=>isset($RESVIEW['NUMBER']) ? $RESVIEW['NUMBER'] : $_DATA['RES_NUM'],
                        "MSG"=>$NOTES,
                        "card_amount"=>(int)$TOTAL_CHARGE + (int)$SUPPLEMENT + $TRANSFER_FEE
                    );
                }
                include "m.reserv.payment.er.submit.php";
            $OUT = ob_get_clean();
            //print "==> ". $OUT;

            $SEND_CONFIRMATION = false;

            if ($MODIFY=="CANCEL" || $MODIFY=="NOSHOW") {
                /*
                 * RESTORE ROOM INVENTORY
                 */
                for ($Y=(int)$YEAR;$Y<=(int)$YEAR+2;++$Y) {
                    $clsReserv->deleteReservationRoomInventory($db, array (
                        "ID"=>$ID,
                        "RES_TABLE"=>"RESERVATIONS_{$CODE}_{$Y}_ROOM_INVENTORY"
                    ));
                }
                $retVal['fees'] = $FEES;

                $SEND_CONFIRMATION = true;

            }

            if ($MODIFY=="TRANSFER" || $MODIFY=="TRANSFER_CANCEL") {
                $SEND_CONFIRMATION = true;
            }

            if ($SEND_CONFIRMATION) {
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
        } else if ($MODIFY=="TRANSFER") {
            include_once "inc/mods/m.reserv.transfer.php";
        } else if ($MODIFY=="TRANSFER_CANCEL") {
            include_once "inc/mods/m.reserv.transfer.cancel.php";
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
