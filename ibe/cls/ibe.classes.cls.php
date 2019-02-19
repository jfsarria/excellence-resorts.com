<?
/*
 * Revised: Oct 07, 2011
 *          Oct 04, 2016
 */

class classes {

    var $showQry = false;

    function save($db, $arg) {
        extract($arg);

        if ((int)$CLASS_ID!=0) {
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
     
        $query = "SELECT * FROM CLASSES WHERE ID='{$CLASS_ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getByKey($db, $arg) {
        extract($arg);

        $query = "SELECT * FROM CLASSES WHERE {$WHERE}";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function addNew($db, $arg) {
        extract($arg);

        $query = "INSERT INTO CLASSES ( ID, UPDATED_BY ) VALUES ( '{$CLASS_ID}', '{$_SESSION['AUTHENTICATION']['ID']}' )";
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
        if (isset($NAME_EN)) array_push($arr," NAME_EN = '$NAME_EN'");
        if (isset($NAME_SP)) array_push($arr," NAME_SP = '$NAME_SP'");
        if (isset($REFERENCE)) array_push($arr," REFERENCE = '$REFERENCE'");
        if (isset($YEAR)) array_push($arr," YEAR = '$YEAR'");
        if (isset($MARKUP)) array_push($arr," MARKUP = '$MARKUP'");
        if (isset($ROOM_ID)) array_push($arr," ROOM_ID = '$ROOM_ID'");
        if (isset($RATE_PER_RP)) array_push($arr," RATE_PER_RP = '$RATE_PER_RP'");
        if (isset($DESCR_EN)) array_push($arr," DESCR_EN = '$DESCR_EN'");
        if (isset($DESCR_SP)) array_push($arr," DESCR_SP = '$DESCR_SP'");
        if (isset($TA_AMENITIES_EN)) array_push($arr," TA_AMENITIES_EN = '$TA_AMENITIES_EN'");

        /* CHECKBOXES */
        array_push($arr," IS_ACTIVE = '".(isset($IS_ACTIVE)?$IS_ACTIVE:"0")."'");
        array_push($arr," IS_ARCHIVE = '".(isset($IS_ARCHIVE)?$IS_ARCHIVE:"0")."'");


        $query = "UPDATE CLASSES SET ".join(", ",$arr)." WHERE ID='$CLASS_ID'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result != 1) { 
            print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n$query\n\n</p>";
        } else {
            // Save RelationShips
            $this->saveSeasons($db, $arg);
            $this->saveUserTypes($db, $arg);
            $this->saveCountries($db, $arg);
            $this->saveBlackout($db, $arg);
        }

        return $result;
    }

    function getByProperty($db, $arg) {
        extract($arg);
        global $_IBE_LANG;

        $WHERE = (isset($YEAR) && (int)$YEAR!=0) ? " AND CLASSES.YEAR='$YEAR'" : "";
     
        $query = "
            SELECT 
                CLASSES.*, 
                ROOMS.NAME_EN AS ROOM_NAME_EN
            FROM 
                CLASSES 
            LEFT JOIN 
                ROOMS ON CLASSES.ROOM_ID = ROOMS.ID
            WHERE 
                CLASSES.PROP_ID='{$PROP_ID}' {$WHERE} 
            ORDER BY `YEAR` DESC, `NAME_{$_IBE_LANG}`
        ";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getByFilters($db, $arg) {
        extract($arg);
        global $_IBE_LANG;
        $WHERE = (isset($WHERE)) ? $WHERE : "";
        $WHERE .= (isset($YEAR) && (int)$YEAR!=0) ? " AND CLASSES.YEAR='$YEAR'" : "";
        if (isset($GEOS)&&$GEOS!="") {
            $arr = array();
            $GEO = explode(",",$GEOS);
            foreach ($GEO as $i=>$CODE) array_push($arr, " GEO_CODE = '$CODE' OR GEO_GROUP = '$CODE' ");
            $WHERE .= " AND (".implode(" OR ",$arr).")";
        }
        if (isset($SEASON)&&$SEASON!="") $WHERE .= " AND SEASON_ID='{$SEASON}'";
        if (isset($ROOM)&&$ROOM!="") $WHERE .= " AND ROOM_ID='{$ROOM}'";
     
        $query = "
            SELECT DISTINCT
                CLASSES.ID, CLASSES.NAME_EN, CLASSES.NAME_SP, CLASSES.YEAR, CLASSES.REFERENCE, CLASSES.RATE_PER_RP, CLASSES.PROP_ID, 
                ROOMS.NAME_EN AS ROOM_NAME_EN
            FROM 
                V_CLASSES_GEO_SEASON AS CLASSES
            LEFT JOIN 
                ROOMS ON CLASSES.ROOM_ID = ROOMS.ID
            WHERE 
                CLASSES.PROP_ID='{$PROP_ID}' {$WHERE} 
        ";

        if (isset($sortBy) && $sortBy != "") $query .= " ORDER BY $sortBy";
        if (isset($startItem) && isset($itemsPerPage)) $query .= " LIMIT $startItem, $itemsPerPage";

        $arg = array('query' => $query);

        //if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getSeasons($db, $arg) {
        extract($arg);

        $query = "
            SELECT * FROM V_CLASS_SEASON
            WHERE CLASS_ID='{$CLASS_ID}'
            ORDER BY `SEASON_FROM`,`SEASON_TO`
        ";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);

        if (isset($AS_ARRAY)&&$AS_ARRAY) {
            $array = array();
            while ($row = $db->fetch_array($result['rSet'])) {
                $array[$row['SEASON_ID']] = $row;
            }
            $result = $array;
        } 
        return $result;
    }

    function getClassSeasonBySeasonId($db, $arg) {
        extract($arg);
        $query = "SELECT * FROM CLASS_SEASON WHERE SEASON_ID = '$SEASON_ID'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function deleteClassSeasonBySeasonId($db, $arg) {
        extract($arg);
        $query = "DELETE FROM CLASS_SEASON WHERE SEASON_ID = '$SEASON_ID'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbExecute($db, $arg);
        return $result;
    }

    function saveSeasons($db, $arg) {
        extract($arg);
        if (!isset($SEASON_ID)) return false;

        $arg['AS_ARRAY'] = true;
        $SEASONS = $this->getSeasons($db, $arg);
        //print "Saved <pre>";print_r($SEASONS);print "</pre>";
        //print "New <pre>";print_r($SEASON_ID);print "</pre>";
        foreach ($SEASON_ID as $SID) {
            if (!array_key_exists($SID,$SEASONS)) {
                $this->addSeasons($db, array("CLASS_ID"=>$CLASS_ID,"SEASON_ID"=>$SID));
            }
        }
        foreach ($SEASONS as $SID => $ARR) {
            if (!in_array($SID,$SEASON_ID)) {
                $this->remSeasons($db, array("CLASS_ID"=>$CLASS_ID,"SEASON_ID"=>$SID));
            }
        }
    }
    function addSeasons($db, $arg) {
        extract($arg);
        $query = "INSERT INTO CLASS_SEASON ( CLASS_ID, SEASON_ID ) VALUES ( '{$CLASS_ID}', '{$SEASON_ID}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        dbExecute($db, $arr);
    }
    function remSeasons($db, $arg) {
        extract($arg);
        $query = "DELETE FROM CLASS_SEASON WHERE CLASS_ID='{$CLASS_ID}' AND SEASON_ID='{$SEASON_ID}'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        dbExecute($db, $arr);
    }

    
    function getUserTypes($db, $arg) {
        extract($arg);
        global $_IBE_LANG;

        $query = "SELECT * FROM CLASS_USERTYPE WHERE CLASS_ID='{$CLASS_ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);

        if (isset($AS_ARRAY)&&$AS_ARRAY) {
            $array = array();
            while ($row = $db->fetch_array($result['rSet'])) {
                $array[$row['USERTYPE_ID']] = $row;
            }
            $result = $array;
        } 
        return $result;
    }
    function saveUserTypes($db, $arg) {
        extract($arg);
        if (!isset($USERTYPE_ID)) return false;

        $arg['AS_ARRAY'] = true;
        $USERTYPES = $this->getUserTypes($db, $arg);
        //print "Saved <pre>";print_r($USERTYPES);print "</pre>";
        //print "New <pre>";print_r($USERTYPE_ID);print "</pre>";
        foreach ($USERTYPE_ID as $SID) {
            if (!array_key_exists($SID,$USERTYPES)) {
                $this->addUserTypes($db, array("CLASS_ID"=>$CLASS_ID,"USERTYPE_ID"=>$SID));
            }
        }
        foreach ($USERTYPES as $SID => $ARR) {
            if (!in_array($SID,$USERTYPE_ID)) {
                $this->remUserTypes($db, array("CLASS_ID"=>$CLASS_ID,"USERTYPE_ID"=>$SID));
            }
        }
    }
    function addUserTypes($db, $arg) {
        extract($arg);
        $query = "INSERT INTO CLASS_USERTYPE ( CLASS_ID, USERTYPE_ID ) VALUES ( '{$CLASS_ID}', '{$USERTYPE_ID}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        dbExecute($db, $arr);
    }
    function remUserTypes($db, $arg) {
        extract($arg);
        $query = "DELETE FROM CLASS_USERTYPE WHERE CLASS_ID='{$CLASS_ID}' AND USERTYPE_ID='{$USERTYPE_ID}'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        dbExecute($db, $arr);
    }

    function getCountries($db, $arg) {
        extract($arg);
        global $_IBE_LANG;

        $query = "SELECT * FROM CLASS_COUNTRY WHERE CLASS_ID='{$CLASS_ID}'";
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
                $this->addCountries($db, array("CLASS_ID"=>$CLASS_ID,"COUNTRY_CODE"=>$SID));
            }
        }
        foreach ($COUNTRIES as $SID => $ARR) {
            if (!in_array($SID,$COUNTRY_CODE)) {
                $this->remCountries($db, array("CLASS_ID"=>$CLASS_ID,"COUNTRY_CODE"=>$SID));
            }
        }
    }
    function addCountries($db, $arg) {
        extract($arg);
        $query = "INSERT INTO CLASS_COUNTRY ( CLASS_ID, COUNTRY_CODE ) VALUES ( '{$CLASS_ID}', '{$COUNTRY_CODE}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        dbExecute($db, $arr);
    }
    function remCountries($db, $arg) {
        extract($arg);
        $query = "DELETE FROM CLASS_COUNTRY WHERE CLASS_ID='{$CLASS_ID}' AND COUNTRY_CODE='{$COUNTRY_CODE}'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        dbExecute($db, $arr);
    }

    function getBlackout($db, $arg) {
        extract($arg);
        global $_IBE_LANG;

        $query = "SELECT * FROM CLASS_BLACKOUT WHERE CLASS_ID='{$CLASS_ID}' ORDER BY DATE_CLOSED";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);

        if (isset($AS_ARRAY)&&$AS_ARRAY) {
            $array = array();
            while ($row = $db->fetch_array($result['rSet'])) {
                $array[$row['DATE_CLOSED']] = $row;
            }
            $result = $array;
        } 
        return $result;
    }
    function saveBlackout($db, $arg) {
        extract($arg);
        if (isset($BLACKOUT_CUR)) {
            $arg['AS_ARRAY'] = true;
            $DATES = $this->getBlackout($db, $arg);
            // print "->".$BLACKOUT_NEW."<BR>".$BLACKOUT_CUR;
            $a1 = explode(",",$BLACKOUT_CUR);
            $a2 = explode(",",$BLACKOUT_NEW);
            $DATE_CLOSED = array_merge($a1,$a2);
            //print "DATE_CLOSED <pre>";print_r($DATE_CLOSED);print "</pre>";
            //print "Saved <pre>";print_r($DATES);print "</pre>";
            //print "New <pre>";print_r($DATE_CLOSED);print "</pre>";
            foreach ($DATE_CLOSED as $SID) {
                if (!array_key_exists($SID,$DATES) && !empty($SID)) {
                    $this->addBlackout($db, array("CLASS_ID"=>$CLASS_ID,"DATE_CLOSED"=>$SID));
                }
            }
            foreach ($DATES as $SID => $ARR) {
                if (!in_array($SID,$DATE_CLOSED) && !empty($SID)) {
                    $this->remBlackout($db, array("CLASS_ID"=>$CLASS_ID,"DATE_CLOSED"=>$SID));
                }
            }
            if (!isset($BLACKOUT_DEL)) $BLACKOUT_DEL = array();
            foreach ($BLACKOUT_DEL as $key => $SID) {
                $this->remBlackout($db, array("CLASS_ID"=>$CLASS_ID,"DATE_CLOSED"=>$SID));
            }
        }
    }
    function addBlackout($db, $arg) {
        extract($arg);
        $query = "INSERT INTO CLASS_BLACKOUT ( CLASS_ID, DATE_CLOSED ) VALUES ( '{$CLASS_ID}', '{$DATE_CLOSED}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        dbExecute($db, $arr);
    }
    function remBlackout($db, $arg) {
        extract($arg);
        $query = "DELETE FROM CLASS_BLACKOUT WHERE CLASS_ID='{$CLASS_ID}' AND DATE_CLOSED='{$DATE_CLOSED}'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        dbExecute($db, $arr);
    }
}
global $clsClasses;
$clsClasses = new classes;
?>