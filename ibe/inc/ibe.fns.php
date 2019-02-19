<?
/*
 * Revised: Jan 06, 2013
 *          Sep 02, 2017
 */

function _l($EN="", $SP="", $LAN) {
    return _trans($EN, $SP, $LAN);
}
function _lp($EN="", $SP="", $LAN) {
    print _trans($EN="", $SP="", $LAN);
}
function _trans($EN="", $SP="", $LAN="") {
    global $_IBE_LANG;
    if ($LAN=="") $LAN = $_IBE_LANG;
    return ($LAN=="EN") ? $EN : (($SP=="") ? $EN : $SP);
}
function _fecha($STR, $LAN, $SHORT=false) {
    global $_IBE_LANG;
    if ($LAN=="") $LAN = $_IBE_LANG;

    if ($LAN!="EN") {
        if ($SHORT) {
            $MONTH_EN = array("/jan/i", "/feb/i", "/mar/i", "/apr/i", "/may/i", "/jun/i", "/jul/i", "/aug/i", "/sep/i", "/oct/i", "/nov/i", "/dec/i");
            $MONTH_SP = array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
            $STR = preg_replace($MONTH_EN, $MONTH_SP, $STR);
        } else {
            $MONTH_EN = array("/january/i", "/february/i", "/march/i", "/april/i", "/may/i", "/june/i", "/july/i", "/august/i", "/september/i", "/october/i", "/november/i", "/december/i");
            $MONTH_SP = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
            $STR = preg_replace($MONTH_EN, $MONTH_SP, $STR);

            $DAY_EN = array("/monday/i", "/tuesday/i", "/wednesday/i", "/thursday/i", "/friday/i", "/saturday/i", "/sunday/i");
            $DAY_SP = array("Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado", "Domingo");
            $STR = preg_replace($DAY_EN, $DAY_SP, $STR);
        }
    }

    return $STR;
}
function _title($STR, $LAN) {
    global $_IBE_LANG;
    if ($LAN=="") $LAN = $_IBE_LANG;
    if ($LAN!="EN") {
        $STR = preg_replace(
            array("/Mr/","/Mrs/","/Miss/"),
            array("Sr","Sra","Srita"),
            $STR
        );
    }
    return $STR;
}
function _pref($STR, $LAN) {
    global $_IBE_LANG;
    if ($LAN=="") $LAN = $_IBE_LANG;

    if ($LAN!="EN") {
        $PREFER = array(
            "No preference"=>"Sin preferencias",
            "1 King"=>"1 King",
            "2 Doubles"=>"2 Dobles",
            "Non-smoking"=>"No Fumar",
            "Smoking"=>"Fumar",
            "Anniversary"=>"Aniversario",
            "Honeymoon"=>"Luna de Miel",
            "Birthday"=>"Cumpleaños"
        );
        $STR = isset($PREFER[$STR]) ? $PREFER[$STR] : $STR;
    }

    return $STR;
}


function uploadFile($FIELDNAME, $LOCATION, $ID) {
    $FILENAME = "";
    $TYPE = "";
    $SIZE = "";
    $WIDTH = 0;
    $HEIGHT = 0;
    $EXTENSION = 0;
    if ($_FILES[$FIELDNAME]['name'] != "") {
        //$FILENAME = $ID."_".$_FILES[$FIELDNAME]['name'];
        $FILENAME = str_replace(".","_{$ID}.",$_FILES[$FIELDNAME]['name']);
        $PATHINFO = $_SERVER["DOCUMENT_ROOT"].$LOCATION.$FILENAME;
        move_uploaded_file($_FILES[$FIELDNAME]['tmp_name'], $PATHINFO);

        $PATH_PARTS = pathinfo($PATHINFO);
        $TYPE = $_FILES[$FIELDNAME]['type'];
        $SIZE = $_FILES[$FIELDNAME]['size'];
        $EXTENSION = $PATH_PARTS['extension'];
        /*
        $OLDFILE = isset($_POST['OLD_'.$FIELDNAME]) ? $_POST['OLD_'.$FIELDNAME] : "";
        if ($OLDFILE != "" && $OLDFILE != $FILENAME && file_exists($_SERVER["DOCUMENT_ROOT"].$OLDFILE)) unlink($_SERVER["DOCUMENT_ROOT"].$OLDFILE);
        */
    } else $FILENAME = "";

    //print "TYPE: $TYPE";

    if ($FILENAME != "" && strpos($TYPE,"image") == 0) {
        /*
        $SIZE = getimagesize($_SERVER["DOCUMENT_ROOT"]."/media/".$FILENAME);
        //print "<pre>"; print_r($SIZE); print "</pre>";
        $WIDTH = $SIZE[0];
        $HEIGHT = $SIZE[1];
        */
    }

    return array('name'=>$FILENAME,'type'=>$TYPE,'size'=>$SIZE,'width'=>$WIDTH,'height'=>$HEIGHT,'extension'=>$EXTENSION);
}

function paginationTbl($totalItems, $pageNo, $noPages, $startItem, $fnc="", $viewpages=true) {
    $fnc = $fnc ? $fnc : "ibe.callcenter.changePage";
    $retVal = "";

    $prevPage = $pageNo - 1;
    $nextPage = $pageNo + 1;

    if ($pageNo > 1 && $noPages != 2) $retVal .= "<SPAN class='pagSpan'><A HREF='javascript:void(0)' onClick='{$fnc}(1)'>&#171; FIRST</A></SPAN>";
    if ($prevPage > 0) $retVal .= "<SPAN class='pagSpan'><A HREF='javascript:void(0)' onClick='{$fnc}($prevPage)'>< PREV</A></SPAN>"; 

    $retVal .= "<SPAN class='pagSpan'>Page $pageNo ".($viewpages?"of $noPages (".number_format($totalItems)." Items)":"")."</SPAN>";

    if ($nextPage <= $noPages) $retVal .= "<SPAN class='pagSpan'><A HREF='javascript:void(0)' onClick='{$fnc}($nextPage)'>NEXT ></A></SPAN>"; 
    if ($pageNo < $noPages && $noPages != 2 && $viewpages) $retVal .= "<SPAN class='pagSpan'><A HREF='javascript:void(0)' onClick='{$fnc}($noPages)'>LAST &#187;</A></SPAN>";
        
    return $retVal;
}

function sanitizeText($text) {
    //$text = is_string($text) ? htmlentities($text, ENT_QUOTES, "ISO-8859-15") : $text;
    $text = is_string($text) ? htmlentities($text, ENT_QUOTES, "UTF-8") : $text;
    return $text;
}

function sanitizeArray($arr) {
    $_DATA = array();
    foreach ($arr as $key => $val) if (strpos($key,"%")===false) $_DATA[$key] = sanitizeText($val);
    return $_DATA;
}

function ng_date($str) {
    $return = "";
    $d = explode("-", $str);
    if (count($d)==3) {
        $YY = (int)$d[0];
        $MM = (int)$d[1];
        $DD = (int)$d[2];
        $return = "{$MM}_{$DD}_{$YY}";
    }
    return $return;
}

function _d($STR,$default) {
    $default = ($default=="") ? "---" : $default;
    return ($STR=="") ? $default : $STR;
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

function addMonthsToDate($strDate, $months, $zeros=true) {
    $newDate = strtotime(date("Y-m-d", strtotime($strDate)) . " +{$months} months");
    $return = $zeros ? date("Y-m-d", $newDate) : date("Y-n-j", $newDate);
    return $return;
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

function justNumbers($str) {
    $patterns = array ('/[^\w]+/');
    $replace = array ('');
    return preg_replace($patterns, $replace, $str);
}

function last4($str) {
    $strlen = strlen($str)-4;
    if ($strlen > 4) {
        $last4 = substr($str, $strlen);
        $stars = str_repeat("*",$strlen);
        return $stars.$last4;
    } else {
        return $str;
    }
}

function str2xml($str) {
    $str = str_replace('&amp;','&',$str);
    $str = str_replace('&','&amp;',$str);
    return simplexml_load_string($str);
}

function appendToString($str, $pos="") {
    return ($str) ? $str . ($pos!=""?$pos:"") : "";
}

function getYearsArr($FROM, $TO) {
    $YEARS = array();

    $start = date("Y",strtotime($FROM."-01-01"));
    if ($start<date("Y")) $start = date("Y");

    $end = date("Y",strtotime($TO."-01-01"));
    if ($end>date("Y")+1) $end = date("Y")+1;

    for ($yy=$start;$yy<=$end;++$yy) array_push($YEARS, $yy);
    return $YEARS;
}

?>