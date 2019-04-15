<?
/*
 * Revised: Jan 11, 2013
 *          Nov 05, 2017
 *          Jan 29, 2019
 *
 */

class reserv {

    var $showQry = false;
    var $CANCELATION_POLICY = array();
    var $TRANSFER_RULES = array();
    var $DEADLINE = "";

    function __construct() {
        $this->language();
    }

    function language() {
        $this->CANCELATION_POLICY['EN'] = "Where we can meet a request, all changes will be subject to any applicable rate changes or extra costs incurred. Where we are unable to assist you, and you do not wish to proceed with the original booking, we will treat this as a cancellation by you. You may cancel your reservation without a charge until CHECK_IN_MINUS_7. Please note that we will assess a fee equivalent to two nights of your stay if you cancel between CHECK_IN_MINUS_6 through CHECK_IN_MINUS_5. No refund will apply if you cancel or modify your reservation after CHECK_IN_MINUS_5. Non-refund will apply in case of a No Show or early departure. The entire cost of your reservation will be charged to your credit card CHECK_IN_MINUS_30";

        $this->CANCELATION_POLICY['SP'] = "Si desea cambiar su reserva, haremos todo lo posible por ayudarle. Los cambios solicitados estarán sujetos a cualquier cambio de tarifa aplicable, o costes extra en los que se pueda incurrir. Cuando no seamos capaces de ayudarle, y usted no desee continuar con la reserva original, lo entenderemos como una cancelación por su parte. Usted puede cancelar su reserva sin cargo alguno hasta el CHECK_IN_MINUS_7. Tenga en cuenta que se cargará una tasa equivalente a dos noches de estancia, si se cancela entre CHECK_IN_MINUS_6 y CHECK_IN_MINUS_5. El reembolso no aplicará si cancela o modifica su reserva después de este día. No se reembolsará en caso de No Show o salida anticipada. El costo total de la reserva se cargará a su tarjeta de crédito CHECK_IN_MINUS_30";

        $this->TRANSFER_RULES['EN'] = "Where we can meet a request, all changes are subject to any applicable rate changes or extra costs incurred. Where we are unable to assist you, and you do not wish to proceed with the original booking, we will treat this as a cancellation by you. You may cancel your reservation without a charge until CHECK_IN_MINUS_7. No refund will apply if you cancel or modify your reservation after CHECK_IN_MINUS_5. Non-refund will apply in case of a No Show or early departure. The entire cost of your reservation will be charged to your credit card CHECK_IN_MINUS_30.";

        $this->TRANSFER_RULES['SP'] = "Dónde podemos satisfacer la solicitud, todos los cambios están sujetos a variaciones en las tarifas o costos adicionales. Cuando seamos incapaces de brindarle asistencia, y usted no desee continuar con la reserva original, se tratará esto como una cancelación realizada por usted. Usted puede cancelar su reserva sin cargo hasta el CHECK_IN_MINUS_7. No aplica reembolso si se cancela o modifica su reserva después de CHECK_IN_MINUS_5. No se reembolsará en caso de No Show o salida anticipada. El costo total de la reserva se cargará a su tarjeta de crédito CHECK_IN_MINUS_30";
    }

    function deadlineDate($strDate, $days, $in_the_past_txt="", $format="") {
        global $_TODAY;
        $d = split("-", $strDate);
        
        $format = $format==""?"l, F j, Y":$format;
        $deadline = mktime(0, 0, 0, (int)$d[1], (int)$d[2]-$days, (int)$d[0]);
        $diff = dateDiff(date("Y-m-j", $deadline), "now", "D", false) * -1;
        $deadline = $diff<=0&&!empty($in_the_past_txt)?$in_the_past_txt:date($format, $deadline);

        return $deadline;
    }

    function getCancellationModificationPolicy($strDate, $LAN="EN", $whichone="POLICY") {
        $LAN =  !$LAN||empty($LAN) ? "EN" : $LAN;
        if ($whichone=="POLICY") {
          $TXT = $this->CANCELATION_POLICY[$LAN];
        }
        if ($whichone=="RULES") {
          $TXT = $this->TRANSFER_RULES[$LAN];
        }

        $deadline_7  = $this->deadlineDate($strDate, 7);
        $deadline_6  = $this->deadlineDate($strDate, 6);
        $deadline_5  = $this->deadlineDate($strDate, 5);
        $deadline_4  = $this->deadlineDate($strDate, 4);
        $deadline_30 = $this->deadlineDate($strDate, 30, $LAN=="EN"?"tonight":"esta noche");

        $TXT = str_replace("CHECK_IN_MINUS_4", $deadline_4, $TXT);
        $TXT = str_replace("CHECK_IN_MINUS_5", $deadline_5, $TXT);
        $TXT = str_replace("CHECK_IN_MINUS_6", $deadline_6, $TXT);
        $TXT = str_replace("CHECK_IN_MINUS_7", $deadline_7, $TXT);
        $TXT = str_replace("CHECK_IN_MINUS_30", $deadline_30, $TXT);

        $this->DEADLINE = array(
          "DAYS_LEFT" => dateDiff($strDate, "now", "D"),
          "MINUS_4" => $this->deadlineDate($strDate, 4, "", "Y-m-d"),
          "MINUS_5" => $this->deadlineDate($strDate, 5, "", "Y-m-d"),
          "MINUS_6" => $this->deadlineDate($strDate, 6, "", "Y-m-d"),
          "MINUS_7" => $this->deadlineDate($strDate, 7, "", "Y-m-d"),
          "MINUS_30" => $this->deadlineDate($strDate, 30, "", "Y-m-d")
        );
        return _fecha($TXT, $LAN);
    }

    function calculateFees($db, $arg) {
        global $_TODAY;
        $FEE = 0;
        $DAYS_LEFT = dateDiff($_TODAY, $arg['CHECK_IN']);
        $TOTAL_CHARGE = (int)$arg['TOTAL_CHARGE'];
        $NIGHTS = (int)$arg['NIGHTS'];

        if ($DAYS_LEFT < 7) {
            if ($DAYS_LEFT <= 4) {
                $FEE = $TOTAL_CHARGE;
            } else {
                $FEE = floor($TOTAL_CHARGE/$NIGHTS) * 2;
            }
        }

        return ($FEE > $TOTAL_CHARGE) ? $TOTAL_CHARGE : $FEE;
    }

    function newReservationNumber($db, $arg) {
        extract($arg);

        $arg = array('query' => "SELECT * FROM IBE_NEXT_ID");
        $result = dbQuery($db, $arg);

        $row = $db->fetch_array($result['rSet']);
        $nextId = (int)$row['NEXT_RESERVATION'];

        if ($nextId < 100000) {
          mail("jaunsarria@gmail.com","*** ERROR WITH NEXT_ID: $nextId ***","newReservationNumber \nnextId: $nextId");
        }

        $arg = array('query' => "UPDATE IBE_NEXT_ID SET NEXT_RESERVATION=".($nextId+1));
        $result = dbExecute($db, $arg);

        $rnd = mt_rand(1,999999);
        $RES_NUMBER = $PROP_ID.date("y").str_repeat("0",6-strlen($nextId)).$nextId.str_repeat("0",6-strlen($rnd)).$rnd;

        return $RES_NUMBER;
    }

    function createReservationsTable($db, $arg) {
        extract($arg);

        $result = 1;
        if ( !dbTableExists($db, $TABLENAME) ) {
            $query = "
                CREATE TABLE $TABLENAME (
                    `ID` bigint(20) NOT NULL,
                    `NUMBER` varchar(20) NOT NULL,
                    `GUEST_ID` bigint(20) NOT NULL DEFAULT '0',
                    `OWNER_ID` bigint(20) NOT NULL DEFAULT '0',
                    `SOURCE_ID` bigint(20) DEFAULT NULL,
                    `PARENT_ID` bigint(20) DEFAULT NULL,
                    `REFERENCE_ID` varchar(50) DEFAULT NULL,
                    `CHECK_IN` date NOT NULL DEFAULT '0000-00-00',
                    `CHECK_OUT` date NOT NULL DEFAULT '0000-00-00',
                    `NIGHTS` int(11) NOT NULL DEFAULT '0',
                    `ROOMS` int(11) NOT NULL DEFAULT '1',
                    `ADULTS` int(11) NOT NULL DEFAULT '0',
                    `CHILDREN` int(11) NOT NULL DEFAULT '0',
                    `TOTAL` bigint(20) NOT NULL DEFAULT '0',
                    `FEES` bigint(20) NOT NULL DEFAULT '0',
                    `SUPPLEMENT` bigint(20) NOT NULL DEFAULT '0',
                    `ARRIVAL_TIME` varchar(10) DEFAULT NULL,
                    `ARRIVAL_AMPM` varchar(2) DEFAULT NULL,
                    `AIRLINE` varchar(100) DEFAULT NULL,
                    `FLIGHT` varchar(50) DEFAULT NULL,
                    `ARRIVAL` varchar(10) DEFAULT NULL,
                    `ARRIVAL_AP` varchar(2) DEFAULT NULL,                    
                    `DEPARTURE_AIRLINE` varchar(50) DEFAULT NULL,
                    `DEPARTURE_FLIGHT` varchar(50) DEFAULT NULL,
                    `DEPARTURE` varchar(10) DEFAULT NULL,
                    `DEPARTURE_AP` varchar(2) DEFAULT NULL,                    
                    `TRANSFER_TYPE` varchar(6) DEFAULT NULL,
                    `TRANSFER_CAR` bigint(20) NOT NULL DEFAULT '0',                   
                    `TRANSFER_FEE` bigint(20) NOT NULL DEFAULT '0',
                    `COMMENTS` longtext,
                    `CC_COMMENTS` longtext,
                    `HEAR_ABOUT_US` mediumtext,
                    `LANGUAGE` char(2) NOT NULL DEFAULT 'EN',
                    `METHOD` varchar(4) NOT NULL DEFAULT 'CC',
                    `NOTES` longtext,
                    `CANCELLED` datetime DEFAULT NULL,
                    `CREATED` datetime DEFAULT NULL,
                    `CREATED_BY` bigint(20) NOT NULL DEFAULT '0',
                    `MODIFIED` datetime DEFAULT NULL,
                    `MODIFIED_BY` bigint(20) NOT NULL DEFAULT '0',
                    `STATUS` int(11) NOT NULL DEFAULT '1',
                    `EMAILED` tinyint(4) DEFAULT '0',
                    `CC_TYPE` varchar(10) DEFAULT NULL,
                    `CC_NUMBER` varchar(4) DEFAULT NULL,
                    `CC_NAME` varchar(50) DEFAULT NULL,
                    `CC_CODE` varchar(5) DEFAULT NULL,
                    `CC_EXP` varchar(5) DEFAULT NULL,
                    `CC_BILL_ADDRESS` varchar(100) DEFAULT NULL,
                    `CC_BILL_CITY` varchar(50) DEFAULT NULL,
                    `CC_BILL_STATE` varchar(50) DEFAULT NULL,
                    `CC_BILL_COUNTRY` varchar(2) DEFAULT NULL,
                    `CC_BILL_ZIPCODE` varchar(20) DEFAULT NULL,
                    `CC_BILL_EMAIL` varchar(100) DEFAULT NULL,
                    `GEO_IP` varchar(50) NOT NULL,
                    `GEO_COUNTRY_CODE` varchar(2) NOT NULL,
                    `GEO_COUNTRY_NAME` varchar(50) NOT NULL,
                    `GEO_STATE_CODE` varchar(2) NOT NULL,
                    `GEO_CITY` varchar(20) NOT NULL,
                    `GEO_ZIPCODE` varchar(20) NOT NULL,
                    `CLASS_NAMES` varchar(255) NOT NULL,
                    `SPECIAL_NAMES` varchar(255) NOT NULL,
                    `ARRAY` longtext NOT NULL,
                    `NAVISION_STATUS` varchar(10) NOT NULL,
                    `NAVISION_SENT` text NOT NULL,
                    `NAVISION_RESULT` text NOT NULL,
                    `NAVISION_CANCEL` text NOT NULL,
                    `NAVISION_ERROR` text NOT NULL,
                    `CURRENCY_CODE` varchar(6) NOT NULL DEFAULT 'USDUSD',
                    `CURRENCY_QUOTE` float NOT NULL DEFAULT '1',
                    `DINGUS_REPORTED` tinyint(1) NULL DEFAULT '0',
                    `DINGUS_CANCELLED` tinyint(1) NULL DEFAULT '0',
                    `DINGUS_SENT` datetime NULL DEFAULT NULL,
                    PRIMARY KEY (`ID`),
                    KEY `NUMBER` (`NUMBER`),
                    KEY `GUEST_ID` (`GUEST_ID`),
                    KEY `OWNER_ID` (`OWNER_ID`),
                    KEY `ID` (`ID`),
                    KEY `ID-NUMBER` (`ID`,`NUMBER`),
                    KEY `CREATED` (`CREATED`),
                    KEY `METHOD` (`METHOD`),
                    KEY `CHECK_IN` (`CHECK_IN`),
                    KEY `CHECK_OUT` (`CHECK_OUT`)
                )
            ";
            if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
            $arr = array('query' => $query);
            $result = dbExecute($db, $arr);
        }
        return $result;
    }

    function getReservationById($db, $arg) {
        extract($arg);
        $FIELDS = isset($FIELDS) ? $FIELDS : "*";
     
        $query = "SELECT {$FIELDS} FROM {$RES_TABLE} WHERE ID='{$ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getReservationByNumber($db, $arg) {
        extract($arg);
        $FIELDS = isset($FIELDS) ? $FIELDS : "*";
     
        $query = "SELECT {$FIELDS} FROM {$RES_TABLE} WHERE NUMBER='{$NUMBER}'";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getByTotalReference($db, $arg) {
        extract($arg);
        $FIELDS = isset($FIELDS) ? $FIELDS : "*";
     
        $query = "SELECT {$FIELDS} FROM {$RES_TABLE} WHERE TOTAL='{$TOTAL}' AND REFERENCE_ID='{$REFERENCE_ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getSingleReservation($db, $arg) {
        extract($arg);
        $qry = array();
        $FIELDS = isset($FIELDS) ? $FIELDS : "*";
        $aIDs = array();
        foreach ($IDs as $i=>$ID) {
            array_push($aIDs, "ID={$ID}");
        }
        foreach ($YEARS as $ind=>$YEAR) {
            $RES_TABLE = "RESERVATIONS_{$CODE}_{$YEAR}";
            if (dbTableExists($db, $RES_TABLE)) array_push($qry, "SELECT {$FIELDS} FROM {$RES_TABLE} WHERE ".implode(" OR ",$aIDs));
        }
        $query = implode(" UNION ",$qry)." ORDER BY ID ASC";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;        
    }

    function setReservation($db, $arg) {
        global $clsGlobal;
        global $isWEBSERVICE;
        extract($arg);

        $GUEST_ID = $RESERVATION['FORWHOM']['RES_GUEST_ID'];
        $OWNER_ID = ($RESERVATION['FORWHOM']['RES_TA_ID']!=0) ? $RESERVATION['FORWHOM']['RES_TA_ID'] : $RESERVATION['FORWHOM']['RES_GUEST_ID'];
        $SOURCE_ID = ($isWEBSERVICE) ? $OWNER_ID : $_SESSION['AUTHENTICATION']['ID'];
        $CREATED_BY = ($isWEBSERVICE) ? $OWNER_ID : $_SESSION['AUTHENTICATION']['ID'];

        $CLASS_NAMES = array();
        $SPECIAL_NAMES = array();
        foreach ($RESERVATION['RES_ROOMS_SELECTED'] as $IND => $ROOM_ID) {
            $GIVEN_SPECIALS = $arg["RES_ROOM_".($IND+1)."_ROOMS"][$ROOM_ID]["SPECIAL_NAMES"];
            $CLASS_NAMES = array_merge($CLASS_NAMES, $arg["RES_ROOM_".($IND+1)."_ROOMS"][$ROOM_ID]["CLASS_NAMES"] );
            if (is_array($GIVEN_SPECIALS)) $SPECIAL_NAMES = array_merge($SPECIAL_NAMES, $GIVEN_SPECIALS);
        }

        $arr = array(
            'RES_TABLE'=>$RESERVATION['RES_TABLE'],
            'ID'=>$RESERVATION['RES_ID'],
            'NUMBER'=>$RESERVATION['RES_NUMBER'],
            'GUEST_ID'=>$GUEST_ID,
            'OWNER_ID'=>$OWNER_ID,
            'SOURCE_ID'=>$SOURCE_ID,
            'PARENT_ID'=>(isset($RES_REBOOKING)&&is_array($RES_REBOOKING)&&isset($RES_REBOOKING['RES_ID']))?$RES_REBOOKING['RES_ID']:"0",
            'REFERENCE_ID'=>isset($RESERVATION['REFERENCE_ID'])?$RESERVATION['REFERENCE_ID']:"",
            'CREATED_BY'=>$CREATED_BY,
            'CHECK_IN'=>$RES_CHECK_IN,
            'CHECK_OUT'=>$RES_CHECK_OUT,
            'NIGHTS'=>$RES_NIGHTS,
            'ROOMS'=>$RES_ROOMS_QTY,
            'ADULTS'=>$RES_ROOMS_ADULTS_QTY,
            'CHILDREN'=>$RES_ROOMS_CHILDREN_QTY,
            'TOTAL'=>$RESERVATION['RES_TOTAL_CHARGE'],
            'ARRIVAL_TIME'=>$RESERVATION['ARRIVAL_TIME'],
            'ARRIVAL_AMPM'=>$RESERVATION['ARRIVAL_AMPM'],
            'AIRLINE'=>isset($RESERVATION['AIRLINE'])?$RESERVATION['AIRLINE']:"",
            'FLIGHT'=>isset($RESERVATION['FLIGHT'])?$RESERVATION['FLIGHT']:"",
            'ARRIVAL'=>isset($RESERVATION['ARRIVAL'])?$RESERVATION['ARRIVAL']:"",
            'ARRIVAL_AP'=>isset($RESERVATION['ARRIVAL_AP'])?$RESERVATION['ARRIVAL_AP']:"",
            'DEPARTURE_AIRLINE'=>isset($RESERVATION['DEPARTURE_AIRLINE'])?$RESERVATION['DEPARTURE_AIRLINE']:"",
            'DEPARTURE_FLIGHT'=>isset($RESERVATION['DEPARTURE_FLIGHT'])?$RESERVATION['DEPARTURE_FLIGHT']:"",
            'DEPARTURE'=>isset($RESERVATION['DEPARTURE'])?$RESERVATION['DEPARTURE']:"",
            'DEPARTURE_AP'=>isset($RESERVATION['DEPARTURE_AP'])?$RESERVATION['DEPARTURE_AP']:"",
            'TRANSFER_TYPE'=>isset($RESERVATION['TRANSFER_TYPE'])?$RESERVATION['TRANSFER_TYPE']:"",
            'TRANSFER_CAR'=>isset($RESERVATION['TRANSFER_CAR'])?$RESERVATION['TRANSFER_CAR']:"",
            'TRANSFER_FEE'=>isset($RESERVATION['TRANSFER_FEE'])?$RESERVATION['TRANSFER_FEE']:"",
            'COMMENTS'=>isset($RESERVATION['COMMENTS'])?$RESERVATION['COMMENTS']:"",
            'HEAR_ABOUT_US'=>isset($RESERVATION['HEAR_ABOUT_US'])?$RESERVATION['HEAR_ABOUT_US']:"",
            'CC_COMMENTS'=>isset($RESERVATION['CC_COMMENTS'])?$RESERVATION['CC_COMMENTS']:"",
            'LANGUAGE'=>isset($RES_LANGUAGE)?$RES_LANGUAGE:"EN",
            'METHOD'=>isset($RESERVATION['RES_GUESTMETHOD'])?$RESERVATION['RES_GUESTMETHOD']:"CC",
            'GEO_IP'=>isset($RES_GEO_IP)?$RES_GEO_IP:"",
            'GEO_COUNTRY_CODE'=>isset($RES_GEO_COUNTRY_CODE)?$RES_GEO_COUNTRY_CODE:$RES_COUNTRY_CODE,
            'GEO_COUNTRY_NAME'=>isset($RES_GEO_COUNTRY_NAME)?$RES_GEO_COUNTRY_NAME:"",
            'GEO_STATE_CODE'=>isset($RES_STATE_CODE)?$RES_STATE_CODE:"",
            'GEO_CITY'=>isset($RES_GEO_CITY)?$RES_GEO_CITY:"",
            'GEO_ZIPCODE'=>isset($RES_GEO_ZIPCODE)?$RES_GEO_ZIPCODE:"",
            'CLASS_NAMES'=>implode(",",$CLASS_NAMES),
            'SPECIAL_NAMES'=>implode(",",$SPECIAL_NAMES),
            'ARRAY'=>$clsGlobal->jsonEncode($_SESSION['AVAILABILITY']),
            'NAVISION_RESULT'=>isset($NAVISION_RESULT)?$NAVISION_RESULT:"",
            'CURRENCY_CODE'=>isset($RESERVATION['CURRENCY_CODE'])?$RESERVATION['CURRENCY_CODE']:"",
            'CURRENCY_QUOTE'=>isset($RESERVATION['CURRENCY_QUOTE'])?$RESERVATION['CURRENCY_QUOTE']:""
        );
        if (isset($RESERVATION['PAYMENT']) && count($RESERVATION['PAYMENT'])>1) {
            $payment = array(
                'CC_TYPE'=>$RESERVATION['PAYMENT']['CC_TYPE'],
                'CC_NUMBER'=>last4($RESERVATION['PAYMENT']['CC_NUMBER']),
                'CC_NAME'=>$RESERVATION['PAYMENT']['CC_NAME'],
                'CC_CODE'=>$RESERVATION['PAYMENT']['CC_CODE'],
                'CC_EXP'=>$RESERVATION['PAYMENT']['CC_EXP'],
                'CC_BILL_ADDRESS'=>$RESERVATION['PAYMENT']['CC_BILL_ADDRESS'],
                'CC_BILL_CITY'=>$RESERVATION['PAYMENT']['CC_BILL_CITY'],
                'CC_BILL_STATE'=>$RESERVATION['PAYMENT']['CC_BILL_STATE'],
                'CC_BILL_COUNTRY'=>$RESERVATION['PAYMENT']['CC_BILL_COUNTRY'],
                'CC_BILL_ZIPCODE'=>$RESERVATION['PAYMENT']['CC_BILL_ZIPCODE'],
                'CC_BILL_EMAIL'=>$RESERVATION['PAYMENT']['CC_BILL_EMAIL'],
            );
        } else $payment = array();

        $result = $this->saveReservation($db, array_merge($arr,$payment));

        return $result;
    }

    function saveReservation($db, $arg) {
        extract($arg);
        $ID = isset($ID) ? (int)$ID : 0;
         
        $result = $this->createReservationsTable($db, array("TABLENAME"=>$RES_TABLE));
        
        if ((int)$result == 1 || $result=="Table '".strtolower($RES_TABLE)."' already exists" || $result=="Table '".$RES_TABLE."' already exists") {
            
            if ($ID!=0) {
                $result = $this->getReservationById($db, $arg);
            } else $result['iCount'] = 0;

            if ( $result['iCount'] == 0 ) {
                $result = $this->addNewReservation($db, $arg);
            } else {
                $result = $this->modifyReservation($db, $arg);
            }
        }
        return $result;
    }

    function addNewReservation($db, $arg) {
        global $_NOW;
        extract($arg);

        if (!isset($PARENT_ID)) $PARENT_ID = 0;

        $CREATED = $_NOW;
        $arg['MODIFIED'] = $CREATED;

        $query = "INSERT INTO {$RES_TABLE} (
            ID, 
            NUMBER, 
            GUEST_ID, 
            OWNER_ID, 
            SOURCE_ID, 
            PARENT_ID,
            CREATED, 
            CREATED_BY,
            MODIFIED, 
            MODIFIED_BY 
        ) VALUES (
            '{$ID}', 
            '{$NUMBER}', 
            '{$GUEST_ID}', 
            '{$OWNER_ID}', 
            '{$SOURCE_ID}', 
            '{$PARENT_ID}', 
            '{$CREATED}', 
            '{$CREATED_BY}',
            '{$CREATED}', 
            '{$CREATED_BY}' 
        )";

        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result == 1) { 
            return $this->modifyReservation($db, $arg);                
        } else {
            return $result;
        }
    }

    function modifyReservation($db, $arg) {
        global $isWEBSERVICE;
        global $clsGlobal, $_NOW;
        extract($arg);
        $arr = array();

        if (!isset($MODIFIED)) $MODIFIED = $_NOW;

        if (isset($ROOMS) && is_array($ROOMS)) $ROOMS = count($ROOMS);

        if (isset($REFERENCE_ID)) array_push($arr," REFERENCE_ID = '$REFERENCE_ID'");
        if (isset($OWNER_ID)) array_push($arr," OWNER_ID = '$OWNER_ID'");
        if (isset($CHECK_IN)) array_push($arr," CHECK_IN = '$CHECK_IN'");
        if (isset($CHECK_OUT)) array_push($arr," CHECK_OUT = '$CHECK_OUT'");
        if (isset($NIGHTS)) array_push($arr," NIGHTS = '$NIGHTS'");
        if (isset($ROOMS)) array_push($arr," ROOMS = '$ROOMS'");
        if (isset($ADULTS)) array_push($arr," ADULTS = '$ADULTS'");
        if (isset($CHILDREN)) array_push($arr," CHILDREN = '$CHILDREN'");
        if (isset($TOTAL)) array_push($arr," TOTAL = '$TOTAL'");
        if (isset($FEES)) array_push($arr," FEES = '$FEES'");
        if (isset($SUPPLEMENT)) array_push($arr," SUPPLEMENT = '$SUPPLEMENT'");
        if (isset($ARRIVAL_TIME)) array_push($arr," ARRIVAL_TIME = '$ARRIVAL_TIME'");
        if (isset($ARRIVAL_AMPM)) array_push($arr," ARRIVAL_AMPM = '$ARRIVAL_AMPM'");
        if (isset($AIRLINE)) array_push($arr," AIRLINE = '$AIRLINE'");
        if (isset($FLIGHT)) array_push($arr," FLIGHT = '$FLIGHT'");        
        if (isset($ARRIVAL)) array_push($arr," ARRIVAL = '$ARRIVAL'");
        if (isset($ARRIVAL_AP)) array_push($arr," ARRIVAL_AP = '$ARRIVAL_AP'");
        if (isset($DEPARTURE_AIRLINE)) array_push($arr," DEPARTURE_AIRLINE = '$DEPARTURE_AIRLINE'");
        if (isset($DEPARTURE_FLIGHT)) array_push($arr," DEPARTURE_FLIGHT = '$DEPARTURE_FLIGHT'");
        if (isset($DEPARTURE)) array_push($arr," DEPARTURE = '$DEPARTURE'");
        if (isset($DEPARTURE_AP)) array_push($arr," DEPARTURE_AP = '$DEPARTURE_AP'");        
        if (isset($TRANSFER_TYPE)) array_push($arr," TRANSFER_TYPE = '$TRANSFER_TYPE'");
        if (isset($TRANSFER_CAR)) array_push($arr," TRANSFER_CAR = '$TRANSFER_CAR'");        
        if (isset($TRANSFER_FEE)) array_push($arr," TRANSFER_FEE = '$TRANSFER_FEE'");
        if (isset($COMMENTS)) array_push($arr," COMMENTS = '$COMMENTS'");
        if (isset($HEAR_ABOUT_US)) array_push($arr," HEAR_ABOUT_US = '$HEAR_ABOUT_US'");
        if (isset($CC_COMMENTS)) array_push($arr," CC_COMMENTS = '$CC_COMMENTS'");
        if (isset($LANGUAGE)) array_push($arr," LANGUAGE = '$LANGUAGE'");
        if (isset($METHOD)) array_push($arr," METHOD = '$METHOD'");
        if (isset($NOTES)) array_push($arr," NOTES = '$NOTES'");
        if (isset($CANCELLED)) array_push($arr," CANCELLED = '$CANCELLED'");
        if (isset($CREATED)) array_push($arr," CREATED = '$CREATED'");
        if (isset($STATUS)) array_push($arr," STATUS = '$STATUS'"); // 1=Booked, -1=Rebooked, 0=Cancelled, 2=No Show

        if (isset($GEO_IP)) array_push($arr," GEO_IP = '$GEO_IP'");
        if (isset($GEO_COUNTRY_CODE)) array_push($arr," GEO_COUNTRY_CODE = '$GEO_COUNTRY_CODE'");
        if (isset($GEO_COUNTRY_NAME)) array_push($arr," GEO_COUNTRY_NAME = '$GEO_COUNTRY_NAME'");
        if (isset($GEO_STATE_CODE)) array_push($arr," GEO_STATE_CODE = '$GEO_STATE_CODE'");
        if (isset($GEO_CITY)) array_push($arr," GEO_CITY = '$GEO_CITY'");
        if (isset($GEO_ZIPCODE)) array_push($arr," GEO_ZIPCODE = '$GEO_ZIPCODE'");

        if (isset($CLASS_NAMES)) array_push($arr," CLASS_NAMES = '$CLASS_NAMES'");
        if (isset($SPECIAL_NAMES)) array_push($arr," SPECIAL_NAMES = '$SPECIAL_NAMES'");
        
        if (isset($ARRAY)) array_push($arr," ARRAY = '".$clsGlobal->cleanJSON($ARRAY)."'");

        if (isset($NAVISION_STATUS)) array_push($arr," NAVISION_STATUS = \"{$NAVISION_STATUS}\"");
        if (isset($NAVISION_SENT)) array_push($arr," NAVISION_SENT = \"{$NAVISION_SENT}\"");
        if (isset($NAVISION_RESULT)) array_push($arr," NAVISION_RESULT = \"{$NAVISION_RESULT}\"");
        if (isset($NAVISION_CANCEL)) array_push($arr," NAVISION_CANCEL = \"{$NAVISION_CANCEL}\"");
        if (isset($NAVISION_ERROR)) array_push($arr," NAVISION_ERROR = \"{$NAVISION_ERROR}\"");

        if (isset($CC_TYPE)) array_push($arr," CC_TYPE = '$CC_TYPE'");
        if (isset($CC_NUMBER)) array_push($arr," CC_NUMBER = '".last4($CC_NUMBER)."'");
        if (isset($CC_NAME)) array_push($arr," CC_NAME = '$CC_NAME'");
        if (isset($CC_CODE)) array_push($arr," CC_CODE = '$CC_CODE'");
        if (isset($CC_EXP)) array_push($arr," CC_EXP = '$CC_EXP'");
        if (isset($CC_BILL_ADDRESS)) array_push($arr," CC_BILL_ADDRESS = '$CC_BILL_ADDRESS'");
        if (isset($CC_BILL_CITY)) array_push($arr," CC_BILL_CITY = '$CC_BILL_CITY'");
        if (isset($CC_BILL_STATE)) array_push($arr," CC_BILL_STATE = '$CC_BILL_STATE'");
        if (isset($CC_BILL_COUNTRY)) array_push($arr," CC_BILL_COUNTRY = '$CC_BILL_COUNTRY'");
        if (isset($CC_BILL_ZIPCODE)) array_push($arr," CC_BILL_ZIPCODE = '$CC_BILL_ZIPCODE'");
        if (isset($CC_BILL_EMAIL)) array_push($arr," CC_BILL_EMAIL = '$CC_BILL_EMAIL'");

        if (isset($CURRENCY_CODE)) array_push($arr," CURRENCY_CODE = '$CURRENCY_CODE'");
        if (isset($CURRENCY_QUOTE)) array_push($arr," CURRENCY_QUOTE = '$CURRENCY_QUOTE'");

        if (isset($EMAILED) && (int)$EMAILED!=0) {
            array_push($arr," EMAILED = '$EMAILED'"); // 0=No, 1=Pre, 2=Post
        } else {
            if (isset($_SESSION['AUTHENTICATION']['ID'])) array_push($arr," MODIFIED_BY = '{$_SESSION['AUTHENTICATION']['ID']}'");
            if (isset($MODIFIED)) array_push($arr," MODIFIED = '$MODIFIED'");
        }

        $WHERE = isset($RES_NUM) ? "NUMBER='$RES_NUM' AND STATUS='1'" : "ID='$ID'";

        $query = "UPDATE {$RES_TABLE} SET ".join(", ",$arr)." WHERE $WHERE";
        //print "<p class='s_notice top_msg'>$query</p>\n<pre>";print_r($arg);print "</pre>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $result;
    }

    function createReservationRoomOptsTable($db, $arg) {
        extract($arg);

        $result = 1;
        if ( !dbTableExists($db, $TABLENAME) ) {
            $query = "
                CREATE TABLE $TABLENAME (
                    `ROOM_KEY` bigint(20) NOT NULL,
                    `RES_ID` bigint(20) NOT NULL DEFAULT '0',
                    `RES_NUM` varchar(20) DEFAULT NULL,
                    `ROOM_ID` bigint(20) NOT NULL,
                    `ROOM_CHARGE` bigint(20) NOT NULL,
                    `GUEST_TITLE` varchar(10) DEFAULT NULL,
                    `GUEST_FIRSTNAME` varchar(50) DEFAULT NULL,
                    `GUEST_LASTNAME` varchar(50) DEFAULT NULL,
                    `GUEST_REPEATED` varchar(20) DEFAULT NULL,
                    `GUEST_BEDTYPE` bigint(20) DEFAULT NULL,
                    `GUEST_BABYCRIB` tinyint(4) DEFAULT NULL,
                    `GUEST_SMOKING` varchar(15) DEFAULT NULL,
                    `GUEST_OCCASION` varchar(255) DEFAULT NULL,
                    `UPDATED` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    KEY `RES_ID` (`RES_ID`),
                    KEY `RES_NUM` (`RES_NUM`),
                    KEY `ROOM_KEY` (`ROOM_KEY`),
                    KEY `ALL_KEYS` (`ROOM_KEY`,`RES_ID`,`RES_NUM`)
                )
            ";
            if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
            $arr = array('query' => $query);
            $result = dbExecute($db, $arr);
        }
        return $result;
    }

    function getReservationRoomOptsById($db, $arg) {
        extract($arg);
     
        $query = "SELECT * FROM ($RES_TABLE) WHERE ROOM_KEY='{$ROOM_KEY}' AND RES_ID='{$RES_ID}' AND RES_NUM='$RES_NUM'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function saveReservationRoomOpts($db, $arg) {
        extract($arg);
        $result = $this->createReservationRoomOptsTable($db, array("TABLENAME"=>$RES_TABLE));
        if ((int)$result == 1 || $result=="Table '".strtolower($RES_TABLE)."' already exists" || $result=="Table '".$RES_TABLE."' already exists") {
            $result = $this->getReservationRoomOptsById($db, $arg);
            if ( $result['iCount'] == 0 ) {
                $result = $this->addNewReservationRoomOpts($db, $arg);
            } else {
                $result = $this->modifyReservationRoomOpts($db, $arg);
            }
        }
        return $result;
    }

    function addNewReservationRoomOpts($db, $arg) {
        extract($arg);

        $query = "INSERT INTO {$RES_TABLE} ( ROOM_KEY, RES_ID, RES_NUM, ROOM_ID, ROOM_CHARGE ) VALUES ( '$ROOM_KEY','$RES_ID','$RES_NUM','$ROOM_ID','$ROOM_CHARGE' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result == 1) { 
            return $this->modifyReservationRoomOpts($db, $arg);                
        } else {
            return $result;
        }
    }

    function modifyReservationRoomOpts($db, $arg) {
        extract($arg);
        $arr = array();

        if (isset($GUEST_TITLE)) array_push($arr," GUEST_TITLE = '$GUEST_TITLE'");
        if (isset($GUEST_FIRSTNAME)) array_push($arr," GUEST_FIRSTNAME = '$GUEST_FIRSTNAME'");
        if (isset($GUEST_LASTNAME)) array_push($arr," GUEST_LASTNAME = '$GUEST_LASTNAME'");
        if (isset($GUEST_REPEATED)) array_push($arr," GUEST_REPEATED = '".implode(",",is_array($GUEST_REPEATED)?$GUEST_REPEATED:array())."'");
        if (isset($GUEST_BEDTYPE)) array_push($arr," GUEST_BEDTYPE = '$GUEST_BEDTYPE'");
        if (isset($GUEST_BABYCRIB)) array_push($arr," GUEST_BABYCRIB = '$GUEST_BABYCRIB'");
        if (isset($GUEST_SMOKING)) array_push($arr," GUEST_SMOKING = '$GUEST_SMOKING'");
        if (isset($GUEST_OCCASION)) array_push($arr," GUEST_OCCASION = '$GUEST_OCCASION'");

        $query = "UPDATE {$RES_TABLE} SET ".join(", ",$arr)." WHERE ROOM_KEY='{$ROOM_KEY}' AND RES_ID='{$RES_ID}' AND RES_NUM='$RES_NUM'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $result;
    }

    function createReservationRoomInventoryTable($db, $arg) {
        extract($arg);

        // e.g. RESERVATIONS_XPC_2011_ROOM_INVENTORY
        $result = 1;
        if ( !dbTableExists($db, $TABLENAME) ) {
            $query = "
                CREATE TABLE $TABLENAME (
                    `ID` bigint(20) NOT NULL,
                    `RES_ID` bigint(20) NOT NULL DEFAULT '0',
                    `RES_NUM` varchar(20) DEFAULT NULL,
                    `RES_DATE` datetime DEFAULT NULL,
                    `ROOM_ID` bigint(20) NOT NULL,
                    PRIMARY KEY (`ID`),
                    KEY `RES_ID` (`RES_ID`),
                    KEY `RES_NUM` (`RES_NUM`),
                    KEY `RES_DATE` (`RES_DATE`),
                    KEY `ROOM_ID` (`RES_DATE`)
                )
            ";
            if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
            $arr = array('query' => $query);
            $result = dbExecute($db, $arr);
        }
        if ((int)$result == 1) {
            $result = $this->createRoomsSoldView($db, $arg);
        }
        return $result;
    }

    function createRoomsSoldView($db, $arg) {
        extract($arg);

        // e.g. V_XPC_2011_ROOM_SOLD
        $patterns = array();
        $patterns[0] = '/RESERVATIONS/';
        $patterns[1] = '/ROOM_INVENTORY/';
        $replacements = array();
        $replacements[2] = 'V';
        $replacements[1] = 'ROOMS_SOLD';
        $VIEWNAME = preg_replace($patterns, $replacements, $TABLENAME);
        $result = 1;
        if ( !dbTableExists($db, $VIEWNAME) ) {
            $query = "
                CREATE ALGORITHM = UNDEFINED VIEW `{$VIEWNAME}` AS 
                SELECT INVENTORY.RES_DATE, INVENTORY.ROOM_ID, count( * ) AS SOLD, MAX_ROOMS, ROOMS.NAME_EN, ROOMS.NAME_SP
                FROM {$TABLENAME} AS INVENTORY
                JOIN ROOMS ON ROOMS.ID = INVENTORY.ROOM_ID
                GROUP BY RES_DATE, ROOM_ID
            ";
            if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
            $arr = array('query' => $query);
            $result = dbExecute($db, $arr);
        }
        return $result;
    }

    function getReservationRoomInventoryById($db, $arg) {
        extract($arg);
     
        $query = "SELECT * FROM {$RES_TABLE} WHERE ID='{$ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function saveReservationRoomInventory($db, $arg) {
        extract($arg);
        $ID = isset($ID) ? (int)$ID : 0;

        $result = $this->createReservationRoomInventoryTable($db, array("TABLENAME"=>$RES_TABLE));
        if ((int)$result == 1 || $result=="Table '".strtolower($RES_TABLE)."' already exists" || $result=="Table '".$RES_TABLE."' already exists") {
            if ($ID!=0) {
                $result = $this->getReservationRoomInventoryById($db, $arg);
            } else $result['iCount'] = 0;
            
            if ( $result['iCount'] == 0 ) {
                $result = $this->addNewReservationRoomInventory($db, $arg);
            } else {
                $result = $this->modifyReservationRoomInventory($db, $arg);
            }
        }
        return $result;
    }

    function addNewReservationRoomInventory($db, $arg) {
        extract($arg);

        $query = "INSERT INTO {$RES_TABLE} ( ID ) VALUES ( '{$ID}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result == 1) { 
            return $this->modifyReservationRoomInventory($db, $arg);                
        } else {
            return $result;
        }
    }

    function modifyReservationRoomInventory($db, $arg) {
        extract($arg);
        $arr = array();

        if (isset($RES_ID)) array_push($arr," RES_ID = '$RES_ID'");
        if (isset($RES_NUM)) array_push($arr," RES_NUM = '$RES_NUM'");
        if (isset($RES_DATE)) array_push($arr," RES_DATE = '$RES_DATE'");
        if (isset($ROOM_ID)) array_push($arr," ROOM_ID = '$ROOM_ID'");
        if (isset($QTY)) array_push($arr," QTY = '$QTY'");

        $query = "UPDATE {$RES_TABLE} SET ".join(", ",$arr)." WHERE ID='$ID'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $result;
    }

    function deleteReservationRoomInventory($db, $arg) {
        extract($arg);
     
        $query = "DELETE FROM {$RES_TABLE} WHERE RES_ID='{$ID}'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arg = array('query' => $query);
        $result = dbExecute($db, $arg);

        return $result;
    }

    function getGuest($db, $arg) {
        global $clsGuest;
        extract($arg);
        $GUEST = array();
        $RSET = $clsGuest->getById($db, array("ID"=>$ID));
        if ( $RSET['iCount'] != 0 ) {
            $row = $db->fetch_array($RSET['rSet']);
            $FIELDS = array('TITLE','FIRSTNAME','LASTNAME','LANGUAGE','ADDRESS','CITY','STATE','COUNTRY','ZIPCODE','PHONE','EMAIL','MAILING_LIST');
            foreach($FIELDS as $FIELD) {
                $GUEST[$FIELD] = $row[$FIELD];
            }
        }
        return $GUEST;
    }

    function getTA($db, $arg) {
        global $clsTA;
        extract($arg);
        $TA = array();
        $RSET = $clsTA->getById($db, array("ID"=>$ID));
        if ( $RSET['iCount'] != 0 ) {
            $row = $db->fetch_array($RSET['rSet']);
            $FIELDS = array('AGENCY_NAME','TITLE','FIRSTNAME','LASTNAME','EMAIL','AGENCY_PHONE','AGENCY_ADDRESS','AGENCY_CITY','AGENCY_STATE','AGENCY_ZIPCODE','AGENCY_COUNTRY');
            foreach($FIELDS as $FIELD) {
                $TA[$FIELD] = $row[$FIELD];
            }
        }
        return $TA;
    }

    function getProperties($db, $arg=array()) {
        extract($arg);
        $FIELDS = isset($FIELDS) ? $FIELDS : "*";
        $query = "SELECT {$FIELDS} FROM PROPERTIES";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arg = array('query' => $query);
        $result = dbQuery($db, $arg);
        return $result;
    }

    function createSearchReservationView($db, $arg) {
        extract($arg);
        $result = 1;

        $arr = array('query' => "SET GLOBAL log_bin_trust_function_creators = 1");
        $result = dbExecute($db, $arr);

        $VIEWNAME = "V_SEARCH_{$CODE}_{$YEAR}";
        $TABLENAME = "RESERVATIONS_{$CODE}_{$YEAR}";

        // e.g. V_SEARCH_XPC_2011
        if ( !dbTableExists($db, $VIEWNAME) ) {
            $query = "
                CREATE ALGORITHM = UNDEFINED VIEW $VIEWNAME AS 

                SELECT 
                    R.ID, R.NUMBER, \"{$CODE}\" AS HOTEL, 
                    R.SOURCE_ID, _fn_getSourceStr(R.SOURCE_ID,R.GUEST_ID,R.OWNER_ID) as SOURCE_STR, CONCAT(_fn_getSourceStr(R.SOURCE_ID,R.GUEST_ID,R.OWNER_ID),_fn_getSourceStr2nd(R.SOURCE_ID,R.GUEST_ID,R.OWNER_ID)) as SECOND_SOURCE_STR,
                    R.STATUS, _fn_getStatusStr(R.STATUS,R.CHECK_IN) as STATUS_STR, R.EMAILED as EMAILED, 
                    R.NIGHTS, R.ROOMS,
                    R.GUEST_ID, R.OWNER_ID, R.PARENT_ID, R.CHECK_IN, R.CHECK_OUT, R.CREATED, R.CREATED_BY, R.MODIFIED, R.MODIFIED_BY, R.CANCELLED, R.CC_COMMENTS, 
                    IF (R.GUEST_ID=R.OWNER_ID, R.GUEST_ID, R.OWNER_ID) AS CONTACT_ID, 
                    IFNULL (G.FIRSTNAME, A.FIRSTNAME) AS CONTACT_FIRSTNAME, IFNULL (G.LASTNAME, A.LASTNAME) AS CONTACT_LASTNAME,
                    IFNULL (G.PHONE, A.AGENCY_PHONE) AS CONTACT_PHONE, IFNULL (G.EMAIL, A.EMAIL) AS CONTACT_EMAIL, R.METHOD

                FROM $TABLENAME AS R

                LEFT JOIN GUESTS AS G ON G.ID = R.OWNER_ID
                LEFT JOIN TRAVEL_AGENTS AS A ON A.ID = R.OWNER_ID
            ";
            if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
            $arr = array('query' => $query);
            $result = dbExecute($db, $arr);
        }
        return $result;
    }

    function searchReservation($db, $arg) {
        extract($arg);
        //print "<pre>";print_r($arg);print "</pre>";
        //IFNULL()

        //ob_start();print_r($arg);$output = ob_get_clean();mail("jaunsarria@gmail.com","searchReservation args",$output);

        $qry = array();
        $WHERE = array();
        //$INDIVIDUAL = isset($INDIVIDUAL) ? $INDIVIDUAL : 0;
        $GROUPED = isset($GROUPED) ? $GROUPED : 0;
        $PROPERTIES = $this->getProperties($db);
        while ($prow = $db->fetch_array($PROPERTIES['rSet'])) { $PROPERTIES[$prow['ID']] = $prow; }
        //print "<pre>";print_r($PROPERTIES);print "</pre>";

        if (!isset($PROP_IDs)) {
            $PROP_IDs = array();
            foreach ($PROPERTIES as $PID=>$PDATA) array_push($PROP_IDs, $PID);
        }

        if (isset($FROM)) $FROM = addZeroToDate($FROM);
        if (isset($TO)) $TO = addZeroToDate($TO);

        //print $FROM . " - " . $TO;

        if ($GROUPED==0) {
            #if ($RESNUM!="") array_push($WHERE, "view.NUMBER LIKE '%{$RESNUM}%'");
            if ($RESNUM!="") array_push($WHERE, "view.NUMBER = '{$RESNUM}'");
            if (isset($isOWNER) && isset($OWNER_ID) && $OWNER_ID!="") {
                array_push($WHERE, "OWNER_ID = '{$OWNER_ID}'");
            }
            //mail("jaunsarria@gmail.com","searchReservation 1",implode(" - ",$WHERE));
        } else {
            if ($VIEWBY=="arrival") {
                $DATE_FIELD = "CHECK_IN";
            } else if ($VIEWBY=="departure") {
                $DATE_FIELD = "CHECK_OUT";
            } else {
                $DATE_FIELD = "CREATED";
            }

            array_push($WHERE, "({$DATE_FIELD} >= '{$FROM} 00:00:00' AND {$DATE_FIELD} <= '{$TO} 23:59:59')");

            if (isset($OWNER_ID) && $OWNER_ID!="") {
                array_push($WHERE, "OWNER_ID = '{$OWNER_ID}'");
            } else {
                /*
                if (isset($LASTNAME) && $LASTNAME!="") array_push($WHERE, "CONTACT_LASTNAME LIKE '%{$LASTNAME}%'");
                if (isset($PHONE) && $PHONE!="") array_push($WHERE, "CONTACT_PHONE LIKE '%{$PHONE}%'");
                if (isset($EMAIL) && $EMAIL!="") array_push($WHERE, "CONTACT_EMAIL LIKE '%{$EMAIL}%'");
                if (isset($RESNUM) && $RESNUM!="") array_push($WHERE, "view.NUMBER LIKE '%{$RESNUM}%'");
                */
                if (isset($LASTNAME) && $LASTNAME!="") array_push($WHERE, "CONTACT_LASTNAME = '$LASTNAME'");
                if (isset($PHONE) && $PHONE!="") array_push($WHERE, "CONTACT_PHONE = '$PHONE'");
                if (isset($EMAIL) && $EMAIL!="") array_push($WHERE, "CONTACT_EMAIL = '$EMAIL'");
                if (isset($RESNUM) && $RESNUM!="") array_push($WHERE, "view.NUMBER = '$RESNUM'");
            }

            if (isset($RESTYPE_IDs) && count($RESTYPE_IDs)) {
                $STATUS = array();
                foreach ($RESTYPE_IDs as $i=>$STATUS_STR) array_push($STATUS, "STATUS_STR='{$STATUS_STR}'");
                array_push($WHERE, "(".implode(" OR ",$STATUS).")");
            }

            $MADEBY = array();
            if (isset($MADEBY_IDs) && count($MADEBY_IDs)) {
                foreach ($MADEBY_IDs as $i=>$MADEBY_ID) { 
                    if ((int)$MADEBY_ID==1) $MADEBY_STR = "GP";
                    if ((int)$MADEBY_ID==2) $MADEBY_STR = "TA";
                    if ((int)$MADEBY_ID==3) $MADEBY_STR = "CC";
                    array_push($MADEBY, "SOURCE_STR='{$MADEBY_STR}'");
                }
            }
            if (isset($AGENT_IDs) && count($AGENT_IDs)) {
                foreach ($AGENT_IDs as $i=>$AGENT_ID) array_push($MADEBY, "CREATED_BY='{$AGENT_ID}'");
            }
            array_push($WHERE, "(".implode(" OR ",$MADEBY).")");

            if (isset($isPreStay)&&$isPreStay) array_push($WHERE, " EMAILED<>'1' ");
            if (isset($isPostStay)&&$isPostStay) array_push($WHERE, " EMAILED<>'2' ");

            //print "WHERE: <pre>";print_r($WHERE);print "</pre>";
            //mail("jaunsarria@gmail.com","searchReservation 2",implode(" - ",$WHERE));
        }

        if (isset($FROM)&&isset($TO)) {
            $YEARS = array();
            //for ($YEAR=2011; $YEAR<=date("Y"); ++$YEAR) array_push($YEARS, $YEAR);

            $YEAR_START = (int)substr($FROM,0,4);
            if ($YEAR_START<2008) $YEAR_START = 2008;

            $YEAR_END = (int)substr($TO,0,4);
            if ($YEAR_END>date("Y")+2) $YEAR_END = date("Y")+2;

            if (isset($VIEWBY) && $VIEWBY == "arrival") {
                $YEAR_START -= 1;
            } 
            
            for ($YEAR=$YEAR_START;$YEAR<=$YEAR_END;++$YEAR) array_push($YEARS, $YEAR);
        }

        //print "YEARS: <pre>";print_r($YEARS);print "</pre>";
        //ob_start();print_r($YEARS);$output = ob_get_clean();mail("juan.sarria@everlivesolutions.com","YEARS",$output);

        foreach ($YEARS as $ind=>$YEAR) {
            foreach ($PROP_IDs as $ind=>$PROP_ID) {
                if ($PROP_ID!="" && (int)$YEAR>2008) {
                    $CODE = $PROPERTIES[$PROP_ID]['CODE'];
                    $VIEWNAME = "V_SEARCH_{$CODE}_{$YEAR}";
                    $TABLENAME = "RESERVATIONS_{$CODE}_{$YEAR}";
                    //print "<p class='s_notice top_msg'>$TABLENAME</p>";
                    if (dbTableExists($db, $TABLENAME)) {
                        $this->createSearchReservationView($db, array("CODE"=>$CODE,"YEAR"=>$YEAR));
                        $this->searchReservationQuery($db, array(
                            "VIEWNAME"=>$VIEWNAME,
                            "TABLENAME"=>$TABLENAME,
                            "GROUPED"=>$GROUPED,
                            "WHERE"=>implode(" AND ",$WHERE)
                        ), $qry);
                    } else {
                         //print "DOES NOT EXISTS ";
                    }
                }
            }
        }

        if (count($qry)!=0) {
            $query = implode(" UNION ",$qry);

            if (isset($sortBy) && $sortBy != "") $query .= " ORDER BY $sortBy";

            if (isset($startItem) && isset($itemsPerPage)) {
                $query .= " LIMIT $startItem, $itemsPerPage";
            } else if (isset($LIMIT)) $query .= $LIMIT;

            $arg = array('query' => $query);
            //print "<p class='s_notice top_msg'>$query</p>";
            //mail("juan.sarria@everlivesolutions.com","query",$query);
            $result = dbQuery($db, $arg);
        } else {
            $result = null;
        }
        return $result;
    }

    function searchPrePostBooked($db, $arg=array()) {
        //print "<pre>";print_r($arg);print "</pre>";
        extract($arg);
        $DAYS = isset($DAYS) ? $DAYS : 7;
        $DATE = addDaysToDate($TODAY, $DAYS);
        $_THIS_YEAR = (int)date("Y", strtotime($DATE));
        $par = array (
            "GROUPED"=>1,
            //"PROP_IDs"=>array('1','2','3','4'),
            "RESTYPE_IDs"=>$RESTYPE_IDs,
            "MADEBY_IDs"=>array('1','2','3'),
            "FROM" => $DATE,
            "TO" => $DATE,
            "YEARS"=>getYearsArr($_THIS_YEAR-1, $_THIS_YEAR+1),
            "RESNUM" => ""
        );
        $par['isPreStay'] = (isset($isPreStay)) ? $isPreStay : false;
        $par['isPostStay'] = (isset($isPostStay)) ? $isPostStay : false;
        $par['VIEWBY'] = $par['isPreStay'] ? "arrival" : "departure";
        return $this->searchReservation($db, $par);
    }

    function searchOwnerReservations($db, $arg) {
        global $_TODAY;
        extract($arg);
        $_THIS_YEAR = (int)date("Y", strtotime($_TODAY));
        $par = array (
            "isOWNER"=>1,
            "OWNER_ID"=>$OWNER_ID,
            "FROM" => "2011-01-01",
            "TO" => $_TODAY,
            //"PROP_IDs"=>array('1','2','3','4'),
            "YEARS"=>array($_THIS_YEAR-1,$_THIS_YEAR,$_THIS_YEAR+1),
            "VIEWBY" => "activity",
            "RESNUM" => "",
            "LIMIT" => $LIMIT
        );
        return $this->searchReservation($db, $par);
    }

    function getOwnerProperty($db, $arg) {
        global $clsGlobal;
        extract($arg);
        $PROPERTY = array();
        $RSET = $this->searchOwnerReservations($db, array("OWNER_ID"=>$OWNER_ID,"LIMIT"=>"LIMIT 0,1"));
        if ( $RSET['iCount'] != 0 ) {
            $row = $db->fetch_array($RSET['rSet']);
            $query = "SELECT * FROM PROPERTIES WHERE CODE='{$row['HOTEL']}'";
            $PSET = dbQuery($db, array('query' => $query));
            if ( $PSET['iCount'] != 0 ) $PROPERTY = $clsGlobal->cleanUp_rSet_Array($db->fetch_array($PSET['rSet']));
        }
        return $PROPERTY;
    }

    function searchReservationQuery($db, $arg, &$qry) {
        extract($arg);

        if ((int)$GROUPED==0) {
            array_push(
                $qry, "
                    SELECT *
                    FROM $VIEWNAME view
                    WHERE {$WHERE}
                "
            );
        } else {
            $short = true;

            if (!$short) {
              // ONLY LAST RECORD
              array_push(
                  $qry, "
                      SELECT  view.*
                      FROM    (
                                  SELECT  DISTINCT `NUMBER`
                                  FROM    $TABLENAME
                              ) md
                      JOIN    $VIEWNAME view
                      ON      view.ID = 
                              (
                                  SELECT  ID
                                  FROM    $TABLENAME mi
                                  WHERE   mi.`NUMBER` = md.`NUMBER`
                                  ORDER BY
                                          mi.ID DESC
                                  LIMIT 1
                              )
                      WHERE {$WHERE}
                  "
              );

            } else {

              array_push(
                  $qry, "
                      SELECT *
                      FROM $VIEWNAME view
                      WHERE {$WHERE}
                  "
              );

            }
        }

    }

    function getCSV($db, $arg) {
        extract($arg);
        $TABLE = "RESERVATIONS_{$CODE}_{$YEAR}";
        $query = "SELECT {$FIELDS} FROM {$TABLE}";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function searchIndividual($db, $arg) {
        extract($arg);

        $PROP_IDs = array();
        $PROPERTIES = $this->getProperties($db, array("FIELDS"=>"ID, CODE"));
        while ($prow = $db->fetch_array($PROPERTIES['rSet'])) { 
            $PROPERTIES[$prow['ID']] = $prow; 
            array_push($PROP_IDs,$prow['ID']);
        }
        //print "<pre>";print_r($PROP_IDs);print "</pre>";

        $start = (int)substr($FROM,0,4);
        $end = (int)substr($TO,0,4);

        $arr = array();

        for ($YEAR=$start; $YEAR<=$end; ++$YEAR) {
            foreach ($PROP_IDs as $ind=>$PROP_ID) {
                if ($PROP_ID!="" && (int)$YEAR>2008) {
                    $CODE = $PROPERTIES[$PROP_ID]['CODE'];
                    $TABLENAME = "RESERVATIONS_{$CODE}_{$YEAR}";
                    //print "<p class='s_notice top_msg'>$TABLENAME</p>";
                    if (dbTableExists($db, $TABLENAME)) {
                        array_push($arr, "SELECT ID,NUMBER,'{$CODE}' AS HOTEL,_fn_getStatusStr(STATUS,CHECK_IN) as STATUS_STR,CHECK_IN,CHECK_OUT,CREATED,MODIFIED,ROOMS,PARENT_ID FROM ".$TABLENAME." WHERE NUMBER = '".$RESNUM."'");
                    }
                }
            }
        }

        if (count($arr)!=0) {
            $query = implode(" UNION ", $arr) . "  ORDER BY ID DESC LIMIT 0,1" ;
            $arg = array('query' => $query);
            //print "<p class='s_notice top_msg'>$query</p>";
            $result = dbQuery($db, $arg);
            return $result;
        }
    }

    function searchSimple($db, $arg) {
        extract($arg);
        //print "<pre>";print_r($arg);print "</pre>";

        $PROPERTIES = $this->getProperties($db, array("FIELDS"=>"ID, CODE"));
        while ($prow = $db->fetch_array($PROPERTIES['rSet'])) { 
            $PROPERTIES[$prow['ID']] = $prow; 
        }
        $start = (int)substr($FROM,0,4);
        $end = (int)substr($TO,0,4);

        $arr = array();

        for ($YEAR=$start; $YEAR<=$end; ++$YEAR) {
            foreach ($PROP_IDs as $ind=>$PROP_ID) {
                if ($PROP_ID!="") {
                    $CODE = $PROPERTIES[$PROP_ID]['CODE'];
                    $TABLENAME = "RESERVATIONS_{$CODE}_{$YEAR}";
                    $WHERE = array();
                    //print "<p class='s_notice top_msg'>$TABLENAME</p>";
                    if (dbTableExists($db, $TABLENAME)) {
                        if ($RESNUM!="") { $WHERE[] = "NUMBER = '{$RESNUM}'"; }
                        if ($LASTNAME!="") { $WHERE[] = "G.LASTNAME LIKE '{$LASTNAME}%'"; }
                        if ($PHONE!="") { $WHERE[] = "G.PHONE LIKE '{$PHONE}%'"; }
                        if ($EMAIL!="") { $WHERE[] = "G.EMAIL LIKE '%{$EMAIL}%'"; }
                        $WHERE[] = "(R.CREATED >= '{$FROM} 00:00:00' AND R.CREATED <= '{$TO} 23:59:59')";
                        $QRYSTR = "SELECT R.STATUS,R.ID,NUMBER,GUEST_ID,{$PROP_ID} AS HOTEL,CHECK_IN,CHECK_OUT,R.CREATED,G.LASTNAME,G.FIRSTNAME,G.PHONE,G.EMAIL FROM {$TABLENAME} AS R JOIN GUESTS AS G ON G.ID = GUEST_ID WHERE ".implode(" AND ",$WHERE);
                        array_push($arr, $QRYSTR);
                    }
                }
            }
        }

        if (count($arr)!=0) {
            $query = implode(" UNION ", $arr) . "  ORDER BY {$sortBy} LIMIT 0, 30 #searchSimple" ;
            $arg = array('query' => $query);
            //print "<p class='s_notice top_msg'>$query</p>";
            $result = dbQuery($db, $arg);
            return $result;
        }
    }


}

global $clsReserv;
$clsReserv = new reserv;
?>