<?

/*
 * Revised: Dec 12, 2011
 *          Jul 31, 2014
 *          Apr 23, 2015
 *          Aug 10, 2016
 */

class availability {

    var $showQry = false;
    var $ITEMS = array('MESSAGES'=>array());
    var $finalTotals = array();
    
    function get_Availability($db, $arg) {
        global $isWEBSERVICE;        
        global $clsSetup;

        //$this->escribe("par_mod_fin",print_r($arg,true));
        //exit;
        $BLOCKED_IPs = $clsSetup->getBlockedIPs($db);
        $arg['BLOCKED_IPs'] = $BLOCKED_IPs[$arg["RES_PROP_ID"]];

        $arg['CURRENCY'] = $clsSetup->getCurrency($db);
       
        $limit=$clsSetup->getLimitmprice($db);
        $arg['LIMIT_MPRICE'] = $limit[$arg["RES_PROP_ID"]];
        $this->escribe('get_Availability101010_Limpio',print_r($arg,true));
        $this->get_Rooms_Total_Guests($db, $arg);
        //$this->escribe('get_Availability02',print_r($arg,true));
        $this->get_Rooms_By_Occupancy($db, $arg);
        //$this->escribe('get_Availability03',print_r($arg,true));
        $this->get_Month_Markup($db, $arg);  //por mes no encuentra algun valor, si tiene margen configurado por año
        //$this->escribe('get_Availability04',print_r($arg,true));
        $this->get_Rooms_Classes($db, $arg);
        //$this->escribe('get_Availability05',print_r($arg,true));
        $this->get_Rooms_Rates($db, $arg);
        //$this->escribe('get_Availability06',print_r($arg,true));
        $this->get_Totals($db, $arg);
        //$this->escribe('get_Availability07',print_r($arg,true));
        $this->get_Property($db, $arg);
        //$this->escribe('get_Availability08',print_r($arg,true));
        $this->get_Properties($db, $arg);
        //$this->escribe('get_Availability09',print_r($arg,true));
        $this->get_Trasnfer($db, $arg);
        $this->get_Custom_Data($db, $arg);

        if ($isWEBSERVICE) {
            $this->get_Cancellation_Policy($db, $arg);
            $this->get_Property_Images($db, $arg);
        }

        $this->wrap_up($db, $arg);
        $this->escribe('arg_final',print_r($arg,true));
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
                    $GROSS_PP = round($DATA['TOTAL']['GROSS_PP']); #(int)$DATA['TOTAL']['GROSS_PP'];
                    $FINAL_PP = round($DATA['TOTAL']['FINAL_PP']); #(int)$DATA['TOTAL']['FINAL_PP'];
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

    function get_Relation_Discounts_query($db,$sistema){
        $query="SELECT * FROM RELATIONMOD WHERE SYSTEM='{$sistema}' AND IS_ACTIVE=1 AND VALUE=1";
       $this->escribe("Salida_relacion_query",print_r($query,true));
        return dbQuery($db, array('query' => $query));
    }

    function get_Relation_Discounts_rSet($db,$RSET) {
        
        $SALIDA=array();        
        while ($row = $db->fetch_array($RSET['rSet'])) {           
                $SALIDA []= array(
                    "SISTEMA"=>$row['SYSTEM'],
                    "SISTEMAREL"=>$row['SYSTEM_REL'],
                    "ORDEN"=>$row['ORDER'],
                    "RELACION"=>$row['VALUE']
                                    
                );                
                            
        }
        
        return $SALIDA;
    }

    function get_Relation_Discounts($db,$par) {
        //$this->escribe("inicia_funcion",print_r($par,true));
        //exit;
        $RSET = $this->get_Relation_Discounts_query($db, $par);
        return $this->get_Relation_Discounts_rSet($db,$RSET);
        //return $RSET;
    }

    function get_Class_Discounts_Query($db, &$arg, $par) {
        //$this->escribe("arg_00001",print_r($arg,true)); 
        
        //SE DEBE AGREGAR EL PARAMETRO DE TIPO DE ACCESO
        $WHERE = (isset($par['WH'])) ? $par['WH'] : "";
        $SIS=(isset($par['SY'])) ? $par['SY'] : "";
        $ENTORNO=(isset($arg['ENTORNO'])) ? $arg['ENTORNO'] : "";
        //$GEO=(isset($arg['RES_COUNTRY_CODE'])) ? $arg['RES_COUNTRY_CODE'] : "";
        $code=(isset($arg['RES_COUNTRY_CODE'])) ? $arg['RES_COUNTRY_CODE'] : "";
        $GEO=$this->get_group_geo($db,$code);
        $_entorno="";
        $hour=date("H:i:s");
        if($ENTORNO!=''){
            $_entorno.=" AND ENVIRONMENT like '%{$ENTORNO}%'";
        }        
        $query="
            SELECT TABLA2.*  FROM (
        SELECT PRIORITY,CAB_FROM,CAB_TO,WIN_FROM,WIN_TO,SYSTEM,NAME_EN,TYPE,LINMODPRICE.* FROM LINMODPRICE INNER JOIN HEADMODPRICE ON LINMODPRICE.ID_CAB=HEADMODPRICE.ID_CAB
      WHERE";

        $query.="((RATECLASES = '{$par['CLASS_ID']}' or RATECLASES =0) AND ";
        if($SIS=='C_'){
            //if($arg['RES_COUPON_CODE']!=""){ //para encontrar offerta especial
                $query.=" (PROMOCODE = '{$arg['RES_COUPON_CODE']}') AND ";
            //}
            //else{
            //    $query.=" (PROMOCODE = '') AND ";
            //}
        }
        $cadena=explode('_', $SIS);                   
        if($cadena[0]=="F"){

            $query.="(ROOM LIKE '%.{$par['ROOM_ID']}.%' or ROOM ='0')) AND ";

            if($GEO!=""){
                $query.=" (GEOCOUNTRY like '%.{$GEO}.%' OR GEOCOUNTRY = '' OR GEOCOUNTRY IS NULL) AND";
            }
            $query.="(((CAB_FROM = '0000-00-00 00:00:00' OR '{$par['DATE']} 00:00:00' >= CAB_FROM) AND 
                (CAB_TO = '0000-00-00 00:00:00' OR '{$par['DATE']} 00:00:00' <= CAB_TO))
                OR (CAB_FROM IS NULL AND CAB_TO IS NULL)) AND ";
        }
        else{
             $query.="(ROOM = '{$par['ROOM_ID']}' or ROOM ='0')) AND ";

            if($GEO!=""){
                $query.=" (GEOCOUNTRY = '{$GEO}' OR GEOCOUNTRY = '' OR GEOCOUNTRY IS NULL) AND";
            }
            $query.="(CAB_FROM = '0000-00-00 00:00:00' OR '{$par['DATE']} 00:00:00' >= CAB_FROM) AND 
                (CAB_TO = '0000-00-00 00:00:00' OR '{$par['DATE']} 00:00:00' <= CAB_TO) AND "; 
        }       
            
        $query.="  PROP_ID = '{$arg['RES_PROP_ID']}' AND
                (WIN_FROM = '0000-00-00 00:00:00' OR '{$arg['RES_DATE']} {$hour}' >= WIN_FROM) AND 
                (WIN_TO = '0000-00-00 00:00:00' OR '{$arg['RES_DATE']} {$hour}' <= WIN_TO) AND ";
        //if($SIS!='F_'){
        //    $query.="(CAB_FROM = '0000-00-00 00:00:00' OR '{$par['DATE']} 00:00:00' >= CAB_FROM) AND 
        //        (CAB_TO = '0000-00-00 00:00:00' OR '{$par['DATE']} 00:00:00' <= CAB_TO) AND ";
        //} 
                
        $query.="((MIN_NIGHT = 0 AND  MAX_NIGHT = 0) OR ({$arg['RES_NIGHTS']} >= MIN_NIGHT AND {$arg['RES_NIGHTS']} <= MAX_NIGHT))AND
                ((MIN_ROOM= 0 AND 0 = MAX_ROOM) OR (MIN_ROOM<= {$arg['RES_ROOMS_QTY']} AND {$arg['RES_ROOMS_QTY']} <= MAX_ROOM)) AND
                LINMODPRICE.IS_ACTIVE=1 AND LINMODPRICE.IS_APPLIED=0 AND HEADMODPRICE.IS_ACTIVE=1 AND {$WHERE} {$_entorno}";
        $query.=" AND PRIORITY=(
                         -- fILTRO POR LA MENOR PRIORITY
                        SELECT MIN(HEADMODPRICE.PRIORITY) FROM LINMODPRICE INNER JOIN HEADMODPRICE ON LINMODPRICE.ID_CAB=HEADMODPRICE.ID_CAB
                    WHERE";

        $query.="((RATECLASES = '{$par['CLASS_ID']}' or RATECLASES =0) AND ";
        if($SIS=='C_'){
            //if($arg['RES_COUPON_CODE']!=""){ //para encontrar offerta especial
                $query.=" (PROMOCODE = '{$arg['RES_COUPON_CODE']}') AND ";
            //}
            //else{
            //    $query.=" (PROMOCODE = '') AND ";
            //}
        }
       $cadena=explode('_', $SIS);                   
        if($cadena[0]=="F"){

            $query.="(ROOM LIKE '%.{$par['ROOM_ID']}.%' or ROOM ='0')) AND ";

            if($GEO!=""){
                $query.=" (GEOCOUNTRY like '%.{$GEO}.%' OR GEOCOUNTRY = '' OR GEOCOUNTRY IS NULL) AND";
            }
            $query.="(((CAB_FROM = '0000-00-00 00:00:00' OR '{$par['DATE']} 00:00:00' >= CAB_FROM) AND 
                (CAB_TO = '0000-00-00 00:00:00' OR '{$par['DATE']} 00:00:00' <= CAB_TO))
                OR (CAB_FROM IS NULL AND CAB_TO IS NULL)) AND ";
        }
        else{
             $query.="(ROOM = '{$par['ROOM_ID']}' or ROOM ='0')) AND ";

            if($GEO!=""){

                $query.=" (GEOCOUNTRY = '{$GEO}' OR GEOCOUNTRY = '' OR GEOCOUNTRY IS NULL) AND";
            }
            $query.="(CAB_FROM = '0000-00-00 00:00:00' OR '{$par['DATE']} 00:00:00' >= CAB_FROM) AND 
                (CAB_TO = '0000-00-00 00:00:00' OR '{$par['DATE']} 00:00:00' <= CAB_TO) AND "; 
        }     
            
        $query.="  PROP_ID = '{$arg['RES_PROP_ID']}' AND
                (WIN_FROM = '0000-00-00 00:00:00' OR '{$arg['RES_DATE']} {$hour}' >= WIN_FROM) AND 
                (WIN_TO = '0000-00-00 00:00:00' OR '{$arg['RES_DATE']} {$hour}' <= WIN_TO) AND ";
        //if($SIS!='F_'){
            //$query.="(CAB_FROM = '0000-00-00 00:00:00' OR '{$par['DATE']} 00:00:00' >= CAB_FROM) AND 
            //    (CAB_TO = '0000-00-00 00:00:00' OR '{$par['DATE']} 00:00:00' <= CAB_TO) AND ";
        //} 
                
        $query.="((MIN_NIGHT = 0 AND  MAX_NIGHT = 0) OR ({$arg['RES_NIGHTS']} >= MIN_NIGHT AND {$arg['RES_NIGHTS']} <= MAX_NIGHT))AND
                ((MIN_ROOM= 0 AND 0 = MAX_ROOM) OR (MIN_ROOM<= {$arg['RES_ROOMS_QTY']} AND {$arg['RES_ROOMS_QTY']} <= MAX_ROOM)) AND
                LINMODPRICE.IS_ACTIVE=1 AND LINMODPRICE.IS_APPLIED=0 AND HEADMODPRICE.IS_ACTIVE=1 AND {$WHERE} {$_entorno}";

        
        $order="    ) 
                ORDER BY VALUE DESC
                -- ORDENO DESCENDENTE PARA TOMAR SOLO EL VALOR DEL PRIMERO, QUE SERIA EL MAXIMO CON LA MENOR PRIORIDAD
                ) AS TABLA2 LIMIT 1 ";

        $query.=$order;
        
       //return "$query"; 
        $this->escribe("query_00002",print_r($query,true)); 
         if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        return dbQuery($db, array('query' => $query));
    }

    function get_Class_Special_rSet($db, &$arg, &$par, $RSET){
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
    function get_count_Discounts_query($db,$id_cab){
        $query="SELECT count(*) as TOTAL FROM LINMODPRICE WHERE IS_ACTIVE=1 AND IS_ARCHIVE=0 AND ID_CAB={$id_cab}";
       //$this->escribe("Salida_relacion_query",print_r($query,true));
        return dbQuery($db, array('query' => $query));
    }
    function get_count_Discounts_query_rSet($db, $RSET) {        
        $salida=0;
        while ($row = $db->fetch_array($RSET['rSet'])) {

               $salida=$row['TOTAL'];         
        }        
        return $salida;
    }

    function get_count_Discounts($db,$id_cab) {
        //$this->escribe("inicia_funcion",$sistema."--".$id_cab);
        //$this->escribe("inicia_funcion",print_r($sistema,true));
        //exit;
        $RSET = $this->get_count_Discounts_query($db,$id_cab);
        return $this->get_count_Discounts_query_rSet($db,$RSET);
        //return "1";
    }

    function get_Class_Discounts_rSet($db, &$arg, &$par, $RSET) {
        $auxdb=$db;
        $SALIDA=array();
        $SPECIAL = "X";
        while ($row = $db->fetch_array($RSET['rSet'])) {           
                $SALIDA []= array(
                    "ID_CAB"=>$row['ID_CAB'],
                    "ID_LIN"=>$row['ID_LIN'],
                    "SISTEMA"=>$row['SYSTEM'],
                    "REFERENCE"=>$row['NAME_EN'],                   
                    "VALOR"=>$row['VALUE'],
                    "SIMBOLO"=>$row['SYMBOL'],
                    "OPERACION"=>$row['TYPE'],
                    "PRIORIDAD"=>$row['PRIORITY'],
                    "FROM"=>$row['CAB_FROM'],
                    "TO"=>$row['CAB_TO'],
                    "WINFROM"=>$row['WIN_FROM'],
                    "WINTO"=>$row['WIN_TO'],
                    //"LEFT"=>$this->get_count_Discounts($auxdb,$row['SYSTEM'],$row['ID_CAB']),
                    "APLICA"=>1
                );                
                //break; //solo se toma el de mayor provecho            
        }
        if(empty($SALIDA)){
            $SALIDA []=$SPECIAL;
        }
        return $SALIDA;
    }
    function get_Class_Discounts_All_Apli_rSet($db, &$arg, &$par, $RSET) {
        
        $SALIDA=array();
        $SPECIAL = "X";
        while ($row = $db->fetch_array($RSET['rSet'])) {           
                $SALIDA []= array(                    
                    "SISTEMA"=>$row['SYSTEM']                   
                );                
                //break; //solo se toma el de mayor provecho            
        }
        if(empty($SALIDA)){
            $SALIDA []=$SPECIAL;
        }
        return $SALIDA;
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
        //mail("juanmcanul@gmail.com","spcieals",$query);
        $this->escribe('get_Availability20',print_r($query,true));
        return dbQuery($db, array('query' => $query));
    }

    
    
    function get_Class_Special_rSet_Off($par, $row) {
        return (int)$par['RATE']['NET'] * ((int)$row['OFF'] / 100);
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
    function get_Class_Discounts_Private($db, &$arg, $par) {        
        
        $RSET = $this->get_Class_Discounts_Query($db, $arg, $par);
        return $this->get_Class_Discounts_rSet($db, $arg, $par, $RSET);
        //return $RSET;
    }
    function get_Class_Discounts_All_Apli($db, &$arg, $par) {       

        $RSET = $this->get_Class_Discounts_All_Apli_Query($db, $arg, $par);
        return $this->get_Class_Discounts_All_Apli_rSet($db, $arg, $par, $RSET);
        //return $RSET;
    }
    function get_group_geo($db,$code){
        $salida="";
        $query="SELECT * FROM countries where CODE='{$code}'";
        //$this->escribe("geo_001",print_r($query,true)); 
        $result = dbQuery($db, array('query' => $query));        
        if ( $result['iCount'] != 0 ) {        
        while ($row = $db->fetch_array($result['rSet'])) {
            $salida=$row['GROUP'];
            }
        }
        if($salida=='AA'){
            $salida=$code;
        }
        return $salida;

    }
    function get_Class_Discounts_All_Apli_Query($db, &$arg, $par) {
        //$this->escribe("arg_00001",print_r($arg,true)); 
        
        //SE DEBE AGREGAR EL PARAMETRO DE TIPO DE ACCESO
        $WHERE = (isset($par['WH'])) ? $par['WH'] : "";
        $ENTORNO=(isset($arg['ENTORNO'])) ? $arg['ENTORNO'] : "";
        $code=(isset($arg['RES_COUNTRY_CODE'])) ? $arg['RES_COUNTRY_CODE'] : "";
        $GEO=".".$this->get_group_geo($db,$code).".";
        $_entorno="";
        $hour=date("H:i:s");
        if($ENTORNO!=''){
            $_entorno.=" AND ENVIRONMENT like '%{$ENTORNO}%'";
        }        
        
        $query="SELECT distinct(SYSTEM) FROM LINMODPRICE INNER JOIN HEADMODPRICE ON LINMODPRICE.ID_CAB=HEADMODPRICE.ID_CAB
                    WHERE";

        $query.="((RATECLASES = '{$par['CLASS_ID']}' or RATECLASES =0) AND ";
        if($arg['RES_COUPON_CODE']!=""){ //para encontrar offerta especial
            $query.=" (PROMOCODE = '{$arg['RES_COUPON_CODE']}' or PROMOCODE ='') AND ";
        }
        $query.="(ROOM like '%.{$par['ROOM_ID']}.%' or ROOM ='0' or ROOM = '{$par['ROOM_ID']}')) AND ";
        if($GEO!=""){
            $geo_limpio=str_replace(".","", $GEO);
            $query.=" (GEOCOUNTRY ='{$geo_limpio}' OR GEOCOUNTRY like '%{$GEO}%' OR GEOCOUNTRY = '' OR GEOCOUNTRY IS NULL) AND";
        } 
            
        $query.="  PROP_ID = '{$arg['RES_PROP_ID']}' AND 
                (WIN_FROM = '0000-00-00 00:00:00' OR '{$arg['RES_DATE']} {$hour}' >= WIN_FROM) AND 
                (WIN_TO = '0000-00-00 00:00:00' OR '{$arg['RES_DATE']} {$hour}' <= WIN_TO) AND ";

        $query.="(((CAB_FROM = '0000-00-00 00:00:00' OR '{$par['DATE']} 00:00:00' >= CAB_FROM) AND 
                (CAB_TO = '0000-00-00 00:00:00' OR '{$par['DATE']} 00:00:00' <= CAB_TO))
                OR (CAB_FROM IS NULL AND CAB_TO IS NULL)) AND ";
        $query.="((MIN_NIGHT = 0 AND  MAX_NIGHT = 0) OR ({$arg['RES_NIGHTS']} >= MIN_NIGHT AND {$arg['RES_NIGHTS']} <= MAX_NIGHT)) AND
                ((MIN_ROOM= 0 AND 0 = MAX_ROOM) OR (MIN_ROOM<= {$arg['RES_ROOMS_QTY']} AND {$arg['RES_ROOMS_QTY']} <= MAX_ROOM)) AND
                LINMODPRICE.IS_ACTIVE=1 AND LINMODPRICE.IS_APPLIED=0 AND HEADMODPRICE.IS_ACTIVE=1 AND HEADMODPRICE.IS_ARCHIVE=0 AND {$WHERE} {$_entorno}";
        $order=" ORDER BY PRIORITY ASC";
        $query.=$order;
        $this->escribe("query_00002_APLI",print_r($query,true)); 
       //return "$query"; 
        
         if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        return dbQuery($db, array('query' => $query));
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

    function get_ListDiscountToApli($db,&$list){       
        //$this->escribe("lista_descuentos_entrada",print_r($list,true)); 
        for($i=0;$i<count($list);$i++){
            if($list[$i][0]['APLICA']==1){        
                $relacion=$this->get_Relation_Discounts($db,$list[$i][0]['SISTEMA']);
                if(empty($relacion)){
                     $list[$i][0]['APLICA']=0;
                }
                  
                $l_rel=array();
                for($h=0;$h<count($relacion);$h++){
                    $l_rel[]=$relacion[$h]['SISTEMAREL'];                    
                }
                //$this->escribe("vuelta_{$i}_{$list[$i][0]['SISTEMA']}",print_r($l_rel,true));
                if(!empty($l_rel)){
                    for($j=0;$j<count($list);$j++){
                        //drump($list[$j][0]['SISTEMA']);
                        $var_text="";
                        $var_text=trim($list[$j][0]['SISTEMA']);
                        if($list[$j][0]['APLICA']==1){
                            if(in_array($var_text,$l_rel)){
                                $list[$j][0]['APLICA']=1;
                            }else{
                                $list[$j][0]['APLICA']=0;
                            }
                        }                                  
                        
                    }

                }
                //$this->escribe("vuelta_{$i}_{$list[$i][0]['SISTEMA']}_Array",print_r($list,true));
            }           
           
        }        
        $this->escribe("lista_descuentos_salida",print_r($list,true));  
    }

    function get_Class_Discounts($db, &$arg, $par) {

        //buscar todos los sistemas que estan condigurados y que aplican
        $par['WH']="(CODE LIKE '%{$arg['_CODE']}%')";
        $SYS=$this->get_Class_Discounts_All_Apli($db, $arg, $par);            
        
        //Traer el mejor descuento, por tipo de sistema
        $listaDescuentos= array();        
        for($i=0;$i<count($SYS);$i++){
            $par['WH']=" (SYSTEM = '{$SYS[$i]['SISTEMA']}' and CODE LIKE '%{$arg['_CODE']}%') ";
            $par['SY']="{$SYS[$i]['SISTEMA']}";
            $tmp=$this->get_Class_Discounts_Private($db, $arg, $par);
            if($tmp[0]!='X'){
                $listaDescuentos[]=$tmp;
            }            

        }
        //agregamos sus contadores
        //"LEFT"=>$this->get_count_Discounts($auxdb,$row['SYSTEM'],$row['ID_CAB']),
        for($i=0;$i<count($listaDescuentos);$i++){           
            if($listaDescuentos[$i][0]['APLICA']==1){
               $listaDescuentos[$i][0]['LEFT']=$this->get_count_Discounts($db,$listaDescuentos[$i][0]['ID_CAB']); 
            }
        }
        //$this->escribe("lista_descuentos_salida",print_r($listaDescuentos,true));
        $this->get_ListDiscountToApli($db,$listaDescuentos);        
        if(empty($listaDescuentos)){ 
            $listaDescuentos=array("ID_CAB"=>"X");
        }
        //$this->escribe("lista_descuentos_salida",print_r($listaDescuentos,true));
        return $listaDescuentos;
        //return $listaDescuentos;
           
    }

    function get_Class_Special($db, &$arg, $par) {
        /*
            1. Private
            2. State
            3. Regular
        */
        $SPECIAL = "X";
        #echo "<pre>";print_r($arg);echo "</pre>";
        if (isset($arg['RES_SPECIAL_CODE']) && $arg['RES_SPECIAL_CODE']!="") {
            $SPECIAL = $this->get_Class_Special_Private($db, $arg, $par);
        }
        if (!is_array($SPECIAL)
            && isset($arg['RES_COUNTRY_CODE'])
            && $arg['RES_COUNTRY_CODE'] == "US"
            && isset($arg['RES_STATE_CODE'])
            && $arg['RES_STATE_CODE'] != "") {
            $SPECIAL = $this->get_Class_Special_State($db, $arg, $par);
        }
        if (!is_array($SPECIAL) && isset($arg['RES_COUNTRY_CODE'])) {
            $SPECIAL = $this->get_Class_Special_Regular($db, $arg, $par);
        }

        $this->set_Class_Special($db, $arg, $par, $SPECIAL);

        return $SPECIAL;
    }

    function set_Class_Special($db, &$arg, $par, &$SPECIAL) {
        if (is_array($SPECIAL)) {
            $off = (float)$SPECIAL['OFF_$'];
            $net = (float)$par['RATE']['NET'] - $off;
            $markup = round($net * ((float)$par['RATE']['MARKUP_%'] / 100));

            $SPECIAL['NET'] = $net;
            $SPECIAL['MARKUP_$'] = $markup;
            $SPECIAL['FINAL'] = round($net + $markup);
        }
    }

    //function get_Class_Special_rSet_Off($par, $row) {
    //    return round((int)$par['RATE']['NET'] * ((int)$row['OFF'] / 100));
    //}
	
	//+021018
	function get_Guest_Total($db,$arg) {
		   /*

			   [RES_ROOM_1_ADULTS_QTY] => 2
			   [RES_ROOM_1_CHILD_AGE_5] => 0
			   [RES_ROOM_1_CHILDREN_QTY] => 0
		   */
		   $sumatoria=0;
		   for ($ROOM=1; $ROOM <= (int)$arg['RES_ROOMS_QTY']; ++$ROOM) {
			   $cnt = 0;

			   //foreach ($arg["RES_ROOM_{$ROOM}_ROOMS"] AS $ROOM_ID => $DATA) {

					   $sumatoria+=$arg['RES_ROOM_'.$ROOM.'_ADULTS_QTY'];
					  // $sumatoria+=$arg['RES_ROOM_'.$ROOM.'_CHILD_AGE_5'];
                       if (isset($arg['RES_ROOM_'.$ROOM.'_CHILDREN_QTY'])) {
                            $sumatoria+=$arg['RES_ROOM_'.$ROOM.'_CHILDREN_QTY'];
                       } else {
                            $arg['RES_ROOM_'.$ROOM.'_CHILDREN_QTY'] = 0;
                       }
					   
			   //}
		   }
		   return $sumatoria;
	   }	
	//-021018
    function get_Total_Discounts($db, $arg, &$par, $rate_base,&$listaDescuentos) {
        $DISCOUNT="X";
        //procesamos el array de descuentos
		//+arq
		$this->escribe("ARG",print_r($arg,true));
		$this->escribe("PAR",print_r($par,true));
		
		//-arq
        $off=0;
        if(!empty($listaDescuentos)){            
            //$SPECIAL= $par['SPECIAL']; 
            $maximo_descuento_por=0;
            $maximo_descuento_mon=0;         
            $_special=0;
            $_special_mon=0;           
            $rate_base_net=0;
            $special_por=0;

            if($rate_base=='SPECIAL') {
                if(is_array($par['SPECIAL'])){  
					//+031018 Se toma despues de markup
                    //$rate_base_net=(int)$par['SPECIAL']['NET'];
                    $rate_base_net=(int)$par['SPECIAL']['FINAL'];					
					//-031018
                    $special_por=(int)$par['SPECIAL']['OFF_%'];
                    //$maximo_descuento_por=100;
                    //$maximo_descuento_mon=$rate_base_net;
               }else{
                    $rate_base='RATE';
               } 
            }
            if($rate_base=='RATE'){
                if(is_array($par['SPECIAL'])){
                    $special_por=(int)$par['SPECIAL']['OFF_%'];
                    //$_special_mon=(int)$par['SPECIAL']['OFF_$'];
                }
				//+031018 Se toma despues de markup
               //$rate_base_net=(int)$par['RATE']['NET'];
			   //$rate_base_net=(int)$par['RATE']['FINAL'];
			   $rate_base_net=(int)$par['RATE']['GROSS'];
			   //-031018
               //$maximo_descuento_por=100-$_special;
               //$maximo_descuento_mon=(int)$par['RATE']['NET']-$_special_mon;
            }
            
            $maximo_descuento_por=$arg['LIMIT_MPRICE'];
            
            $suma_por = 0;
            $suma_porTot = 0;
            $suma_mon = 0;
            $porcentajeDeTarifa = 0;
            $aux = 0;
            
            $sistemas_apli="";
            $referencia_apli="";
            $this->escribe("arg_select_room001",print_r($listaDescuentos,true));
            for($i=0;$i<count($listaDescuentos);$i++){
                $aplica=false;
                if($listaDescuentos[$i][0]['APLICA']==1){
                    //if($listaDescuentos[$i][0]['SIMBOLO']=='%'){
                                                
                    //}
                    $this->escribe(
                        "rates",
                        print_r($par, true).
                        " rate_base:".$rate_base.
                        " rate_base_net: ".$rate_base_net.
                        " Especial NET : ".$par['SPECIAL']['NET'].
						" Rate NET : ".$par['RATE']['NET']."\n"
                    );

                    $porcentaje=$listaDescuentos[$i][0]['VALOR'];
                    if($listaDescuentos[$i][0]['SIMBOLO']=='$'){
                        //calculamos que porcentaje es
						//+031018
						//$porcentaje=($arg['RES_ROOM_'.$par['ROOM'].'_ADULTS_QTY'])*($listaDescuentos[$i][0]['VALOR']/$this->get_Guest_Total($db,$arg))*100/$rate_base_net;
						//$porcentaje = ($arg['RES_ROOMS_QTY'])*($listaDescuentos[$i][0]['VALOR']/$this->get_Guest_Total($db,$arg))*100/$rate_base_net;
                        //$totals[$ROOM_ID]['RATE']['FINAL_TOTAL']
                        
                        /*
                            04/10/18 20:19:55
                            porcentajes calculados contra el total de la reserva
                        */
                        $porcentaje = ($listaDescuentos[$i][0]['VALOR'] / $this->get_Guest_Total($db,$arg)) * 100 / $rate_base_net;
                        $porcentajeTarifaTotal = ($rate_base_net * 100) / $this->finalTotals[$par['ROOM_ID']]['RATE']['FINAL_TOTAL'];
                        $porcentajeDeTarifa = $porcentaje * ($porcentajeTarifaTotal / 100);
                        $this->escribe(
                            "porcentajes",
                            "\n%: ".$porcentaje.
                            "\n%tarifatotal: ".$porcentajeTarifaTotal.
                            "\n%porcentajedetarifa: ".$porcentajeDeTarifa.
                            "\n%finalacumulado: ".$porcentajeDeTarifa
                        );
						//$porcentaje = $porcentajeDeTarifa;
                        //-031018
						$this->escribe("ARG1",''.print_r($listaDescuentos[$i][0]['VALOR'],true));
						$this->escribe("ARG1",print_r($listaDescuentos[$i][0]['VALOR']/$this->get_Guest_Total($db,$arg),true));
						$this->escribe("ARG1",print_r($par['ROOM'],true));
						$this->escribe("ARG1",print_r($porcentaje,true));
						$this->escribe("ARG1",'TOTAL adult ' . print_r($this->get_Guest_Total($db,$arg),true));
						$this->escribe("ARG1",'ADULTOS POR ROOM' . print_r($arg['RES_ROOM_'.$par['ROOM'].'_ADULTS_QTY'],true));
						$this->escribe("ARG1",print_r($rate_base_net,true));
                        //$porcentaje=$listaDescuentos[$i][0]['VALOR']*100/(int)$par['RATE']['NET'];
                        //$aux=$suma_mon+$listaDescuentos[$i][0]['VALOR'];
                        //if($aux<=$maximo_descuento_mon){
                        //    $suma_mon+=$listaDescuentos[$i][0]['VALOR'];
                        //    $aplica=true;
                        //}
                    }

                    //$aux = $suma_por + $porcentaje + $special_por;
					$aux = $suma_porTot + $porcentajeDeTarifa + $special_por;
                    $this->escribe("comparing", "aux: ".$aux." - maximo: ".$maximo_descuento_por."\n");
                    if ($aux <= $maximo_descuento_por) {
                        $suma_por += $porcentaje; 
						$suma_porTot += $porcentajeDeTarifa; 
                        $aplica = true;                          
                    }
                }
                
                if($aplica){
                    /*
                    if($listaDescuentos[$i][0]['SISTEMA']=="C_"){
                        //$par['Lineas_Eliminar'][]=$listaDescuentos[$i][0]['ID_LIN'];
                        //$par['Lineas_Eliminar_C'][]="{$listaDescuentos[$i][0]['ID_CAB']},{$listaDescuentos[$i][0]['ID_LIN']}";
                    }
                    */
                    
                    
                    $separador="";
                    if($i>0){
                        $separador=" , ";
                    }
                    $sistemas_apli.=$separador.$listaDescuentos[$i][0]['SISTEMA'];
                    $referencia_apli.=$separador.$listaDescuentos[$i][0]['REFERENCE'];

                    
                        //desactivar si el valor es igual a makebooking=1 
                    $modificar=isset($arg['makebooking']);                       
                    if($modificar==1){
                        

                        switch ($listaDescuentos[$i][0]['SISTEMA']) {
                            case 'F_':
                                $room_selec="";
                                $room_selec=$arg['ROOM_SELEC'];
                                //$this->escribe("arg_select_room",print_r($arg['ROOM_SELEC'],true));

                                $room_s=explode('-',$room_selec);
                                //$this->escribe("arg_array_selecroom","--".print_r($room_s,true)."--");
                                $id_room=$par['ROOM_ID'];
                                //$this->escribe("PAR_arg_id_room",print_r($id_room,true));
                                if(in_array($id_room,$room_s)){
                                    $par['Lineas_Eliminar_F'][]="{$listaDescuentos[$i][0]['ID_CAB']},{$listaDescuentos[$i][0]['ID_LIN']}";
                                    //$query="UPDATE LINMODPRICE SET IS_PENDING=0,IS_APPLIED=1,APPLIED_ON='{$hoy_aplica}',IS_ACTIVE=0, IS_ARCHIVE=0 WHERE ID_CAB={$listaDescuentos[$i][0]['ID_CAB']} AND ID_LIN={$listaDescuentos[$i][0]['ID_LIN']}";
                                    //$this->escribe("query_F_",print_r($query,true));
                                    //$result = dbExecute($db, array('query' => $query));
                                }
                                break;
                            case 'F_ROOM':
                                $room_selec="";
                                $room_selec=$arg['ROOM_SELEC'];
                                //$this->escribe("arg_select_room",print_r($arg['ROOM_SELEC'],true));

                                $room_s=explode('-',$room_selec);
                                //$this->escribe("arg_array_selecroom","--".print_r($room_s,true)."--");
                                $id_room=$par['ROOM_ID'];
                                //$this->escribe("PAR_arg_id_room",print_r($id_room,true));
                                if(in_array($id_room,$room_s)){
                                    $par['Lineas_Eliminar_F'][]="{$listaDescuentos[$i][0]['ID_CAB']},{$listaDescuentos[$i][0]['ID_LIN']}";
                                    //$query="UPDATE LINMODPRICE SET IS_PENDING=0,IS_APPLIED=1,APPLIED_ON='{$hoy_aplica}',IS_ACTIVE=0, IS_ARCHIVE=0 WHERE ID_CAB={$listaDescuentos[$i][0]['ID_CAB']} AND ID_LIN={$listaDescuentos[$i][0]['ID_LIN']}";
                                    //$this->escribe("query_F_",print_r($query,true));
                                    //$result = dbExecute($db, array('query' => $query));
                                }
                                break;
                            case 'C_':
                                $room_selec="";
                                $room_selec=$arg['ROOM_SELEC'];
                                //$this->escribe("arg_select_room",print_r($arg['ROOM_SELEC'],true));

                                $room_s=explode('-',$room_selec);
                                //$this->escribe("arg_array_selecroom","--".print_r($room_s,true)."--");
                                $id_room=$par['ROOM_ID'];
                                //$this->escribe("PAR_arg_id_room",print_r($id_room,true));
                                if(in_array($id_room,$room_s)){
                                    $par['Lineas_Eliminar_C'][]="{$listaDescuentos[$i][0]['ID_CAB']},{$listaDescuentos[$i][0]['ID_LIN']}";
                                    //$query="UPDATE LINMODPRICE SET IS_PENDING=0,IS_APPLIED=1,APPLIED_ON='{$hoy_aplica}',IS_ACTIVE=0, IS_ARCHIVE=0 WHERE ID_CAB={$listaDescuentos[$i][0]['ID_CAB']} AND ID_LIN={$listaDescuentos[$i][0]['ID_LIN']}";
                                    //$this->escribe("query_F_",print_r($query,true));
                                    //$result = dbExecute($db, array('query' => $query));
                                }

                                break;                            
                        }

                        
                    }
                }else{
                    $listaDescuentos[$i][0]['APLICA']=0;
                }               
                                
            }
            //convierto tdo a moneda
            //$off=round($rate_base_net * ($suma_por / 100));
            if($suma_por>0){
                $off=(float)$rate_base_net * $suma_por / 100;
            }
            else{
                $off=0;
            }
            
            //$off+=$suma_mon;
           if($off>0){
                $DISCOUNT = array(
                    "ID_CAB"=>"{$listaDescuentos[0][0]['ID_CAB']}",
                    //"ID_LIN"=>$row['ID_LIN'],
                    "SISTEMA"=>"{$sistemas_apli}",
                    "REFERENCE"=>"{$referencia_apli}",                   
                    "VALOR"=>$off,
                    "VALOR_%"=>$suma_por,
                    "base_net"=>$rate_base_net,
                    "SIMBOLO"=>"$"                   
                );
           }
            
        }
        return $DISCOUNT;
    }

    function set_Discounts_Special($db, &$arg, &$par, &$SPECIAL,&$listaDescuentos) {
        //SPECIAL o RATE
        //$modo="RATE";
        $modo="SPECIAL";
		//+arq
		$this->escribe("ListaDescuentos",print_r($listaDescuentos,true));
		//-arq
        $DISCOUNT=$this->get_Total_Discounts($db, $arg, $par,$modo,$listaDescuentos);
		//+arq
		$this->escribe("ListaDescuentosDiscount",print_r($DISCOUNT,true));
		$this->escribe("ListaDescuentosDespues",print_r($listaDescuentos,true));
		//-arq        
        if(is_array($DISCOUNT)){
            if (is_array($SPECIAL)) {
                
                $off=0;
                
                if($modo=="RATE"){ //no se usa hacer modificacion del limite al tratar de usarlo
                    if(is_array($DISCOUNT)){
                        $off = (float)$SPECIAL['OFF_$']+(float)$DISCOUNT['VALOR'];
                    }else{
                        $off = (float)$SPECIAL['OFF_$'];
                    }
                    $net = (int)$par['RATE']['NET'] - $off;
                    $markup = round($net * ((float)$par['RATE']['MARKUP_%'] / 100));
                    $SPECIAL['OFF_$']=$off;
                    $SPECIAL['NET'] = $net;
                    $SPECIAL['MARKUP_$'] = $markup;
                    $SPECIAL['FINAL'] = $net + $markup;
                }else{
                    $off2=0;
                    if(is_array($DISCOUNT)){
                        if((int)$SPECIAL['OFF_%']<=(int)$arg['LIMIT_MPRICE']){
                            $off = (float)$SPECIAL['OFF_$'];
                        }                   

                        $off2=$DISCOUNT['VALOR'];
                    }
                    $net = (int)$par['RATE']['NET'] - $off; //quitando descuento special
					//+031018  Aplicamos descuentos despues de especial
                    //$net= $net-$off2;
                    $markup = round($net * ((float)$par['RATE']['MARKUP_%'] / 100));
                    //$markup = $net * ((float)$par['RATE']['MARKUP_%'] / 100);
					//-031018
                    $SPECIAL['OFF_$']=$off;
                    $SPECIAL['OFF2_$']=$off2;
                    $SPECIAL['OFF2_%'] = $DISCOUNT['VALOR_%'];
                    $SPECIAL['base_net'] = $DISCOUNT['base_net'];
                    $SPECIAL['NET'] = $net;
                    $SPECIAL['MARKUP_$'] = $markup;
					//+031018
                    $SPECIAL['FINAL'] = $net + $markup - $off2;
                    //$SPECIAL['FINAL'] = $net + $markup;
					//-031018
                }
                
            }
            else{
               
                if(is_array($DISCOUNT)){
                    $off=0;
                    $off = (float)$DISCOUNT['VALOR'];
                    //$net = (int)$par['RATE']['NET'] - $off;
					$net = (int)$par['RATE']['NET'];
                    //$markup = round($net * ((float)$par['RATE']['MARKUP_%'] / 100));
                    $markup = $net * ((float)$par['RATE']['MARKUP_%'] / 100);
                    $SPECIAL=array();
                    $SPECIAL['ID']=$DISCOUNT['ID_CAB'];
                    $SPECIAL['REFERENCE']=$DISCOUNT['REFERENCE'];
                    $SPECIAL['ACCES_CODE']=$DISCOUNT['SISTEMA'];
                    $SPECIAL['OFF_%']=$DISCOUNT['VALOR'];
                    $SPECIAL['OFF_$']=$off;
                    $SPECIAL['NET'] = $net;
                    $SPECIAL['MARKUP_$'] = $markup;
                    //$SPECIAL['FINAL'] = $net + $markup;
					$SPECIAL['FINAL'] = $net + $markup - $off;

                }
            }
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
        //$this->escribe("calculate_Room_Rates--par",print_r($par,true));
        $RATE = array();
        if (isset($CLASS_ID) && isset($this->ITEMS[$CLASS_ID])) {
            $CLASS = $this->ITEMS[$CLASS_ID];
            //$this->escribe("calculate_Room_Rates--CLASS",print_r($CLASS,true));
            $MONTHLY_MARKUP = (float)$this->ITEMS['MARKUPS']["MONTH"];
            //$this->escribe("calculate_Room_Rates--MONTHLY_MARKUP",print_r($MONTHLY_MARKUP,true));
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
        //$this->escribe("calculate_Room_Rates--RATE",print_r($RATE,true));
        return $RATE;
    }

    function calculate_Final_Rate($db, &$arg, &$par) {
        $RATE = array();
        if (isset($par['RATE'])&&is_array($par['RATE'])) { //solo usa el array que necesita, solo contepla especial y tarifa base
            $RATE['GROSS'] = $par['RATE']['GROSS'];
            $RATE['FINAL'] = (isset($par['SPECIAL'])&&is_array($par['SPECIAL'])) ? $par['SPECIAL']['FINAL'] : $RATE['GROSS'];

            $arg["RES_ROOM_{$par['ROOM']}_ROOMS"][$par['ROOM_ID']]['TOTAL']['GROSS_PP'] += round($RATE['GROSS']);
            $arg["RES_ROOM_{$par['ROOM']}_ROOMS"][$par['ROOM_ID']]['TOTAL']['FINAL_PP'] += round($RATE['FINAL']);
        }
        return $RATE;
    }

    function get_Rooms_Rates($db, &$arg) {
        #echo "<pre>";
        #print_r($this->get_Rooms_Rates_Discounts($db, $arg));
        #echo "</pre>";
        $this->finalTotals = $this->get_Rooms_Rates_Discounts($db, $arg);
        $this->escribe("partotales", print_r($this->finalTotals, true));
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
                            $par['RATE'] = $this->calculate_Room_Rates($db, $arg, $par);  //TARIFA BASE, CON MARGEN
                            $par['SPECIAL'] = $this->get_Class_Special($db, $arg, $par);
                            
                            
                            
                            //AQUI AGREGAR EL MODIFICADOR,PRECIO +MC -------------------------
                            if($arg['LIMIT_MPRICE']>0){
                                $par['SPECIAL_OLD']=$par['SPECIAL'];
                                $par['DISCOUNT'] = $this->get_Class_Discounts($db, $arg, $par);  //----111
                                //set_Discounts_Special
                                
                                //fusionador de descuentos
                                if(!isset($par['DISCOUNT']['ID_CAB'])){
                                    //solo modificamos a special para usarlo en tarifa final
                                    $this->set_Discounts_Special($db, $arg, $par, $par['SPECIAL'], $par['DISCOUNT']);
                                }
                                //$this->escribe("par_salida001",print_r($par,true));                               
                            }
                            //----------------------------------------------------------------
                            
                            $par['FINAL'] = $this->calculate_Final_Rate($db, $arg, $par);
                            //$this->escribe("paramtros",print_r($par,true));
                            $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]["NIGTHS"][$DATE]['CLASS']["RATE"] = $par['RATE'];
                            $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]["NIGTHS"][$DATE]['CLASS']["SPECIAL"] = $par['SPECIAL'];
                            $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]["NIGTHS"][$DATE]['CLASS']["SPECIAL_OLD"] = $par['SPECIAL_OLD'];
                            $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]["NIGTHS"][$DATE]['DISCOUNT'] = isset($par['DISCOUNT'])?$par['DISCOUNT']:"";
                            $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]["NIGTHS"][$DATE]['RATE'] = $par['FINAL'];
                            $arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]["NIGTHS"]['Lineas_Eliminar_F']= isset($par['Lineas_Eliminar_F'])?$par['Lineas_Eliminar_F']:array();
                            $arg['Lineas_Eliminar_C'][]= isset($par['Lineas_Eliminar_C'])?$par['Lineas_Eliminar_C']:array();
                            
                            #$this->escribe("parperroom", print_r($par, true));
                        }                        
                    }
                }
                //$this->escribe("par_salida_2510_001",print_r($arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]["NIGTHS"]['Lineas_Eliminar'],true)); 
                //marcar flash sale
                if(isset($arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]["NIGTHS"]['Lineas_Eliminar_F']) && (!empty($arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]["NIGTHS"]['Lineas_Eliminar_F']))){
                    $list_del=$arg["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]["NIGTHS"]['Lineas_Eliminar_F'];
                    //$this->escribe("query_F_array",print_r($list_del,true));
                        $hoy_aplica = date("Y-m-d H:i:s");
                        for($i=0;$i<count($list_del);$i++){
                            $list_del_det=explode(',',$list_del[$i]);
                             $query="UPDATE LINMODPRICE SET IS_PENDING=0,IS_APPLIED=1,APPLIED_ON='{$hoy_aplica}',IS_ACTIVE=0, IS_ARCHIVE=0 WHERE ID_CAB={$list_del_det[0]} AND ID_LIN={$list_del_det[1]}";
                            $this->escribe("query_F_",print_r($query,true));
                            $result = dbExecute($db, array('query' => $query));   
                        }
                }
                $arg["RES_ROOM_{$ROOM}_ROOMS_ORDER"][++$cnt] = $ROOM_ID;
            }
        }
        //solo tomamos en cuenta los diferentes
        $n_list_coupon=array();
        for($h=0;$h<count($arg['Lineas_Eliminar_C']);$h++){
            for($k=0;$k<count($arg['Lineas_Eliminar_C'][$h]);$k++){
                if(!in_array($arg['Lineas_Eliminar_C'][$h][$k],$n_list_coupon)){
                    $n_list_coupon[]=$arg['Lineas_Eliminar_C'][$h][$k];
                }
            }
        }
        //$this->escribe("n_list_coupon",print_r($n_list_coupon,true));
        //marcar coupon
        if(!empty($n_list_coupon)){
                    $list_del=$n_list_coupon;
                    $this->escribe("query_C_array",print_r($list_del,true));
                        $hoy_aplica = date("Y-m-d H:i:s");
                        for($i=0;$i<count($list_del);$i++){
                            $list_del_det=explode(',',$list_del[$i]);
                             $query="UPDATE LINMODPRICE SET IS_PENDING=0,IS_APPLIED=1,APPLIED_ON='{$hoy_aplica}',IS_ACTIVE=0, IS_ARCHIVE=0 WHERE ID_CAB={$list_del_det[0]} AND ID_LIN={$list_del_det[1]}";
                            $this->escribe("query_C_",print_r($query,true));
                            $result = dbExecute($db, array('query' => $query));   
                        }
        }
    }

    function get_Rooms_Rates_Discounts($db, $arg) {
        $totals = array();
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
                            if (isset($totals[$ROOM_ID]['RATE']['FINAL_TOTAL'])) {
                                $totals[$ROOM_ID]['RATE']['FINAL_TOTAL'] =
                                    $totals[$ROOM_ID]['RATE']['FINAL_TOTAL'] + $par['RATE']['GROSS'];
                            } else {
                                $totals[$ROOM_ID] = $par;
                                $totals[$ROOM_ID]['RATE']['FINAL_TOTAL'] = $par['RATE']['GROSS'];
                            }
                            #$totals[$ROOM_ID] = $par;
                        }
                    }
                }
            }
        }

        return $totals;
    }

    //JMCA para crear archivos.
    function escribe($nombre,$msg){
        if($archivo = fopen('Test/'.$nombre.".txt", "a"))
        {
            fwrite($archivo, $msg. "\n");          
     
            fclose($archivo);
        }
    }

    function get_Room_Class($db, &$arg, $par) {
        extract($arg);
        extract($par);
        //$this->escribe("arg",print_r($arg,true));
        //$this->escribe("par",print_r($par,true));
        $RES_SRC = isset($arg['RES_SRC']) ? $arg['RES_SRC'] : "";
        //$this->escribe("RES_SRC",print_r($RES_SRC,true));
        $blockIP = in_array($_SERVER["REMOTE_ADDR"],$arg['BLOCKED_IPs']) && $RES_SRC!="CC";
        //print $blockIP ? "Yes" : "No";
        //$blockIP = true;
        //$this->escribe("blockIP",print_r($blockIP,true));
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
                (COUNTRY_CODE = '{$COUNTRY_CODE}' OR COUNTRY_CODE = 'US')
                AND
                ROOM_ID = '{$ROOM_ID}' 
                AND 
                '{$THIS_DAY} 00:00:00' >= SEASON_FROM AND '{$THIS_DAY} 00:00:00' <= SEASON_TO AND
                (".(implode(" OR ",$USERTYPE)).")
        ";
        //$this->escribe("Query001",print_r($query,true));
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $RSET = dbQuery($db, array('query' => $query));

        // SELECT THE ONE FOR THE COUNTRY OR US AS DEFAULT
        // $CLASS = ($RSET['iCount']!=0) ? $db->fetch_array($RSET['rSet']) : array();
        if ($RSET['iCount']==1) {
            return $db->fetch_array($RSET['rSet']);
        } else {
            $CLASS = array();
            $USA = array();
            while ($row = $db->fetch_array($RSET['rSet'])) {
                if (count($CLASS)==0 && $row['COUNTRY_CODE']!='US' && (int)$row['BLACKOUT']!=1) $CLASS = $row;
                if (count($USA)==0 && $row['COUNTRY_CODE']=='US' && (int)$row['BLACKOUT']!=1) $USA = $row;
            }
            return (count($CLASS)==0 || $blockIP) ? $USA : $CLASS; //cuando encuentro los margenes por habitacion, respaldo todo en $CLASS
        }
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

        $RES_LANGUAGE = !isset($RES_LANGUAGE)||empty($RES_LANGUAGE) ? "EN" : $RES_LANGUAGE;

        extract($arg);
        //print "<pre>";print_r($arg);print "</pre>";

        for ($ROOM=1; $ROOM <= (int)$arg['RES_ROOMS_QTY']; ++$ROOM) {
            $GUEST = $arg["RES_ROOM_{$ROOM}_GUESTS_QTY"];
            $ADULTS = $arg["RES_ROOM_{$ROOM}_ADULTS_QTY"];
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
            $arg["RES_ROOM_{$ROOM}_GUESTS_QTY"] = (int)$arg["RES_ROOM_{$ROOM}_ADULTS_QTY"];
            $arg["RES_ROOM_{$ROOM}_IGNORE_QTY"] = 0;
            if (isset($arg["RES_ROOM_{$ROOM}_CHILDREN_QTY"])) {
                for ($CHILD=1; $CHILD <= (int)$arg["RES_ROOM_{$ROOM}_CHILDREN_QTY"]; ++$CHILD) {
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