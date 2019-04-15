<?
/*
 * Revised: Apr 23, 2016
 *          Jun 09, 2016
 *          Oct 04, 2016
 */

class discounts {

    var $showQry = false;

    function save($db, $arg) {
        extract($arg);

        //print "<p class='s_notice top_msg'> ----- ".PRINT_R($arg)." ------ </p>";

        if ((int)$ID_CAB!=0) {
            $result = $this->getById($db, $arg);
        } else $result['iCount'] = 0;

        if ( $result['iCount'] == 0 ) {
            $result = $this->addNew($db, $arg);
        } else {
            $result = $this->modify($db, $arg);
        }

        return $result;
    }
    function saveLinea($db, $arg) {
        extract($arg);
        //print "<p class='s_notice top_msg'> SAVE ".PRINT_R($arg)."</p>";

        //exit;
        //eliminamos las lineas, para volver a crearlas
        if(isset($ID_CAB)&& $ID_CAB!=0){
            //if($SYSTEM=='T_ACCESO'){
                $result = $this->remove($db, array("DELETE_ID"=>$ID_CAB),false);
                //remove
            //}
            //else{
            //    $result = $this->archive($db, array("DELETE_ID"=>$ID_CAB),false);
            //}
            
        }
        $opcion=(isset($aply_to)?$aply_to:"na");
        $lin_mod_final=array();
        switch ($opcion) {            
            /*case 'geo':
                $total=count($GEOS);
                if($total>0){                    
                    for($i=0;$i<$total;$i++){
                        if($GEOS[$i]!=""){
                            $val_por=(isset($Graltxtgeo)?$Graltxtgeo:"");
                            $pos= array_search($GEOS[$i], $geocheck);
                            if($GEOStext[$pos]!=""){
                                $val_por=$GEOStext[$pos];
                            } 
                            $lin_mod[]=array('ID_LINEA' =>$geoid[$pos] ,
                                            'CAMPO'=>'GEOCOUNTRY',
                                            'VCAMPO'=>$GEOS[$i],
                                            'VALOR'=>$val_por
                                             );                          
                        }
                    }
                }
                break;                */
            case 'rate':
                if(isset($CLASS_ID)){
                    $total=count($CLASS_ID);
                    if($total>0){                    
                        for($i=0;$i<$total;$i++){
                            if($CLASS_ID[$i]!=""){
                                $val_por=(isset($Graltxtrat)?$Graltxtrat:"");
                                $pos= array_search($CLASS_ID[$i], $ratcheck);
                                if($val_por==""){
                                    if($rattext[$pos]!=""){
                                    $val_por=$rattext[$pos];
                                    }
                                    else{
                                            $val_por=$VALUE;
                                    } 
                                }
                                
                                $lin_mod_final[]=array('ID_LINEA' =>'' ,
                                                'CAMPO'=>'RATECLASES',
                                                'VCAMPO'=>$CLASS_ID[$i],
                                                'VALOR'=>$val_por,
                                                'SIMBOLO'=>$SYMBOL,
                                                    'MIN_NIGHT'=>(isset($MIN_NIGHT)?$MIN_NIGHT:0),
                                                    'MAX_NIGHT'=>(isset($MAX_NIGHT)?$MAX_NIGHT:0),
                                                    'MIN_ROOM'=>(isset($MIN_ROOM)?$MIN_ROOM:0),
                                                    'MAX_ROOM'=>(isset($MAX_ROOM)?$MAX_ROOM:0)
                                                 );                          
                            }
                        }
                    }
                } 
                break;
            case 'rooms':
                if(isset($ROOM_IDs)){
                    $total=count($ROOM_IDs);
                    if($total>0){                    
                        for($i=0;$i<$total;$i++){
                            if($ROOM_IDs[$i]!=""){
                                $val_por=(isset($Graltxtroo)?$Graltxtroo:"");
                                $pos= array_search($ROOM_IDs[$i], $roocheck);
                                if($val_por==""){
                                    if($rootext[$pos]!=""){
                                    $val_por=$rootext[$pos];
                                    }
                                    else{
                                        $val_por=$VALUE;
                                    }
                                }
                                
                                $lin_mod_final[]=array('ID_LINEA' =>'' ,
                                                'CAMPO'=>'ROOM',
                                                'VCAMPO'=>$ROOM_IDs[$i],
                                                'VALOR'=>$val_por,
                                                'SIMBOLO'=>$SYMBOL,
                                                'MIN_NIGHT'=>(isset($MIN_NIGHT)?$MIN_NIGHT:0),
                                                'MAX_NIGHT'=>(isset($MAX_NIGHT)?$MAX_NIGHT:0),
                                                'MIN_ROOM'=>(isset($MIN_ROOM)?$MIN_ROOM:0),
                                                'MAX_ROOM'=>(isset($MAX_ROOM)?$MAX_ROOM:0)
                                                 );                          
                            }
                        }
                    }
                }
                break;
            case 'na':
                 $lin_mod_final[]=array('ID_LINEA' =>'' ,
                                            //'CAMPO'=>'GEOCOUNTRY',
                                            //'VCAMPO'=>"",
                                            'VALOR'=>$VALUE,
                                            'SIMBOLO'=>$SYMBOL,
                                                'MIN_NIGHT'=>(isset($MIN_NIGHT)?$MIN_NIGHT:0),
                                                'MAX_NIGHT'=>(isset($MAX_NIGHT)?$MAX_NIGHT:0),
                                                'MIN_ROOM'=>(isset($MIN_ROOM)?$MIN_ROOM:0),
                                                'MAX_ROOM'=>(isset($MAX_ROOM)?$MAX_ROOM:0)
                                             );
                break;
            
        }
         //print_r($lin_mod_final);
        // ahora geo aplica para todo
        $lin_mod = array();
            //print_r($lin_mod);

        $cantidad=((isset($INVENTORY) && $INVENTORY!="")?$INVENTORY:1);
        //print "<p class='s_notice top_msg'> SAVE: ".print_r($cantidad)."</p>";
        for($h=0;$h<$cantidad;$h++){
            if(isset($GEOS)){
                $total=count($GEOS);
                if($total>0){                    
                    for($i=0;$i<$total;$i++){
                        if($GEOS[$i]!=""){
                            //$val_por=(isset($Graltxtgeo)?$Graltxtgeo:"");
                            $pos= array_search($GEOS[$i], $geocheck);
                            //if($GEOStext[$pos]!=""){
                            //    $val_por=$GEOStext[$pos];
                            //} 
                            /*$lin_mod[]=array('ID_LINEA' =>$geoid[$pos] ,
                                            'CAMPO'=>'GEOCOUNTRY',
                                            'VCAMPO'=>$GEOS[$i],
                                            'VALOR'=>$val_por
                                             );*/
                            
                                $id_geo=$geoid[$pos];
                                for($k=0;$k<count($lin_mod_final);$k++){
                                    $lin_mod_final[$k]['ID_LINEA']='';
                                    $lin_mod_final[$k]['GEOCOUNTRY']=$GEOS[$i];
                                    $lin_mod[]=$lin_mod_final[$k];
                                }
                                                      
                        }
                    }
                }
            }else{
               $lin_mod=$lin_mod_final; 
            }
        }



               
        
         //print "<p class='s_notice top_msg'> SAVE: ".print_r($lin_mod)."</p>";
            

            for($i=0;$i<count($lin_mod);$i++){
                $result = array();
                if ((int)$lin_mod[$i]['ID_LINEA']!=0) {
                    $result = $this->getByIdLin($db, $arg, "and ID_LIN='".$lin_mod[$i]['ID_LINEA']."'" );
                    //exit;
                } else {
                    //print "<p class='s_notice top_msg'> SAVE ".print_r($result)."</p>";
                    $result['iCount'] = 0;

                    $lin_mod[$i]['ID_LINEA'] = dbNextIdMod($db);
                    }

                if ( $result['iCount'] == 0 ) {

                    $result = $this->addNewLinea($db, $arg,$lin_mod[$i]);
                } else {
                    $result = $this->modifyLinea($db, $arg,$lin_mod[$i]);
                }
            }
        
        

        return $result;
    }

    function getById($db, $arg) {
        extract($arg);
     
        $query = "SELECT * FROM HEADMODPRICE WHERE ID_CAB='{$ID_CAB}'";
        $arg = array('query' => $query);
        //if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getByIdLin($db, $arg, $valor) {
        extract($arg);
        //print "<p class='s_notice top_msg'> SAVE ".PRINT_R($valor)."</p>";
        //if($valor!=""){
        //    $valor="and ID_LIN='".$valor."'";
        //}
        //$query = "SELECT * FROM LINMODPRICE WHERE ID_CAB='{$ID_CAB}' {$valor}";
        $query = "SELECT t1.*,t2.EMAIL FROM LINMODPRICE t1 
            left join REL_LINMODPRICE_EMAIL t2 on t1.ID_LIN=t2.ID_LIN 
            left join GUESTS on t2.ID_GUEST=GUESTS.ID WHERE ID_CAB='{$ID_CAB}' {$valor}";
        //print "<p class='s_notice top_msg'> SAVE ".$query."</p>";

        //exit;
        $arg = array('query' => $query);
        //if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getByIdInventory($db, $arg, $valor) {
        extract($arg);
        //print "<p class='s_notice top_msg'> SAVE ".PRINT_R($valor)."</p>";
        //if($valor!=""){
        //    $valor="and ID_LIN='".$valor."'";
        //}
        $query = "SELECT * FROM LINMODPRICE WHERE ID_CAB='{$ID_CAB}' {$valor}";
        //print "<p class='s_notice top_msg'> SAVE ".$query."</p>";

        //exit;
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        $_Lineas=array();
        if ( $result['iCount'] != 0 ) {        
        while ($row = $db->fetch_array($result['rSet'])) {
            $_Lineas[]=$row;
            }
        }
        return $_Lineas;
    }

    function addNew($db, $arg) {
        extract($arg);
        //print_r($arg);
        $query = "INSERT INTO HEADMODPRICE ( ID_CAB, UPDATED_BY ) VALUES ( '{$ID_CAB}', '{$_SESSION['AUTHENTICATION']['ID']}' )";
        //print "<p class='s_notice top_msg'>Insert: $query</p>";
        //exit;
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $this->modify($db, $arg);
    }
   
    function addNewLinea($db, $arg, $valor) {
        extract($arg);
        //print_r($arg);
        $query = "INSERT INTO LINMODPRICE ( ID_CAB,ID_LIN,UPDATED_BY ) VALUES ( '{$ID_CAB}','{$valor['ID_LINEA']}', '{$_SESSION['AUTHENTICATION']['ID']}' )";
        //print "<p class='s_notice top_msg'>Insert: $query</p>";
        //exit;
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $this->modifyLinea($db, $arg,$valor);
    }
   

    function modifyLinea($db, $arg, $valor) {

        extract($arg);
        global $clsUploads;
        $arr = array();

        //print "<pre>";print_r($SYSTEM);print "</pre>";
        //exit;
        
        if (isset($valor["CAMPO"])) array_push($arr," {$valor['CAMPO']} = '{$valor['VCAMPO']}'");
        if (isset($valor["VALOR"])) array_push($arr," VALUE = {$valor['VALOR']}");
        if (isset($valor["SIMBOLO"])) array_push($arr," SYMBOL = '{$valor['SIMBOLO']}'");
        if (isset($valor["GEOCOUNTRY"])) array_push($arr," GEOCOUNTRY = '{$valor['GEOCOUNTRY']}'");
        if (isset($valor["MIN_NIGHT"])) array_push($arr," MIN_NIGHT = '{$valor['MIN_NIGHT']}'");
        if (isset($valor["MAX_NIGHT"])) array_push($arr," MAX_NIGHT = '{$valor['MAX_NIGHT']}'");
        if (isset($valor["MIN_ROOM"])) array_push($arr," MIN_ROOM = '{$valor['MIN_ROOM']}'");
        if (isset($valor["MAX_ROOM"])) array_push($arr," MAX_ROOM = '{$valor['MAX_ROOM']}'");
        //if ($SYSTEM=="C_"){

        //    $cadena=$this->coupon($PROP_ID);
            //print "<pre>";print_r($cadena);print "</pre>";
        //    array_push($arr," PROMOCODE = '{$cadena}'");
        //} 
        /*
        ,
                                            'MIN_NIGHT'=>$MIN_NIGHT,
                                            'MAX_NIGHT'=>$MIN_NIGHT,
                                            'MIN_ROOM'=>$MIN_ROOM,
                                            'MAX_ROOM'=>$MAX_ROOM
        */
        //array_push($lin_mod[$k]," GEOCOUNTRY = '{$GEOS[$i]}'");
        //array_push($arr," SIMBOLO = '%'");
    
        /* CHECKBOXES */
        //array_push($arr," IS_VIP = '".(isset($IS_VIP)?$IS_VIP:"0")."'");
        array_push($arr," IS_ACTIVE = '".(isset($IS_ACTIVE)?$IS_ACTIVE:"0")."'");
        array_push($arr," IS_ARCHIVE = '".(isset($IS_ARCHIVE)?$IS_ARCHIVE:"0")."'");
        //array_push($arr," BEDS = '".(isset($BEDS)?implode(",", $BEDS):"0")."'");

        $query = "UPDATE LINMODPRICE SET ".join(", ",$arr)." WHERE ID_CAB='$ID_CAB' and ID_LIN='{$valor['ID_LINEA']}'";
        //print "<pre>";print_r($query);print "</pre>";
        //exit;
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        
        //exit;
        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);


        
        //return print_r($lin_mod);
        //$this->saveLinea($db,$arg);

        /*
        if ((int)$result != 1) { 
            print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n $query\n\n</p>";
        } else {
            if (isset($DELETE_UPS)) {
                $clsUploads->deleteByIds($db, $arg);
            }
            if (isset($ROOM_IMAGES_ORDER_CURRENT)&&isset($ROOM_IMAGES_ORDER)&&$ROOM_IMAGES_ORDER_CURRENT!=$ROOM_IMAGES_ORDER) {
                $arg['IMAGES_ORDER'] = $ROOM_IMAGES_ORDER;
               $clsUploads->saveOrder($db, $arg);
            }
        }
        */
        return $result;
    }
    function modify($db, $arg) {
        extract($arg);
        global $clsUploads;
        $arr = array();

       // print "<pre>";print_r($arg);print "</pre>";

        if (isset($PROP_ID)) array_push($arr," PROP_ID = '$PROP_ID'");
        if (isset($NAME_EN)) array_push($arr," NAME_EN = '$NAME_EN'");
        if (isset($NAME_SP)) array_push($arr," NAME_SP = '$NAME_SP'");
        if (isset($DESCR_EN)) array_push($arr," DESCR_EN = '$DESCR_EN'");
        if (isset($DESCR_SP)) array_push($arr," DESCR_SP = '$DESCR_SP'");
               
        if (isset($WIN_FROM)){
            $horaini="00:00:00";
            $fecha=substr($WIN_FROM,0,10);
            if (isset($hourstart)){
                $horaini=$hourstart;          
            } 
            $fecha2=$fecha." ".$horaini;
            array_push($arr," WIN_FROM = '$fecha2'");        
            
        }
        if (isset($WIN_TO)){
            $horafin="23:59:59";            
            if (isset($hourend)){ 
                $horafin=$hourend;
            }
            $fecha=substr($WIN_TO,0,10);
            $fecha2=$fecha." ".$horafin;
            array_push($arr," WIN_TO = '$fecha2'");
           
        }  
        if (isset($CAB_FROM)) array_push($arr," CAB_FROM = '$CAB_FROM'");
        if (isset($CAB_TO)) array_push($arr," CAB_TO = '$CAB_TO'");        
        if (isset($CODE)) array_push($arr," CODE = '$CODE'");
        if (isset($ENVIRONMENT)) array_push($arr," ENVIRONMENT = '$ENVIRONMENT'");
        if (isset($TYPE)) array_push($arr," TYPE = '$TYPE'");
        if (isset($SYSTEM)) {
            $M_NIGHT = isset($MIN_NIGHT) ? $MIN_NIGHT : 0;
            $M_NIGHT1 = isset($MAX_NIGHT) ? $MAX_NIGHT : 0;
            if ($M_NIGHT != 0 || $M_NIGHT1 != 0) {
                $SYSTEM = 'D_N_NIGHT';
            }
            //print "<p class='s_notice top_msg'>Modify: $SYSTEM</p>";
            $M_ROOM=isset($MIN_ROOM)?$MIN_ROOM:0;
            $M_ROOM1=isset($MAX_ROOM)?$MAX_ROOM:0;
            if($M_ROOM!=0 || $M_ROOM1!=0){
                $SYSTEM='D_N_ROOM';
            }
            //print "<p class='s_notice top_msg'>Modify: $SYSTEM</p>";
            $option=(isset($aply_to)?$aply_to:"na");
            switch ($option) {
                case "rooms":
                        $SYSTEM='D_ROOM';
                    break;
                 case "rate":
                        $SYSTEM='D_RATE';
                    break;                
                
            }
            $p=$PRIORITY;
            $mode_op=(isset($mode)?$mode:"na");
            if($mode_op!="na"){
                $SYSTEM=$mode_op;
                $p=$this->get_priority($db,$SYSTEM);
            }             
            array_push($arr," SYSTEM = '$SYSTEM'");
            array_push($arr," PRIORITY = '$p'");
        } 
        if (isset($INVENTORY)) array_push($arr," INVENTORY = '$INVENTORY'");
        /*
            'MIN_NIGHT'=>(isset($MIN_NIGHT)?$MIN_NIGHT:0),
            'MAX_NIGHT'=>(isset($MAX_NIGHT)?$MAX_NIGHT:0),
            'MIN_ROOM'=>(isset($MIN_ROOM)?$MIN_ROOM:0),
            'MAX_ROOM'=>(isset($MAX_ROOM)?$MAX_ROOM:0)
        */
    
        /* CHECKBOXES */
        //array_push($arr," IS_VIP = '".(isset($IS_VIP)?$IS_VIP:"0")."'");
        array_push($arr," IS_ACTIVE = '".(isset($IS_ACTIVE)?$IS_ACTIVE:"0")."'");
        array_push($arr," IS_ARCHIVE = '".(isset($IS_ARCHIVE)?$IS_ARCHIVE:"0")."'");
        //array_push($arr," BEDS = '".(isset($BEDS)?implode(",", $BEDS):"0")."'");

        $query = "UPDATE HEADMODPRICE SET ".join(", ",$arr)." WHERE ID_CAB='$ID_CAB'";
        //print "<p class='s_notice top_msg'>Modify: $query</p>";
        //exit;
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        
        //exit;
        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);


        
        //return print_r($lin_mod);
        $this->saveLinea($db,$arg);

        /*
        if ((int)$result != 1) { 
            print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n $query\n\n</p>";
        } else {
            if (isset($DELETE_UPS)) {
                $clsUploads->deleteByIds($db, $arg);
            }
            if (isset($ROOM_IMAGES_ORDER_CURRENT)&&isset($ROOM_IMAGES_ORDER)&&$ROOM_IMAGES_ORDER_CURRENT!=$ROOM_IMAGES_ORDER) {
                $arg['IMAGES_ORDER'] = $ROOM_IMAGES_ORDER;
               $clsUploads->saveOrder($db, $arg);
            }
        }
        */
        return $result;
    }
 // se trae toda la ista de la tabla, en args puedes pasarle complemento del query
    function getByProperty($db, $arg) {
        extract($arg);
        global $_IBE_LANG;
            
        $WEHRE = isset($WEHRE) ? $WEHRE : "";
     
        $query = "SELECT * FROM HEADMODPRICE WHERE PROP_ID='{$PROP_ID}' {$WEHRE} ORDER BY SYSTEM DESC";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }
   


    function remove($db, $arg,$Delcab) {
        extract($arg);
            
            if($Delcab){
                $result = dbExecute($db, array('query' => "DELETE FROM HEADMODPRICE WHERE ID_CAB='{$DELETE_ID}'"));                          
            }
            $result = dbExecute($db, array('query' => "DELETE FROM LINMODPRICE WHERE ID_CAB='{$DELETE_ID}'"));
            
        
        return $result;
    }
    function archive($db, $arg,$Delcab) {
        extract($arg);
            
            if($Delcab){
                $result = dbExecute($db, array('query' => "UPDATE HEADMODPRICE SET IS_ACTIVE=0, IS_ARCHIVE=1 WHERE ID_CAB='{$DELETE_ID}'"));                          
            }
            $result = dbExecute($db, array('query' => "UPDATE LINMODPRICE SET IS_ACTIVE=0, IS_ARCHIVE=1 WHERE ID_CAB='{$DELETE_ID}'"));
            
        
        return $result;
    }

    function get_priority($db,$system){        
        $query = "SELECT PRIORITY FROM MODPRIORITY WHERE SYSTEM='{$system}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        $priority="1";
        if ( $result['iCount'] != 0 ) {        
        while ($row = $db->fetch_array($result['rSet'])) {
            $priority=$row['PRIORITY'];
            }
        }       
        return $priority;
    }
    function get_all_priority($db){        
        $query = "SELECT * FROM MODPRIORITY ORDER by priority ASC";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);           
        return $result;
    }

    /* ************************** */
//+MC 21-12-18
    //JMCA para crear archivos.
    function escribe($nombre,$msg){
        if($archivo = fopen('Test/'.$nombre.".txt", "a"))
        {
            fwrite($archivo, $msg. "\n");          
     
            fclose($archivo);
        }
    }
    
    function get_Rooms_Rates_Discounts($db, $arg) {
        global $clsAvailability;
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
                            //$par['RATE'] = $this->calculate_Room_Rates($db, $arg, $par);
                            $par['RATE'] =$clsAvailability->calculate_Room_Rates($db, $arg, $par);
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

    function get_group_geo($db,$code){
        $salida="";
        //$query="SELECT * FROM countries where CODE='{$code}'"; //-MC-19-12-18
        $query="SELECT * FROM COUNTRIES where CODE='{$code}'";
        
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


    function get_Class_Discounts_Private($db, &$arg, $par) {        
        
        $RSET = $this->get_Class_Discounts_Query($db, $arg, $par);
        return $this->get_Class_Discounts_rSet($db, $arg, $par, $RSET);
        //return $RSET;
    }


    function get_Class_Discounts_Query($db, &$arg, $par) {               
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
                $query.=" (PROMOCODE = '{$arg['RES_SPECIAL_CODE']}') AND ";
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
                $query.=" (PROMOCODE = '{$arg['RES_SPECIAL_CODE']}') AND ";
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
        
     
         if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
         //$this->escribe('Query',print_r($query,true));
        return dbQuery($db, array('query' => $query));
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


    function get_Class_Discounts_All_Apli($db, &$arg, $par) {       

        $RSET = $this->get_Class_Discounts_All_Apli_Query($db, $arg, $par);
        return $this->get_Class_Discounts_All_Apli_rSet($db, $arg, $par, $RSET);
        //return $RSET;
    }

    function get_Class_Discounts_All_Apli_Query($db, &$arg, $par) {
                
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
        if($arg['RES_SPECIAL_CODE']!=""){ //para encontrar offerta especial
            $query.=" (PROMOCODE = '{$arg['RES_SPECIAL_CODE']}' or PROMOCODE ='') AND ";
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
        
       //return "$query"; 
        
         if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
         //$this->escribe('Query',print_r($query,true));
        return dbQuery($db, array('query' => $query));
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

    function get_count_Discounts_query($db,$id_cab){
        $query="SELECT count(*) as TOTAL FROM LINMODPRICE WHERE IS_ACTIVE=1 AND IS_ARCHIVE=0 AND ID_CAB={$id_cab}";
       
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
        $RSET = $this->get_count_Discounts_query($db,$id_cab);
        return $this->get_count_Discounts_query_rSet($db,$RSET);
       
    }

        function get_Relation_Discounts_query($db,$sistema){
        $query="SELECT * FROM RELATIONMOD WHERE SYSTEM='{$sistema}' AND IS_ACTIVE=1 AND VALUE=1";     
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
        $RSET = $this->get_Relation_Discounts_query($db, $par);
        return $this->get_Relation_Discounts_rSet($db,$RSET);       
    }

    function get_ListDiscountToApli($db,&$list){         
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
                
            }           
           
        }        
     
    }

    function get_Class_Discounts($db, &$arg, $par) {

        //buscar todos los sistemas que estan condigurados y que aplican
        //110419+ _CODE --> MACCESS
        $par['WH']="(CODE LIKE '%{$arg['MACCESS']}%')";
        $SYS=$this->get_Class_Discounts_All_Apli($db, $arg, $par);            
        
        //Traer el mejor descuento, por tipo de sistema
        $listaDescuentos= array();
        
        if(!empty($SYS)){ //+MC-19-12-18
                  
            for($i=0;$i<count($SYS);$i++){
                if(isset($SYS[$i]['SISTEMA'])){ //+MC-19-12-18
                    //110419+ _CODE --> MACCESS
                    $par['WH']=" (SYSTEM = '{$SYS[$i]['SISTEMA']}' and CODE LIKE '%{$arg['MACCESS']}%') ";
                    $par['SY']="{$SYS[$i]['SISTEMA']}";
                    $tmp=$this->get_Class_Discounts_Private($db, $arg, $par);
                    if($tmp[0]!='X'){
                        $listaDescuentos[]=$tmp;
                    }            
                }
            }
            //agregamos sus contadores
            //"LEFT"=>$this->get_count_Discounts($auxdb,$row['SYSTEM'],$row['ID_CAB']),
            for($i=0;$i<count($listaDescuentos);$i++){           
                if($listaDescuentos[$i][0]['APLICA']==1){
                   $listaDescuentos[$i][0]['LEFT']=$this->get_count_Discounts($db,$listaDescuentos[$i][0]['ID_CAB']); 
                }
            }
           
            $this->get_ListDiscountToApli($db,$listaDescuentos); 
        
        }  //+MC-19-12-18     
        if(empty($listaDescuentos)){ 
            $listaDescuentos=array("ID_CAB"=>"X");
        }        
        return $listaDescuentos;
              
    }

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
        global $clsAvailability; //RTORRES 04.01.2019

        $DISCOUNT="X";
        //procesamos el array de descuentos
        //+arq
        
        
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
            //$this->escribe("maximo_descuento_por",print_r($maximo_descuento_por, true));
            $suma_por = 0;
            $suma_porTot = 0;
            $suma_mon = 0;
            $porcentajeDeTarifa = 0;
            
            $sistemas_apli="";
            $referencia_apli="";
            
            for($i=0;$i<count($listaDescuentos);$i++){
                $aplica=false;
                if($listaDescuentos[$i][0]['APLICA']==1){
                    //if($listaDescuentos[$i][0]['SIMBOLO']=='%'){
                                                
                    //}
                    //-MC-19-12-18
                    /*$this->escribe(
                        "rates",
                        print_r($par, true).
                        " rate_base:".$rate_base.
                        " rate_base_net: ".$rate_base_net.
                        " Especial NET : ".$par['SPECIAL']['NET'].
                        " Rate NET : ".$par['RATE']['NET']."\n"
                    );*/
                    //-04-04-19 
                    //$porcentaje=$listaDescuentos[$i][0]['VALOR'];
                    //+04-04-19 
                    $porcentajeDeTarifa=$listaDescuentos[$i][0]['VALOR'];
                    
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
                        //$porcentajeTarifaTotal = ($rate_base_net * 100) / 1; testing ok
                        //$porcentajeTarifaTotal = ($rate_base_net * 100) / $this->finalTotals[$par['ROOM_ID']]['RATE']['FINAL_TOTAL'];
                        
                        //RTORRES 04.01.2019 +001
                        $finalTotals = $clsAvailability->finalTotals[$par['ROOM_ID']]['RATE']['FINAL_TOTAL'];

                        if($finalTotals != 0) {
                            $porcentajeTarifaTotal = ($rate_base_net * 100) / $finalTotals;
                        } else {
                            $porcentajeTarifaTotal = 0;
                        }
                        //-001
                        
                        $porcentajeDeTarifa = $porcentaje * ($porcentajeTarifaTotal / 100);
                        
                        /*$this->escribe(
                            "porcentajes",
                            "\n%: ".$porcentaje.
                            "\n%tarifatotal: ".$porcentajeTarifaTotal.
                            "\n%porcentajedetarifa: ".$porcentajeDeTarifa.
                            "\n%finalacumulado: ".$porcentajeDeTarifa
                        );*/
                        
                        //$porcentaje = $porcentajeDeTarifa;
                        //-031018
                        //$this->escribe("ARG1",''.print_r($listaDescuentos[$i][0]['VALOR'],true));
                        //$this->escribe("ARG1",print_r($listaDescuentos[$i][0]['VALOR']/$this->get_Guest_Total($db,$arg),true));
                        //$this->escribe("ARG1",print_r($par['ROOM'],true));
                        //$this->escribe("ARG1",print_r($porcentaje,true));
                        //$this->escribe("ARG1",'TOTAL adult ' . print_r($this->get_Guest_Total($db,$arg),true));
                        //$this->escribe("ARG1",'ADULTOS POR ROOM' . print_r($arg['RES_ROOM_'.$par['ROOM'].'_ADULTS_QTY'],true));
                        //$this->escribe("ARG1",print_r($rate_base_net,true));
                        
                        //$porcentaje=$listaDescuentos[$i][0]['VALOR']*100/(int)$par['RATE']['NET'];
                        //$aux=$suma_mon+$listaDescuentos[$i][0]['VALOR'];
                        //if($aux<=$maximo_descuento_mon){
                        //    $suma_mon+=$listaDescuentos[$i][0]['VALOR'];
                        //    $aplica=true;
                        //}
                    }

                    //$aux = $suma_por + $porcentaje + $special_por;
                    $aux = $suma_porTot + $porcentajeDeTarifa + $special_por;
                    //$this->escribe("Comparacion",print_r("Respuesta {$i}: ( {$aux} = {$suma_porTot} + {$porcentajeDeTarifa} + {$special_por} ) <= {$maximo_descuento_por}",true));
                    if ($aux <= $maximo_descuento_por) {
                        //$suma_por += $porcentaje;  
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
                                

                                $room_s=explode('-',$room_selec);
                                
                                $id_room=$par['ROOM_ID'];
                                
                                if(in_array($id_room,$room_s)){
                                    $par['Lineas_Eliminar_F'][]="{$listaDescuentos[$i][0]['ID_CAB']},{$listaDescuentos[$i][0]['ID_LIN']}";
                                    //$query="UPDATE LINMODPRICE SET IS_PENDING=0,IS_APPLIED=1,APPLIED_ON='{$hoy_aplica}',IS_ACTIVE=0, IS_ARCHIVE=0 WHERE ID_CAB={$listaDescuentos[$i][0]['ID_CAB']} AND ID_LIN={$listaDescuentos[$i][0]['ID_LIN']}";
                                    
                                    //$result = dbExecute($db, array('query' => $query));
                                }
                                break;
                            case 'F_ROOM':
                                $room_selec="";
                                $room_selec=$arg['ROOM_SELEC'];
                                

                                $room_s=explode('-',$room_selec);
                                
                                $id_room=$par['ROOM_ID'];
                                
                                if(in_array($id_room,$room_s)){
                                    $par['Lineas_Eliminar_F'][]="{$listaDescuentos[$i][0]['ID_CAB']},{$listaDescuentos[$i][0]['ID_LIN']}";
                                    //$query="UPDATE LINMODPRICE SET IS_PENDING=0,IS_APPLIED=1,APPLIED_ON='{$hoy_aplica}',IS_ACTIVE=0, IS_ARCHIVE=0 WHERE ID_CAB={$listaDescuentos[$i][0]['ID_CAB']} AND ID_LIN={$listaDescuentos[$i][0]['ID_LIN']}";
                                    
                                    //$result = dbExecute($db, array('query' => $query));
                                }
                                break;
                            case 'C_':
                                $room_selec="";
                                $room_selec=$arg['ROOM_SELEC'];
                                

                                $room_s=explode('-',$room_selec);
                                
                                $id_room=$par['ROOM_ID'];
                                
                                if(in_array($id_room,$room_s)){
                                    $par['Lineas_Eliminar_C'][]="{$listaDescuentos[$i][0]['ID_CAB']},{$listaDescuentos[$i][0]['ID_LIN']}";
                                    //$query="UPDATE LINMODPRICE SET IS_PENDING=0,IS_APPLIED=1,APPLIED_ON='{$hoy_aplica}',IS_ACTIVE=0, IS_ARCHIVE=0 WHERE ID_CAB={$listaDescuentos[$i][0]['ID_CAB']} AND ID_LIN={$listaDescuentos[$i][0]['ID_LIN']}";
                                   
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
            //+04-04-19
            //if($suma_por>0){
            if($suma_porTot>0){
                //$off=(float)$rate_base_net * $suma_por / 100;
                $off=(float)$rate_base_net * $suma_porTot / 100;
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
                    //"VALOR_%"=>$suma_por,
                    "VALOR_%"=>$suma_porTot,
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
        //$this->escribe("ListaDescuentos_JM",print_r($listaDescuentos,true));
        //-arq
        $DISCOUNT=$this->get_Total_Discounts($db, $arg, $par,$modo,$listaDescuentos);
        //+arq
        //$this->escribe("ListaDescuentosDiscount_JM",print_r($DISCOUNT,true));
        //$this->escribe("ListaDescuentosDespues",print_r($listaDescuentos,true));
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
                    /*$off=0;
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
                    $SPECIAL['FINAL'] = $net + $markup - $off;*/
                    
                    $off=0;
                    //$off = (float)$DISCOUNT['VALOR'];
                   
                    if((int)$DISCOUNT['VALOR_%']<=(int)$arg['LIMIT_MPRICE']){
                        $off = (float)$DISCOUNT['VALOR'];
                    }
                    //$net = (int)$par['RATE']['NET'] - $off;
                    $net = (int)$par['RATE']['NET'];
                    //$markup = round($net * ((float)$par['RATE']['MARKUP_%'] / 100));
                    $markup = $net * ((float)$par['RATE']['MARKUP_%'] / 100);
                    $SPECIAL=array();
                    $SPECIAL['ID']=$DISCOUNT['ID_CAB'];
                    $SPECIAL['REFERENCE']=$DISCOUNT['REFERENCE'];
                    $SPECIAL['ACCES_CODE']=$DISCOUNT['SISTEMA'];
                    $SPECIAL['OFF_%']=$DISCOUNT['VALOR_%'];
                    $SPECIAL['OFF_$']=$off;
                    $SPECIAL['NET'] = $net;
                    $SPECIAL['MARKUP_$'] = $markup;
                    //$SPECIAL['FINAL'] = $net + $markup;
                    $SPECIAL['FINAL'] = $net + $markup - $off;
                    
                }
            }
        }

    }
//-MC 21-12-18

}
global $clsDiscounts;
$clsDiscounts = new discounts;
?>