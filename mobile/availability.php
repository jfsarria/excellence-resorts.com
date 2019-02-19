<? 

include "inc/open.php"; 

if (isset($_REQUEST['PAGE_CODE'])&&$_REQUEST['PAGE_CODE']=="ws.availability") {
    $QUERY_ARRAY = query_string_2_array($_SERVER["QUERY_STRING"]);

    //if (!isset($QUERY_ARRAY['RES_COUNTRY_CODE'])||$QUERY_ARRAY['RES_COUNTRY_CODE']=="") {
        include "geo.php";
        $QUERY_ARRAY['RES_COUNTRY_CODE']=$RES_COUNTRY_CODE;
    //}

    //print "<pre>";print_r($QUERY_ARRAY);print "</pre>";
    for ($RNUM=1;$RNUM<=(int)$QUERY_ARRAY['RES_ROOMS_QTY'];++$RNUM) { 
        if (isset($QUERY_ARRAY["RES_ROOM_{$RNUM}_CHILD_AGE_5"])&&(int)$QUERY_ARRAY["RES_ROOM_{$RNUM}_CHILD_AGE_5"]==1) {
            $CHILDREN_QTY = (int)$QUERY_ARRAY["RES_ROOM_{$RNUM}_CHILDREN_QTY"]+1;
            $QUERY_ARRAY["RES_ROOM_{$RNUM}_CHILDREN_QTY"] = $CHILDREN_QTY;
            $QUERY_ARRAY["RES_ROOM_{$RNUM}_CHILD_AGE_{$CHILDREN_QTY}"] = 1;
        }
    }
    //print "<pre>";print_r($QUERY_ARRAY);print "</pre>";

    $JSON_URL = $_SERVER_URL.'/ibe/index.php?' . array_2_query_string($QUERY_ARRAY);

    print "<!-- {$QUERY_ARRAY['RES_COUNTRY_CODE']} \n ==>\n$JSON_URL\n-->";

    $JSON = file_get_contents($JSON_URL);
    $_SESSION['AVAILABILITY'] = json_decode($JSON, true);
    $_SESSION['AVAILABILITY']['RESERVATION']['RES_ROOMS_SELECTED'] = array();
}

if ((isset($_REQUEST['START'])&&(int)$_REQUEST['START']==1) || !isset($_SESSION['AVAILABILITY']) || count($_SESSION['AVAILABILITY'])==0) {

    unset($_SESSION['AVAILABILITY']);
    include "availability.box.php";

} else {
    if (isset($_REQUEST['ROOM_NUM_SELECTED'])&&isset($_REQUEST['ROOM_ID_SELECTED'])) {
        $_SESSION['AVAILABILITY']['RESERVATION']['RES_ROOMS_SELECTED'][(int)$_REQUEST['ROOM_NUM_SELECTED']-1] = $_REQUEST['ROOM_ID_SELECTED'];
        $ROOM_NUM = (int)$_REQUEST['ROOM_NUM_SELECTED']+1;
    } else {
        $ROOM_NUM = 1;
    }

    extract($_SESSION['AVAILABILITY']);

    if (isset($_REQUEST['GET_INFO'])) {
        include "availability.info.php";
    } else {
        if ($ROOM_NUM<=(int)$RES_ROOMS_QTY) {
            include "availability.list.php";
        } else {
            include "availability.summary.room.php";
        }
    }
}

include "inc/close.php"; 

?>
