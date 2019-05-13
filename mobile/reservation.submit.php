<? 

include "inc/open.php"; 

//print "<pre>";print_r($_GET);print "</pre>";

if ((isset($_REQUEST['START'])&&(int)$_REQUEST['START']==1) || !isset($_SESSION['AVAILABILITY']) || count($_SESSION['AVAILABILITY'])==0) {

    unset($_SESSION['AVAILABILITY']);
    include "availability.box.php";

} else {

    if (!isset($_SESSION['AVAILABILITY']['RESERVATION']['RES_NUMBER'])||$_SESSION['AVAILABILITY']['RESERVATION']['RES_NUMBER']=="") {

        unset($_SESSION['AVAILABILITY']['RESERVATION']['RES_NUMBER']);

        $_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM'] = array(
            'RES_TO_WHOM' => 'GUEST',
            'RES_GUEST_ID' => (isset($_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['GUEST_ID']) && (int)$_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['GUEST_ID']!=0) ? $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['GUEST_ID'] : 0,
            'RES_NEW_GUEST' => (isset($_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['GUEST_ID']) && (int)$_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['GUEST_ID']!=0) ? 0 : 1,
            'RES_TA_ID' => 0,
            'RES_NEW_TA' => 0,
            'TA' => array()
        );

        $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['TITLE'] = $_GET['TITLE'];
        $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['FIRSTNAME'] = $_GET['FIRSTNAME'];
        $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['LASTNAME'] = $_GET['LASTNAME'];
        $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['ADDRESS'] = $_GET['ADDRESS'];
        $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['PHONE'] = $_GET['PHONE'];
        $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['CITY'] = $_GET['CITY'];
        $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['STATE'] = $_GET['STATE'];
        $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['COUNTRY'] = $_GET['RES_GUEST_COUNTRY'];
        $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['ZIPCODE'] = $_GET['ZIPCODE'];
        $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['EMAIL'] = $_GET['EMAIL'];

        $_SESSION['AVAILABILITY']['RESERVATION']['RES_GUESTMETHOD'] = "CC";

        $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_TYPE'] = $_GET['CC_TYPE'];
        $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_NUMBER'] = $_GET['CC_NUMBER'];
        $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_NAME'] = $_GET['CC_NAME'];
        $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_CODE'] = $_GET['CC_CODE'];
        $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_EXP'] = $_GET['CC_EXP'];
        $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_BILL_ADDRESS'] = trim($_GET['CC_BILL_ADDRESS'])=="" ? $_GET['ADDRESS'] : $_GET['CC_BILL_ADDRESS'];
        $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_BILL_CITY'] = trim($_GET['CC_BILL_CITY'])=="" ? $_GET['CITY'] : $_GET['CC_BILL_CITY'];
        $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_BILL_STATE'] = trim($_GET['RES_CC_BILL_STATE'])=="" ? $_GET['STATE'] : $_GET['RES_CC_BILL_STATE'];
        $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_BILL_COUNTRY'] = trim($_GET['RES_CC_BILL_COUNTRY'])=="" ? $_GET['RES_GUEST_COUNTRY'] : $_GET['RES_CC_BILL_COUNTRY'];
        $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_BILL_ZIPCODE'] = trim($_GET['CC_BILL_ZIPCODE'])=="" ? $_GET['ZIPCODE'] : $_GET['CC_BILL_ZIPCODE'];
        $_SESSION['AVAILABILITY']['RESERVATION']['ARRIVAL_TIME'] = $_GET['ARRIVAL_TIME'];
        $_SESSION['AVAILABILITY']['RESERVATION']['ARRIVAL_AMPM'] = $_GET['ARRIVAL_AMPM'];
        $_SESSION['AVAILABILITY']['RESERVATION']['AIRLINE'] = isset($_GET['AIRLINE']) ? $_GET['AIRLINE'] : "";
        $_SESSION['AVAILABILITY']['RESERVATION']['FLIGHT'] = isset($_GET['FLIGHT']) ? $_GET['FLIGHT'] : "";
        $_SESSION['AVAILABILITY']['RESERVATION']['COMMENTS'] = "";
        $_SESSION['AVAILABILITY']['RESERVATION']['HEAR_ABOUT_US'] = "";
        $_SESSION['AVAILABILITY']['RESERVATION']['CC_COMMENTS'] = "";

        for ($t=0; $t<(int)$_SESSION['AVAILABILITY']['RES_ROOMS_QTY']; ++$t) {
            $_SESSION['AVAILABILITY']['RESERVATION']['ROOMS'][$t]['GUEST_TITLE'] = $_GET["GUEST_TITLE_ROOM_".$t];
            $_SESSION['AVAILABILITY']['RESERVATION']['ROOMS'][$t]['GUEST_FIRSTNAME'] = $_GET["GUEST_FIRSTNAME_ROOM_".$t];
            $_SESSION['AVAILABILITY']['RESERVATION']['ROOMS'][$t]['GUEST_LASTNAME'] = $_GET["GUEST_LASTNAME_ROOM_".$t];
            $_SESSION['AVAILABILITY']['RESERVATION']['ROOMS'][$t]['GUEST_BEDTYPE'] = $_GET["GUEST_BEDTYPE_ROOM_".$t];
            $_SESSION['AVAILABILITY']['RESERVATION']['ROOMS'][$t]['GUEST_SMOKING'] = $_GET["GUEST_SMOKING_ROOM_".$t];
            $_SESSION['AVAILABILITY']['RESERVATION']['ROOMS'][$t]['GUEST_OCCASION'] = $_GET["GUEST_OCCASION_ROOM_".$t];
            if (isset($_GET["GUEST_BABYCRIB_".$t])) {
                $_SESSION['AVAILABILITY']['RESERVATION']['ROOMS'][$t]['GUEST_BABYCRIB'] = $_GET["GUEST_BABYCRIB_".$t];
            }
        }

        // CLEAN UP ROOMS ** THIS IS TEMPORAL. THE IDEA IS CLEAN REMOVE ROOMS AND CLASSES THAT ARE NOT SELECTED IN THE RESERVATION TO SEND LESS DATA
        foreach ($_SESSION['AVAILABILITY']['RES_ITEMS'] as $KEY => $VALUE) {
            if (isset($_SESSION['AVAILABILITY']['RES_ITEMS'][$KEY]["INCLU_EN"])) { // IS A ROOM
            if (in_array($KEY, $_SESSION['AVAILABILITY']['RESERVATION']['RES_ROOMS_SELECTED'])) {
              $_SESSION['AVAILABILITY']['RES_ITEMS'][$KEY]["INCLU_EN"] = "---";
              $_SESSION['AVAILABILITY']['RES_ITEMS'][$KEY]["INCLU_SP"] = "---";
            } else {
              unset($_SESSION['AVAILABILITY']['RES_ITEMS'][$KEY]);
            }
          }
        }

        $JSON = json_encode($_SESSION['AVAILABILITY'],true);
        $JSON = preg_replace(array('/\r\n\s+/i','/\"\s+\:\s+/i'),array('','":'), $JSON);

        //print $JSON;

        $ARGS = array (
            "HTTP_REFERER"=>"excellence",
            "PAGE_CODE"=>"ws.makeReservation",
            "JSON"=>$JSON
        );

        $B_WEBSERVER = str_replace("https://www.excellence-resorts.com","http://www.excellence-resorts.com",$B_WEBSERVER);

        //PRINT "<!-- B_WEBSERVER: $B_WEBSERVER -->";

        ob_start();
            $url = $B_WEBSERVER."/ibe/index.php";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $ARGS);
            
            //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            //curl_setopt($ch, CURLOPT_CAINFO, $_SERVER['DOCUMENT_ROOT'] . "/mobile/ER-GeoTrustGlobalCA.crt");

            $response = curl_exec($ch);
            curl_close($ch);
        $RESULT = json_decode(ob_get_clean(),true);

        //ob_start();print $url." :: ".$RESULT." :: ";print_r($RESULT);print "RESULT";print_r($ARGS);$DEBUG = ob_get_clean();
        //print "<!-- DEBUG:: $DEBUG -->";

        if (isset($RESULT['error'])) {
            $_SESSION['AVAILABILITY']['RESERVATION']['ERROR'] = $RESULT['error'];
            $ts = strtotime("now");
            print "
                <script>
                    document.location.href='/mobile/availability.php?GET_INFO=1&ts={$ts}';
                </script>
            ";
        } else {
            unset($_SESSION['AVAILABILITY']['RESERVATION']['ERROR']);
            $_SESSION['AVAILABILITY']['RESERVATION']['RES_NUMBER'] = $RESULT['RES_NUMBER'];
        }
    }

    if (!isset($RESULT['error'])) include "reservation.confirmation.php";

}

include "inc/close.php"; 

?>
