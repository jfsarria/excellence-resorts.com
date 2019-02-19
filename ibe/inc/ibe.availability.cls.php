<?
/*
 * Revised: May 30, 2011
 */

class availability {

    var $showQry = false;
    var $ITEMS = array('MESSAGES'=>array());
    
    function get_Availability($db, $arg) {

        $this->get_Rooms_Total_Guests($db, $arg);
        $this->get_Rooms_By_Occupancy($db, $arg);
        $this->get_Rooms_Classes($db, $arg);
        $this->get_Rooms_Rates($db, $arg);
        $this->get_Totals($db, $arg);
        $this->get_Property($db, $arg);
        $this->wrap_up($db, $arg);

        return $arg;
    }

    function wrap_up($db, &$arg) {
        $this->ITEMS['MESSAGES'] = array_unique($this->ITEMS['MESSAGES']);
        $arg['RES_ITEMS'] = $this->ITEMS;
        $RES_ROOMS_ADULTS_QTY = 0;
        $RES_ROOMS_CHILDREN_QTY = 0;
        for ($ROOM=1; $ROOM <= (int)$arg['RES_ROOMS_QTY']; ++$ROOM) {

            if (isset($arg["RES_ROOM_{$ROOM}_ADULTS_QTY"])) $RES_ROOMS_ADULTS_QTY += $arg["RES_ROOM_{$ROOM}_ADULTS_QTY"];
            if (isset($arg["RES_ROOM_{$ROOM}_CHILDREN_QTY"])) $RES_ROOMS_CHILDREN_QTY += $arg["RES_ROOM_{$ROOM}_CHILDREN_QTY"];

            foreach ($arg["RES_ROOM_{$ROOM}_ROOMS"] AS $ROOM_ID => $DATA) {
                if ((int)$DATA["AVAILABLE_NIGHTS"]==0) {
                    foreach ($DATA AS $KEY => $VAL) if ($KEY!="NAME" && $KEY!="AVAILABLE_NIGHTS") unset($arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID][$KEY]);
                } else {
                    $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]["CLASS_NAMES"] = ($DATA["AVAILABLE_NIGHTS"]!=0) ? array_unique($DATA["CLASS_NAMES"]) : "X";
                    $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]["SPECIAL_NAMES"] = ($DATA["AVAILABLE_NIGHTS"]!=0&&count($DATA["SPECIAL_NAMES"])!=0) ? array_unique($DATA["SPECIAL_NAMES"]) : "X";
                }
            }
        }
        $arg["RES_ROOMS_ADULTS_QTY"] = $RES_ROOMS_ADULTS_QTY;
        $arg["RES_ROOMS_CHILDREN_QTY"] = $RES_ROOMS_CHILDREN_QTY;
    }

    function get_Property($db, $arg) {
        global $clsGlobal;
        global $clsRooms;
        $RSET = $clsGlobal->getPropertyById($db, array("PROPERTY_ID"=>$arg["RES_PROP_ID"]));
        if ($RSET['iCount']!=0) {
            $this->ITEMS['PROPERTY'] = $clsGlobal->cleanUp_rSet_Array($db->fetch_array($RSET['rSet']));
        }
        $this->ITEMS['PROPERTY']['BED_TYPES'] = array();
        $RSET = $clsRooms->getBedOptions($db, array("PROP_ID"=>$arg["RES_PROP_ID"]));
        while ($brow = $db->fetch_array($RSET['rSet'])) {
            $this->ITEMS['PROPERTY']['BED_TYPES'][$brow['ID']] = $brow['NAME'];
        }
    }

    function get_Totals($db, &$arg) {
        $RES_NIGHTS = (int)$arg['RES_NIGHTS'];
        for ($ROOM=1; $ROOM <= (int)$arg['RES_ROOMS_QTY']; ++$ROOM) {
            foreach ($arg["RES_ROOM_{$ROOM}_ROOMS"] AS $ROOM_ID => $DATA) {
                if ((int)$DATA["AVAILABLE_NIGHTS"]==$RES_NIGHTS) {
                    $GUESTS_QTY = (int)$arg["RES_ROOM_{$ROOM}_GUESTS_QTY"];
                    $GROSS_PP = (int)$DATA['TOTAL']['GROSS_PP'];
                    $FINAL_PP = (int)$DATA['TOTAL']['FINAL_PP'];
                    $GROSS = $GROSS_PP * $GUESTS_QTY;
                    $FINAL = $FINAL_PP * $GUESTS_QTY;
                    $AVG_GROSS_PN = $GROSS / $RES_NIGHTS;
                    $AVG_FINAL_PN = $FINAL / $RES_NIGHTS;

                    $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['TOTAL']['GROSS'] = $GROSS;
                    $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['TOTAL']['FINAL'] = $FINAL;
                    $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['TOTAL']['AVG_GROSS_PN'] = $AVG_GROSS_PN;
                    $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['TOTAL']['AVG_FINAL_PN'] = $AVG_FINAL_PN;
                }
            }
        }
    }

    function get_Class_Special_Query($db, &$arg, $par) {
        $SELECT = (isset($par['SELECT'])) ? $par['SELECT'] : "";
        $JOIN = (isset($par['JOIN'])) ? $par['JOIN'] : "";
        $WHERE = (isset($par['WHERE'])) ? $par['WHERE'] : "";
        $query = "
            SELECT 
                *,
                (SELECT 1 FROM SPECIAL_CLOSED WHERE SPECIAL_ID = V_SPECIALS.ID AND DATE_CLOSED = '{$arg['RES_CHECK_IN']}') AS CLOSED,
                (SELECT 1 FROM SPECIAL_BLACKOUT WHERE SPECIAL_ID = V_SPECIALS.ID AND DATE_CLOSED = '{$par['DATE']}') AS BLACKOUT
                {$SELECT}

            FROM 
                V_SPECIALS 

            {$JOIN}

            WHERE 
                CLASS_ID = '{$par['CLASS_ID']}' AND 
                PROP_ID = '{$arg['RES_PROP_ID']}' AND 

                (BOOK_FROM = '0000-00-00 00:00:00' OR '{$arg['RES_DATE']} 00:00:00' >= BOOK_FROM) AND 
                (BOOK_TO = '0000-00-00 00:00:00' OR '{$arg['RES_DATE']} 00:00:00' <= BOOK_TO) AND

                (TRAVEL_FROM = '0000-00-00 00:00:00' OR '{$par['DATE']} 00:00:00' >= TRAVEL_FROM) AND 
                (TRAVEL_TO = '0000-00-00 00:00:00' OR '{$par['DATE']} 00:00:00' <= TRAVEL_TO) 

                {$WHERE}
        ";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        return dbQuery($db, array('query' => $query));
    }

    function get_Class_Special_rSet($db, &$arg, &$par, $RSET) {
        global $clsGlobal;
        $SPECIAL = "X";
        while ($row = $db->fetch_array($RSET['rSet'])) {
            if ((int)$row['BLACKOUT']==0 && (int)$row['CLOSED']==0) {
                $this->ITEMS[$row['ID']] = $clsGlobal->cleanUp_rSet_Array($row);
                $SPECIAL = array(
                    "ID"=>$row['ID'],
                    "REFERENCE"=>$row['REFERENCE'],
                    "OFF_%"=>$row['OFF'],
                    "OFF_$"=>round((int)$par['RATE']['NET'] * ((int)$row['OFF'] / 100))
                );
                $arg["RES_ROOM_{$par['ROOM']}_ROOMS"][$par['ROOM_ID']]['SPECIAL_NAMES'][$row['ID']] = $row['NAME_'.$arg['RES_LANGUAGE']];
                break;
            } else {
                $SPECIAL = ((int)$row['BLACKOUT']==1) ? 'BLACKOUT' : 'CLOSED';
            }
        }
        return $SPECIAL;
    }

    function get_Class_Special_Private($db, &$arg, $par) {
        $par['WHERE'] = "AND IS_PRIVATE='1' AND ACCESS_CODE='{$arg['RES_SPECIAL_CODE']}'";
        $RSET = $this->get_Class_Special_Query($db, $arg, $par);
        if ($RSET['iCount']==0) {
            array_push($this->ITEMS['MESSAGES'], "Special Code \"{$arg['RES_SPECIAL_CODE']}\" Not Found");
        } else {
            return $this->get_Class_Special_rSet($db, $arg, $par, $RSET);
        }
    }

    function get_Class_Special_State($db, &$arg, $par) {
        $par['SELECT'] = ",SPECIAL_STATE.STATE_CODE";
        $par['JOIN'] = "JOIN SPECIAL_STATE ON SPECIAL_STATE.SPECIAL_ID = V_SPECIALS.ID ";
        $par['WHERE'] = "AND IS_PRIVATE='0' AND IS_GEO='1' AND STATE_CODE='{$arg['RES_STATE_CODE']}'";
        $RSET = $this->get_Class_Special_Query($db, $arg, $par);
        return $this->get_Class_Special_rSet($db, $arg, $par, $RSET);
    }

    function get_Class_Special_Regular($db, &$arg, $par) {
        $par['WHERE'] = "AND IS_PRIVATE='0' AND IS_GEO='0'";
        $RSET = $this->get_Class_Special_Query($db, $arg, $par);
        return $this->get_Class_Special_rSet($db, $arg, $par, $RSET);
    }

    function get_Class_Special($db, &$arg, $par) {
        /*
            1. Private
            2. State
            3. Regular
        */
        $SPECIAL = "X";

        if (isset($arg['RES_SPECIAL_CODE']) && $arg['RES_SPECIAL_CODE']!="") {
            $SPECIAL = $this->get_Class_Special_Private($db, $arg, $par);
        }
        if (!is_array($SPECIAL) && isset($arg['RES_COUNTRY_CODE']) && $arg['RES_COUNTRY_CODE']=="US" && isset($arg['RES_STATE_CODE']) && $arg['RES_STATE_CODE']!="") {
            $SPECIAL = $this->get_Class_Special_State($db, $arg, $par);
        }
        if (!is_array($SPECIAL) && isset($arg['RES_COUNTRY_CODE'])) {
            $SPECIAL = $this->get_Class_Special_Regular($db, $arg, $par);
        }

        if (is_array($SPECIAL)) {
            $off = (int)$SPECIAL['OFF_$'];
            $net = (int)$par['RATE']['NET'] - $off;
            $markup = round($net * ((float)$par['RATE']['MARKUP_%'] / 100));

            $SPECIAL['NET'] = $net;
            $SPECIAL['MARKUP_$'] = $markup;
            $SPECIAL['FINAL'] = $net + $markup;
        }

        return $SPECIAL;
    }

    function calculate_Room_Rates($db, &$arg, $par) {
        /*
        Deduction is applyed to the net rate, i.e. rate before markup
        Gross = (Net(A)+/-Supplement) + Markup(B)
        Final = (Net(A)+/-Supplement) - Special + Markup(B)
        */
        extract($par);
        $RATE = array();
        if (isset($CLASS_ID) && isset($this->ITEMS[$CLASS_ID])) {
            $CLASS = $this->ITEMS[$CLASS_ID];

            $guests = (int)$arg["RES_ROOM_{$ROOM}_GUESTS_QTY"];
            $override = (float)$CLASS['MARKUP'];
            $year = (float)$CLASS['MARKUP_YEAR'];
            $prpn = (float)$CLASS['RATE_PER_RP'];
            $single = (int)$CLASS['SUPL_SINGLE'];
            $triple = (int)$CLASS['SUPL_TRIPLE'];

            $supplement = 0;
            if ($guests == 1) $supplement = $single;
            if ($guests >= 3) $supplement = $triple * -1;
            
            $net = $prpn + $supplement;
            $markup = ($override!=0) ? $override : $year;
            $percentage = round($net * ($markup / 100));
            $gross = $net + $percentage;

            $RATE['PER_PERSON'] = $prpn;
            $RATE['SUPPLEMENT_$'] = $supplement;
            $RATE['NET'] = $net;
            $RATE['MARKUP_%'] = $markup;
            $RATE['MARKUP_$'] = $percentage;
            $RATE['GROSS'] = $gross;
        }
        return $RATE;
    }

    function calculate_Final_Rate($db, &$arg, &$par) {
        $RATE = array();
        if (isset($par['RATE'])&&is_array($par['RATE'])) {
            $RATE['GROSS'] = $par['RATE']['GROSS'];
            $RATE['FINAL'] = (isset($par['SPECIAL'])&&is_array($par['SPECIAL'])) ? $par['SPECIAL']['FINAL'] : $RATE['GROSS'];

            $arg["RES_ROOM_{$par['ROOM']}_ROOMS"][$par['ROOM_ID']]['TOTAL']['GROSS_PP'] += (int)$RATE['GROSS'];
            $arg["RES_ROOM_{$par['ROOM']}_ROOMS"][$par['ROOM_ID']]['TOTAL']['FINAL_PP'] += (int)$RATE['FINAL'];
        }
        return $RATE;
    }

    function get_Rooms_Rates($db, &$arg) {
        for ($ROOM=1; $ROOM <= (int)$arg['RES_ROOMS_QTY']; ++$ROOM) {
            foreach ($arg["RES_ROOM_{$ROOM}_ROOMS"] AS $ROOM_ID => $DATA) {
                if ((int)$DATA["AVAILABLE_NIGHTS"]!=0) {
                    foreach ($DATA["NIGTHS"] AS $DATE => $ARRAY) {
                        if (is_array($ARRAY)&&isset($ARRAY['CLASS'])) {
                            $par = array(
                                "DATE"=>$DATE,
                                "ROOM"=>$ROOM,
                                "ROOM_ID"=>$ROOM_ID,
                                "CLASS_ID"=>$ARRAY['CLASS']['ID']
                            );
                            $par['RATE'] = $this->calculate_Room_Rates($db, $arg, $par);
                            $par['SPECIAL'] = $this->get_Class_Special($db, $arg, $par);
                            $par['FINAL'] = $this->calculate_Final_Rate($db, $arg, $par);

                            $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]["NIGTHS"][$DATE]['CLASS']["RATE"] = $par['RATE'];
                            $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]["NIGTHS"][$DATE]['CLASS']["SPECIAL"] = $par['SPECIAL'];
                            $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]["NIGTHS"][$DATE]['RATE'] = $par['FINAL'];
                        }
                    }
                }
            }
        }
    }

    function get_Room_Class($db, &$arg, $par) {
        extract($arg);
        extract($par);

        //$USERTYPE = ((int)$_SESSION['AUTHENTICATION']['ROLE'] == 1) ? "USERTYPE_ID = '1'" : "( USERTYPE_ID = '1' OR USERTYPE_ID = '{$_SESSION['AUTHENTICATION']['ROLE']}' )";
        $USERTYPE = array();
        foreach ($RES_USERTYPE AS $KEY=>$VAL) array_push($USERTYPE, "USERTYPE_ID = '$VAL'");
        $COUNTRY_CODE = isset($RES_GEO_COUNTRY_CODE) ? $RES_GEO_COUNTRY_CODE : $RES_COUNTRY_CODE;
        $query = "
            SELECT 
                *,
                (SELECT 1 FROM CLASS_BLACKOUT WHERE CLASS_ID = V_CLASSES.ID AND DATE_CLOSED = '{$THIS_DAY}') AS BLACKOUT
            FROM 
                V_CLASSES 
            WHERE 
                COUNTRY_CODE = '{$COUNTRY_CODE}' AND
                ROOM_ID = '{$ROOM_ID}' AND 
                '{$THIS_DAY} 00:00:00' >= SEASON_FROM AND '{$THIS_DAY} 00:00:00' <= SEASON_TO AND
                (".(implode(" OR ",$USERTYPE)).")
        ";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $RSET = dbQuery($db, array('query' => $query));
        $CLASS = ($RSET['iCount']!=0) ? $db->fetch_array($RSET['rSet']) : array();

        return $CLASS;
    }

    function get_Rooms_Classes($db, &$arg) {
        global $clsGlobal;
        extract($arg);

        for ($ROOM=1; $ROOM <= (int)$arg['RES_ROOMS_QTY']; ++$ROOM) {
            foreach ($arg["RES_ROOM_{$ROOM}_ROOMS"] AS $ROOM_ID => $DATA) {
                for ($t=0; $t < (int)$RES_NIGHTS; ++$t) {
                    $THIS_DAY = addDaysToDate($RES_CHECK_IN, $t);
                    $CLASS = $this->get_Room_Class($db, $arg, array(
                        "ROOM_ID"=>$ROOM_ID,
                        "THIS_DAY"=>$THIS_DAY
                    ));
                    if (count($CLASS)==0 || (int)$CLASS['BLACKOUT']==1) {} else {
                        $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['AVAILABLE_NIGHTS'] += 1;
                        $this->ITEMS[$CLASS['ID']] = $clsGlobal->cleanUp_rSet_Array($CLASS);
                    }
                    $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['NIGTHS'][$THIS_DAY] = (count($CLASS)==0) ? "X" : ( ((int)$CLASS['BLACKOUT']==1) ? "BLACKOUT" : array('CLASS' => array(
                        "ID"=>$CLASS['ID'],
                        "REFERENCE"=>$CLASS['REFERENCE']
                    )));
                    if (isset($CLASS['ID'])) $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['CLASS_NAMES'][$CLASS['ID']] = $CLASS['NAME_'.$RES_LANGUAGE];
                }
            }
        } 
    }

    function get_Rooms_By_Occupancy($db, &$arg) {
        global $clsGlobal;
        extract($arg);

        for ($ROOM=1; $ROOM <= (int)$arg['RES_ROOMS_QTY']; ++$ROOM) {
            $GUEST = $arg["RES_ROOM_{$ROOM}_GUESTS_QTY"];
            $query = "
                SELECT 
                    * 
                FROM 
                    ROOMS 
                WHERE 
                    PROP_ID = '{$RES_PROP_ID}' AND 
                    MAX_OCUP >= {$GUEST} AND
                    IS_ACTIVE = '1'
                ORDER BY 
                    `IS_VIP`,
                    `ORDER`
            "; 
            if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
            $RSET = dbQuery($db, array('query' => $query));
            $arg["RES_ROOM_{$ROOM}_ROOMS"] = array();
            while ($row = $db->fetch_array($RSET['rSet'])) {
                $arg["RES_ROOM_{$ROOM}_ROOMS"][$row['ID']] = array(
                    "NAME"=>$row['NAME_'.$RES_LANGUAGE],
                    "AVAILABLE_NIGHTS"=>0,
                    "TOTAL"=>array(
                        "GROSS_PP"=>0,
                        "FINAL_PP"=>0,
                        "AVG_GROSS_PN"=>0,
                        "AVG_FINAL_PN"=>0,
                        "GROSS"=>0,
                        "FINAL"=>0
                    ),
                    "AVG_PER_NIGHT"=>0,
                    "NIGTHS"=>array(),
                    "CLASS_NAMES"=>array(),
                    "SPECIAL_NAMES"=>array()
                );
                $this->ITEMS[$row['ID']] = $clsGlobal->cleanUp_rSet_Array($row);
            }
        }
    }

    function get_Rooms_Total_Guests($db, &$arg) {
        for ($ROOM=1; $ROOM <= (int)$arg['RES_ROOMS_QTY']; ++$ROOM) {
            $arg["RES_ROOM_{$ROOM}_GUESTS_QTY"] = (int)$arg["RES_ROOM_{$ROOM}_ADULTS_QTY"];
            if (isset($arg["RES_ROOM_{$ROOM}_CHILDREN_QTY"])) {
                for ($CHILD=1; $CHILD <= (int)$arg["RES_ROOM_{$ROOM}_CHILDREN_QTY"]; ++$CHILD) {
                    $age = (int)$arg["RES_ROOM_{$ROOM}_CHILD_AGE_{$CHILD}"];
                    if ($this->child_Age_Counts($db,array("PROP_ID"=>$arg["RES_PROP_ID"],"AGE"=>$age))) $arg["RES_ROOM_{$ROOM}_GUESTS_QTY"] += 1;
                }
            }
        }
    }

    function child_Age_Counts($db, $arg) {
        extract($arg);
        $query = "
            SELECT 
                ID 
            FROM 
                CHILDREN_RATES 
            WHERE 
                PROP_ID = '{$PROP_ID}' AND 
                {$AGE} >= `FROM` AND {$AGE} <= `TO` AND 
                `COUNTED`='1'
        ";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, array('query' => $query));
        return ( $result['iCount'] == 0 ) ? false : true;
    }
}

global $clsAvailability;
$clsAvailability = new availability;

?>