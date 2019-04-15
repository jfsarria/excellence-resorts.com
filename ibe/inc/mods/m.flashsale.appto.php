
<fieldset>
    <legend>Applicable To</legend>
        <input type="radio" name="aply_to" value="na" <?print ($radio['na'])?"checked='checked'":"";?>/>
        <label for="dewey">N/A</label>
        <!--<input type="radio" name="aply_to" value="geo" <?print ($radio['geo'])?"checked='checked'":"";?> />
        <label for="dewey">GEO</label> -->

        <input type="radio"  name="aply_to" value="rooms" <?print ($radio['rooms'])?"checked='checked'":"";?>/>
        <label for="dewey">Rooms</label>

</fieldset>
<script>

        $("input[type=radio]").click(function(event){
        var valor = $(event.target).val(); 
        $("#por").css('display','inline');
        $("#laberpor").css('display','inline');
        $("#VALUE").css('display','inline');       
       /* if(valor =="geo"){
            $("#geo1").show();
            $("#rooms1").hide();
            $("#rate1").hide();            
            $("#VALOR").hide();
            $("#por").hide();
            $("#labelpor").hide();

            //labelpor
        } else */
        if (valor == "rooms") {
            //$("#geo1").hide();
            $("#rooms1").show();
            $("#rate1").hide();
            //$("#INVENTORY").hide();
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
        if (valor == "unicode") {
            alert("No Disponible");
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
                 
                 &nbsp;
            </td>
            <td>
                <input type='text' name='Graltxtroo' id="Graltxtroo" value="<? print (isset($_DATA[0]['INVENTORY']) AND $_DATA[0]['INVENTORY']!=0) ? $_DATA[0]['INVENTORY'] : "" ?>" maxlength='3' size='3' >
                <input type='checkbox' name='Generalroo' id="Generalroo" value='generalroo' checked><label>General</label>  
                &nbsp;
            </td>
        </tr>
        </table>
        <br style='clear:both'>
            <table class="pickList" width='100%' id='roomsTbl'>
            <tr>
            <?
            //print "<h1>".$clsFlashsale->detailsRoom($db,$ID_CAB,"2079166")."</h1>";
            $ROOM_IDs=array();
            $ROOM_valor=array();
            $ROOM_id=array();
            if(isset($_DATA['LINMOD'])){
                for($i=0;$i<count($_DATA['LINMOD']);$i++){
                    //$ROOM_IDs[]=$_DATA['LINMOD'][$i]['ROOM'];
                    $_room=explode(',',$_DATA['LINMOD'][$i]['ROOM']);
                    for($j=0;$j<count($_room);$j++){
                       $ROOM_IDs[] =str_replace('.', '', $_room[$j]);
                       
                        if(count($_room)==1){
                            $ROOM_valor[]=$clsFlashsale->detailsRoom($db,$ID_CAB,$_room[$j],"0")." - ".$clsFlashsale->detailsRoom($db,$ID_CAB,$_room[$j],"");
                        }else{
                             $ROOM_valor[]= "";
                        }
                        $ROOM_id[]=$_DATA['LINMOD'][$i]['ID_LIN'];
                    }  
                   
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
                    <input type='hidden' id='rooid[]' name='rooid[]' value='.{$idroo}.'>
                    <input type='hidden' id='roocheck[]' name='roocheck[]' value='.{$row['ID']}.'>
                    <input type='checkbox' name='ROOM_IDs[]' value='.{$row['ID']}.' ".((in_array($row['ID'],$ROOM_IDs)===true)?"checked":"").">&nbsp;{$NAME} </td> <td class='pickListItem i{$cnt}'><input type='text' id='rootext[]' name='rootext[]' ".($valortext!=""?"style='display:inline'":"style='display:none;'")." value='{$valortext}' maxlength='3' size='3'> <div id='porroo{$cnt}' name='porroo{$cnt}' ".((in_array($row['ID'],$ROOM_IDs)===true)?"style='display:inline;'":"style='display:none;'")."></div></td>";
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


