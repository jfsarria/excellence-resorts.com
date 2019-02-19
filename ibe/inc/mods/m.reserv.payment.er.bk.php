<?
/*
 * Revised: Aug 16, 2011
 */

ob_start();

if (!isset($_SESSION['AVAILABILITY']['RESERVATION']['DONE']['PAYMENT']) && $_SESSION['AVAILABILITY']['RESERVATION']['RES_GUESTMETHOD']=="CC") {
    if (isset($_SESSION['AVAILABILITY']['RESERVATION']['RES_NUMBER']) && trim($_SESSION['AVAILABILITY']['RESERVATION']['RES_NUMBER'])!="") {
        $RES_PAYMENT_VERIFY = isset($RES_PAYMENT_VERIFY) ? $RES_PAYMENT_VERIFY : 0;
        $RESERVATION = $_SESSION['AVAILABILITY']['RESERVATION'];

        $_PUBLISHER_NAME = "";
        if ((int)$RES_PROP_ID==1) $_PUBLISHER_NAME = "excellence2"; // Riviera Cancun, Mexico
        if ((int)$RES_PROP_ID==2) $_PUBLISHER_NAME = "excellence3"; // Playa Mujeres, Mexico
        if ((int)$RES_PROP_ID==3) $_PUBLISHER_NAME = "excellence";  // Punta Cana, Dominican Rep.
        if ((int)$RES_PROP_ID==4) $_PUBLISHER_NAME = "excellence4"; // La Amada

        $CCDATA = array(
            "RES_ID"=>$_SESSION['AVAILABILITY']['RESERVATION']['RES_NUMBER'],
            "GUEST_NAME"=>$RESERVATION["GUEST"]["FIRSTNAME"]." ".$RESERVATION["GUEST"]["LASTNAME"],
            "GUEST_EMAIL"=>$RESERVATION["GUEST"]["EMAIL"],
            "CHECK_IN"=>$RES_CHECK_IN,
            "CHECK_OUT"=>$RES_CHECK_OUT,
            "ROOMS"=>$RES_ROOMS_QTY,
            "GUESTS"=>$RES_ROOMS_ADULTS_QTY,
            "publisher_name"=>$_PUBLISHER_NAME,
            "card_number"=>$RESERVATION["PAYMENT"]["CC_NUMBER"],
            "card_name"=>$RESERVATION["PAYMENT"]["CC_NAME"],
            "card_address1"=>$RESERVATION["PAYMENT"]["CC_BILL_ADDRESS"],
            "card_city"=>$RESERVATION["PAYMENT"]["CC_BILL_CITY"],
            "card_state"=>$RESERVATION["PAYMENT"]["CC_BILL_STATE"],
            "card_zip"=>$RESERVATION["PAYMENT"]["CC_BILL_ZIPCODE"],
            "card_country"=>$RESERVATION["PAYMENT"]["CC_BILL_COUNTRY"],
            "card_type"=>$RESERVATION["PAYMENT"]["CC_TYPE"],
            "card_exp"=>$RESERVATION["PAYMENT"]["CC_EXP"],
            "card_amount"=>$RESERVATION["RES_TOTAL_CHARGE"],
            "REDIRECT_URL"=>$B_REDIRECT_URL,
            "REDIRECT_METHOD"=>"",
            "FORCE"=>1,
            "VERIFY"=>$RES_PAYMENT_VERIFY
        );
        $CCDATA["email"] = (isset($RESERVATION["PAYMENT"]["CC_BILL_EMAIL"]) && trim($RESERVATION["PAYMENT"]["CC_BILL_EMAIL"])!="") ? $RESERVATION["PAYMENT"]["CC_BILL_EMAIL"] : $RESERVATION["GUEST"]["EMAIL"];
        $CCDATA["admin-email"] = ($RESERVATION['FORWHOM']['RES_TO_WHOM']=="TA") ? $RESERVATION['FORWHOM']['TA']['EMAIL'] : "";

        //print "<pre>";print_r($CCDATA);print "</pre>";
        //print "<pre>";print_r($RESERVATION['PAYMENT']);print "</pre>";

        print "<div id='cc_processing' class='cc_info'><div class='lbl'><b>".(($RES_PAYMENT_VERIFY==0)?"Processing":"Verifying")." Payment Information...</b></div><div class='result'>";

        ob_start();
            include "m.reserv.payment.er.submit.php";
        $_PAYMENT_RESULT_STR = ob_get_clean();

        print $_PAYMENT_RESULT_STR."</div></div>";

        $_PAYMENT_RESULT_XML = str2xml($_PAYMENT_RESULT_STR);
        $_P_ERR = $_PAYMENT_RESULT_XML->err;
        if ( $_P_ERR && count($_P_ERR->children()) != 0 ) {
            $isOk = false;
            print "<p class='s_notice top_msg'><b>{$_PAYMENT_RESULT_XML->msg}</b><br><br>";
            foreach ($_P_ERR->children() as $_ERR) {
                print $_ERR."<br>";
                array_push($err, (string)$_ERR);
            }
            print "</p>";
            include "m.reserv.room.payment.php";
        } else {
            if ($RES_PAYMENT_VERIFY==0) $_SESSION['AVAILABILITY']['RESERVATION']['DONE']['PAYMENT'] = 1;
        }

        print "<script>$('div#cc_processing.cc_info').hide();</script>";
    } else {
        $isOk = false;
        array_push($err, "There is not a Reservation Number.");
        array_push($errMsg, "No reservation number assigned before running m.reserv.payment.er.php");
    }
}

$RESULT = ob_get_clean();

if (!$isWEBSERVICE) print $RESULT;

?>
