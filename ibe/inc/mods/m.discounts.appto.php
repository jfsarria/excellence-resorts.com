<fieldset class="aply_to" <? print $style_ocultar2; ?>>
    <legend>Applicable To</legend>
        <input type="radio" name="aply_to"  value="na" <?print ($radio['na'])?"checked='checked'":"";?>/>
        <label for="dewey">N/A</label>
        <!--<input type="radio" name="aply_to" value="geo" <?print ($radio['geo'])?"checked='checked'":"";?> />
        <label for="dewey">GEO</label> -->

        <input type="radio"  name="aply_to"  value="rooms" <?print ($radio['rooms'])?"checked='checked'":"";?>/>
        <label for="dewey">Rooms</label>

        <input type="radio"  name="aply_to"  value="rate" <?print ($radio['rate'])?"checked='checked'":"";?>/>
        <label for="dewey">Rate Class</label>
</fieldset>
<script>

        $("input[type=radio]").click(function(event){
        var valor = $(event.target).val();
        var valor2=event.target.name;
        if(valor2=="mode"){
            if(valor=="BO_G"){
                $(".options").hide();
                $(".aply_to").hide();
                $("#geo1").hide();
                
            }else if(valor=="BO_P") {            
                $(".options").hide();
                $(".aply_to").show();
                $("#geo1").show();
            }else if(valor=="na") {
            
                $(".options").show();
                $(".aply_to").show();
                $("#geo1").show();
            }
        }
        if(valor2=="aply_to"){
            $("#por").css('display','inline');
            $("#laberpor").css('display','inline');
            $("#VALUE").css('display','inline');      
           
            if (valor == "rooms") {
                //$("#geo1").hide();
                $("#rooms1").show();
                $("#rate1").hide();
                $("#VALUE").hide();
                $("#por").hide();
                $("#labelpor").hide();
            } else if (valor == "rate") {
                //$("#geo1").hide();
                $("#rooms1").hide();
                $("#rate1").show();
                $("#VALUE").hide();
                 $("#por").hide();
                 $("#labelpor").hide();
            }else if (valor == "na") {
                //$("#geo1").hide();
                $("#rooms1").hide();
                $("#rate1").hide();
                $("#VALUE").show();
                $("#por").show();
                $("#labelpor").show();
            }
        }        
        
    });

</script>

<script>
$("#Generalgeo").click(function() {
        var p = $(this);
        if(p[0].checked==true){
            var cont=0;
            $("#GeosPickList input[type='text']").each(function() { 
                //alert("hola "+$(this)[0].name);
                $(this)[0].style.display="none";
               $("#porgeo"+cont).hide();  
               cont++;                
            });
            $("#porgeo").show();
                $("#Graltxtgeo").show(); 
        }else{
            var cont=0;
             $("#GeosPickList input[type='text']").each(function() { 
                //alert("hola "+$(this)[0].value);
                $(this)[0].style.display="inline";
                $("#porgeo"+cont).css('display','inline');
                //obj.addClass("selected");
               // $("#por"+cont).show();
                //var cadena="por"+cont;
                //document.getElementById(cadena).style.visibility = "inline";                
                cont++;  
            });
             $("#porgeo").hide();

                $("#Graltxtgeo").hide(); 
        }
    });
$("#Generalroo").click(function() {
        var p = $(this);
        if(p[0].checked==true){
            var cont=0;
            $("#roomsTbl input[type='text']").each(function() { 
                //alert("hola "+$(this)[0].name);
                $(this)[0].style.display="none";
               $("#porroo"+cont).hide();  
               cont++;                
            });
            $("#porroo").show();
                $("#Graltxtroo").show(); 
        }else{
            var cont=0;
             $("#roomsTbl input[type='text']").each(function() { 
                //alert("hola "+$(this)[0].value);
                $(this)[0].style.display="inline";
                $("#porroo"+cont).css('display','inline');
                //obj.addClass("selected");
               // $("#por"+cont).show();
                //var cadena="por"+cont;
                //document.getElementById(cadena).style.visibility = "inline";                
                cont++;  
            });
             $("#porroo").hide();

                $("#Graltxtroo").hide(); 
        }
    });

</script>

<div id="rooms1" <?print ($radio['rooms'])?"style='display:visibility;'":"style='display:none;'";?>>
<fieldset>
    <legend>Applicable to Rooms Type</legend>
    <div class="fieldset">
        <div class="label">
            <table width="100%" style='border-bottom:solid 1px #C0C0C0;margin-bottom:5px;'>
            <tr>
                <td><span><input id='check_all_rooms' name='check_all_rooms' type='checkbox' <? if ($check_all_rooms=="on") print "checked" ?>></span>&nbsp;Select all</div></td>
            </tr>
            </table>
            
            <table>
            <tr>
                <td>
                 <input type='checkbox' name='Generalroo' id="Generalroo" value='generalroo' checked><label>General</label>
                 &nbsp;
            </td>
            <td>
                <input type='text' name='Graltxtroo' id="Graltxtroo" value="<? print isset($_DATA['LINMOD'][0]['VALUE']) ? $_DATA['LINMOD'][0]['VALUE'] : "" ?>" maxlength='3' size='3'><!-- <div id='porroo' name='porroo' style='display:inline;'>%</div> -->
                &nbsp;
            </td>
        </tr>
        </table>
        <br style='clear:both'>
            <table class="pickList" width='100%' id='roomsTbl'>
            <tr>
            <?
            $ROOM_IDs=array();
            $ROOM_valor=array();
            $ROOM_id=array();
            if(isset($_DATA['LINMOD'])){
                for($i=0;$i<count($_DATA['LINMOD']);$i++){
                    $ROOM_IDs[]=$_DATA['LINMOD'][$i]['ROOM'];
                    $ROOM_valor[]=$_DATA['LINMOD'][$i]['VALUE'];
                    $ROOM_id[]=$_DATA['LINMOD'][$i]['ID_LIN'];
                } 
            }
               
            //$ROOM_IDs = isset($_DATA['ROOM_IDs']) ? $_DATA['ROOM_IDs'] : array();

            $RSET = $clsRooms->getByProperty($db, array("PROP_ID"=>$PROP_ID));
            if ( $RSET['iCount'] != 0 ) {
                $cnt=0;
                while ($row = $db->fetch_array($RSET['rSet'])) {
                    $NAME = _d($row['NAME_'.$_IBE_LANG],$row['NAME_EN']);
                    if (in_array($row['ID'],$ROOM_IDs)===false && $check_all_rooms=="on") array_push($ROOM_IDs,$row['ID']);
                    $pos=-1;
                $valortext="";
                $idroo="";
                if(in_array($row['ID'],$ROOM_IDs)===true){
                    $pos= array_search($row['ID'], $ROOM_IDs);
                    if($pos>=0){
                        $valortext=$ROOM_valor[$pos];
                        $idroo=$ROOM_id[$pos];
                    }  
                }

                    print "<td width='40%' class='pickListItem i{$cnt}'>
                    <input type='hidden' id='rooid[]' name='rooid[]' value='{$idroo}'>
                    <input type='hidden' id='roocheck[]' name='roocheck[]' value='{$row['ID']}'>
                    <input type='checkbox' name='ROOM_IDs[]' value='{$row['ID']}' ".((in_array($row['ID'],$ROOM_IDs)===true)?"checked":"").">&nbsp;{$NAME} </td> <td class='pickListItem i{$cnt}'><input type='text' id='rootext[]' name='rootext[]' ".((in_array($row['ID'],$ROOM_IDs)===true)?"style='display:none'":"style='display:none;'")." value='{$valortext}' maxlength='3' size='3'> <div id='porroo{$cnt}' name='porroo{$cnt}' ".((in_array($row['ID'],$ROOM_IDs)===true)?"style='display:inline;'":"style='display:none;'")."></div></td>";
                    if (fmod(++$cnt,2)==0) print "</tr><tr>";
                }
            }
            ?>
            </tr>
            </table>
        </div>
    </div>
</fieldset>
</div>
<script>
    $("#check_all_rooms").click(function() {
        var p = $(this);
        $("#roomsTbl input[type='checkbox']").each(function() { 
            $(this)[0].checked = p[0].checked;
        });
    });

    //$("#roomsTbl input[type='checkbox']").each(function() { 
    //    $(this).click(function() {
    //        if (!$(this)[0].checked) $("#check_all_rooms")[0].checked = false;
    //    });
    //});
    $("#Generalroo").click(function() {
        var p = $(this);
        if(p[0].checked==true){
            var cont=0;
            $("#roomsTbl input[type='text']").each(function() { 
                //alert("hola "+$(this)[0].name);
                $(this)[0].style.display="none";
               $("#porroo"+cont).hide();  
               cont++;                
            });
            $("#porroo").show();
                $("#Graltxtroo").show(); 
        }else{
            var cont=0;
             $("#roomsTbl input[type='text']").each(function() { 
                //alert("hola "+$(this)[0].value);
                $(this)[0].style.display="inline";
                $("#porroo"+cont).css('display','inline');
                //obj.addClass("selected");
               // $("#por"+cont).show();
                //var cadena="por"+cont;
                //document.getElementById(cadena).style.visibility = "inline";                
                cont++;  
            });
             $("#porroo").hide();

                $("#Graltxtroo").hide(); 
        }
    });

</script>
</script>

<div id="rate1" <?print ($radio['rate'])?"style='display:visibility;'":"style='display:none;'";?> >
<fieldset>
    <legend>Applicable to Class</legend>
    <?
    $fecha_det[]=date("Y");
    if(isset($_DATA[0]['CAB_FROM'])){
        $fecha=date($_DATA[0]['CAB_FROM']);
        $fecha2=date($_DATA[0]['CAB_TO']); 
        $date1 = new DateTime($fecha);
        $date2 = new DateTime($fecha2);
        $diff = $date1->diff($date2);
       
        //$diff = $fecha->diff($fecha2);
        //print_r($diff->y);
        $dif=(int)$diff->y;
        if($dif>0){
            for($i=0;$i<$dif;$i++){
                $nuevafecha = strtotime ( '+'.$i.' year' , strtotime ( $fecha ) ) ;
                $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
                $fecha_det[]= explode("-",$nuevafecha);
            }
        }else{
            $fecha_det[]= explode("-",$fecha);
        }
        
        
    }

    
//print_r($fecha_det[0]);

    include "m.discounts.class.filters.php"; ?>
    <div class="fieldset">
        <div class="label">
            <div style='display:none'>
                Applicable to All&nbsp;<span><input type="checkbox" id="ALL_CLASSES" name="ALL_CLASSES" value="1" <? print (isset($_DATA['ALL_CLASSES'])&&(int)$_DATA['ALL_CLASSES']==1) ? "checked" : "" ?>>

                </span>
            </div>
            <br>

            <div id='classList'>
                <?
                $cont=count($fecha_det)-1;
                for ($YEAR=$fecha_det[0]; $YEAR <= date("Y"); ++$YEAR) {
                    $DISPLAY = (in_array($YEAR,$fecha_det)) ? "" : "none";
                    print "<div id='classList_{$YEAR}' style='display:{$DISPLAY};margin-bottom:20px;'>
                                <div class='classYear' style='padding-bottom:5px'><b>{$YEAR} Classes</b>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"ibe.select.setAllClassesByYear('{$YEAR}', true)\">Check all</a>&nbsp;-&nbsp;<a href='javascript:void(0)' onclick=\"ibe.select.setAllClassesByYear('{$YEAR}', false)\">Uncheck all</a></div>
                                <div id='classes_$YEAR'></div>";
                    print "</div>";
                }?>
            </div>
        </div>
    </div>
</fieldset>
</div>

<script>
    $("#YearsPickList input[type='checkbox']").each(function() {
        
        ibe.select.showClassesDiscounts($(this),_ID_CAB);
        //ibe.select.showClassesDiscounts($("#YearsPickList"),$("#YearsPickList"));
    });

    ibe.select.controlSpecialFiltersDiscounts(_ID_CAB);
</script>
