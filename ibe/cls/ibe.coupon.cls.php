<?
/*
 * Revised: Apr 23, 2016
 *          Jun 09, 2016
 *          Oct 04, 2016
 */

class coupon extends discounts {

    var $showQry = false;
  
    function saveLinea($db, $arg) {
        extract($arg);
        //print "<p class='s_notice top_msg'> SAVE ".PRINT_R($arg)."</p>";

        //exit;
        //eliminamos las lineas, para volver a crearlas
        if (isset($ID_CAB) && $ID_CAB != 0) {
            //if($SYSTEM=='T_ACCESO'){
                $result = $this->remove($db, array("DELETE_ID"=>$ID_CAB),false);
                //remove
            //}
            //else{
            //    $result = $this->archive($db, array("DELETE_ID"=>$ID_CAB),false);
            //}
            
        }
        //$opcion=(isset($aply_to)?$aply_to:"na");
        $lin_mod_final=array();
        
        $lin_mod_final[] = array(
                               'ID_LINEA' => '' ,
                               //'CAMPO'=>'GEOCOUNTRY',
                               //'VCAMPO'=>"",
                               'VALOR' => $VALUE,
                               'SIMBOLO' => $SYMBOL,
                               'MIN_NIGHT' => (isset($MIN_NIGHT) ? $MIN_NIGHT : 0),
                               'MAX_NIGHT '=> (isset($MAX_NIGHT) ? $MAX_NIGHT : 0),
                               'MIN_ROOM' => (isset($MIN_ROOM) ? $MIN_ROOM : 0),
                               'MAX_ROOM' => (isset($MAX_ROOM) ? $MAX_ROOM : 0)
                           );
        
            
        
         //print_r($lin_mod_final);
        // ahora geo aplica para todo
        $lin_mod = array();
            //print_r($lin_mod);
        $cantidad = ((isset($INVENTORY) && $INVENTORY != "") ? $INVENTORY : 0);
        //print "<p class='s_notice top_msg'> SAVE: ".print_r($cantidad)."</p>";
        $promo = "";
        if ($multicode == "multicode") {
                $promo = $this->get_coupon($PROP_ID);
        } else {
            $cantidad = count($mails);
        }

        if ($cantidad > 0) {     
 
            for ($h = 0; $h < $cantidad; $h++) {
                //if(isset($GEOS)){
                    //$total=count($GEOS);
                    //if($total>0){                    
                        //for($i=0;$i<$total;$i++){
                            //if($GEOS[$i]!=""){
                                //$val_por=(isset($Graltxtgeo)?$Graltxtgeo:"");
                             //   $pos= array_search($GEOS[$i], $geocheck);
                                //if($GEOStext[$pos]!=""){
                                //    $val_por=$GEOStext[$pos];
                                //} 
                                /*$lin_mod[]=array('ID_LINEA' =>$geoid[$pos] ,
                                                'CAMPO'=>'GEOCOUNTRY',
                                                'VCAMPO'=>$GEOS[$i],
                                                'VALOR'=>$val_por
                                                 );*/
                                
                                    //$id_geo=$geoid[$pos];
                                    //for($k=0;$k<count($lin_mod_final);$k++){
                                        //$lin_mod_final[0]['ID_LINEA']='';
                                        //$lin_mod_final[0]['GEOCOUNTRY']=$GEOS[0];
                                        //$lin_mod[]=$lin_mod_final[0];
                                    //}
                                                          
                            //}
                        //}
                    //}
                //}else{
                   $lin_mod[] = $lin_mod_final[0]; 
                //}
            }
        } else {
            //if(isset($GEOS)){
            //    $lista=$this->get_count_guest($db,$GEOS[0],"list");
            //    if(is_array($lista)){
            //        for($i=0;$i<count($lista);$i++){
            //            $lin_mod_final[0]['GEOCOUNTRY']=$GEOS[0];
            //            $lin_mod_final[0]['couponrel']=$lista[$i];
            //            $lin_mod[]=$lin_mod_final[0];
            //        }
            //    }
            //}else{
               $lin_mod = $lin_mod_final;
            //}
        }
            
        

         //print_r($lin_mod);      
        
         //print "<p class='s_notice top_msg'> SAVE: ".print_r($lin_mod)."</p>";
            

            for ($i = 0; $i<count($lin_mod); $i++) {
                //creamos los codigos;
                if ($promo != "") {
                    $lin_mod[$i]['PROMOCODE'] = $promo;
                } else {
                    $lin_mod[$i]['PROMOCODE'] = $this->get_coupon($PROP_ID);
                }

                $result = array();
                if ((int)$lin_mod[$i]['ID_LINEA'] != 0) {
                    $result = $this->getByIdLin($db, $arg, "and ID_LIN='".$lin_mod[$i]['ID_LINEA']."'" );
                    //exit;
                } else {
                    //print "<p class='s_notice top_msg'> SAVE ".print_r($result)."</p>";
                    $result['iCount'] = 0;

                    $lin_mod[$i]['ID_LINEA'] = dbNextIdMod($db);
                }

                if ( $result['iCount'] == 0 ) {

                    $result = $this->addNewLinea($db, $arg,$lin_mod[$i]);
                    if (isset($lin_mod[$i]['couponrel'])) {
                        $lin_mod[$i]['ID_LIN'] = $lin_mod[$i]['ID_LINEA'];
                        //print_r($lin_mod[$i]);
                        $this->addNewRelation($db, $lin_mod[$i]);
                    } else if ($multicode == "unicode") {
                        $lin_mod[$i]['ID_LIN'] = $lin_mod[$i]['ID_LINEA'];
                        $lin_mod[$i]['email'] = $mails[$i];
                        $this->addNewRelationMail($db, $lin_mod[$i]);
                    }
                } else {
                    $result = $this->modifyLinea($db, $arg,$lin_mod[$i]);
                }
            }
        
        

        return $result;
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

    function addNewRelationMail($db, $arg) {
        extract($arg);
        //ELIMINO
        $query = "DELETE FROM REL_LINMODPRICE_EMAIL WHERE ID_LIN='{$ID_LIN}'"; 
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        //print "<p class='s_notice top_msg'>Insert: $query</p>";
        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);
        //CREO RELACION      
        $query = "INSERT INTO REL_LINMODPRICE_EMAIL ( ID_LIN, EMAIL ) VALUES ( '{$ID_LIN}', '{$email}' )"; 
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $result;
    }

    function codigo($longitud, $tipo=0){
        $codigo = "";        
        $caracteres="ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";        
        $max=strlen($caracteres)-1;       
        for($i=0;$i < $longitud;$i++)
        {
            $codigo.=$caracteres[rand(0,$max)];
        }        
        return $codigo;
    }
    
    function get_coupon($PROP_ID){
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
            //if ($SYSTEM=="C_"){

            //$cadena=$this->get_coupon($PROP_ID);
            //print "<pre>";print_r($cadena);print "</pre>";
            array_push($arr," PROMOCODE = '{$valor['PROMOCODE']}'");
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
        $is_inf = true;
        /*if (isset($INVENTORY)){ 
            if ($INVENTORY != ""){
                $is_inf = false;
            }
            array_push($arr," INVENTORY = '$INVENTORY'");
        }*/
        if (isset($INVENTORY)){ 
            if ($multicode == "multicode") {
                $is_inf = false;
            }
            array_push($arr," INVENTORY = '$INVENTORY'");
        }
       
        if (isset($SYSTEM)){ 
            if($is_inf){
                $SYSTEM = 'C_INF';
            }
            array_push($arr," SYSTEM = '$SYSTEM'");
            //$priority=$this->get_priority($db,$SYSTEM);
            //array_push($arr," PRIORITY = '$priority'");
              
        }      
        
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

    function get_count_guest($db,$geo,$mode){
        global $clsGlobal;
        $arr = array("CODE"=>$geo);
        $group=$clsGlobal->getCountryGroupByCode($db,$arr);
        $arg=Array();
        if($group=="AA"){
           $group= $geo;
           $arg = array(                
                "CODE"=>$geo,
                "MAILING_LIST"=>"1",
                'COUNT'=>'1'
            );
        }else{
                $arg = array(                
                "GROUP"=>$geo,
                "MAILING_LIST"=>"1",
                'COUNT'=>'1'
            );

        }
        
        global $clsGuest;
        $RSET=$clsGuest->searchGuestsbyGeo($db, $arg);
        $salida="";
        switch ($mode) {
            case 'count':
               $salida=$RSET['iCount'];
                break;
            case 'list':
                while ($row = $db->fetch_array($RSET['rSet'])) {
                    $salida[]=$row['ID'];         
                }    
                break;
            
        }                 
        return $salida;
        
    }

    function remove($db, $arg,$Delcab) {
        extract($arg);
           
            if($Delcab){
                $result = dbExecute($db, array('query' => "DELETE FROM HEADMODPRICE WHERE ID_CAB='{$DELETE_ID}'"));                          
            }            
            $ar=array('query' => "SELECT ID_LIN FROM LINMODPRICE WHERE ID_CAB='{$DELETE_ID}'");
            
            $RSET = dbQuery($db,$ar);
            $lista_del=array();
            while ($row = $db->fetch_array($RSET['rSet'])) {               
               $lista_del[]=$row['ID_LIN'];
            }
            for($i=0;$i<count($lista_del);$i++){
                $result = dbExecute($db, array('query' => "DELETE FROM REL_LINMODPRICE_EMAIL WHERE ID_LIN='{$lista_del[$i]}'"));
            }                   
            $result = dbExecute($db, array('query' => "DELETE FROM LINMODPRICE WHERE ID_CAB='{$DELETE_ID}'"));

        return $result;
    }
  

}
global $clsCoupon;
$clsCoupon = new coupon;
?>