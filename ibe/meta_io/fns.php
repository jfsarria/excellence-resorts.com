<?
function addUnitsToDate($strDate, $units, $zeros=true, $unitsStr="days") {
    $newDate = strtotime(date("Y-m-d", strtotime($strDate)) . " {$units} {$unitsStr}");
    $return = $zeros ? date("Y-m-d", $newDate) : date("Y-n-j", $newDate);
    return $return;
}

function getMetaIOProp($PROP_ID=0) {
    $PROP = array();
    $HTTP_HOST = $_SERVER['HTTP_HOST'];
    if (stristr($HTTP_HOST,"excellence-resorts")!==FALSE || stristr($HTTP_HOST,"locateandshare")!==FALSE) {
        if ($PROP_ID==0) {
            $PROP = array(
              "CODE" => array("XRC","XPM","XPC","XEC","XOB"),
              "ID" => array(1,2,3,6)
            );
        } else {
            switch ($PROP_ID) {
                case "1":
                    $PROP_CODE = "XRC";
                    break;
                case "2":
                    $PROP_CODE = "XPM";
                    break;
                case "3":
                    $PROP_CODE = "XPC";
                    break;
                case "6":
                    $PROP_CODE = "XEC";
                    break;
                case "7":
                    $PROP_CODE = "XOB";
                    break;
            };
            $PROP = array(
                "CODE" => array($PROP_CODE),
                "ID" => array($PROP_ID)
            );
        }
    } else if (stristr($HTTP_HOST,"belovedhotels")!==FALSE || stristr($HTTP_HOST,"hoopsydoopsy")!==FALSE) {
        $PROP = array(
            "CODE" => array("TBH"),
            "ID" => array(4)
        );
    } else if (stristr($HTTP_HOST,"finestresorts")!==FALSE) {
        $PROP = array(
            "CODE" => array("FPM"),
            "ID" => array(5)
        );
    }
    return $PROP;
}

function getMetaIORooms($DATA, $PROP_ID, $CHECK_IN, $CHECK_OUT, $COUNTRY="US", $ADULTS_QTY="1") {
    //$COUNTRIES = array("US","CA","GB");
    $KEY = str_replace("-","",$CHECK_IN);
    $DEBUG = "";

          $API = "http://{$_SERVER['HTTP_HOST']}/ibe/index.php?PAGE_CODE=ws.availability&ACTION=SUBMIT&RES_LANGUAGE=EN&RES_PROP_ID={$PROP_ID}&RES_CHECK_IN={$CHECK_IN}&RES_CHECK_OUT={$CHECK_OUT}&RES_NIGHTS=1&RES_ROOMS_QTY=1&RES_ROOM_1_ADULTS_QTY={$ADULTS_QTY}&RES_ROOM_1_CHILDREN_QTY=0&RES_COUNTRY_CODE={$COUNTRY}";
          //print "<hr>$API<hr>";
          //$DEBUG .= "$API \n\n";


          $RESULT = json_decode(file_get_contents($API), true);
          foreach ($RESULT['RES_ROOM_1_ROOMS'] as $ROOM_ID => $ROOM) {
              print "<br>updating ROOM_ID:$ROOM_ID :: AVAILABLE_NIGHTS: ".(int)$ROOM['AVAILABLE_NIGHTS']." == ".$ROOM['AVAILABLE_NIGHTS']."<br>";
              //print "<pre>";print_r($ROOM);print "</pre>";
              $COUNTRY = $COUNTRY=="GB" ? "RW" : $COUNTRY; 
              $ROOM_DATA = isset($DATA['calendar'][$KEY]["Adults ".$ADULTS_QTY][$ROOM_ID]) ? $DATA['calendar'][$KEY]["Adults ".$ADULTS_QTY][$ROOM_ID] : array("name"=>"","price"=>array());
              $ROOM_DATA["name"] = $ROOM['NAME'];
              if (!is_array($ROOM_DATA["price"])) { $ROOM_DATA["price"] = array(); } 
              $ROOM_DATA["price"][$COUNTRY] = (int)$ROOM['AVAILABLE_NIGHTS']!=0 ? $ROOM['TOTAL']['FINAL'] : 0;
              $DATA['calendar'][$KEY]["Adults ".$ADULTS_QTY][$ROOM_ID] = $ROOM_DATA;
              ksort($DATA['calendar'][$KEY]["Adults ".$ADULTS_QTY]);
              //print "<pre>";print_r($ROOM_DATA);print "</pre>";
          }


    //mail("jaunsarria@gmail.com","getMetaIORooms $PROP_ID - $CHECK_IN - $CHECK_OUT - $COUNTRY - $ADULTS_QTY",$DEBUG);

    return $DATA;
}

function saveMetaIO($FILES, $DEBUG=false) {
    foreach ($FILES as $FILE_NAME => $DATA) {
        ksort($DATA['calendar']);
        $JSON = json_encode($DATA);
        chmod($FILE_NAME, 0777);
        if (file_put_contents($FILE_NAME, $JSON)) {
            if ($DEBUG) { print "<br>Writing $FILE_NAME<br>"; }
        }
    }
}

function getCronStatus($db, $arg) {
    extract($arg);
    $arr = array();
    $query = "SELECT * FROM METAIO_CRON WHERE PROPERTY = '$PROPERTY' LIMIT 1";
    //print "<p class='s_notice top_msg'>$query</p>";
    $RSET = dbQuery($db, array('query' => $query));
    return $RSET;
}

function deleteCronStatus($db, $arg) {
  extract($arg);
  $query = "DELETE FROM METAIO_CRON WHERE PROPERTY = '$PROPERTY'";
  //print "<p class='s_notice top_msg'>$query</p>";
  $result = dbExecute($db, array('query' => $query));
}

function saveCronStatus($db, $arg) {
  extract($arg);

  $query = "INSERT INTO METAIO_CRON ( PROPERTY, COUNTRY, ADULTS, MONTH, `DATE` ) VALUES ( '$PROPERTY', '$COUNTRY', '$ADULTS', '$MONTH', '$DATE' )";
  //print "<p class='s_notice top_msg'>$query</p>";
  $result = dbExecute($db, array('query' => $query));
}

function updateCronStatus($db, $arg) {
  deleteCronStatus($db, $arg);
  saveCronStatus($db, $arg);
}