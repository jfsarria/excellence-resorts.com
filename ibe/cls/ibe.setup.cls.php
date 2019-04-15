<?
/*
 * Revised: Mar 23, 2012
 *          Nov 08, 2016
 *          May 13, 2017
 */

class setup {

    var $showQry = false;

    function save($db, $arg) {
        extract($arg);

        if ((int)$PROP_ID!=0) {
            $result = $this->getById($db, $arg);
        } else $result['iCount'] = 0;

        if ( $result['iCount'] == 0 ) {
            $result = $this->addNew($db, $arg);
        } else {
            $result = $this->modify($db, $arg);
        }

        return $result;
    }

    function save_global_setup_mod($db, $arg){
        extract($arg);
        //$arr = array();
        for($i=0;$i<count($C_SYSTEM);$i++){
            //array_push($arr,"PRIORITY = '{$C_PRIORITY[$i]}'");
            $query = "UPDATE MODPRIORITY SET PRIORITY = '{$C_PRIORITY[$i]}' WHERE SYSTEM='{$C_SYSTEM[$i]}'";
            if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

            $arr = array('query' => $query);
            $result = dbExecute($db, $arr);
        }
        
    }

    function getById($db, $arg) {
        global $PROP_ID;
        extract($arg);
     
        $query = "SELECT * FROM PROPERTIES WHERE ID='{$PROP_ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);

        if (isset($asArray) && $asArray) {
            $result = $db->fetch_array($result['rSet']);
        }
        return $result;
    }

    function getByCode($db, $arg) {
        global $PROP_ID;
        extract($arg);
     
        $query = "SELECT * FROM PROPERTIES WHERE CODE='{$CODE}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);

        if (isset($asArray) && $asArray) {
            $result = $db->fetch_array($result['rSet']);
        }
        return $result;
    }

    function addNew($db, $arg) {
        global $PROP_ID;
        extract($arg);

        $query = "INSERT INTO PROPERTIES ( ID, UPDATED_BY ) VALUES ( '{$PROP_ID}', '{$_SESSION['AUTHENTICATION']['ID']}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $this->modify($db, $arg);
    }


    function modify($db, $arg) {
        global $PROP_ID;
        global $clsUploads;
        global $clsGlobal;
        extract($arg);

        $arr = array();

        //print "<pre>";print_r($arg);print "</pre>";

        if (isset($NAME)) array_push($arr," NAME = '$NAME'");
        if (isset($LIMIT_MPRICE)) array_push($arr," LIMIT_MPRICE = '$LIMIT_MPRICE'");
        if (isset($BLOCK_IP)) array_push($arr," BLOCK_IP = '$BLOCK_IP'");

        if (isset($DESCR_EN)) array_push($arr," DESCR_EN = '$DESCR_EN'");
        if (isset($DESCR_SP)) array_push($arr," DESCR_SP = '$DESCR_SP'");
        if (isset($PHOTO_FILE)) array_push($arr," PHOTO_FILE = '$PHOTO_FILE'");
        if (isset($VIDEO_FILE)) array_push($arr," VIDEO_FILE = '$VIDEO_FILE'");

        if (isset($HOME_URL)) array_push($arr," HOME_URL = '$HOME_URL'");
        if (isset($HOME_URL_SP)) array_push($arr," HOME_URL_SP = '$HOME_URL_SP'");

        if (isset($INFO_EN)) array_push($arr," INFO_EN = '".$clsGlobal->nl2br($INFO_EN)."'");
        if (isset($INFO_SP)) array_push($arr," INFO_SP = '".$clsGlobal->nl2br($INFO_SP)."'");

        if (isset($RES_URL)) array_push($arr," RES_URL = '$RES_URL'");
        if (isset($RES_URL_SP)) array_push($arr," RES_URL_SP = '$RES_URL_SP'");

        if (isset($RES_EMAIL)) array_push($arr," RES_EMAIL = '$RES_EMAIL'");
        if (isset($AIR_EMAIL)) array_push($arr," AIR_EMAIL = '$AIR_EMAIL'");
        if (isset($ADMIN_EMAIL)) array_push($arr," ADMIN_EMAIL = '$ADMIN_EMAIL'");

        if (isset($SPA_URL_EN)) array_push($arr," SPA_URL_EN = '$SPA_URL_EN'");
        if (isset($SPA_URL_SP)) array_push($arr," SPA_URL_SP = '$SPA_URL_SP'");
        if (isset($SPA_RES_EN)) array_push($arr," SPA_RES_EN = '$SPA_RES_EN'");
        if (isset($SPA_RES_SP)) array_push($arr," SPA_RES_SP = '$SPA_RES_SP'");

        if (isset($MLIST_URL_EN)) array_push($arr," MLIST_URL_EN = '$MLIST_URL_EN'");
        if (isset($MLIST_URL_SP)) array_push($arr," MLIST_URL_SP = '$MLIST_URL_SP'");

        if (isset($EMAIL_HDR_EN)) array_push($arr," EMAIL_HDR_EN = '$EMAIL_HDR_EN'");
        if (isset($EMAIL_HDR_SP)) array_push($arr," EMAIL_HDR_SP = '$EMAIL_HDR_SP'");

        if (isset($EMAIL_CAN_EN)) array_push($arr," EMAIL_CAN_EN = '$EMAIL_CAN_EN'");
        if (isset($EMAIL_CAN_SP)) array_push($arr," EMAIL_CAN_SP = '$EMAIL_CAN_SP'");

        if (isset($EMAIL_RES_EN)) array_push($arr," EMAIL_RES_EN = '$EMAIL_RES_EN'");
        if (isset($EMAIL_RES_SP)) array_push($arr," EMAIL_RES_SP = '$EMAIL_RES_SP'");

        if (isset($EMAIL_PRESTAY_EN)) array_push($arr," EMAIL_PRESTAY_EN = '$EMAIL_PRESTAY_EN'");
        if (isset($EMAIL_PRESTAY_SP)) array_push($arr," EMAIL_PRESTAY_SP = '$EMAIL_PRESTAY_SP'");

        if (isset($EMAIL_POSTSTAY_IMG_1_EN)) array_push($arr," EMAIL_POSTSTAY_IMG_1_EN = '$EMAIL_POSTSTAY_IMG_1_EN'");
        if (isset($EMAIL_POSTSTAY_IMG_1_SP)) array_push($arr," EMAIL_POSTSTAY_IMG_1_SP = '$EMAIL_POSTSTAY_IMG_1_SP'");
        if (isset($EMAIL_POSTSTAY_IMG_2_EN)) array_push($arr," EMAIL_POSTSTAY_IMG_2_EN = '$EMAIL_POSTSTAY_IMG_2_EN'");
        if (isset($EMAIL_POSTSTAY_IMG_2_SP)) array_push($arr," EMAIL_POSTSTAY_IMG_2_SP = '$EMAIL_POSTSTAY_IMG_2_SP'");
        if (isset($EMAIL_POSTSTAY_IMG_3_EN)) array_push($arr," EMAIL_POSTSTAY_IMG_3_EN = '$EMAIL_POSTSTAY_IMG_3_EN'");
        if (isset($EMAIL_POSTSTAY_IMG_3_SP)) array_push($arr," EMAIL_POSTSTAY_IMG_3_SP = '$EMAIL_POSTSTAY_IMG_3_SP'");
        if (isset($EMAIL_POSTSTAY_EN)) array_push($arr," EMAIL_POSTSTAY_EN = '$EMAIL_POSTSTAY_EN'");
        if (isset($EMAIL_POSTSTAY_SP)) array_push($arr," EMAIL_POSTSTAY_SP = '$EMAIL_POSTSTAY_SP'");
        if (isset($EMAIL_POSTSTAY_WIDTH_EN)) array_push($arr," EMAIL_POSTSTAY_WIDTH_EN = '$EMAIL_POSTSTAY_WIDTH_EN'");
        if (isset($EMAIL_POSTSTAY_WIDTH_SP)) array_push($arr," EMAIL_POSTSTAY_WIDTH_SP = '$EMAIL_POSTSTAY_WIDTH_SP'");
        if (isset($EMAIL_POSTSTAY_SIGNATURE_EN)) array_push($arr," EMAIL_POSTSTAY_SIGNATURE_EN = '$EMAIL_POSTSTAY_SIGNATURE_EN'");
        if (isset($EMAIL_POSTSTAY_SIGNATURE_SP)) array_push($arr," EMAIL_POSTSTAY_SIGNATURE_SP = '$EMAIL_POSTSTAY_SIGNATURE_SP'");
        if (isset($EMAIL_POSTSTAY_PROMO_LBL_EN)) array_push($arr," EMAIL_POSTSTAY_PROMO_LBL_EN = '$EMAIL_POSTSTAY_PROMO_LBL_EN'");
        if (isset($EMAIL_POSTSTAY_PROMO_LBL_SP)) array_push($arr," EMAIL_POSTSTAY_PROMO_LBL_SP = '$EMAIL_POSTSTAY_PROMO_LBL_SP'");
        if (isset($EMAIL_POSTSTAY_PROMO_VAL_EN)) array_push($arr," EMAIL_POSTSTAY_PROMO_VAL_EN = '$EMAIL_POSTSTAY_PROMO_VAL_EN'");
        if (isset($EMAIL_POSTSTAY_PROMO_VAL_SP)) array_push($arr," EMAIL_POSTSTAY_PROMO_VAL_SP = '$EMAIL_POSTSTAY_PROMO_VAL_SP'");

        if (isset($EMAIL_REB_EN)) array_push($arr," EMAIL_REB_EN = '$EMAIL_REB_EN'");
        if (isset($EMAIL_REB_SP)) array_push($arr," EMAIL_REB_SP = '$EMAIL_REB_SP'");

        if (isset($EMAIL_ARR_EN)) array_push($arr," EMAIL_ARR_EN = '$EMAIL_ARR_EN'");
        if (isset($EMAIL_ARR_SP)) array_push($arr," EMAIL_ARR_SP = '$EMAIL_ARR_SP'");
        
        if (isset($EMAIL_AIR_EN)) array_push($arr," EMAIL_AIR_EN = '$EMAIL_AIR_EN'");
        if (isset($EMAIL_AIR_SP)) array_push($arr," EMAIL_AIR_SP = '$EMAIL_AIR_SP'");

        if (isset($EMAIL_CCDETAILS_EN)) array_push($arr," EMAIL_CCDETAILS_EN = '$EMAIL_CCDETAILS_EN'");
        if (isset($EMAIL_CCDETAILS_SP)) array_push($arr," EMAIL_CCDETAILS_SP = '$EMAIL_CCDETAILS_SP'");

        $query = "UPDATE PROPERTIES SET ".join(", ",$arr)." WHERE ID='$PROP_ID'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result != 1) { 
            print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n$query\n\n</p>";
        } else {
            $this->saveMarkups($db, $arg);
            if (isset($DELETE_UPS)) {
                $clsUploads->deleteByIds($db, $arg);
            }
            if (isset($PROP_IMAGES_ORDER_CURRENT)&&isset($PROP_IMAGES_ORDER)&&$PROP_IMAGES_ORDER_CURRENT!=$PROP_IMAGES_ORDER) {
                $arg['IMAGES_ORDER'] = $PROP_IMAGES_ORDER;
                $clsUploads->saveOrder($db, $arg);
            }
        }

        return $result;
    }

    function getMarkups($db, $arg) {
        global $PROP_ID;
        extract($arg);
     
        $query = "SELECT * FROM MARKUPS WHERE PROP_ID='{$PROP_ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $RSET = dbQuery($db, $arg);

        if (isset($asArray) && $asArray) {
            $result = array();
            while ($row = $db->fetch_array($RSET['rSet'])) {
                $result[$row['YEAR']] = $row;
            }
        } else {
            $result = $RSET;
        }
        return $result;
    }

    function saveMarkups($db, $arg) {
        foreach ($arg as $KEY => $VALUE) {
            if (strpos($KEY,"MARKUP_")===false) {} else {
                $arr = array(
                    'PROP_ID'=>$arg['PROP_ID'],
                    'YEAR' => str_replace("MARKUP_","",$KEY),
                    'MARKUP' => $VALUE
                );
                $this->saveMarkup($db, $arr);
            }
        }
    }

    function saveMarkup($db, $arg) {
        extract($arg);

        if ((int)$PROP_ID!=0) {
            $result = $this->getMarkupByYear($db, $arg);
        } else $result['iCount'] = 0;

        if ( $result['iCount'] == 0 ) {
            $result = $this->addMarkupNew($db, $arg);
        } else {
            $result = $this->modifyMarkup($db, $arg);
        }

        return $result;
    }

    function getMarkupByYear($db, $arg) {
        global $PROP_ID;
        extract($arg);
     
        $query = "SELECT * FROM MARKUPS WHERE PROP_ID='{$PROP_ID}' AND YEAR='{$YEAR}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);

        if (isset($asArray) && $asArray) {
            $result = $db->fetch_array($result['rSet']);
        }
        return $result;
    }

    function addMarkupNew($db, $arg) {
        global $PROP_ID;
        extract($arg);

        $query = "INSERT INTO MARKUPS ( PROP_ID, YEAR, MARKUP, UPDATED_BY ) VALUES ( '{$PROP_ID}', '{$YEAR}', '{$MARKUP}', '{$_SESSION['AUTHENTICATION']['ID']}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);
    }


    function modifyMarkup($db, $arg) {
        global $PROP_ID;
        global $clsUploads;
        extract($arg);

        $arr = array();

        //print "<pre>";print_r($arg);print "</pre>";

        if (isset($MARKUP)) array_push($arr," MARKUP = '$MARKUP'");
        if (isset($UPDATED_BY)) array_push($arr," UPDATED_BY = '{$_SESSION['AUTHENTICATION']['ID']}'");

        $query = "UPDATE MARKUPS SET ".join(", ",$arr)." WHERE PROP_ID='$PROP_ID' AND YEAR='{$YEAR}'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result != 1) { 
            print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n$query\n\n</p>";
        } else {
            if (isset($DELETE_UPS)) {
                $clsUploads->deleteByIds($db, $arg);
            }
            if (isset($PROP_IMAGES_ORDER_CURRENT)&&isset($PROP_IMAGES_ORDER)&&$PROP_IMAGES_ORDER_CURRENT!=$PROP_IMAGES_ORDER) {
                $arg['IMAGES_ORDER'] = $PROP_IMAGES_ORDER;
                $clsUploads->saveOrder($db, $arg);
            }
        }

        return $result;
    }
    function getLimitmprice($db) {
        $query = "SELECT ID,LIMIT_MPRICE FROM PROPERTIES";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $RSET = dbQuery($db, $arg);
        $result = array();
        while ($row = $db->fetch_array($RSET['rSet'])) {
            $result[$row['ID']] = $row['LIMIT_MPRICE'];
        }
        return $result;
    }

    function getBlockedIPs($db) {
        $query = "SELECT ID,CODE,BLOCK_IP FROM PROPERTIES";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $RSET = dbQuery($db, $arg);
        $result = array();
        while ($row = $db->fetch_array($RSET['rSet'])) {
            $BLOCKED_IPs = str_replace(array(" "),array(","),$row['BLOCK_IP']);
            $BLOCKED_IPs = explode(",",$BLOCKED_IPs);
            $result[$row['ID']] = $BLOCKED_IPs;
        }
        return $result;
    }

    function getCurrency($db) {
      //$json = file_get_contents("http://excellence-resorts.com/ibe/index.php?PAGE_CODE=ws.getCurrency");
      
      $query = "SELECT * FROM CURRENCY ORDER BY DATE DESC LIMIT 6";
      //print "<p class='s_notice top_msg'>$query</p>";
      $RSET = dbQuery($db, array('query' => $query));
      $CURRENCY = array();
      while ($row = $db->fetch_array($RSET['rSet'])) {
        $CURRENCY[$row['CODE']] = (double)$row['QUOTE'];
      }

      return $CURRENCY;
    }

    function updateCurrency($db, $args) {
        extract($args);

        $TODAY = date("Y-m-d");
        if (isset($useAPI) && $useAPI=="Yes") {
            $query = "SELECT * FROM CURRENCY WHERE `DATE` = '{$TODAY}'";
            //print "<p class='s_notice top_msg'>$query</p>";
            $RSET = dbQuery($db, array('query' => $query));
            $result = array();
            if ($RSET['iCount']==0) {
              $API = "http://www.apilayer.net/api/live?access_key=0f67e23962d218cebd793bd4bde8ab90&currencies=CAD,AUD,GBP,EUR,MXN,BRL";
              $JSON = file_get_contents($API);
              $ARR = json_decode($JSON, true);
              if (isset($ARR['quotes'])) {
                foreach ($ARR['quotes'] as $CODE => $QUOTE) {
                    $result[$CODE] = $QUOTE;
                    $query = "INSERT INTO CURRENCY (CODE, QUOTE, DATE) VALUES ( '{$CODE}', '{$QUOTE}', '{$TODAY}' )";
                    dbExecute($db, array('query' => $query));
                }
              }
            } else {
              while ($row = $db->fetch_array($RSET['rSet'])) {
                  $result[$row['CODE']] = (double)$row['QUOTE'];
              }
            }
            return $result;
        } else {
            $json = file_get_contents("http://excellence-resorts.com/ibe/index.php?PAGE_CODE=ws.getCurrency");
            $arr = json_decode($json, true);
            foreach ($arr as $CODE => $QUOTE) {
                $query = "INSERT INTO CURRENCY (CODE, QUOTE, DATE) VALUES ( '{$CODE}', '{$QUOTE}', '{$TODAY}' )";
                dbExecute($db, array('query' => $query));
            }
            return $arr;
        }

    }

}
global $clsSetup;
$clsSetup = new setup;


class markups {

    function getById($db, $arg) {
        global $PROP_ID;
        extract($arg);
     
        $query = "SELECT MARKUPS.*, MARKUPS_MONTHS.MONTH, MARKUPS_MONTHS.MARKUP AS MONTHLY FROM MARKUPS JOIN MARKUPS_MONTHS ON MARKUPS.ID = MARKUPS_MONTHS.MARKUP_ID WHERE MARKUPS.ID='{$MARKUP_ID}' ORDER BY MARKUPS.YEAR, MARKUPS_MONTHS.MONTH";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function save($db, $arg) {
        extract($arg);

        //print "<pre>";print_r($arg);print "</pre>";

        $query = "DELETE FROM MARKUPS_MONTHS WHERE MARKUP_ID='$MARKUP_ID'";
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbExecute($db, array('query' => $query));

        for ($MONTH=1; $MONTH<=12; ++$MONTH) {
          $MARKUP = $arg["MARKUP_$MONTH"];
          $query = "INSERT INTO MARKUPS_MONTHS (MARKUP_ID, MONTH, MARKUP) VALUES ($MARKUP_ID, $MONTH, $MARKUP)";
          //print "<p class='s_notice top_msg'>$query</p>";
          $result = dbExecute($db, array('query' => $query));
        }
    }

    function getByProperty($db, $arg) {
        extract($arg);
     
        $query = "SELECT * FROM MARKUPS WHERE PROP_ID='{$PROP_ID}' ORDER BY MARKUPS.YEAR";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getByYear($db, $arg) {
        extract($arg);
     
        $query = "SELECT * FROM MARKUPS WHERE PROP_ID='{$PROP_ID}' ORDER BY MARKUPS.YEAR";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getByYearMonth($db, $arg) {
        extract($arg);
     
        $query = "SELECT MARKUPS.*, MARKUPS_MONTHS.MONTH, MARKUPS_MONTHS.MARKUP AS MONTHLY FROM MARKUPS JOIN MARKUPS_MONTHS ON MARKUPS.ID = MARKUPS_MONTHS.MARKUP_ID WHERE MARKUPS.PROP_ID='{$PROP_ID}' AND MARKUPS.YEAR='{$YEAR}' AND MARKUPS_MONTHS.`MONTH`='{$MONTH}'";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

}
global $clsMarkups;
$clsMarkups = new markups;

?>