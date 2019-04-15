<?
/*
 * Revised: Apr 23, 2016
 *          Jun 09, 2016
 *          Oct 04, 2016
 */

class flashsale extends discounts {
    function detailsRoom($db,$ID_CAB,$ROOM,$APPLIED){
        $app="";
        if($APPLIED!=""){
            $app="AND IS_APPLIED='{$APPLIED}'";
        }

        $query="select count(*)  TOTAL,ROOM from LINMODPRICE WHERE IS_ACTIVE=1 AND IS_ARCHIVE=0 AND ID_CAB={$ID_CAB} and ROOM='{$ROOM}' {$app}";
        $RSET=dbQuery($db, array('query' => $query));
        $salida=0;
        while ($row = $db->fetch_array($RSET['rSet'])) {
               $salida=$row['TOTAL'];         
        }    
        return $salida;
    }    

    function saveLinea($db, $arg) {
        extract($arg);               
        if(isset($ID_CAB)&& $ID_CAB!=0){          
                $result = $this->remove($db, array("DELETE_ID"=>$ID_CAB),false);           
            
        }
        $opcion=(isset($aply_to)?$aply_to:"na");
        $lin_mod_final=array();
        switch ($opcion) {          
            case 'rooms':
                if(isset($ROOM_IDs)){
                    $total=count($ROOM_IDs);
                    if($total>0){ 
                        if(isset($Generalroo)){
                             $val_por=(isset($Graltxtroo)?$Graltxtroo:0);
                             if($val_por==0){
                                $val_por=1;
                             }
                            for($k=0;$k<$val_por;$k++){
                                $lin_mod_final[]=array('ID_LINEA' =>'' ,
                                                    'CAMPO'=>'ROOM',
                                                    'VCAMPO'=>implode(',',$ROOM_IDs),
                                                    'VALOR'=>$VALUE,
                                                    'SIMBOLO'=>$SYMBOL,
                                                    'MIN_NIGHT'=>(isset($MIN_NIGHT)?$MIN_NIGHT:0),
                                                    'MAX_NIGHT'=>(isset($MAX_NIGHT)?$MAX_NIGHT:0),
                                                    'MIN_ROOM'=>(isset($MIN_ROOM)?$MIN_ROOM:0),
                                                    'MAX_ROOM'=>(isset($MAX_ROOM)?$MAX_ROOM:0)
                                                     ); 
                            }
                        }else{                            
                            for($i=0;$i<$total;$i++){
                                if($ROOM_IDs[$i]!=""){
                                    $val_por=(isset($Graltxtroo)?$Graltxtroo:0);
                                    $pos= array_search($ROOM_IDs[$i], $roocheck);
                                    if($val_por==0){
                                        if($rootext[$pos]!=""){
                                        $val_por=(int)$rootext[$pos];
                                        }
                                       
                                    }
                                    
                                    for($k=0;$k<$val_por;$k++){

                                        $lin_mod_final[]=array('ID_LINEA' =>'' ,
                                                    'CAMPO'=>'ROOM',
                                                    'VCAMPO'=>$ROOM_IDs[$i],
                                                    'VALOR'=>$VALUE,
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
       
      
        $lin_mod = array();
            //print_r($lin_mod_final);
        $cantidad=((isset($INVENTORY) && $INVENTORY!="")?$INVENTORY:1);
        
        

        
        for($h=0;$h<$cantidad;$h++){
           
            if(isset($Generalgeo)){
                if(isset($GEOS)){
                    for($k=0;$k<count($lin_mod_final);$k++){
                    //    $lin_mod_final[$k]['ID_LINEA']='';
                        $lin_mod_final[$k]['GEOCOUNTRY']=implode(',',$GEOS);
                        $lin_mod[]=$lin_mod_final[$k];
                    }
                }
                //else{
                 //   $lin_mod=$lin_mod_final;
                //}
            }else{

                if(isset($GEOS)){
                    $total=count($GEOS);
                    if($total>0){                    
                        for($i=0;$i<$total;$i++){
                            
                            if($GEOS[$i]!=""){
                                $val_por=0;
                                $pos= array_search($GEOS[$i], $geocheck);
                                if($GEOStext[$pos]!=""){
                                    $val_por=(int)$GEOStext[$pos];
                                }                             
                              
                                for($j=0;$j<$val_por;$j++){

                                    for($k=0;$k<count($lin_mod_final);$k++){
                                        
                                        $lin_mod_final[$k]['GEOCOUNTRY']=$GEOS[$i];
                                        $lin_mod[]=$lin_mod_final[$k];
                                    }
                                }                                  
                                    
                                                          
                            }
                        }
                    }
                }
            }
        }     

        if(empty($lin_mod)){
            $lin_mod=$lin_mod_final;
        }            
        //print_r($lin_mod); 
            

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
        
        $is_inv=false;
        if ($INVENTORY!=""){
            array_push($arr," INVENTORY = '$INVENTORY'");
            $is_inv=true;
        }else{
            $val_=(isset($Graltxtroo)?$Graltxtroo:0);
            if($val_!=0){
                array_push($arr," INVENTORY = '$val_'");
                $is_inv=true;
            }
            else{
                $cont=0;
                if(isset($ROOM_IDs)){
                    $total=count($ROOM_IDs);
                    for($i=0;$i<$total;$i++){
                                                if($ROOM_IDs[$i]!=""){
                                                    $val_por=(isset($Graltxtroo)?$Graltxtroo:0);
                                                    $pos= array_search($ROOM_IDs[$i], $roocheck);
                                                    if($val_por==0){
                                                        if($rootext[$pos]!=""){
                                                        $val_por=(int)$rootext[$pos];
                                                        $cont+=$val_por;
                                                        }
                                                       
                                                    }
                                }
                        
                    }
                }
                if($cont!=0){
                    array_push($arr," INVENTORY = '$cont'");
                    $is_inv=true;
                }
            }
            
        }
        if (isset($SYSTEM)){                              
            $option=(isset($aply_to)?$aply_to:"na");
            switch ($option) {
                case "rooms":
                        $SYSTEM='F_ROOM';
                    break;
                 case "rate":
                        $SYSTEM='F_RATE';
                    break;                
                
            }
            if(!$is_inv){
              $SYSTEM='F_INF';  
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
 
}
global $clsFlashsale;
$clsFlashsale = new flashsale;
?>