<?
/*
 * Revised: Nov 29, 2011
 */

class specials {

    var $showQry = false;

    function save($db, $arg) {
        extract($arg);

        if ((int)$SPECIAL_ID!=0) {
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
     
        $query = "SELECT * FROM SPECIALS WHERE ID='{$SPECIAL_ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function addNew($db, $arg) {
        extract($arg);

        $query = "INSERT INTO SPECIALS ( ID, UPDATED_BY ) VALUES ( '{$SPECIAL_ID}', '{$_SESSION['AUTHENTICATION']['ID']}' )";
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
        if (isset($NAME_SP)) array_push($arr," NAME_SP = '$NAME_SP'");
        if (isset($REFERENCE)) array_push($arr," REFERENCE = '$REFERENCE'");
        if (isset($YEARS)) array_push($arr," YEARS = '".implode(",",$YEARS)."'");
        if (isset($GEOS)) array_push($arr," GEOS = '".implode(",",$GEOS)."'");
        if (isset($TYPE)) array_push($arr," TYPE = '$TYPE'");
        if (isset($OFF)) array_push($arr," OFF = '$OFF'");
        if (isset($ACCESS_CODE)) array_push($arr," ACCESS_CODE = '$ACCESS_CODE'");
        if (isset($DESCR_EN)) array_push($arr," DESCR_EN = '$DESCR_EN'");
        if (isset($DESCR_SP)) array_push($arr," DESCR_SP = '$DESCR_SP'");
        if (isset($BOOK_FROM)) array_push($arr," BOOK_FROM = '$BOOK_FROM'");
        if (isset($BOOK_TO)) array_push($arr," BOOK_TO = '$BOOK_TO'");
        if (isset($TRAVEL_FROM)) array_push($arr," TRAVEL_FROM = '$TRAVEL_FROM'");
        if (isset($TRAVEL_TO)) array_push($arr," TRAVEL_TO = '$TRAVEL_TO'");

        /* CHECKBOXES */
        array_push($arr," IS_ACTIVE = '".(isset($IS_ACTIVE)?$IS_ACTIVE:"0")."'");
        array_push($arr," IS_ARCHIVE = '".(isset($IS_ARCHIVE)?$IS_ARCHIVE:"0")."'");
        array_push($arr," IS_PRIVATE = '".(isset($IS_PRIVATE)?$IS_PRIVATE:"0")."'");
        array_push($arr," IS_GEO = '".(isset($IS_GEO)?$IS_GEO:"0")."'");

        $query = "UPDATE SPECIALS SET ".join(", ",$arr)." WHERE ID='$SPECIAL_ID'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result != 1) { 
            print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n$query\n\n</p>";
        } else {
            // Save RelationShips
            $this->saveClasses($db, $arg);
            $this->saveStates($db, $arg);
            $this->saveClosed($db, $arg);
            $this->saveBlackout($db, $arg);
        }

        return $result;
    }

    function getByProperty($db, $arg) {
        extract($arg);
        global $_IBE_LANG;

        //print "<pre>";print_r($arg);print "</pre>";

        $WEHRE = isset($WEHRE) ? $WEHRE : "";
     
        $query = "SELECT * FROM SPECIALS WHERE PROP_ID='{$PROP_ID}' {$WEHRE} ORDER BY `NAME_{$_IBE_LANG}`";
        $arg = array('query' => $query);
        //if ($this->showQry) 
            //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getTypes($db, $arg=array()) {
        extract($arg);
     
        $query = "SELECT * FROM SPECIAL_TYPES ORDER BY `NAME`";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getClasses($db, $arg) {
        extract($arg);
        global $_IBE_LANG;
        $WHERE = isset($WHERE) ? $WHERE : "";

        $query = "
            SELECT * FROM SPECIAL_CLASS 
            JOIN CLASSES ON CLASSES.ID = SPECIAL_CLASS.CLASS_ID
            WHERE SPECIAL_ID='{$SPECIAL_ID}' $WHERE
        ";

        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);

        if (isset($AS_ARRAY)&&$AS_ARRAY) {
            $array = array();
            while ($row = $db->fetch_array($result['rSet'])) {
                $array[$row['CLASS_ID']] = $row;
            }
            $result = $array;
        } 
        return $result;
    }
    function saveClasses($db, $arg) {
        extract($arg);
        $arg['AS_ARRAY'] = true;
        $SPECIALS = $this->getClasses($db, $arg);
        if (!isset($CLASS_ID)) $CLASS_ID = array();
        //print "Saved <pre>";print_r($SPECIALS);print "</pre>";
        //print "New <pre>";print_r($CLASS_ID);print "</pre>";
        foreach ($CLASS_ID as $SID) {
            if (!array_key_exists($SID,$SPECIALS)) {
                $this->addClasses($db, array("SPECIAL_ID"=>$SPECIAL_ID,"CLASS_ID"=>$SID));
            }
        }
        foreach ($SPECIALS as $SID => $ARR) {
            if (!in_array($SID,$CLASS_ID)) {
                $this->remClasses($db, array("SPECIAL_ID"=>$SPECIAL_ID,"CLASS_ID"=>$SID));
            }
        }
    }
    function addClasses($db, $arg) {
        extract($arg);
        $query = "INSERT INTO SPECIAL_CLASS ( SPECIAL_ID, CLASS_ID ) VALUES ( '{$SPECIAL_ID}', '{$CLASS_ID}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        dbExecute($db, $arr);
    }
    function remClasses($db, $arg) {
        extract($arg);
        $query = "DELETE FROM SPECIAL_CLASS WHERE SPECIAL_ID='{$SPECIAL_ID}' AND CLASS_ID='{$CLASS_ID}'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        dbExecute($db, $arr);
    }


    function getStates($db, $arg) {
        extract($arg);
        global $_IBE_LANG;

        $query = "SELECT * FROM SPECIAL_STATE WHERE SPECIAL_ID='{$SPECIAL_ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);

        if (isset($AS_ARRAY)&&$AS_ARRAY) {
            $array = array();
            while ($row = $db->fetch_array($result['rSet'])) {
                $array[$row['STATE_CODE']] = $row;
            }
            $result = $array;
        } 
        return $result;
    }
    function saveStates($db, $arg) {
        extract($arg);
        $arg['AS_ARRAY'] = true;
        $STATES = $this->getStates($db, $arg);
        if (!isset($STATE_CODE)) $STATE_CODE = array();
        //print "Saved <pre>";print_r($STATES);print "</pre>";
        //print "New <pre>";print_r($STATE_CODE);print "</pre>";
        foreach ($STATE_CODE as $SID) {
            if (!array_key_exists($SID,$STATES)) {
                $this->addStates($db, array("SPECIAL_ID"=>$SPECIAL_ID,"STATE_CODE"=>$SID));
            }
        }
        foreach ($STATES as $SID => $ARR) {
            if (!in_array($SID,$STATE_CODE)) {
                $this->remStates($db, array("SPECIAL_ID"=>$SPECIAL_ID,"STATE_CODE"=>$SID));
            }
        }
    }
    function addStates($db, $arg) {
        extract($arg);
        $query = "INSERT INTO SPECIAL_STATE ( SPECIAL_ID, STATE_CODE ) VALUES ( '{$SPECIAL_ID}', '{$STATE_CODE}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        dbExecute($db, $arr);
    }
    function remStates($db, $arg) {
        extract($arg);
        $query = "DELETE FROM SPECIAL_STATE WHERE SPECIAL_ID='{$SPECIAL_ID}' AND STATE_CODE='{$STATE_CODE}'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        dbExecute($db, $arr);
    }

    function getClosed($db, $arg) {
        extract($arg);
        global $_IBE_LANG;

        $query = "SELECT * FROM SPECIAL_CLOSED WHERE SPECIAL_ID='{$SPECIAL_ID}' ORDER BY DATE_CLOSED";
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
    function saveClosed($db, $arg) {
        extract($arg);
        $arg['AS_ARRAY'] = true;
        $DATES = $this->getClosed($db, $arg);
        // print "->".$CLOSED_ARRIVAL_NEW."<BR>".$CLOSED_ARRIVAL_CUR;
        $a1 = explode(",",$CLOSED_ARRIVAL_CUR);
        $a2 = explode(",",$CLOSED_ARRIVAL_NEW);
        $DATE_CLOSED = array_merge($a1,$a2);
        //print "DATE_CLOSED <pre>";print_r($DATE_CLOSED);print "</pre>";
        //print "Saved <pre>";print_r($DATES);print "</pre>";
        //print "New <pre>";print_r($DATE_CLOSED);print "</pre>";
        foreach ($DATE_CLOSED as $SID) {
            if (!array_key_exists($SID,$DATES) && !empty($SID)) {
                $this->addClosed($db, array("SPECIAL_ID"=>$SPECIAL_ID,"DATE_CLOSED"=>$SID));
            }
        }
        foreach ($DATES as $SID => $ARR) {
            if (!in_array($SID,$DATE_CLOSED) && !empty($SID)) {
                $this->remClosed($db, array("SPECIAL_ID"=>$SPECIAL_ID,"DATE_CLOSED"=>$SID));
            }
        }
        if (!isset($CLOSED_ARRIVAL_DEL)) $CLOSED_ARRIVAL_DEL = array();
        foreach ($CLOSED_ARRIVAL_DEL as $key => $SID) {
            $this->remClosed($db, array("SPECIAL_ID"=>$SPECIAL_ID,"DATE_CLOSED"=>$SID));
        }
    }
    function addClosed($db, $arg) {
        extract($arg);
        $query = "INSERT INTO SPECIAL_CLOSED ( SPECIAL_ID, DATE_CLOSED ) VALUES ( '{$SPECIAL_ID}', '{$DATE_CLOSED}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        dbExecute($db, $arr);
    }
    function remClosed($db, $arg) {
        extract($arg);
        $query = "DELETE FROM SPECIAL_CLOSED WHERE SPECIAL_ID='{$SPECIAL_ID}' AND DATE_CLOSED='{$DATE_CLOSED}'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        dbExecute($db, $arr);
    }

    function getBlackout($db, $arg) {
        extract($arg);
        global $_IBE_LANG;

        $query = "SELECT * FROM SPECIAL_BLACKOUT WHERE SPECIAL_ID='{$SPECIAL_ID}' ORDER BY DATE_CLOSED";
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
                $this->addBlackout($db, array("SPECIAL_ID"=>$SPECIAL_ID,"DATE_CLOSED"=>$SID));
            }
        }
        foreach ($DATES as $SID => $ARR) {
            if (!in_array($SID,$DATE_CLOSED) && !empty($SID)) {
                $this->remBlackout($db, array("SPECIAL_ID"=>$SPECIAL_ID,"DATE_CLOSED"=>$SID));
            }
        }
        if (!isset($BLACKOUT_DEL)) $BLACKOUT_DEL = array();
        foreach ($BLACKOUT_DEL as $key => $SID) {
            $this->remBlackout($db, array("SPECIAL_ID"=>$SPECIAL_ID,"DATE_CLOSED"=>$SID));
        }
    }
    function addBlackout($db, $arg) {
        extract($arg);
        $query = "INSERT INTO SPECIAL_BLACKOUT ( SPECIAL_ID, DATE_CLOSED ) VALUES ( '{$SPECIAL_ID}', '{$DATE_CLOSED}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        dbExecute($db, $arr);
    }
    function remBlackout($db, $arg) {
        extract($arg);
        $query = "DELETE FROM SPECIAL_BLACKOUT WHERE SPECIAL_ID='{$SPECIAL_ID}' AND DATE_CLOSED='{$DATE_CLOSED}'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        dbExecute($db, $arr);
    }

}
global $clsSpecials;
$clsSpecials = new specials;
?>