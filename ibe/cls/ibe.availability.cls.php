<?
/*
 * Revised: Dec 12, 2011
 *          Jul 31, 2014
 *          Apr 23, 2015
 *          Aug 10, 2016
 *          Sep 12, 2018 Cajica
 */

class availability {

    var $showQry = false;
    var $ITEMS = array('MESSAGES'=>array());
    
    function get_Availability($db, $arg) {
        global $isWEBSERVICE;
        global $clsSetup;

        $BLOCKED_IPs = $clsSetup->getBlockedIPs($db);
        $arg['BLOCKED_IPs'] = $BLOCKED_IPs[$arg["RES_PROP_ID"]];

        $arg['CURRENCY'] = $clsSetup->getCurrency($db);

        $this->get_Rooms_Total_Guests($db, $arg);
        $this->get_Rooms_By_Occupancy($db, $arg);
        $this->get_Month_Markup($db, $arg);
        $this->get_Rooms_Classes($db, $arg);
        $this->get_Rooms_Rates($db, $arg);
        $this->get_Totals($db, $arg);
        $this->get_Property($db, $arg);
        $this->get_Properties($db, $arg);
        $this->get_Trasnfer($db, $arg);
        $this->get_Custom_Data($db, $arg);

        if ($isWEBSERVICE) {
            $this->get_Cancellation_Policy($db, $arg);
            $this->get_Property_Images($db, $arg);
        }

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

    function get_Properties($db, $arg) {
        global $clsGlobal;
        $clsGlobal->PROPERTIES = $clsGlobal->getPropertiesByIDs($db, array("asArray"=>true));
        $this->ITEMS['PROPERTIES'] = $clsGlobal->PROPERTIES;
    }

    function get_Month_Markup($db, $arg) {
        global $clsMarkups;
        if (!isset($this->ITEMS['MARKUPS'])) {
          $this->ITEMS['MARKUPS'] = array();
        }
        $this->ITEMS['MARKUPS']["MONTH"] = 0;
        $date = explode("-",$arg["RES_CHECK_IN"]);
        $RSET = $clsMarkups->getByYearMonth($db, array("PROP_ID"=>$arg["RES_PROP_ID"],"YEAR"=>$date[0],"MONTH"=>(int)$date[1]));
        while ($row = $db->fetch_array($RSET['rSet'])) {
            $this->ITEMS['MARKUPS']["MONTH"] = $row["MONTHLY"];
        }
    }

    function get_Trasnfer($db, $arg) {
      global $clsGlobal;
      global $clsTransfer;
      $RSET = $clsTransfer->getCarsByProperty($db, array("PROP_ID"=>$arg["RES_PROP_ID"],"WEHRE"=>"AND IS_ARCHIVE='0'"));
      $this->ITEMS['TRANSFER'] = array();
      while ($row = $db->fetch_array($RSET['rSet'])) {
        $this->ITEMS['TRANSFER'][$row['ID']] = $clsGlobal->cleanUp_rSet_Array($row);
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
        //mail("jaunsarria@gmail.com","spcieals",$query);
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
                    "ACCESS_CODE"=>$row['ACCESS_CODE'],
                    "OFF_%"=>$row['OFF'],
                    "OFF_$"=>$this->get_Class_Special_rSet_Off($par, $row)
                );
                $arg["RES_ROOM_{$par['ROOM']}_ROOMS"][$par['ROOM_ID']]['SPECIAL_NAMES'][$row['ID']] = $row['REFERENCE'];//$row['NAME_'.$arg['RES_LANGUAGE']];
                break;
            } else {
                $SPECIAL = ((int)$row['BLACKOUT']==1) ? 'BLACKOUT' : 'CLOSED';
            }
        }
        return $SPECIAL;
    }
    
    function get_Class_Special_rSet_Off($par, $row) {
        return round((int)$par['RATE']['NET'] * ((int)$row['OFF'] / 100));
    }

    function get_Class_Special_Private($db, &$arg, $par) {
        $par['WHERE'] = "AND IS_PRIVATE='1' AND ACCESS_CODE='{$arg['RES_SPECIAL_CODE']}'";
        $RSET = $this->get_Class_Special_Query($db, $arg, $par);
        if ($RSET['iCount']==0) {
            array_push($this->ITEMS['MESSAGES'], htmlentities("Special Code {$arg['RES_SPECIAL_CODE']} Not Found"));
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

        $arg["DEBUGGING"]["COUNTRY_CODE"]["SPECIAL"] = isset($arg['RES_COUNTRY_CODE']) ? $arg['RES_COUNTRY_CODE'] : "*** NO RES_COUNTRY_CODE ***";

        $this->set_Class_Special($db, $arg, $par, $SPECIAL);

        return $SPECIAL;
    }

    function set_Class_Special($db, &$arg, $par, &$SPECIAL) {
        if (is_array($SPECIAL)) {
            $off = (int)$SPECIAL['OFF_$'];
            $net = (int)$par['RATE']['NET'] - $off;
            $markup = round($net * ((float)$par['RATE']['MARKUP_%'] / 100));

            $SPECIAL['NET'] = $net;
            $SPECIAL['MARKUP_$'] = $markup;
            $SPECIAL['FINAL'] = $net + $markup;
        }
    }

    function calculate_Room_Rates($db, &$arg, $par) {
        /*
        DEFAULT: EXCELLENCE RESORTS CALCULATIONS

        Deduction is applyed to the net rate, i.e. rate before markup
        Gross = (Net(A)+/-Supplement) + Markup(B)
        Final = (Net(A)+/-Supplement) - Special + Markup(B)
        */
        extract($par);
        $RATE = array();
        if (isset($CLASS_ID) && isset($this->ITEMS[$CLASS_ID])) {
            $CLASS = $this->ITEMS[$CLASS_ID];
            $MONTHLY_MARKUP = (float)$this->ITEMS['MARKUPS']["MONTH"];
            $override = (float)$CLASS['MARKUP'];

            $override = $override!=0 ? $override : $MONTHLY_MARKUP;
            $guests = (int)$arg["RES_ROOM_{$ROOM}_GUESTS_QTY"];
            $year = (float)$CLASS['MARKUP_YEAR'];
            $prpn = (float)$CLASS['RATE_PER_RP'];
            $single = (int)$CLASS['SUPL_SINGLE'];
            $triple = (int)$CLASS['SUPL_TRIPLE'];
            $spltype = $CLASS['SUPL_TYPE'];

            $supplement = 0;
            if ($guests == 1) $supplement = $single;
            if ($guests >= 3) $supplement = (($spltype=="$") ? $triple : round($prpn * ($triple / 100))) * -1;
            
            $net = $prpn + $supplement;
            $markup = ($override!=0) ? $override : $year;
            $percentage = round($net * ($markup / 100));
            $gross = $net + $percentage;

            $RATE['PER_PERSON'] = $prpn;

            if ($guests == 1) $RATE['SUPL_SINGLE'] = $single;
            if ($guests >= 3) $RATE['SUPL_TRIPLE'] = $triple;
            if ($guests != 2) $RATE['SUPL_TYPE'] = $spltype;
            if ($guests != 2) $RATE['SUPPLEMENT_$'] = $supplement;

            $RATE['NET'] = $net;
            $RATE['MARKUP_%'] = $markup;
            $RATE['MARKUP_$'] = $percentage;
            $RATE['GROSS'] = $gross;
            $RATE['QTY'] = $guests;
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
            $cnt = 0;
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
                $arg["RES_ROOM_{$ROOM}_ROOMS_ORDER"][++$cnt] = $ROOM_ID;
            }
        }
    }

    function get_Room_Class($db, &$arg, $par) {
        extract($arg);
        extract($par);

        $RES_SRC = isset($arg['RES_SRC']) ? $arg['RES_SRC'] : "";
        $blockIP = in_array($_SERVER["REMOTE_ADDR"],$arg['BLOCKED_IPs']) && $RES_SRC!="CC";
        //print $blockIP ? "Yes" : "No";
        //$blockIP = true;

        $USERTYPE = array();
        foreach ($RES_USERTYPE AS $KEY=>$VAL) array_push($USERTYPE, "USERTYPE_ID = '$VAL'");
        $COUNTRY_CODE = isset($RES_GEO_COUNTRY_CODE) ? $RES_GEO_COUNTRY_CODE : $RES_COUNTRY_CODE;

        // DEBUGGING CODE
        $arg["DEBUGGING"]["COUNTRY_CODE"]["CLASS"] = array(
            "RES_GEO_COUNTRY_CODE" => isset($RES_GEO_COUNTRY_CODE) ? $RES_GEO_COUNTRY_CODE : "*** X ***",
            "RES_COUNTRY_CODE" => $RES_COUNTRY_CODE,
            "COUNTRY_CODE" => $COUNTRY_CODE
        );

        $query = "
            SELECT 
                *,
                (SELECT 1 FROM CLASS_BLACKOUT WHERE CLASS_ID = V_CLASSES.ID AND DATE_CLOSED = '{$THIS_DAY}') AS BLACKOUT
            FROM 
                V_CLASSES 
            WHERE 
                (COUNTRY_CODE = '{$COUNTRY_CODE}' OR COUNTRY_CODE = 'US')
                AND
                ROOM_ID = '{$ROOM_ID}' 
                AND 
                '{$THIS_DAY} 00:00:00' >= SEASON_FROM AND '{$THIS_DAY} 00:00:00' <= SEASON_TO AND
                (".(implode(" OR ",$USERTYPE)).")
        ";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $RSET = dbQuery($db, array('query' => $query));

        //$arg["DEBUGGING"]["COUNTRY_CODE"]["CLASS"]["QUERY"] = "query"; // DEBUGGING GEO

        // SELECT THE ONE FOR THE COUNTRY OR US AS DEFAULT
        // $CLASS = ($RSET['iCount']!=0) ? $db->fetch_array($RSET['rSet']) : array();
        $RETVAL = "";
        if ($RSET['iCount']==1) {
            $RETVAL = $db->fetch_array($RSET['rSet']);
        } else {
            $CLASS = array();
            $USA = array();
            while ($row = $db->fetch_array($RSET['rSet'])) {
                if (count($CLASS)==0 && $row['COUNTRY_CODE']!='US' && (int)$row['BLACKOUT']!=1) $CLASS = $row;
                if (count($USA)==0 && $row['COUNTRY_CODE']=='US' && (int)$row['BLACKOUT']!=1) $USA = $row;
            }
            $RETVAL = (count($CLASS)==0 || $blockIP) ? $USA : $CLASS;
        }

        $arg["DEBUGGING"]["COUNTRY_CODE"]["CLASS"]["RETVAL"] = array(
            "iCount" => $RSET['iCount'] . " Record Found",
            "blockIP" => $blockIP,
            "RES_SRC" => $RES_SRC,
            "RETVAL" => $RETVAL
        ); // DEBUGGING GEO

        return $RETVAL;
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
                    if (count($CLASS)==0 || (int)$CLASS['BLACKOUT']==1) {
                    } else {
                        $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['AVAILABLE_NIGHTS'] += 1;
                        $this->ITEMS[$CLASS['ID']] = $clsGlobal->cleanUp_rSet_Array($CLASS);
                    }
                    $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['NIGTHS'][$THIS_DAY] = (count($CLASS)==0) ? "X" : ( ((int)$CLASS['BLACKOUT']==1) ? "BLACKOUT" : array('CLASS' => array(
                        "ID"=>$CLASS['ID'],
                        "REFERENCE"=>$CLASS['REFERENCE']
                    )));
                    if (isset($CLASS['ID'])) $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['CLASS_NAMES'][$CLASS['ID']] = $CLASS['REFERENCE']; //$CLASS['NAME_'.$RES_LANGUAGE];
                }
            }
        } 
    }

    function get_Rooms_By_Occupancy($db, &$arg) {
        global $clsGlobal;
        global $isWEBSERVICE;

        extract($arg);
        //print "<pre>";print_r($arg);print "</pre>";
        $RES_LANGUAGE = !isset($RES_LANGUAGE)||empty($RES_LANGUAGE) ? "EN" : $RES_LANGUAGE;

        for ($ROOM=1; $ROOM <= (int)$arg['RES_ROOMS_QTY']; ++$ROOM) {
            $GUEST = $arg["RES_ROOM_{$ROOM}_GUESTS_QTY"];
            $ADULTS = isset($arg["RES_ROOM_{$ROOM}_ADULTS_QTY"])?$arg["RES_ROOM_{$ROOM}_ADULTS_QTY"]:2;
            $CHILDREN = (isset($arg["RES_ROOM_{$ROOM}_CHILDREN_QTY"])) ? (int)$arg["RES_ROOM_{$ROOM}_CHILDREN_QTY"] : 0;
            $INFANTS = (isset($arg["RES_ROOM_{$ROOM}_IGNORE_QTY"])) ? (int)$arg["RES_ROOM_{$ROOM}_IGNORE_QTY"] : 0;
            $KIDS = max(0, $CHILDREN - $INFANTS);
            $query = "
                SELECT 
                    *, _fn_getRoomOrder(IS_VIP) as ROOM_ORDER
                FROM 
                    ROOMS 
                WHERE 
                     MAX_OCUP >= {$GUEST} AND
                    (MAX_ADUL IS NULL OR MAX_ADUL = 0 OR MAX_ADUL >= {$ADULTS}) AND
            ";

            if ($CHILDREN != 0 || $INFANTS != 0) {
                $query .= "
                    MAX_CHIL <> 0 AND
                    MAX_CHIL >= {$KIDS} AND
                ";
            }

            $query .= "
                    PROP_ID = '{$RES_PROP_ID}' AND 
                    IS_ACTIVE = '1'
                ORDER BY 
                    `ROOM_ORDER`,
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
                if ($isWEBSERVICE) {
                    $this->ITEMS[$row['ID']]['IMAGES'] = $this->get_Uploads($db, array("PARENT_ID"=>$row['ID'],"TYPE"=>"image","LOCATION"=>"ibe/ups/rooms/"));
                }
            }
        }
    }

    function get_Rooms_Total_Guests($db, &$arg) {
        for ($ROOM=1; $ROOM <= (int)$arg['RES_ROOMS_QTY']; ++$ROOM) {
            $arg["RES_ROOM_{$ROOM}_GUESTS_QTY"] = isset($arg["RES_ROOM_{$ROOM}_ADULTS_QTY"]) ? (int)$arg["RES_ROOM_{$ROOM}_ADULTS_QTY"] : 2;
            $arg["RES_ROOM_{$ROOM}_IGNORE_QTY"] = 0;
            if (isset($arg["RES_ROOM_{$ROOM}_CHILDREN_QTY"])) {
                for ($CHILD=1; $CHILD <= (int)$arg["RES_ROOM_{$ROOM}_CHILDREN_QTY"]; ++$CHILD) {
                  if (isset($arg["RES_ROOM_{$ROOM}_CHILD_AGE_{$CHILD}"])) {
                    $age = (int)$arg["RES_ROOM_{$ROOM}_CHILD_AGE_{$CHILD}"];
                    if ($this->child_Age_Counts($db,array("PROP_ID"=>$arg["RES_PROP_ID"],"AGE"=>$age))) {
                        $arg["RES_ROOM_{$ROOM}_GUESTS_QTY"] += 1;
                    } else {
                        $arg["RES_ROOM_{$ROOM}_IGNORE_QTY"] += 1;
                    }
                  }
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

    /*
     * WEB SERVICE 
     */

    function get_Cancellation_Policy($db, $arg) {
        global $clsReserv;
        $this->ITEMS['CANCELLATION_POLICY'] = $clsReserv->getCancellationModificationPolicy($arg['RES_CHECK_IN'], $arg['RES_LANGUAGE']);
        $this->ITEMS['TRANSFER_RULES'] = $clsReserv->getCancellationModificationPolicy($arg['RES_CHECK_IN'], $arg['RES_LANGUAGE'], "RULES");
        $this->ITEMS['DEADLINE'] = $clsReserv->DEADLINE;
    }

    function get_Property_Images($db, $arg) {
        $this->ITEMS['PROPERTY']['IMAGES'] = $this->get_Uploads($db, array("PARENT_ID"=>$arg['RES_PROP_ID'],"TYPE"=>"image","LOCATION"=>"ibe/ups/props/"));
        $this->ITEMS['PROPERTY']['VIDEOS'] = $this->get_Uploads($db, array("PARENT_ID"=>$arg['RES_PROP_ID'],"TYPE"=>"video","LOCATION"=>"ibe/ups/props/"));
    }

    function get_Uploads($db, $arg) {
        global $clsUploads;
        extract($arg);
        $result = array();
        $IRSET = $clsUploads->getByParent($db, array("PARENT_ID"=>$PARENT_ID,"TYPE"=>$TYPE));
        if ($IRSET['iCount']!=0) {
            while ($irow = $db->fetch_array($IRSET['rSet'])) { 
                $result[$irow['ID']] = "{$LOCATION}{$irow['NAME']}";
            }
        }
        return $result;
    }

    function get_Custom_Data($db, $arg) {
    }
}

global $clsAvailability;
$clsAvailability = new availability;

?>