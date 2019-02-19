<?
    if (isset($_POST)) { $_GET = array_merge($_GET, $_POST); }
    if (isset($_GET) && isset($_GET['RES_COUNTRY_CODE'])) { unset($_GET['RES_COUNTRY_CODE']); }

	//if (isset($isMakeBooking)) { ob_start();print_r($_GET);	$_ARR = ob_get_clean(); mail("jaunsarria@gmail.com","arr 1",$_ARR); }
/*
    $isPAST = dateDiff($_GET["RES_CHECK_IN"],"now","D",false);

    //print "<!-- GET: ";print_r($_GET);print " $isPAST -->";

    if ($isPAST > 0) {
      $_GET["RES_CHECK_IN"] = DATE("Y-m-d");
      //$_GET["RES_CHECK_OUT"] = addDaysToDate($_GET["RES_CHECK_IN"], 1);
      
      print " <!-- {$_GET['RES_CHECK_IN']} -  {$_GET['RES_CHECK_OUT']}  --> ";
    }
*/
    $search_url = "http://" . $_SERVER['SERVER_NAME'] . "/ibe/index.php?PAGE_CODE=ws.availability&ACTION=SUBMIT&";

    $search_qry_pre = "RES_PROP_ID=".(isset($_REQUEST['RES_PROP_ID'])?$_REQUEST['RES_PROP_ID']:"1")."&";

    $_TOMORROW = date("Y-m-d",mktime(0,0,0,date("m") ,date("d")+1,date("Y")));
    $_AFTER_TOMORROW = date("Y-m-d",mktime(0,0,0,date("m") ,date("d")+2,date("Y")));

    $_DATA = array();
    include "get_geo.php";

    $search_qry = "RES_CHECK_IN={$_TOMORROW}&RES_CHECK_OUT={$_AFTER_TOMORROW}&RES_NIGHTS=2&RES_ROOMS_QTY=1&RES_ROOM_1_ADULTS_QTY=2&RES_ROOM_1_CHILD_AGE_5=0&RES_ROOM_1_CHILDREN_QTY=0&RES_ROOM_1_CHILD_AGE_3=4&RES_ROOM_1_CHILD_AGE_2=4&RES_ROOM_1_CHILD_AGE_1=4&RES_ROOM_2_ADULTS_QTY=1&RES_ROOM_2_CHILD_AGE_5=0&RES_ROOM_2_CHILDREN_QTY=0&RES_ROOM_2_CHILD_AGE_3=4&RES_ROOM_2_CHILD_AGE_2=4&RES_ROOM_2_CHILD_AGE_1=4&RES_ROOM_3_ADULTS_QTY=1&RES_ROOM_3_CHILD_AGE_5=0&RES_ROOM_3_CHILDREN_QTY=0&RES_ROOM_3_CHILD_AGE_3=4&RES_ROOM_3_CHILD_AGE_2=4&RES_ROOM_3_CHILD_AGE_1=4&RES_SPECIAL_CODE=&RES_SPECIAL_CODE=&RES_LANGUAGE=EN&RES_STATE_CODE=&RES_COUNTRY_CODE={$_GEO['RES_COUNTRY_CODE']}&";

    $search_qry = isset($_POST['QRYSTR'])&&!empty($_POST['QRYSTR']) ? $_POST['QRYSTR'] : $search_qry;

    if (isset($_GET) && isset($_GET['RES_CHECK_IN'])) {
        $_GET = array_merge(array(
          'RES_LANGUAGE'=>"EN",
          'RES_COUNTRY_CODE'=>$_GEO['RES_COUNTRY_CODE']
        ), $_GET);
        $search_qry = http_build_query($_GET);
    }

    //ob_start();print_r($_GET);$_ARR = ob_get_clean(); mail("jaunsarria@gmail.com","GEO",$_ARR);

    $results_url = $search_url . $search_qry_pre . $search_qry;
    //print $results_url;exit;
    $results_json = file_get_contents($results_url);
    $results = json_decode($results_json, true);
    $RES_LANGUAGE = $results['RES_LANGUAGE'];
    $COUNTRY_CODE = $_GEO['RES_COUNTRY_CODE'];
    //print "<pre>";print_r($results);print "</pre>";exit;
    FILE_PUT_CONTENTS('../../dev/typo3/js/tree.txt', PRINT_R($_REQUEST['BOOK'], TRUE),FILE_APPEND);

?>