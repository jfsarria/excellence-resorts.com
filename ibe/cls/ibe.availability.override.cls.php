<?
/*
 * Revised: Jun 25, 2011
 *          Jul 17, 2014
 *          Sep 15, 2014
 */

class LAMA_availability extends availability {

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
                            $par['SPECIAL'] = $this->get_Class_Special($db, $arg, $par);
                            $par['RATE'] = $this->calculate_Room_Rates($db, $arg, $par);
                            $par['FINAL'] = $this->calculate_Final_Rate($db, $arg, $par);

                            $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]["NIGTHS"][$DATE]['CLASS']["SPECIAL"] = $par['SPECIAL'];
                            $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]["NIGTHS"][$DATE]['CLASS']["RATE"] = $par['RATE'];
                            $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]["NIGTHS"][$DATE]['RATE'] = $par['FINAL'];
                        }
                    }
                }
                $arg["RES_ROOM_{$ROOM}_ROOMS_ORDER"][++$cnt] = $ROOM_ID;
            }
        }
    }

    function calculate_Room_Rates($db, &$arg, $par) {
        /*
        LA AMADA

        1) 3 Adults => AD1-100% + AD2-100% + AD3-50% percentage of adult rate or-minus-$ (whatever set in the season)
        2) 2AD + 1CH => AD1-100% + AD2-100% + CH-75%(whatever set in the child)
        3) 3AD + 1CH => AD1-100% + AD2-100% + AD3-50%or$discount + CH-75%
        4) 1AD + 2CH => AD100% + CH1-100% + CH75% (CHD discount only apply when sharing with 2 adults, so in this case 1st child is considered adult and second child gets discount. This doesn't apply to infants, they get in free even with 1 adult)
        5) 2AD + 2CH => AD1-100% + AD2-100% + CH1-75% + CH2-75%
        6) 4AD => AD1-100% + AD2-100% + AD3-50% + AD4-50%
        */
        extract($par);
        $RATE = array();
        if (isset($CLASS_ID) && isset($this->ITEMS[$CLASS_ID])) {
            $CLASS = $this->ITEMS[$CLASS_ID];
            $MONTHLY_MARKUP = (float)$this->ITEMS['MARKUPS']["MONTH"];
            $override = (float)$CLASS['MARKUP'];

            $par['override'] = $override!=0 ? $override : $MONTHLY_MARKUP;
            $par['year'] = (float)$CLASS['MARKUP_YEAR'];
            $par['prpn'] = (float)$CLASS['RATE_PER_RP'];
            $par['adults'] = (int)$arg["RES_ROOM_{$ROOM}_ADULTS_QTY"];
            $par['children'] = (int)$arg["RES_ROOM_{$ROOM}_CHILDREN_QTY"];
            extract($par);

            $par['SUPL_QTY'] = (($adults-2)<0) ? 0 : $adults-2;
            $par['FULL_QTY'] = $adults-$par['SUPL_QTY'];

            $RATE['FULL'] = $this->calculate_Room_Rate_Full($db, $arg, $par);
            if ($adults != 2) $RATE['SUPL'] = $this->calculate_Room_Rate_Supl($db, $arg, $par);
            if ($children != 0) {
                $RATE['CHILDREN'] = $this->calculate_Room_Rate_Children($db, $arg, $par);
                foreach ($RATE['CHILDREN'] as $CHILD_NUM=>$CDATA) {
                    if ((int)$CDATA['PERCENTAGE']==100) { unset($RATE['SUPL']); break; }
                }
            }
        }
        return $RATE;
    }

    function calculate_Special($db, $arg, $par, $RATE) {
        extract($par);
        $RETURN = array();

        $discount = round(($RATE['NET']*$SPECIAL['OFF_%'])/100);
        $net = $RATE['NET'] - $discount;
        $markup = ($override!=0) ? $override : $year;
        $percentage = round($net * ($markup / 100));
        $final = $net + $percentage;

        $RETURN['DISCOUNT'] = $discount;
        $RETURN['NET'] = $net;
        $RETURN['MARKUP_%'] = $markup;
        $RETURN['MARKUP_$'] = $percentage;
        $RETURN['FINAL'] = $final;

        return $RETURN;
    }

    function calculate_Room_Rate_Full($db, &$arg, $par) {
        extract($par);
        $RATE = array();
            
        $net = $prpn;
        $markup = ($override!=0) ? $override : $year;
        $percentage = round($net * ($markup / 100));
        $gross = $net + $percentage;

        $RATE['PER_PERSON'] = $prpn;
        $RATE['NET'] = $net;
        $RATE['MARKUP_%'] = $markup;
        $RATE['MARKUP_$'] = $percentage;
        $RATE['GROSS'] = $gross;
        if (is_array($SPECIAL)) {
            $RATE['SPECIAL'] = $this->calculate_Special($db, $arg, $par, $RATE);
        }
        $RATE['QTY'] = $FULL_QTY;

        return $RATE;
    }

    function calculate_Room_Rate_Supl($db, &$arg, $par) {
        extract($par);
        $RATE = array();
        $CLASS = $this->ITEMS[$CLASS_ID];

        $single = (int)$CLASS['SUPL_SINGLE'];
        $triple = (int)$CLASS['SUPL_TRIPLE'];
        $spltype = $CLASS['SUPL_TYPE'];

        $supplement = 0;
        if ($adults == 1) $supplement = $single;
        if ($adults >= 3) $supplement = (($spltype=="$") ? $triple : round($prpn * ($triple / 100))) * -1;
        
        $net = ($adults == 1) ? $supplement : $prpn + $supplement;
        $markup = ($override!=0) ? $override : $year;
        $percentage = round($net * ($markup / 100));
        $gross = $net + $percentage;

        $RATE['PER_PERSON'] = $prpn;

        if ($adults == 1) $RATE['SUPL_SINGLE'] = $single;
        if ($adults >= 3) $RATE['SUPL_TRIPLE'] = $triple;
        if ($adults != 2) $RATE['SUPL_TYPE'] = $spltype;
        if ($adults != 2) $RATE['SUPPLEMENT_$'] = $supplement;

        $RATE['NET'] = $net;
        $RATE['MARKUP_%'] = $markup;
        $RATE['MARKUP_$'] = $percentage;
        $RATE['GROSS'] = $gross;
        if (is_array($SPECIAL)) {
            $RATE['SPECIAL'] = $this->calculate_Special($db, $arg, $par, $RATE);
        }
        $RATE['QTY'] = ($adults == 1) ? 1 : $SUPL_QTY;

        return $RATE;
    }

    function calculate_Room_Rate_Children($db, &$arg, $par) {
        extract($par);
        $RATE = array();
        $par['KIDS_%'] = array();
           
        for ($CHILD_NUM=1; $CHILD_NUM <= $children; ++$CHILD_NUM) {
            $par['CHILD_NUM'] = $CHILD_NUM;
            $this->calculate_Room_Rate_Child($db, $arg, $par, $RATE);
        }
        if ($adults==1&&$children!=0) {
            arsort($par['KIDS_%']);
            //print "<pre>";print_r($par['KIDS_%']);print "</pre>";
            foreach ($par['KIDS_%'] as $CHILD_NUM=>$VAL) {
                $par['CHILD_NUM'] = $CHILD_NUM;
                $par['KID_PERCENTAGE'] = 100;
                $this->calculate_Room_Rate_Child($db, $arg, $par, $RATE);
                break;
            }
            //print "<pre>";print_r($arg);print "</pre>";
        }
        return $RATE;
    }

    function calculate_Room_Rate_Child($db, &$arg, &$par, &$RATE) {
        global $clsChildrate;
        extract($par);
           
        $age = isset($arg["RES_ROOM_{$ROOM}_CHILD_AGE_{$CHILD_NUM}"]) ? (int)$arg["RES_ROOM_{$ROOM}_CHILD_AGE_{$CHILD_NUM}"] : 0;
        $kidp = $clsChildrate->getPercentage($db, array('PROP_ID'=>$arg['RES_PROP_ID'],'AGE'=>$age));
        if ($kidp!=0 && isset($KID_PERCENTAGE)) {
            $kidp = $KID_PERCENTAGE;
        }
        $par['KIDS_%'][$CHILD_NUM] = $kidp;

        $net = round(($kidp * $prpn) / 100);
        $markup = ($override!=0) ? $override : $year;
        $percentage = round($net * ($markup / 100));
        $gross = $net + $percentage;

        $RATE[$CHILD_NUM]['AGE'] = $age;
        $RATE[$CHILD_NUM]['PER_PERSON'] = $prpn;
        $RATE[$CHILD_NUM]['PERCENTAGE'] = $kidp;

        $RATE[$CHILD_NUM]['NET'] = $net;
        $RATE[$CHILD_NUM]['MARKUP_%'] = $markup;
        $RATE[$CHILD_NUM]['MARKUP_$'] = $percentage;
        $RATE[$CHILD_NUM]['GROSS'] = $gross;
        if (is_array($SPECIAL)) {
            $RATE[$CHILD_NUM]['SPECIAL'] = $this->calculate_Special($db, $arg, $par, $RATE[$CHILD_NUM]);
        }
    }

    function calculate_Final_Rate($db, &$arg, &$par) {
        extract($par);
        $RATE = array();
        if (isset($par['RATE'])&&is_array($par['RATE'])) {
            $adults = (int)$arg["RES_ROOM_{$ROOM}_ADULTS_QTY"];
            $children = (int)$arg["RES_ROOM_{$ROOM}_CHILDREN_QTY"];

            $RATE['GROSS'] = 0;
            $RATE['FINAL'] = 0;
            if (isset($par['RATE']['FULL'])) $RATE['GROSS'] += $par['RATE']['FULL']['GROSS'] * $par['RATE']['FULL']['QTY'];
            if (isset($par['RATE']['SUPL'])) $RATE['GROSS'] += $par['RATE']['SUPL']['GROSS'] * $par['RATE']['SUPL']['QTY'];
            if (isset($par['RATE']['CHILDREN'])) foreach ($par['RATE']['CHILDREN'] as $KEY=>$CDATA) $RATE['GROSS'] += $CDATA['GROSS'];

            if (isset($par['SPECIAL'])&&is_array($par['SPECIAL'])) {
                if (isset($par['RATE']['FULL']['SPECIAL'])) $RATE['FINAL'] += $par['RATE']['FULL']['SPECIAL']['FINAL'] * $par['RATE']['FULL']['QTY'];
                if (isset($par['RATE']['SUPL']['SPECIAL'])) $RATE['FINAL'] += $par['RATE']['SUPL']['SPECIAL']['FINAL'] * $par['RATE']['SUPL']['QTY'];
                if (isset($par['RATE']['CHILDREN'])) foreach ($par['RATE']['CHILDREN'] as $KEY=>$CDATA) $RATE['FINAL'] += $CDATA['SPECIAL']['FINAL'];
            } else {
                $RATE['FINAL'] = $RATE['GROSS'];
            }
        }
        return $RATE;
    }

    function get_Totals($db, &$arg) {
        $INFANTS = 0;
        $RES_NIGHTS = (int)$arg['RES_NIGHTS'];
        for ($ROOM=1; $ROOM <= (int)$arg['RES_ROOMS_QTY']; ++$ROOM) {
            foreach ($arg["RES_ROOM_{$ROOM}_ROOMS"] AS $ROOM_ID => $DATA) {
                if ((int)$DATA["AVAILABLE_NIGHTS"]==$RES_NIGHTS) {
                    $GROSS = 0; $FINAL = 0;
                    for ($t=0; $t < (int)$RES_NIGHTS; ++$t) {
                        $THIS_DAY = addDaysToDate($arg['RES_CHECK_IN'], $t);
                        $RATE = $DATA['NIGTHS'][$THIS_DAY]['RATE'];
                        $GROSS += (int)$RATE['GROSS'];
                        $FINAL += (int)$RATE['FINAL'];

                        $AVG_GROSS_PN = round($GROSS / $RES_NIGHTS);
                        $AVG_FINAL_PN = round($FINAL / $RES_NIGHTS);

                        $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['TOTAL']['GROSS'] = $GROSS;
                        $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['TOTAL']['FINAL'] = $FINAL;
                        $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['TOTAL']['AVG_GROSS_PN'] = $AVG_GROSS_PN;
                        $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['TOTAL']['AVG_FINAL_PN'] = $AVG_FINAL_PN;

                        if (!isset($arg["RES_ROOM_{$ROOM}_INFANTS_QTY"])) {
                            $rinfants = 0;
                            $RATE = $DATA['NIGTHS'][$THIS_DAY]['CLASS']['RATE'];
                            if (isset($RATE['CHILDREN'])) foreach ($RATE['CHILDREN'] as $ind => $CHILD) if ((int)$CHILD['PERCENTAGE']==0) ++$rinfants;
                            $arg["RES_ROOM_{$ROOM}_INFANTS_QTY"] = $rinfants;
                            $INFANTS += $rinfants;
                        }
                    }
                }
            }
        }
        $arg["RES_ROOMS_INFANTS_QTY"] = $INFANTS;
    }

    function get_Custom_Data($db, $arg) {
        $this->get_Children_tbl($db, $arg);
    }

    function get_Children_tbl($db, $arg) {
        global $clsChildrate;
        global $clsGlobal;
        $this->ITEMS['CHILDREN'] = array();
        $RSET = $clsChildrate->getByProperty($db, array("PROP_ID"=>$arg["RES_PROP_ID"]));
        while ($row = $db->fetch_array($RSET['rSet'])) {
            $this->ITEMS['CHILDREN'][$row['ID']] = array (
                "NAME" => $row['NAME'],
                "FROM" => $row['FROM'],
                "TO" => $row['TO'],
                "COUNTS" => $row['COUNTED'],
                "PERCENTAGE" => $row['PERCENTAGE']
            );
        }
    }

    function get_Class_Special_rSet_Off($par, $row) { return 0; }
    function set_Class_Special($db, &$arg, $par, &$SPECIAL) { }
}

$clsAvailability = new LAMA_availability;