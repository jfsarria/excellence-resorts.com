<?
/*
 * Revised: Apr 25, 2011
 */
 //print "<h1>".print_r($_DATA)."</h1>";

//calculamos que tipo de relacion mostraremos
$flag="discounts";
$radio['na']=true;
$radio['rate']=$radio['rooms']=$radio['geo']=false;
//$array=
if(isset($_DATA['LINMOD'])){
    //print_r($_DATA['LINMOD']);
    //echo "entre a qui";

        for($i=0;$i<count($_DATA['LINMOD']);$i++){
            //print_r($i);
            //print_r($_DATA['LINMOD'][$i]['GEOCOUNTRY']);
            //if($_DATA['LINMOD'][$i]['GEOCOUNTRY']!=""){
            //    $radio['geo']=true;
            //    $radio['na']=true;
            //    //echo "entre geo";
            //    break;
            //}
            if($_DATA['LINMOD'][$i]['RATECLASES']!=0){
                $radio['rate']=true;
                $radio['na']=false;
                //echo "entre rate";
                break;
            }
            if($_DATA['LINMOD'][$i]['ROOM']!=0){
                $radio['rooms']=true;
                $radio['na']=false;
                //echo "entre room";
                break;
            }
    }
}
//print_r($radio);
?>
<br>
<fieldset>

    <div class="fieldset">
        <div class="label">
            <table align="center">
            <tr>
               <!--<td nowrap>Active&nbsp;<span><input type="checkbox" id="IS_ACTIVE" name="IS_ACTIVE" value="1" <? print (isset($_DATA[0]['IS_ACTIVE'])&&(int)$_DATA[0]['IS_ACTIVE']==1) ? "checked" : "" ;?>></span>&nbsp;&nbsp;&nbsp;</td> -->

                <td >Active&nbsp;<span><input type="checkbox" id="IS_ACTIVE" name="IS_ACTIVE" value="1" <? 
                print (isset($_DATA[0]['IS_ACTIVE'])&&(int)$_DATA[0]['IS_ACTIVE']==1) ? "checked" : " " ;?>></span>&nbsp;&nbsp;&nbsp;</td>
                <td >Archive&nbsp;<span><input type="checkbox" id="IS_ARCHIVE" name="IS_ARCHIVE" value="1" <? print (isset($_DATA[0]['IS_ARCHIVE'])&&(int)$_DATA[0]['IS_ARCHIVE']==1) ? "checked" : "" ?>></span></td>
            </tr>
            </table>                    
        </div>
    </div>
</fieldset>

<fieldset>
    <legend>Discounts Detail</legend>
    <div class="fieldset">
        <input type="hidden" name="ID_LIN" id="ID_LIN" value="<? print isset($_DATA['LINMOD'][0]['ID_LIN'])?$_DATA['LINMOD'][0]['ID_LIN']:"";?>"> 

        <div class="label">Name English</div>
        <div class="field"><input type="text" id="NAME_EN" name="NAME_EN" value="<? print isset($_DATA[0]['NAME_EN']) ? $_DATA[0]['NAME_EN'] : "" ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="EN") print " s_required" ?>"></div>
        <div class="label">Nombre Spanish</div>
        <div class="field"><input type="text" id="NAME_SP" name="NAME_SP" value="<? print isset($_DATA[0]['NAME_SP']) ? $_DATA[0]['NAME_SP'] : "" ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="SP") print " s_required" ?>"></div>
        <div class="label">Descripcion English</div>
        <div class="field"><input type="textarea" id="DESCR_EN" name="DESCR_EN" value="<? print isset($_DATA[0]['DESCR_SP']) ? $_DATA[0]['DESCR_SP'] : "" ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="SP") print " s_required" ?>"></div>
        <div class="label">Description Spanish</div>
        <div class="field"><input type="textarea" id="DESCR_SP" name="DESCR_SP" value="<? print isset($_DATA[0]['DESCR_SP']) ? $_DATA[0]['DESCR_SP'] : "" ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="SP") print " s_required" ?>"></div>       
    </div>
     <input type="hidden" name="SYSTEM" id="SYSTEM" value="D_">
     <input type="hidden" name="TYPE" id="TYPE" value="discounts">
</fieldset>
<?//PRIORIDAD 1-10?>

<? //inicia calendario?>
<fieldset>
    <legend>Booking Windows</legend>
    <div class="fieldset">
        <div class="label">
            <table align="center">
            <tr>
                <td><b>From</b>&nbsp;</td>
                <td align="right"><input type="hidden" id="WIN_FROM" name="WIN_FROM" value="<? print isset($_DATA[0]['WIN_FROM']) ? $_DATA[0]['WIN_FROM'] : "" ?>" /></td>
                <td style="padding-left:100px"></td>
                <input type="hidden" id="RES_NIGHTS" name="RES_NIGHTS" value="0" class="small">
                <td><b>To</b>&nbsp;</td>
                <td align="right"><input type="hidden" id="WIN_TO" name="WIN_TO" value="<? print isset($_DATA[0]['WIN_TO']) ? $_DATA[0]['WIN_TO'] : "" ?>" /></td>
            </tr>
            <tr>
                <td colspan="2"><div id="objWIN_FROM"></div></td>
                <td></td>
                <td colspan="2"><div id="objWIN_TO"></div></td>
            </tr>            
            </table>
        </div>
    </div>
</fieldset>

<script type="text/javascript">
    /* http://nogray.com/calendar.php */
    var sBookFrom,
        sBookTo

    ng.ready(function(){
        sBookFrom = new ng.Calendar({
            input: 'WIN_FROM',
            start_date: 'year - 5',
            display_date: '<? print (isset($_DATA[0]['WIN_FROM'])&&$_DATA[0]['WIN_FROM']!="0000-00-00 00:00:00") ? $_DATA[0]['WIN_FROM'] : $_TODAY ?>',
            visible: true,
            object: "objWIN_FROM",
            events: { 
                onLoad: function() { ibe.calendarClick($("#objWIN_FROM .ng_cal_date_<? print ng_date(isset($_DATA[0]['WIN_FROM'])&&$_DATA[0]['WIN_FROM']!="0000-00-00 00:00:00" ? $_DATA[0]['WIN_FROM'] : $_TODAY) ?>")); ibe.page.height(); },
                onDateClick: function(date) { 
                    ibe.callcenter.addDaysToCalendar('WIN_FROM', 'WIN_TO', 'RES_NIGHTS');
                }
            }
        });
        sBookTo = new ng.Calendar({
            input: 'WIN_TO',
            start_date: 'year - 5',
            display_date: '<? print (isset($_DATA[0]['WIN_TO'])&&$_DATA[0]['WIN_TO']!="0000-00-00 00:00:00") ? $_DATA[0]['WIN_TO'] : $_TODAY ?>',
            visible: true,
            object: "objWIN_TO",
            events: { 
                onLoad: function() { ibe.calendarClick($("#objWIN_TO .ng_cal_date_<? print ng_date(isset($_DATA[0]['WIN_TO'])&&$_DATA[0]['WIN_TO']!="0000-00-00 00:00:00" ? $_DATA[0]['WIN_TO'] : $_TODAY) ?>")); ibe.page.height(); }
            }
        });
    });
</script>


