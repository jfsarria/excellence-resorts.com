<?
/*
 * Revised: Sep 18, 2011
 */

$YEARS = isset($_GET['YEAR']) ? $_GET['YEAR'] : 0;
$GEOS = isset($_GET['GEOS']) ? $_GET['GEOS'] : "";
$SEASON = isset($_GET['SEASON']) ? $_GET['SEASON'] : "";
$ROOM = isset($_GET['ROOM']) ? $_GET['ROOM'] : "";
$ID_CAB=isset($_GET['ID_CAB']) ? $_GET['ID_CAB'] : "";
//print_r($_GET);

$RSET = $clsDiscounts->getByIdLin($db, array("ID_CAB"=>$ID_CAB),"");
     //print "<pre> SalidLin";print_r($RSET);print "</pre>";
$_rateclass=array();
$_idlin=array();
$_idvalor=array();
if ( $RSET['iCount'] != 0 ) {
    
    while ($row = $db->fetch_array($RSET['rSet'])) {
        $_rateclass[]=$row['RATECLASES'];
        $_idlin[]=$row['ID_LIN'];
        $_idvalor[]=$row['VALUE'];
    }
    //$_DATA[] = $db->fetch_array($RSET['rSet']);  //while ($row = $db->fetch_array($RSET['rSet'])) {
    //$_DATA['LINMOD']=$_Lineas;
}
        

?>

<div id="wrapper" year="<? print isset($_GET['YEAR']) ? $_GET['YEAR'] : $PROP_ID ?>">
        <table>
            <tr>
                 <td>
                     <input type='checkbox' name='Generalrat' id="Generalrat" value='generalrat' checked><label>General</label>
                     &nbsp;
                </td>
                <td>
                    <input type='text' name='Graltxtrat' id="Graltxtrat" value='<? print (isset($_idvalor[0])?$_idvalor[0]:"");?>' maxlength='3' size='3'> <!--<div id='porrat' name='porrat' style='display:inline;'>%</div> -->
                    &nbsp;
                </td>
            </tr>
        </table>
        <br>
    <table class="pickList" width='100%' id="packlistrate">
    <tr>
    <?// esto esta estalecidoa una tabla, debe enfocarsea lso descuentos que tenemso ahora.
        $CLASSES = $clsSpecials->getClasses($db, array("SPECIAL_ID"=>$SPECIAL_ID,"AS_ARRAY"=>true,"WHERE"=>" AND CLASSES.IS_ACTIVE='1' "));

        
        $RSET = $clsClasses->getByFilters($db, array("PROP_ID"=>$PROP_ID,"YEAR"=>$YEARS,"GEOS"=>$GEOS,"SEASON"=>$SEASON,"ROOM"=>$ROOM));
        if ( $RSET['iCount'] != 0 ) {
            $cnt=0;
            while ($row = $db->fetch_array($RSET['rSet'])) {
                $ID_CAB=$row['ID'];
                //print_r($row);

                $pos=-1;
                $valortext="";
                $idrate="";
                if(in_array($row['ID'],$_rateclass)){
                    $pos= array_search($row['ID'], $_rateclass);
                    if($pos>=0){
                        $valortext=$_idvalor[$pos];
                        $idrate=$_idlin[$pos];
                    }  
                }

                $NAME = _d($row['NAME_'.$_IBE_LANG],$row['NAME_EN']);
                $REFERENCE = trim($row['REFERENCE'])!=""?$row['REFERENCE']:$NAME;
                $CHECKED = (in_array($row['ID'],$_rateclass)) ? "checked" : "";
                $visible=(in_array($row['ID'],$_rateclass)) ? "style='display:none;'" : "style='display:none;'";
                $discounts="<input type='hidden' id='ratid[]' name='ratid[]' value='{$idrate}'> <input type='text' id='rattext[]' name='rattext[]' {$visible} maxlength='3' size='3' value='{$valortext}'> <div id='porrat{$cnt}' name='porrat{$cnt}' {$visible}></div>";

                print "
                <td width='50%' class='pickListItem i{$cnt}' valign='top'>
                    <table cellpadding='1' cellspacing='1' border='0'>
                    <tr>
                        <td valign='top'><span>
                        <input type='hidden' id='ratcheck[]' name='ratcheck[]' value='{$row['ID']}'>
                        <input rel='{$row['YEAR']}' type='checkbox' name='CLASS_ID[]' value='{$row['ID']}' {$CHECKED}> </span></td>
                        <td valign='top' style='padding-left:3px'>{$REFERENCE} {$discounts}</td>
                    </tr>
                    </table>
                </td>";
                if (fmod(++$cnt,2)==0) print "</tr><tr>";
            }
        }
    ?>
    </tr>
    </table>
</div>
<script>
$("#Generalrat").click(function() {
        var p = $(this);
        if(p[0].checked==true){
            var cont=0;
            $("#packlistrate input[type='text']").each(function() { 
                //alert("hola "+$(this)[0].name);
                $(this)[0].style.display="none";
               $("#porrat"+cont).hide();  
               cont++;                
            });
            $("#porrat").show();
                $("#Graltxtrat").show(); 
        }else{
            var cont=0;
             $("#packlistrate input[type='text']").each(function() { 
                //alert("hola "+$(this)[0].value);
                $(this)[0].style.display="inline";
                $("#porrat"+cont).css('display','inline');
                //obj.addClass("selected");
               // $("#por"+cont).show();
                //var cadena="por"+cont;
                //document.getElementById(cadena).style.visibility = "inline";                
                cont++;  
            });
             $("#porrat").hide();

                $("#Graltxtrat").hide(); 
        }
    });
</script>