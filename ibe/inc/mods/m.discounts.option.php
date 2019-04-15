
<fieldset>
    <legend>Mode</legend>
        <input type="radio" name="mode" value="na" <?print (isset($_DATA[0]['SYSTEM']) and $_DATA[0]['SYSTEM']!="BO_P" and $_DATA[0]['SYSTEM']!="BO_G" )?"checked='checked'":"";?>/>
        <label for="dewey">Discount</label>
        

        <input type="radio"  name="mode" value="BO_P" <?print (isset($_DATA[0]['SYSTEM']) and $_DATA[0]['SYSTEM']=="BO_P")?"checked='checked'":"";?>/>
        <label for="dewey">Partial Black Out</label>

        <input type="radio"  name="mode" value="BO_G" <?print (isset($_DATA[0]['SYSTEM']) and $_DATA[0]['SYSTEM']=="BO_G")?"checked='checked'":"";?>/>
        <label for="dewey">Global Black Out</label>
</fieldset>
<?
$style_ocultar="";
$style_ocultar2="";
if(isset($_DATA[0]['SYSTEM'])){
     if($_DATA[0]['SYSTEM']=="BO_G"){
        $style_ocultar="style='display:none;'";
        $style_ocultar2="style='display:none;'";
     }elseif ($_DATA[0]['SYSTEM']=="BO_P") {
         $style_ocultar2="style='display:inline;'";
         $style_ocultar="style='display:none;'"; 
     }  
}
  
?>

<fieldset class="options" <?print $style_ocultar; ?>>
    <legend>Options</legend>
    <div class="fieldset">
        <div class="label">
            <table width="100%" border="0">
            <tr>
                <td width="15%" nowrap>
                    Access Type&nbsp;
                   <select name="CODE" id="CODE">
                        <?  print "<option value='Desktop' ".((isset($_DATA[0]['CODE']) && $_DATA[0]['CODE']=='Desktop') ? "selected" : "").">Desktop</option>"; ?>
                        <?  print "<option value='Movil' ".((isset($_DATA[0]['CODE']) && $_DATA[0]['CODE']=='Movil') ? "selected" : "").">Movil</option>"; ?>
                        <?  print "<option value='Desktop+Movil' ".((isset($_DATA[0]['CODE']) && $_DATA[0]['CODE']=='Desktop+Movil') ? "selected" : "").">Desktop+Movil</option>"; ?>
                            
                        </select>
                </td>
                <td width="30%" nowrap>
                    
                        Priority&nbsp;
                    <select name="PRIORITY" id="PRIORITY" ><?
                    $prioridad_f=$clsDiscounts->get_priority($db,"D_");
                    $prioridad_bd=isset($_DATA[0]['PRIORITY'])?$_DATA[0]['PRIORITY']:0;
                    if($prioridad_bd==0){
                       $prioridad_bd=$prioridad_f;
                    }
                    for ($t=1; $t<= 20; ++$t){                       
                        //print ($prioridad_bd==$t)?"selected":"";
                        print "<option value='{$t}' ".($prioridad_bd==$t? "selected" : "").">{$t}</option>";
                    } 
                        //print "<option value='{$t}' ".(((isset($_DATA[0]['PRIORITY']) && (int)$_DATA[0]['PRIORITY']==$t) or $clsDiscounts->get_priority($db,"D_")==$t ) ? "selected" : "").">{$t}</option>"; 
                    ?></select>
                </td>
                <td width="25%" nowrap>
                    Environment&nbsp;
                    <select name="ENVIRONMENT" id="ENVIRONMENT">
                        <?  print "<option value='Front' ".((isset($_DATA[0]['ENVIRONMENT']) && $_DATA[0]['ENVIRONMENT']=='Front') ? "selected" : "").">Front</option>"; ?>
                        <?  print "<option value='Back' ".((isset($_DATA[0]['ENVIRONMENT']) && $_DATA[0]['ENVIRONMENT']=='Back') ? "selected" : "").">Back</option>"; ?>
                        <?  print "<option value='Front+Back' ".((isset($_DATA[0]['ENVIRONMENT']) && $_DATA[0]['ENVIRONMENT']=='Front+Back') ? "selected" : "").">Front+Back</option>"; ?>
                            
                        </select>
                </td>
                <td width="10%" nowrap>
                    
                    
                    <?
                    $valor_por="style='display:none; background-color: #E9E9E9; font-size: 20px;'";
                    //$valor_por="style='display:inline; background-color: #E9E9E9; font-size: 20px;'";
                    if($radio['na']){
                                    $valor_por="style='display:inline; background-color: #E9E9E9; font-size: 20px;'";
                                } 
                    if(isset($_DATA['LINMOD'])){
                                
                                           
                                ?>
                           
                            <div class="field"><input   type="text" id="VALUE" name="VALUE"  maxlength='3' size='3' value="<? print isset($_DATA['LINMOD'][0]['VALUE']) ? $_DATA['LINMOD'][0]['VALUE'] : "" ?>" class="small" <?print $valor_por;?>> <!-- <div id='por' name='por' <?print $valor_por;?>>%</div> --></div>
                            <? 
                        }else
                        {?>
                                
                            <div class="field"><input   type="text" id="VALUE" name="VALUE"  maxlength='3' size='3' value="" class="form-control" <?print $valor_por;?>><!--<div id='por' name='por' style='display:inline;'>%</div>--> </div>
                     <?}?>
   
                </td>
                <td width="10%" nowrap>
                    <b>Type</b>&nbsp;
                    <!--<select name="TIPO" id="TIPO">
                        <?  print "<option value='Descuento' ".((isset($_DATA[0]['TYPE']) && $_DATA[0]['TYPE']=='Descuento') ? "selected" : "").">Descuento</option>"; ?>
                        <?  print "<option value='Aumento'".((isset($_DATA[0]['TYPE']) && $_DATA[0]['TYPE']=='Aumento') ? "selected" : "").">Aumento</option>"; ?> 
                       
                            
                        </select>-->
                        

                        <select name="SYMBOL" id="SYMBOL">
                        <?  print "<option value='%' ".((isset($_DATA['LINMOD'][0]['SYMBOL']) && $_DATA['LINMOD'][0]['SYMBOL']=='%') ? "selected" : "").">%</option>"; ?>
                        <?  print "<option value='$'".((isset($_DATA['LINMOD'][0]['SYMBOL']) && $_DATA['LINMOD'][0]['SYMBOL']=='$') ? "selected" : "").">$</option>"; ?> 
                       
                            
                        </select>
                </td>
                
                
            </tr>
            <tr>
            </tr>
            <tr><td>
                <b>Night Range</b>&nbsp;
                </td>
                <td>
                
                </td>
                <td>
                <b>Rooms Range</b>&nbsp;
                </td>
                <td>
                
                </td>
            </tr>
            <tr>
                <td width="15%" nowrap>
                    Min. Night&nbsp;
                    <select name="MIN_NIGHT" id="MIN_NIGHT"><? for ($t=0; $t<= 10; ++$t) print "<option value='{$t}' ".((isset($_DATA['LINMOD'][0]['MIN_NIGHT']) && (int)$_DATA['LINMOD'][0]['MIN_NIGHT']==$t) ? "selected" : "").">{$t}</option>"; ?></select>
                     Max. Night&nbsp;
                    <select name="MAX_NIGHT" id="MAX_NIGHT"><? for ($t=0; $t<= 10; ++$t) print "<option value='{$t}' ".((isset($_DATA['LINMOD'][0]['MAX_NIGHT']) && (int)$_DATA['LINMOD'][0]['MAX_NIGHT']==$t) ? "selected" : "").">{$t}</option>"; ?></select>
                </td>
                <td width="15%" nowrap>
                   
                </td>
                <td width="15%" nowrap>
                    Min. Rooms&nbsp;
                    <select name="MIN_ROOM" id="MIN_ROOM"><? for ($t=0; $t<= 3; ++$t) print "<option value='{$t}' ".((isset($_DATA['LINMOD'][0]['MIN_ROOM']) && (int)$_DATA['LINMOD'][0]['MIN_ROOM']==$t) ? "selected" : "").">{$t}</option>"; ?></select>
                    Max. Rooms
                    <select name="MAX_ROOM" id="MAX_ROOM"><? for ($t=0; $t<= 3; ++$t) print "<option value='{$t}' ".((isset($_DATA['LINMOD'][0]['MAX_ROOM']) && (int)$_DATA['LINMOD'][0]['MAX_ROOM']==$t) ? "selected" : "").">{$t}</option>"; ?></select>

                </td>
                <td width="15%" nowrap>
                    
                </td>
            </tr>

            <tr>

                <td width="25%" nowrap>
                   <!--Sistema&nbsp;-->
                  
               </td>
                   
            </table>
            
        </div>
    </div>
</fieldset>

