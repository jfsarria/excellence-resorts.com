<?
/*
 * Revised: Jul 26, 2011
 */

ob_start();

if ($MODIFY!="") {
    $showEdit = true;

    if ($SUBMIT=="SUBMIT") {

        if (!$isWEBSERVICE) {
            $PAYMENT = array(
                "CC_TYPE"=>$_DATA['RES_CC_TYPE'],
                "CC_NUMBER"=>$_DATA['RES_CC_NUMBER'],
                "CC_NAME"=>$_DATA['RES_CC_NAME'],
                "CC_CODE"=>$_DATA['RES_CC_CODE'],
                "CC_EXP"=>$_DATA['card-exp-MM']."/".$_DATA['card-exp-YY'],
                "CC_BILL_ADDRESS"=>$_DATA['RES_CC_BILL_ADDRESS'],
                "CC_BILL_CITY"=>$_DATA['RES_CC_BILL_CITY'],
                "CC_BILL_STATE"=>$_DATA['RES_CC_BILL_STATE'],
                "CC_BILL_COUNTRY"=>$_DATA['RES_CC_BILL_COUNTRY'],
                "CC_BILL_ZIPCODE"=>$_DATA['RES_CC_BILL_ZIPCODE'],
                "CC_BILL_EMAIL"=>(isset($_DATA['RES_CC_BILL_EMAIL']) && trim($_DATA['RES_CC_BILL_EMAIL'])!="") ? $_DATA['RES_CC_BILL_EMAIL'] : $GUEST["EMAIL"]
            );
        } else {
            $PAYMENT = array();
            if (isset($_DATA['CC_TYPE'])) $PAYMENT["CC_TYPE"] = $_DATA['CC_TYPE'];
            if (isset($_DATA['CC_NUMBER'])) $PAYMENT["CC_NUMBER"] = $_DATA['CC_NUMBER'];
            if (isset($_DATA['CC_NAME'])) $PAYMENT["CC_NAME"] = $_DATA['CC_NAME'];
            if (isset($_DATA['CC_CODE'])) $PAYMENT["CC_CODE"] = $_DATA['CC_CODE'];
            if (isset($_DATA['CC_EXP'])) $PAYMENT["CC_EXP"] = $_DATA['CC_EXP'];
            if (isset($_DATA['CC_BILL_ADDRESS'])) $PAYMENT["CC_BILL_ADDRESS"] = $_DATA['CC_BILL_ADDRESS'];
            if (isset($_DATA['CC_BILL_CITY'])) $PAYMENT["CC_BILL_CITY"] = $_DATA['CC_BILL_CITY'];
            if (isset($_DATA['CC_BILL_STATE'])) $PAYMENT["CC_BILL_STATE"] = $_DATA['CC_BILL_STATE'];
            if (isset($_DATA['CC_BILL_COUNTRY'])) $PAYMENT["CC_BILL_COUNTRY"] = $_DATA['CC_BILL_COUNTRY'];
            if (isset($_DATA['CC_BILL_ZIPCODE'])) $PAYMENT["CC_BILL_ZIPCODE"] = $_DATA['CC_BILL_ZIPCODE'];
            if (isset($_DATA['CC_BILL_EMAIL'])) $PAYMENT["CC_BILL_EMAIL"] = $_DATA['CC_BILL_EMAIL'];
        }

        //if (isset($_DATA['RES_GUEST_EMAIL']) && $_DATA['RES_GUEST_EMAIL'] == "") $error['RES_GUEST_EMAIL'] = 'RES_GUEST_EMAIL';

        if (isset($error) && sizeof($error) != 0) {
            include_once "inc/ibe.frm.err.php";
        } else {
            $PAYMENT = array_merge($JSON['RESERVATION']['PAYMENT'], $PAYMENT);
            $JSON['RESERVATION']['PAYMENT'] = $PAYMENT;

            $PAYMENT['ID'] = $ID;
            $PAYMENT['ARRAY'] = $clsGlobal->jsonEncode($JSON);
            $PAYMENT['RES_TABLE'] = "RESERVATIONS_{$CODE}_{$YEAR}";

            //print "<pre>";print_r($PAYMENT);print "</pre>";

            $result = $clsReserv->modifyReservation($db, $PAYMENT); 

            if ((int)$result == 1) {
                include_once "inc/ibe.frm.ok.php";
                // UPDATE CCPS
                include "m.reserv.payment.er.server.php";

                $CCDATA = array(
                    "ONLY_PENDING"=>1,
                    "UPDATE"=>$JSON['RESERVATION']['RES_NUMBER'],
                    "card_number"=>$PAYMENT["CC_NUMBER"],
                    "card_name"=>$PAYMENT["CC_NAME"],
                    "card_address1"=>$PAYMENT["CC_BILL_ADDRESS"],
                    "card_city"=>$PAYMENT["CC_BILL_CITY"],
                    "card_state"=>$PAYMENT["CC_BILL_STATE"],
                    "card_zip"=>$PAYMENT["CC_BILL_ZIPCODE"],
                    "card_country"=>$PAYMENT["CC_BILL_COUNTRY"],
                    "card_type"=>$PAYMENT["CC_TYPE"],
                    "card_exp"=>$PAYMENT["CC_EXP"],
                    "email"=>$PAYMENT["CC_BILL_EMAIL"]
                );
                if (strstr($CCDATA['card_number'],"*")!==FALSE || trim($CCDATA['card_number'])=="") unset($CCDATA['card_number']);

                //print "<pre>";print_r($CCDATA);print "</pre>";

                ob_start();
                    include "m.reserv.payment.er.submit.php";
                $_PAYMENT_RESULT_STR = ob_get_clean();

                //print "<PRE>";print $_PAYMENT_RESULT_STR;print "</PRE>";

                $showEdit = false;
            } else {
                print "<div id='s_notice' class='top_msg'>$result</div><br><br>";
            }
        }

    }

    if (!$isWEBSERVICE) {
        if ($showEdit) {
            extract($PAYMENT);
            include_once "inc/mods/".$_RESERVMOD[0]['rooms']['payment'];
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
        <?
        $BILL_EMAIL = (isset($PAYMENT['CC_BILL_EMAIL']) && trim($PAYMENT['CC_BILL_EMAIL'])!="") ? $PAYMENT['CC_BILL_EMAIL'] : $GUEST['EMAIL'];
        ?>
        <legend>Payment Info</legend>
        <div class="fieldset">
            <? if ($RESERVATION['RES_GUESTMETHOD']=="WIRE") { print "WIRE"; } else { ?>
                <div>Card Type: <? print $PAYMENT['CC_TYPE'] ?></div>
                <div>Card Number: <? print $PAYMENT['CC_NUMBER'] ?></div>
                <div>Expiration: <? print $PAYMENT['CC_EXP'] ?></div>
                <div>Card Holder: <? print $PAYMENT['CC_NAME'] ?></div>
                <div>Address: <? print $PAYMENT['CC_BILL_ADDRESS']."<br>".appendToString($PAYMENT['CC_BILL_CITY'],", ").appendToString($PAYMENT['CC_BILL_STATE']," ").appendToString($PAYMENT['CC_BILL_ZIPCODE'],", ").$PAYMENT['CC_BILL_COUNTRY'] ?></div>
                <div>Payment confirmation email:</div>
                <div><? print $BILL_EMAIL ?></div>
                <? //if ($RESVIEW['STATUS_STR']=="booked") { ?>
                <div style='text-align:center;margin-top:10px'>
                    <a href="<? print $THIS_PAGE."&MODIFY=PAYMENT"; ?>"><span class="button key">Modify</span></a>
                </div>
                <? //} ?>
            <? } ?>
        </div>
    </fieldset>
<? } 
        
$RESULT = ob_get_clean();

if (!$isWEBSERVICE) print $RESULT;

?>