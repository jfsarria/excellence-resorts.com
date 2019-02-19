<?
/*
 * Revised: Jun 10, 2015
 */

class Banners {

    var $showQry = false;

    function save($db, $arg) {
        extract($arg);

        if ((int)$BANNER_ID!=0) {
            $result = $this->getById($db, $arg);
        } else $result['iCount'] = 0;

        if ( $result['iCount'] == 0 ) {
            $result = $this->addNew($db, $arg);
        } else {
            $result = $this->modify($db, $arg);
        }

        return $result;
    }

    function getById($db, $arg) {
        extract($arg);
     
        $query = "SELECT * FROM BANNERS WHERE ID='{$BANNER_ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function addNew($db, $arg) {
        extract($arg);

        $query = "INSERT INTO BANNERS ( ID, UPDATED_BY ) VALUES ( '{$BANNER_ID}', '{$_SESSION['AUTHENTICATION']['ID']}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $this->modify($db, $arg);
    }
    /*
    function PublishURLs($PUBLISH_URLS) {
        $arr = explode("\r\n",$PUBLISH_URLS);
        return "*".implode("*",$arr);
    }

    function PublishURLsBack($PUBLISH_URLS) {
        $arr = explode("*",$PUBLISH_URLS);
        array_shift($arr);
        return implode("\r\n",$arr);
    }
    */
    function modify($db, $arg) {
        extract($arg);
        global $clsUploads;
        $arr = array();

        //print "<pre>";print_r($arg);print "</pre>";

        //$PUBLISH_URLS = isset($PUBLISH_URLS) ? str_replace("\r\n","",$PUBLISH_URLS)."\r\n" : "";

        if (isset($PROP_ID)) array_push($arr," PROP_ID = '$PROP_ID'");
        if (isset($NAME_EN)) array_push($arr," NAME_EN = '$NAME_EN'");
        if (isset($NAME_SP)) array_push($arr," NAME_SP = '$NAME_SP'");
        if (isset($RTEXT_EN)) array_push($arr," RTEXT_EN = '$RTEXT_EN'");
        if (isset($RTEXT_SP)) array_push($arr," RTEXT_SP = '$RTEXT_SP'");
        if (isset($RLABEL_EN)) array_push($arr," RLABEL_EN = '$RLABEL_EN'");
        if (isset($RLABEL_SP)) array_push($arr," RLABEL_SP = '$RLABEL_SP'");
        if (isset($CONDITIONS_EN)) array_push($arr," CONDITIONS_EN = '$CONDITIONS_EN'");
        if (isset($CONDITIONS_SP)) array_push($arr," CONDITIONS_SP = '$CONDITIONS_SP'");
        if (isset($PUBLISH_URLS)) array_push($arr," PUBLISH_URLS = '$PUBLISH_URLS'");
        if (isset($FONT_COLOR)) array_push($arr," FONT_COLOR = '$FONT_COLOR'");
        if (isset($BG_COLOR)) array_push($arr," BG_COLOR = '$BG_COLOR'");
        if (isset($HTML)) array_push($arr," HTML = '$HTML'");

        /* CHECKBOXES */
        array_push($arr," IS_ACTIVE = '".(isset($IS_ACTIVE)?$IS_ACTIVE:"0")."'");

        $query = "UPDATE BANNERS SET ".join(", ",$arr)." WHERE ID='$BANNER_ID'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result != 1) { 
            print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n$query\n\n</p>";
        } else {
            $this->saveCountries($db, $arg);
            if (isset($DELETE_UPS)) {
                $clsUploads->deleteByIds($db, $arg);
            }
            if (isset($BANNER_IMAGES_EN_ORDER_CURRENT)&&isset($BANNER_IMAGES_EN_ORDER)&&$BANNER_IMAGES_EN_ORDER_CURRENT!=$BANNER_IMAGES_EN_ORDER) {
                $arg['IMAGES_EN_ORDER'] = $BANNER_IMAGES_EN_ORDER;
                $clsUploads->saveOrder($db, $arg);
            }
            if (isset($BANNER_IMAGES_SP_ORDER_CURRENT)&&isset($BANNER_IMAGES_SP_ORDER)&&$BANNER_IMAGES_SP_ORDER_CURRENT!=$BANNER_IMAGES_SP_ORDER) {
                $arg['IMAGES_SP_ORDER'] = $BANNER_IMAGES_SP_ORDER;
                $clsUploads->saveOrder($db, $arg);
            }
        }

        return $result;
    }

    function getByProperty($db, $arg) {
        extract($arg);
        global $_IBE_LANG;
            
        $WHERE = isset($WHERE) ? $WHERE : "";
     
        $query = "SELECT * FROM BANNERS WHERE PROP_ID='{$PROP_ID}' {$WHERE} ORDER BY NAME_{$_IBE_LANG} ";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function remove($db, $arg) {
        extract($arg);
        global $clsUploads;
        global $clsClasses;

        $DELETE_UPS = array();
        $RSET = $clsUploads->getByParent($db, array("PARENT_ID"=>$DELETE_ID));
        while ($row = $db->fetch_array($RSET['rSet'])) array_push($DELETE_UPS,$row['ID']);
        if (count($DELETE_UPS)!=0) $clsUploads->deleteByIds($db, array("DELETE_UPS"=>$DELETE_UPS,"UPS_FOLDER"=>"/ibe/ups/banners/"));
        $result = dbExecute($db, array('query' => "DELETE FROM BANNERS WHERE ID='{$DELETE_ID}'"));

        return $result;
    }

    function getCountries($db, $arg) {
        extract($arg);
        global $_IBE_LANG;

        $query = "SELECT * FROM BANNER_COUNTRY WHERE BANNER_ID='{$BANNER_ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);

        if (isset($AS_ARRAY)&&$AS_ARRAY) {
            $array = array();
            while ($row = $db->fetch_array($result['rSet'])) {
                $array[$row['COUNTRY_CODE']] = $row;
            }
            $result = $array;
        } 
        return $result;
    }

    function saveCountries($db, $arg) {
        extract($arg);
        if (!isset($COUNTRY_CODE)) return false;

        $arg['AS_ARRAY'] = true;
        $COUNTRIES = $this->getCountries($db, $arg);
        //print "Saved <pre>";print_r($COUNTRIES);print "</pre>";
        //print "New <pre>";print_r($COUNTRY_CODE);print "</pre>";
        foreach ($COUNTRY_CODE as $SID) {
            if (!array_key_exists($SID,$COUNTRIES)) {
                $this->addCountries($db, array("BANNER_ID"=>$BANNER_ID,"COUNTRY_CODE"=>$SID));
            }
        }
        foreach ($COUNTRIES as $SID => $ARR) {
            if (!in_array($SID,$COUNTRY_CODE)) {
                $this->remCountries($db, array("BANNER_ID"=>$BANNER_ID,"COUNTRY_CODE"=>$SID));
            }
        }
    }

    function addCountries($db, $arg) {
        extract($arg);
        $query = "INSERT INTO BANNER_COUNTRY ( BANNER_ID, COUNTRY_CODE ) VALUES ( '{$BANNER_ID}', '{$COUNTRY_CODE}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        dbExecute($db, $arr);
    }

    function remCountries($db, $arg) {
        extract($arg);
        $query = "DELETE FROM BANNER_COUNTRY WHERE BANNER_ID='{$BANNER_ID}' AND COUNTRY_CODE='{$COUNTRY_CODE}'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        dbExecute($db, $arr);
    }

    function getWebBanner($db, $arg) {
        extract($arg);

        $output = array();

        $query = "
            SELECT *
            FROM `BANNERS` 
            JOIN BANNER_COUNTRY ON BANNERS.ID = BANNER_COUNTRY.BANNER_ID
            WHERE IS_ACTIVE = 1
            AND PROP_ID = $PROP_ID
            AND COUNTRY_CODE = '$COUNTRY_CODE'
        ";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);

        if ($result['iCount']!=0) {
          /*
          $row = $db->fetch_array($result['rSet']);
          foreach ($row as $key => $value) {
            if (is_string($key)) {
              $output[$key] = $value;
            }
          }
          */
          while ($row = $db->fetch_array($result['rSet'])) {
            $output[] = $row;
          }
        }

        return $output;
    
    }
}
global $clsBanners;
$clsBanners = new Banners;
?>