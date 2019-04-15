<?// print_r($_DATA)?>
<fieldset>
    <legend>Options</legend>
    <div class="fieldset">
        <div class="label">
            <table width="100%" border="0">
            <tr>
                <td width="30%" nowrap>
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
                    $prioridad_f=$clsDiscounts->get_priority($db,"F_");
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
                <td width="20%" nowrap>
                    
                    <b>Quantity</b>&nbsp;                    
                    <input type="text" id="INVENTORY" name="INVENTORY" value="<? print (isset($_DATA[0]['INVENTORY']) and $_DATA[0]['INVENTORY']!=0) ? $_DATA[0]['INVENTORY'] : "" ?>" maxlength='3' size='3' <? print (isset($_DATA[0]['INVENTORY']) and $_DATA[0]['INVENTORY']!=0) ? "style='display:inline;" : "style='display:none;" ?>'>   
                    <input type='checkbox' name='Infinito' id="Infinito" value='Infinito' <?print (isset($_DATA[0]['INVENTORY']) and $_DATA[0]['INVENTORY']!=0)?"checked":""?> >
                </td>
                <td width="5%" nowrap>
                    
                    
                    <?
                    $valor_por="style='display:inline; background-color: #E9E9E9; font-size: 20px;'";
                    //$valor_por="";
                     ?>
                   
                           
                            <div class="field"><b>Off</b>&nbsp;
                                <input   type="text" id="VALUE" name="VALUE"  maxlength='3' size='3' value="<? print isset($_DATA['LINMOD'][0]['VALUE']) ? $_DATA['LINMOD'][0]['VALUE'] : "" ?>" class="small s_required" <?print $valor_por;?>> <!-- <div id='por' name='por' <?print $valor_por;?>>%</div> --></div>
                            
                       
   
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
            

            </table>
            
        </div>
    </div>
</fieldset>
<script>

        $("input[type=checkbox]").click(function(event){
        var valor = $(event.target).val(); 
        
        if (valor == "Infinito") {
            //$("#geo1").hide();
            if($("#INVENTORY").is(':hidden')){
                $("#INVENTORY").show()
                $("#mode").show();
                $("#INVENTORY").val(''); 
            }
            else{
                $("#INVENTORY").hide();
                $("#INVENTORY").val('');
                $("#multicode").checked=false;
                $("#mode").hide(); 

            }
         $("#calculo").hide();

            //$("#rate1").hide();
            //$("#INVENTORY").hide();
            //$("#por").hide();
            //$("#labelpor").hide();
        //}

            //$("#rate1").hide();
            //$("#INVENTORY").hide();
            //$("#por").hide();
            //$("#labelpor").hide();
        } 
    });

</script>
