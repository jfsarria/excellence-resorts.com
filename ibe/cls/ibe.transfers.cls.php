<?
/*
 * Revised: Feb 04, 2014
 *          Apr 24, 2016
 */

class transfers {

    var $showQry = false;

    function saveSetUp($db, $arg) {
        extract($arg);

        if ((int)$PROP_ID!=0) {
            $result = $this->getSetUpById($db, $arg);
        } else $result['iCount'] = 0;

        if ( $result['iCount'] == 0 ) {
            $result = $this->addNewSetUp($db, $arg);
        } else {
            $result = $this->modifySetUp($db, $arg);
        }

        return $result;
    }

    function getSetUpById($db, $arg) {
        global $PROP_ID;
        extract($arg);
        $FIELDS = isset($FIELDS) ? "ID,".$FIELDS : "*";
     
        $query = "SELECT $FIELDS FROM TRANSFER_SETUP WHERE ID='{$PROP_ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);

        if (isset($asArray) && $asArray) {
            $result = $db->fetch_array($result['rSet']);
        }
        return $result;
    }

    function getAllSetUp($db, $arg) {
        extract($arg);
        $FIELDS = isset($FIELDS) ? "ID,".$FIELDS : "*";

        $arr = array();
        $query = "SELECT $FIELDS FROM TRANSFER_SETUP";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $RSET = dbQuery($db, $arg);
        while ($row = $db->fetch_array($RSET['rSet'])) {
          foreach ($row as $key=>$value) {
            if (is_string($key) && $key!="ID") {
              $arr[$row['ID']][$key] = $value;
            }
          }
        }
        //print_r($arr);
        return json_encode($arr);
    }

    function getSetUpByCode($db, $arg) {
        global $PROP_ID;
        extract($arg);
     
        $query = "SELECT * FROM TRANSFER_SETUP WHERE CODE='{$CODE}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);

        if (isset($asArray) && $asArray) {
            $result = $db->fetch_array($result['rSet']);
        }
        return $result;
    }

    function addNewSetUp($db, $arg) {
        global $PROP_ID;
        extract($arg);

        $query = "INSERT INTO TRANSFER_SETUP ( ID, UPDATED_BY ) VALUES ( '{$PROP_ID}', '{$_SESSION['AUTHENTICATION']['ID']}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $this->modifySetUp($db, $arg);
    }


    function modifySetUp($db, $arg) {
        global $PROP_ID;
        global $clsUploads;
        extract($arg);

        $arr = array();

        //print "<pre>";print_r($arg);print "</pre>";

        if (isset($COMPANY_PHONE)) array_push($arr," COMPANY_PHONE = '$COMPANY_PHONE'");
        if (isset($COMPANY_NAME)) array_push($arr," COMPANY_NAME = '$COMPANY_NAME'");
        if (isset($COMPANY_EMAIL)) array_push($arr," COMPANY_EMAIL = '$COMPANY_EMAIL'");
        if (isset($OVERVIEW_EN)) array_push($arr," OVERVIEW_EN = '$OVERVIEW_EN'");
        if (isset($OVERVIEW_SP)) array_push($arr," OVERVIEW_SP = '$OVERVIEW_SP'");
        if (isset($CONFIRM_EN)) array_push($arr," CONFIRM_EN = '$CONFIRM_EN'");
        if (isset($CONFIRM_SP)) array_push($arr," CONFIRM_SP = '$CONFIRM_SP'");
        if (isset($CHANGE_EN)) array_push($arr," CHANGE_EN = '$CHANGE_EN'");
        if (isset($CHANGE_SP)) array_push($arr," CHANGE_SP = '$CHANGE_SP'");
        if (isset($CANCEL_EN)) array_push($arr," CANCEL_EN = '$CANCEL_EN'");
        if (isset($CANCEL_SP)) array_push($arr," CANCEL_SP = '$CANCEL_SP'");
        if (isset($REMINDER_EN)) array_push($arr," REMINDER_EN = '$REMINDER_EN'");
        if (isset($REMINDER_SP)) array_push($arr," REMINDER_SP = '$REMINDER_SP'");
        if (isset($REMINDER_EN)) array_push($arr," TRANSFERS_URL_EN = '$TRANSFERS_URL_EN'");
        if (isset($REMINDER_SP)) array_push($arr," TRANSFERS_URL_SP = '$TRANSFERS_URL_SP'");

        array_push($arr," IS_ACTIVE = '".(isset($IS_ACTIVE)?$IS_ACTIVE:"0")."'");

        $query = "UPDATE TRANSFER_SETUP SET ".join(", ",$arr)." WHERE ID='$PROP_ID'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result != 1) { 
            print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n$query\n\n</p>";
        } else {

        }

        return $result;
    }

    function saveCar($db, $arg) {
        extract($arg);

        if ((int)$CAR_ID!=0) {
            $result = $this->getCarById($db, $arg);
        } else $result['iCount'] = 0;

        if ( $result['iCount'] == 0 ) {
            $result = $this->addNewCar($db, $arg);
        } else {
            $result = $this->modifyCar($db, $arg);
        }

        return $result;
    }

    function getCarById($db, $arg) {
        extract($arg);
     
        $query = "SELECT * FROM TRANSFER_CARS WHERE ID='{$CAR_ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);

        if (isset($getName)) {
          while ($row = $db->fetch_array($result['rSet'])) {
            return $row['NAME_'.$RES_LANGUAGE];
          }        
        } else {
          return $result;
        }

        return $result;
    }

    function getCarByProp($db, $arg) {
        extract($arg);
     
        $query = "
          SELECT * FROM TRANSFER_CARS 
          WHERE PROP_ID='{$PROP_ID}' 
          AND MAX_PAX >= {$PEOPLE}
          AND IS_ACTIVE = 1
        ";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function addNewCar($db, $arg) {
        extract($arg);

        $query = "INSERT INTO TRANSFER_CARS ( ID, UPDATED_BY ) VALUES ( '{$CAR_ID}', '{$_SESSION['AUTHENTICATION']['ID']}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $this->modifyCar($db, $arg);
    }


    function modifyCar($db, $arg) {
        extract($arg);
        global $clsUploads;
        $arr = array();

        //print "<pre>";print_r($arg);print "</pre>";

        if (isset($PROP_ID)) array_push($arr," PROP_ID = '$PROP_ID'");
        if (isset($NAME_EN)) array_push($arr," NAME_EN = '$NAME_EN'");
        if (isset($NAME_SP)) array_push($arr," NAME_SP = '$NAME_SP'");
        if (isset($DESCR_EN)) array_push($arr," DESCR_EN = '$DESCR_EN'");
        if (isset($DESCR_SP)) array_push($arr," DESCR_SP = '$DESCR_SP'");
        if (isset($MAX_PAX)) array_push($arr," MAX_PAX = '$MAX_PAX'");
        if (isset($TYPE)) array_push($arr," TYPE = '$TYPE'");

        if (isset($PRICE_1_YEAR)) array_push($arr," PRICE_1_YEAR = '$PRICE_1_YEAR'");
        if (isset($PRICE_1_ONEWAY)) array_push($arr," PRICE_1_ONEWAY = '$PRICE_1_ONEWAY'");
        if (isset($PRICE_1_ROUNDT)) array_push($arr," PRICE_1_ROUNDT = '$PRICE_1_ROUNDT'");
        if (isset($PRICE_2_YEAR)) array_push($arr," PRICE_2_YEAR = '$PRICE_2_YEAR'");
        if (isset($PRICE_2_ONEWAY)) array_push($arr," PRICE_2_ONEWAY = '$PRICE_2_ONEWAY'");
        if (isset($PRICE_2_ROUNDT)) array_push($arr," PRICE_2_ROUNDT = '$PRICE_2_ROUNDT'");
        if (isset($PRICE_3_YEAR)) array_push($arr," PRICE_3_YEAR = '$PRICE_3_YEAR'");
        if (isset($PRICE_3_ONEWAY)) array_push($arr," PRICE_3_ONEWAY = '$PRICE_3_ONEWAY'");
        if (isset($PRICE_3_ROUNDT)) array_push($arr," PRICE_3_ROUNDT = '$PRICE_3_ROUNDT'");

        /* CHECKBOXES */
        array_push($arr," IS_ACTIVE = '".(isset($IS_ACTIVE)?$IS_ACTIVE:"0")."'");
        array_push($arr," IS_ARCHIVE = '".(isset($IS_ARCHIVE)?$IS_ARCHIVE:"0")."'");


        $query = "UPDATE TRANSFER_CARS SET ".join(", ",$arr)." WHERE ID='$CAR_ID'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result != 1) { 
            print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n$query\n\n</p>";
        } else {
            if (isset($DELETE_UPS)) {
                $clsUploads->deleteByIds($db, $arg);
            }
            if (isset($CAR_IMAGES_ORDER_CURRENT)&&isset($CAR_IMAGES_ORDER)&&$CAR_IMAGES_ORDER_CURRENT!=$CAR_IMAGES_ORDER) {
                $arg['IMAGES_ORDER'] = $CAR_IMAGES_ORDER;
                $clsUploads->saveOrder($db, $arg);
            }
        }

        return $result;
    }

    function getCarsByProperty($db, $arg) {
        extract($arg);
        global $_IBE_LANG;
            
        $_IBE_LANG = empty($_IBE_LANG) ? "EN" : $_IBE_LANG;
        $WEHRE = isset($WEHRE) ? $WEHRE : "";
     
        $query = "SELECT * FROM TRANSFER_CARS WHERE PROP_ID='{$PROP_ID}' {$WEHRE} ORDER BY NAME_{$_IBE_LANG} ";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function removeCar($db, $arg) {
        extract($arg);
        global $clsUploads;

        $DELETE_UPS = array();
        $RSET = $clsUploads->getByParent($db, array("PARENT_ID"=>$DELETE_ID));
        while ($row = $db->fetch_array($RSET['rSet'])) array_push($DELETE_UPS,$row['ID']);
        if (count($DELETE_UPS)!=0) $clsUploads->deleteByIds($db, array("DELETE_UPS"=>$DELETE_UPS,"UPS_FOLDER"=>"/ibe/ups/transfers/"));
        $result = dbExecute($db, array('query' => "DELETE FROM TRANSFER_CARS WHERE ID='{$DELETE_ID}'"));

        return $result;
    }

    function isActive($db, $arg) {
        extract($arg);

        $IS_ACTIVE = 0;
        $RSET = $this->getSetUpById($db, $arg);
        while ($row = $db->fetch_array($RSET['rSet'])) $IS_ACTIVE = (int)$row['IS_ACTIVE'];
        return $IS_ACTIVE;
    }
}
global $clsTransfer;
$clsTransfer = new transfers;

class transfer_seasons {

    var $showQry = false;

    function save($db, $arg) {
        extract($arg);

        if ((int)$SEASON_ID!=0) {
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
     
        $query = "SELECT * FROM TRANSFER_SEASONS WHERE ID='{$SEASON_ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function addNew($db, $arg) {
        extract($arg);

        $query = "INSERT INTO TRANSFER_SEASONS ( ID, UPDATED_BY ) VALUES ( '{$SEASON_ID}', '{$_SESSION['AUTHENTICATION']['ID']}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $this->modify($db, $arg);
    }


    function modify($db, $arg) {
        extract($arg);
        global $clsUploads;
        $arr = array();

        //print "<pre>";print_r($arg);print "</pre>";

        if (isset($PROP_ID)) array_push($arr," PROP_ID = '$PROP_ID'");
        if (isset($NAME)) array_push($arr," NAME = '$NAME'");
        if (isset($NAME)) array_push($arr," YEAR = '$YEAR'");
        if (isset($FROM)) array_push($arr," `FROM` = '{$FROM} 00:00:00'");
        if (isset($TO)) array_push($arr," `TO` = '{$TO} 23:59:59'");

        $query = "UPDATE TRANSFER_SEASONS SET ".join(", ",$arr)." WHERE ID='$SEASON_ID'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result != 1) { 
            print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n$query\n\n</p>";
        } else {
            //
        }

        return $result;
    }

    function getByProperty($db, $arg) {
        extract($arg);
     
        $WHERE = (isset($YEAR)) ? " AND YEAR='{$YEAR}'" : "";

        $query = "SELECT * FROM TRANSFER_SEASONS WHERE PROP_ID='{$PROP_ID}' $WHERE ORDER BY `YEAR` DESC, `FROM` DESC,`TO` DESC";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function deleteById($db, $arg) {
        extract($arg);
        $query = "DELETE FROM TRANSFER_SEASONS WHERE ID = '$ID'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbExecute($db, $arg);
        return $result;
    }

    function remove($db, $arg) {
        extract($arg);
        $result = $this->deleteById($db, array("ID"=>$DELETE_ID));
        return $result;
    }
}

global $clsTransferSeasons;
$clsTransferSeasons = new transfer_seasons;

class transfer_car_season {

    var $showQry = false;

    function save($db, $arg) {
        extract($arg);

        if ((int)$SEASON_ID!=0) {
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
     
        $query = "SELECT * FROM TRANSFER_CAR_SEASON WHERE ID='{$ID}'";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function addNew($db, $arg) {
        extract($arg);

        $query = "INSERT INTO TRANSFER_CAR_SEASON ( ID, UPDATED_BY ) VALUES ( '{$ID}', '{$_SESSION['AUTHENTICATION']['ID']}' )";
        //print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $this->modify($db, $arg);
    }


    function modify($db, $arg) {
        extract($arg);
        global $clsUploads;
        $arr = array();

        //print "modify:<pre>";print_r($arg);print "</pre>";

        if (isset($CAR_ID)) array_push($arr," CAR_ID = '$CAR_ID'");
        if (isset($SEASON_ID)) array_push($arr," SEASON_ID = '$SEASON_ID'");
        if (isset($PRICE_ONEWAY)) array_push($arr," PRICE_ONEWAY = '$PRICE_ONEWAY'");
        if (isset($PRICE_ROUNDT)) array_push($arr," PRICE_ROUNDT = '$PRICE_ROUNDT'");

        $query = "UPDATE TRANSFER_CAR_SEASON SET ".join(", ",$arr)." WHERE ID='$ID'";
        //print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result != 1) { 
            print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n$query\n\n</p>";
        } else {
            //
        }

        return $result;
    }

    function getByProperty($db, $arg) {
        extract($arg);
     
        $WHERE = (isset($YEAR)) ? " AND YEAR='{$YEAR}'" : "";

        $query = "
            SELECT TCS.*, TC.NAME_EN, TS.PROP_ID, TS.NAME, TS.YEAR, TS.FROM, TS.TO
            FROM TRANSFER_CAR_SEASON AS TCS
            JOIN TRANSFER_CARS AS TC ON TC.ID = TCS.CAR_ID
            JOIN TRANSFER_SEASONS AS TS ON TS.ID = TCS.SEASON_ID
            WHERE TC.PROP_ID = '{$PROP_ID}' $WHERE 
            ORDER BY `YEAR` DESC, `FROM` DESC,`TO` DESC
        ";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getPrice($db, $arg) {
        extract($arg);
     
        $query = "
            SELECT TCS.*, TC.NAME_EN, TS.PROP_ID, TS.NAME, TS.YEAR, TS.FROM, TS.TO 
            FROM TRANSFER_CAR_SEASON AS TCS 
            JOIN TRANSFER_CARS AS TC ON TC.ID = TCS.CAR_ID 
            JOIN TRANSFER_SEASONS AS TS ON TS.ID = TCS.SEASON_ID 
            WHERE TC.PROP_ID = '{$PROP_ID}'
            AND CAR_ID = '{$CAR_ID}'
            AND TS.FROM <= '{$CHECK_IN}' AND TS.TO >= '{$CHECK_IN}'
            ORDER BY `YEAR` DESC, `FROM` DESC,`TO` DESC 
            LIMIT 1
        ";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
				//mail("jaunsarria@gmail.com","transfer",$query);
        $result = dbQuery($db, $arg);
        return $result;
    }

    function deleteById($db, $arg) {
        extract($arg);
        $query = "DELETE FROM TRANSFER_CAR_SEASON WHERE ID = '$ID'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbExecute($db, $arg);
        return $result;
    }

    function remove($db, $arg) {
        extract($arg);
        $result = $this->deleteById($db, array("ID"=>$DELETE_ID));
        return $result;
    }
}

global $clsTransferCarSeason;
$clsTransferCarSeason = new transfer_car_season;
