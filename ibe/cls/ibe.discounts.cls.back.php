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
        $cantidad=(isset($INVENTORY)?$INVENTORY:1);
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
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
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
        $query = "SELECT t1.*,guests.EMAIL FROM LINMODPRICE t1 
            left join REL_LINMODPRICE_EMAIL t2 on t1.ID_LIN=t2.ID_LIN 
            left join guests on t2.ID_GUEST=guests.ID WHERE ID_CAB='{$ID_CAB}' {$valor}";
        //print "<p class='s_notice top_msg'> SAVE ".$query."</p>";

        //exit;
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
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
    function addNewRelation($db, $arg) {
        extract($arg);
        //ELIMINO
        $query = "DELETE FROM REL_LINMODPRICE_EMAIL WHERE ID_LIN='{$ID_LIN}'"; 
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        //print "<p class='s_notice top_msg'>Insert: $query</p>";
        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);
        //CREO RELACION      
        $query = "INSERT INTO REL_LINMODPRICE_EMAIL ( ID_LIN, ID_GUEST ) VALUES ( '{$ID_LIN}', '{$couponrel}' )"; 
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $result;
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
    function codigo($longitud, $tipo=0)
    {
        $codigo = "";        
        $caracteres="ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";        
        $max=strlen($caracteres)-1;       
        for($i=0;$i < $longitud;$i++)
        {
            $codigo.=$caracteres[rand(0,$max)];
        }        
        return $codigo;
    }
    function coupon($PROP_ID){
        $var=$PROP_ID;
        $var.=$this->codigo(2);

        $var.=date("y");
        $var.=$this->codigo(7);

        return $var;
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
            if ($SYSTEM=="C_"){

                $cadena=$this->coupon($PROP_ID);
                //print "<pre>";print_r($cadena);print "</pre>";
                array_push($arr," PROMOCODE = '{$cadena}'");
            } 
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

        if (isset($PRIORITY)) array_push($arr," PRIORITY = '$PRIORITY'");
        if (isset($CODE)) array_push($arr," CODE = '$CODE'");
        if (isset($ENVIRONMENT)) array_push($arr," ENVIRONMENT = '$ENVIRONMENT'");
        if (isset($TYPE)) array_push($arr," TYPE = '$TYPE'");
        if (isset($SYSTEM)){
            switch ($TYPE) {
                case 'discounts':
                        //print "<p class='s_notice top_msg'>Modify: $SYSTEM</p>";
                        $M_NIGHT=isset($MIN_NIGHT)?$MIN_NIGHT:0;
                        $M_NIGHT1=isset($MAX_NIGHT)?$MAX_NIGHT:0;
                        if($M_NIGHT!=0 || $M_NIGHT1!=0){
                            $SYSTEM='D_N_NIGHT';
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
                    break;
                case 'flashsale':                   
                        $option=(isset($aply_to)?$aply_to:"na");
                        switch ($option) {
                            case "rooms":
                                    $SYSTEM='F_ROOM';
                                break;
                             case "rate":
                                    $SYSTEM='F_RATE';
                                break;                
                            
                        }
                    break;
            }

            
            //print "<p class='s_notice top_msg'>Modify: $SYSTEM</p>";            
            array_push($arr," SYSTEM = '$SYSTEM'");
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

        return $result;
    }
 // se trae toda la ista de la tabla, en args puedes pasarle complemento del query
    function getByProperty($db, $arg) {
        extract($arg);
        global $_IBE_LANG;
            
        $WEHRE = isset($WEHRE) ? $WEHRE : "";
     
        $query = "SELECT * FROM HEADMODPRICE WHERE PROP_ID='{$PROP_ID}' {$WEHRE} ORDER BY `PRIORITY`, NAME_{$_IBE_LANG} ";
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

    /* ************************** */

  

}
global $clsDiscounts;
$clsDiscounts = new discounts;
?>