<?
/*
 * Revised: Aug 22, 2011
 */

?>
<fieldset>
    <legend>Applicable to Rooms Type</legend>
    <div class="fieldset">
        <div class="label">
            <table width="100%" style='border-bottom:solid 1px #C0C0C0;margin-bottom:5px;'>
            <tr>
                <td><span><input id='check_all_rooms' name='check_all_rooms' type='checkbox' <? if ($check_all_rooms=="on") print "checked" ?>></span>&nbsp;Select all</div></td>
                <td><div id="objFROM"><span>Date:&nbsp;</span><span><input type="text" id="FROM" name="FROM" value="<? if (isset($_DATA['FROM'])) print $_DATA['FROM'] ?>" /></span></div></td>
            </tr>
            </table><br style='clear:both'>
            
            <table class="pickList" width='100%' id='roomsTbl'>
            <tr>
            <?
            $RSET = $clsRooms->getByProperty($db, array("PROP_ID"=>$PROP_ID));
            if ( $RSET['iCount'] != 0 ) {
                $cnt=0;
                while ($row = $db->fetch_array($RSET['rSet'])) {
                    $NAME = _d($row['NAME_'.$_IBE_LANG],$row['NAME_EN']);
                    if (in_array($row['ID'],$ROOM_IDs)===false && $check_all_rooms=="on") array_push($ROOM_IDs,$row['ID']);
                    print "<td width='50%' class='pickListItem i{$cnt}'><span><input type='checkbox' name='ROOM_IDs[]' value='{$row['ID']}' ".((in_array($row['ID'],$ROOM_IDs)===true)?"checked":"")."></span>&nbsp;{$NAME}</td>";
                    if (fmod(++$cnt,2)==0) print "</tr><tr>";
                }
            }
            ?>
            </tr>
            </table>
        </div>
    </div>
    <script>
        var sFrom,
            dFrom = '<? print (isset($_DATA['FROM'])&&$_DATA['FROM']!="0000-00-00 00:00:00") ? $_DATA['FROM'] : $_TODAY ?>';

        ng.ready(function(){
            sFrom = new ng.Calendar({
                input: 'FROM',
                start_date: '<? print $_TODAY ?>',
                display_date: dFrom,
                server_date_format:'Y-m-d',
                events: { 
                    onLoad: function() { 
                        ibe.calendarClick($(".ng_cal_date_<? print ng_date(isset($_DATA['FROM'])&&$_DATA['FROM']!="0000-00-00 00:00:00" ? $_DATA['FROM'] : "") ?>"));
                        $("#objFROM table").css("padding-top","15px");
                    } 
                }
            });
        });

        $("#check_all_rooms").click(function() {
            var p = $(this);
            $("#roomsTbl input[type='checkbox']").each(function() { 
                $(this)[0].checked = p[0].checked;
            });
        });
        $("#roomsTbl input[type='checkbox']").each(function() { 
            $(this).click(function() {
                if (!$(this)[0].checked) $("#check_all_rooms")[0].checked = false;
            });
        });
    </script>
</fieldset>

<div id='inventoryEditBox'></div>