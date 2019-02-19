<?
//ob_start();
    //print "<pre>";print_r($_POST);print "</pre>";

    if (isset($_POST)&&isset($_POST['BOOK'])&&!is_array($_POST['BOOK'])) {
      $_POST['BOOK'] = json_decode($_POST['BOOK'], true);
    }

    $isMakeBooking = true;

    include "get_search.php";

    $BOOK = $_REQUEST['BOOK'];

    // CLEAN UP ROOMS
    foreach ($results['RES_ITEMS'] as $KEY => $VALUE) {
        if (isset($results['RES_ITEMS'][$KEY]["INCLU_EN"])) { // IS A ROOM
        if (in_array($KEY, $BOOK['RES_ROOMS_SELECTED'])) {
          //$results['RES_ITEMS'][$KEY]["INCLU_EN"] = "---";
          //$results['RES_ITEMS'][$KEY]["INCLU_SP"] = "---";
        } else {
          unset($results['RES_ITEMS'][$KEY]);
        }
      }
    }

    //file_put_contents('submit.txt', print_r($BOOK, true),FILE_APPEND);
    $BOOK['RES_TOTAL_CHARGE'] = 0;
    $BOOK['RES_ROOM_CHARGE'] = array();
    $BOOK['RES_ROOMS_SELECTED_NAMES'] = array();
    foreach ($BOOK['RES_ROOMS_SELECTED'] as $i => $ROOM_ID) {
        $ROOM = $results['RES_ROOM_'.($i+1).'_ROOMS'];
        $TOTAL = $ROOM[$ROOM_ID]['TOTAL']['FINAL'];
        $BOOK['RES_ROOM_CHARGE'][] = $TOTAL;
        $BOOK['RES_ROOMS_SELECTED_NAMES'][$ROOM_ID] = $ROOM[$ROOM_ID]['NAME'];
        $BOOK['RES_TOTAL_CHARGE'] += $TOTAL; 
    }

    //print "BOOK:\n\n";print_r($BOOK);

    $TRANSFERS_COST = isset($BOOK['TRANSFER_TYPE'])&&!empty($BOOK['TRANSFER_TYPE']) ? (int)$BOOK['TRANSFER_TYPE'] : 0;
    $BOOK['RES_TOTAL_CHARGE'] += $TRANSFERS_COST;FILE_PUT_CONTENTS('../../bw/t3lib/G.TXT', PRINT_R($BOOK, TRUE),FILE_APPEND);

    $results['RESERVATION'] = $BOOK;
    $JSON = json_encode($results);

    $POSTFIELDS = array(
        'PAGE_CODE'=>'ws.makeReservation', 
        'JSON'=>$JSON
    );

    //mail("jaunsarria@gmail.com,wandamara_01@hotmail.com","CURLOPT_URL","https://".$_SERVER['HTTP_HOST']."/ibe/index.php");

    $isStaging = strstr($_SERVER["HTTP_HOST"],"staging") || strstr($_SERVER["HTTP_HOST"],"hoopsydoopsy") || strstr($_SERVER["HTTP_HOST"],"locate");
    $CURLOPT_URL = "http".(strstr($API_URL,".com")!==FALSE && !$isStaging ? "s" : "")."://".$_SERVER['HTTP_HOST']."/ibe/index.php";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $CURLOPT_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, 1);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $POSTFIELDS);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
    $result = curl_exec($ch);

    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header("Content-Type:application/json");

    print $result;
