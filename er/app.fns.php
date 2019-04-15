<?

date_default_timezone_set('America/New_York');

function excerpt($str) {
    $p = explode(". ", $str);
    $p[0] = "<span class='excerpt'>".$p[0].". </span><span class='description'>";
    $str = implode(". ", $p)."</span>";
    return $str;
}

function shortDate($STR) {
    return date("M j, Y", strtotime($STR));
}

function shortDateTime($STR, $brk=" ") {
    $date = date("M j, Y^h:i:s A", strtotime($STR));
    $date = str_replace("^",$brk,$date);
    return $date;
}

function addZeroToDate($strDate) {
    return date("Y-m-d", strtotime($strDate));
}

function addDaysToDate($strDate, $days, $zeros=true) {
    $newDate = strtotime(date("Y-m-d", strtotime($strDate)) . " +{$days} days");
    $return = $zeros ? date("Y-m-d", $newDate) : date("Y-n-j", $newDate);
    return $return;
}

function daily_rate_details($_AVAILABILITY, $arg) {
    extract($arg);
    $str = daily_rate_openWeekBox(array(
        "DATE_START"=>$_AVAILABILITY['RES_CHECK_IN'],
        "DATE_END"=>$_AVAILABILITY['RES_CHECK_OUT']
    ));
    //return " ok ";break;
    $str .= daily_rate_row($_AVAILABILITY, $arg);
    $str .= daily_rate_closeWeekBox();
    return $str;
}

function daily_rate_openWeekBox($arg) {
    extract($arg);
    $str = "
        <TABLE class='dailyDetailsTbl' border='0' cellpadding='2' cellspacing='2'>
        <TR>
            <TD width='30%' class='dowTbl'>&nbsp;</TD>
            <TD width='10%' class='dowTbl'>".ln("Sun","Dom")."</TD>
            <TD width='10%' class='dowTbl'>".ln("Mon","Lun")."</TD>
            <TD width='10%' class='dowTbl'>".ln("Tue","Mar")."</TD>
            <TD width='10%' class='dowTbl'>".ln("Wed","Mie")."</TD>
            <TD width='10%' class='dowTbl'>".ln("Thr","Jue")."</TD>
            <TD width='10%' class='dowTbl'>".ln("Fri","Vie")."</TD>
            <TD width='10%' class='dowTbl'>".ln("Sat","sab")."</TD>
        </TR>
        <TR class='dailyRow'>
    ";

    $DOW = date("w", strtotime($DATE_START))+1;
    $str .= daily_rate_BoxDates($DATE_START, $DATE_END, $DOW);
    for ($t=1; $t < $DOW; ++$t) $str .= "<TD>&nbsp;</TD>";

    return $str;
}

function daily_rate_closeWeekBox() {
    $str = "</TR></TABLE>";
    return $str;
}

function daily_rate_BoxDates($FROM, $TO, $DOW) {
    $LIMIT = addDaysToDate($FROM, 7-$DOW);
    $TO = (strtotime($LIMIT) < strtotime($TO)) ? $LIMIT : addDaysToDate($TO,-1);

    $return = "<TD nowrap valign='top' style='padding:0 15px 0 5px'>";
    $return .= date("M j", strtotime($FROM));
    if ( $FROM != $TO ) $return .= "&nbsp;-&nbsp;".date("M j", strtotime($TO));
    //$return .= "</TD><TD>Discounts</TD>";

    return $return;
}

function daily_discounts_row($_AVAILABILITY, $arg) {
    extract($arg);
    $return = "";

    $ROOM = $_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ROOMS"][$ROOM_ID];
    if (!isset($ROOM["NIGTHS"])) $ROOM["NIGTHS"] = array();

    $AV_STAY = (int)$_AVAILABILITY['RES_NIGHTS'];
    $AV_DAY = $_AVAILABILITY['RES_CHECK_IN'];
    $AV_TO = $_AVAILABILITY['RES_CHECK_OUT'];
    
    $incr = 0;
    $diff=array();

    do {
        $hasRate = true;
        if (isset($ROOM["NIGTHS"][$AV_DAY])) {
            $NIGHT = $ROOM["NIGTHS"][$AV_DAY];
            if (is_array($NIGHT)) {
                
                for($i=0;$i<count($NIGHT['DISCOUNT']);$i++){
                    if(isset($NIGHT['DISCOUNT'][$i][0])){
                       $s="";
                       $s=$NIGHT['DISCOUNT'][$i][0]['SISTEMA'];
                       $cadena=explode('_', $s);
                       if($cadena[0]=="F" && !in_array($NIGHT['DISCOUNT'][$i][0]['ID_LIN'], $diff) && $NIGHT['DISCOUNT'][$i][0]['APLICA']==1){
                           $return .= "<TR >";
                          // $return .= "<TD>";
                          // $return .=  $AV_DAY;   
                          // $return .= "</TD>";
                          // $return .= "</TR>";

                           //$return .= "<TR>";
                           //$return .= "<TD>";
                           //$return .=  $NIGHT['DISCOUNT'][$i][0]['ID_LIN'];   
                           //$return .= "</TD>";
                           array_push($diff,$NIGHT['DISCOUNT'][$i][0]['ID_LIN']);
                           $return .= "<TD>";
                           $return .=  $NIGHT['DISCOUNT'][$i][0]['VALOR']; 
                           //$return .= "</TD>";                   
                            
                           //$return .= "<TD>";
                           $return .=  $NIGHT['DISCOUNT'][$i][0]['SIMBOLO']; 
                           $return .= "</TD>";   
                           //$return .= "</TR>";
                           $return .= "<TD>";
                           $return .=  "<img height='42' width='50' src='/ibe/img/sale.png'> ";
                           $return .= "</TD>";
                           $return .= "<TD>";
                          
                            $from_fecha=date("M j", strtotime($NIGHT['DISCOUNT'][$i][0]['WINFROM']));              
                            $to_fecha=date("M j", strtotime($NIGHT['DISCOUNT'][$i][0]['WINTO']));

                           //$hoy= date("M j");
                            //if($hoy==$to_fecha){

                            //}
                           $return .=  " Valid {$from_fecha} - {$to_fecha}";

                           $return .= "</TD>";
                           //if($s!="F_INV"){
                           //     $return .= "<TD>";
                           //     $return .=  "&nbsp;&nbsp; left:  <b>{$NIGHT['DISCOUNT'][$i][0]['LEFT']}</b>";
                           //     $return .= "</TD>";
                           //}
                           
                              
                           $return .= "</TR>";

                       }

                    }
                   
                   
                }                
                
            } else $hasRate = false;
        } else $hasRate = false;
        if (!$hasRate) $return .= "<TD></TD>";

        // NEXT DAY
        $DOW = date("w", strtotime($AV_DAY))+1;
        $AV_DAY = addDaysToDate($AV_DAY, 1);
        ++$incr;

        //if ( $DOW == 7 && $incr < $AV_STAY ) {
        //    $return .= "<TR>".daily_rate_BoxDates($AV_DAY, $AV_TO, 1)."</TD>";
        //}
    } while ($incr < $AV_STAY);

    return $return;
}

function daily_rate_row($_AVAILABILITY, $arg) {
    extract($arg);
    $return = "";

    $ROOM = $_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ROOMS"][$ROOM_ID];
    if (!isset($ROOM["NIGTHS"])) $ROOM["NIGTHS"] = array();

    $AV_STAY = (int)$_AVAILABILITY['RES_NIGHTS'];
    $AV_DAY = $_AVAILABILITY['RES_CHECK_IN'];
    $AV_TO = $_AVAILABILITY['RES_CHECK_OUT'];
    
    $incr = 0;
    do {
        $hasRate = true;
        if (isset($ROOM["NIGTHS"][$AV_DAY])) {
            $NIGHT = $ROOM["NIGTHS"][$AV_DAY];
            if (is_array($NIGHT)) {
                $GUESTS = ($_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ROOMS"][$ROOM_ID]['TOTAL']['GROSS_PP']!=0) ? (int)$_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_GUESTS_QTY"] : 1;
                $GROSS = (int)$NIGHT["RATE"]["GROSS"]*$GUESTS;
                $FINAL = (int)$NIGHT["RATE"]["FINAL"]*$GUESTS;
                $return .= "<TD nowrap valign='top'>";
                    if ($GROSS!=$FINAL) $return .= "<div class='GROSS crossed'>$".number_format($GROSS)."</div>";
                    $return .= "<div class='FINAL'>$".number_format($FINAL)."</div>";
                $return .= "</TD>";
            } else $hasRate = false;
        } else $hasRate = false;
        if (!$hasRate) $return .= "<TD>X</TD>";

        // NEXT DAY
        $DOW = date("w", strtotime($AV_DAY))+1;
        $AV_DAY = addDaysToDate($AV_DAY, 1);
        ++$incr;

        if ( $DOW == 7 && $incr < $AV_STAY ) {
            $return .= "<TR>".daily_rate_BoxDates($AV_DAY, $AV_TO, 1)."</TD>";
        }
    } while ($incr < $AV_STAY);

    return $return;
}

function ln($en, $sp) {
    global $RES_LANGUAGE;
    return $RES_LANGUAGE=="EN" ? $en : $sp;
}

function dateDiff($start_date,$end_date="now",$unit="D",$abs=true) {
    /*
    "Y" The number of complete years in the period. 
    "M" The number of complete months in the period. 
    "D" The number of complete days in the period. 
    "MD" The difference between the days in start_date and end_date. The months and years of the dates are ignored. 
    "YM" The difference between the months in start_date and end_date. The days and years of the dates are ignored. 
    "YD" The difference between the days in start_date and end_date. The years of the dates are ignored. 
    */

    //print "<p>start_date: $start_date -- end_date: $end_date</p>";

    $start_date .= " 01:01:01";
    $end_date .= " 01:01:01";

    $unit = strtoupper($unit);
    $start=strtotime($start_date.' GMT');
    if ($start === -1) {
        return "invalid start date";
    }
    
    $end=strtotime($end_date.' GMT');			
    if ($end === -1) {
        return "invalid end date";
    }
    
    if ($start > $end && $abs) {
        $temp = $start;
        $start = $end;
        $end = $temp;
    }
    
    $diff = $end-$start;

    //print "<p>diff: $diff -- $start -- $end</p>";
    
    $day1 = date("j", $start);
    $mon1 = date("n", $start);
    $year1 = date("Y", $start);
    $day2 = date("j", $end);
    $mon2 = date("n", $end);
    $year2 = date("Y", $end);
    
    switch($unit) {
        case "H":
            return floor($diff/(24*60*60)) * 24;
            break;
        case "D":
    // 86400
            //return intval($diff/(24*60*60));
            return floor($diff/(24*60*60));
            break;
        case "M":
            if($day1>$day2) {
                $mdiff = (($year2-$year1)*12)+($mon2-$mon1-1);
            } else {
                $mdiff = (($year2-$year1)*12)+($mon2-$mon1);
            }
            return $mdiff;
            break;
        case "Y":
            if(($mon1>$mon2) || (($mon1==$mon2) && ($day1>$day2))){
                $ydiff = $year2-$year1-1;
            } else {
                $ydiff = $year2-$year1;
            }
            return $ydiff;
            break;
        case "YM":
            if($day1>$day2) {
                if($mon1>=$mon2) {
                    $ymdiff = 12+($mon2-$mon1-1);
                } else {
                    $ymdiff = $mon2-$mon1-1;
                }
            } else {
                if($mon1>$mon2) {
                    $ymdiff = 12+($mon2-$mon1);
                } else {
                    $ymdiff = $mon2-$mon1;
                }
            }
            return $ymdiff;
            break;
        case "YD":
            if(($mon1>$mon2) || (($mon1==$mon2) &&($day1>$day2))) {
                $yddiff = intval(($end - mktime(0, 0, 0, $mon1, $day1, $year2-1))/(24*60*60));						
            } else {
                $yddiff = intval(($end - mktime(0, 0, 0, $mon1, $day1, $year2))/(24*60*60));
            }
            return $yddiff;
            break;
        case "MD":
            if($day1>$day2) {
                $mddiff = intval(($end - mktime(0, 0, 0, $mon2-1, $day1, $year2))/(24*60*60));						
            } else {
                $mddiff = intval(($end - mktime(0, 0, 0, $mon2, $day1, $year2))/(24*60*60));
            }
            return $mddiff;
            break;
        default:
        //return "{Datedif Error: Unrecognized \$unit parameter. Valid values are 'Y', 'M', 'D', 'YM'. Default is 'D'.}";
    }
} 


?>