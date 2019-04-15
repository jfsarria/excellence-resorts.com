<fieldset>
    <legend>Stop Sale Name</legend>
    <div class="fieldset">
        <div class="field"><input type="text" id="NAME" name="NAME" value="<? if (isset($isCopy)&&$isCopy) print "Copy of " ?><? print isset($_DATA['NAME']) ? $_DATA['NAME'] : "" ?>" class="full<? if (isset($error['NAME'])) print " s_required" ?>"></div>
    </div>
    <div class="fieldset">
        <div class="label">
            <table>
            <tr>
                <td width="80%" nowrap>&nbsp;</td>
                <td width="10%" nowrap>
                    Active&nbsp;
                    <input
                        type="checkbox"
                        id="IS_ACTIVE"
                        name="IS_ACTIVE"
                        value="1"
                        <? print (isset($_DATA['IS_ACTIVE']) && (int)$_DATA['IS_ACTIVE'] == 1) ? "checked" : "" ?>>
                    &nbsp;&nbsp;&nbsp;
                </td>
                <td nowrap>
                    Archive&nbsp;
                    <span>
                        <input
                            type="checkbox"
                            id="IS_ARCHIVE"
                            name="IS_ARCHIVE"
                            value="1"
                            <? print (isset($_DATA['IS_ARCHIVE']) && (int)$_DATA['IS_ARCHIVE'] == 1) ? "checked" : "" ?>>
                    </span>
                </td>
            </tr>
            </table>                    
        </div>
    </div>
</fieldset>

<fieldset>
    <legend>Stop Sale Dates</legend>
    <div class="fieldset">
        <div class="label" style="margin-top:5px;margin-bottom:20px;text-align: center;">
            Stop Sale Belongs to the Year&nbsp;
            <select id="YEAR" name="YEAR">
            <?
            for ($t = 2016; $t <= date("Y") + 2; $t++) {
                $selected = (isset($_DATA['YEAR']) && (int)$_DATA['YEAR'] == $t) ? "selected":"";
                print "<option value='{$t}' $selected>{$t}</option>";
            }
            ?>
            </select>
        </div>
        <div class="label">
            <table align="center">
            <tr>
                <td><b>From</b>&nbsp;</td>
                <td align="right">
                    <input type="hidden" id="FROM" name="FROM" value="<? print isset($_DATA['FROM']) ? $_DATA['FROM'] : "" ?>" />
                </td>
                <td style="padding-left:100px"></td>
                <td><b>To</b>&nbsp;</td>
                <td align="right">
                    <input type="hidden" id="TO" name="TO" value="<? print isset($_DATA['TO']) ? $_DATA['TO'] : "" ?>" />
                </td>
            </tr>
            <tr>
                <td colspan="2"><div id="objFROM"></div></td>
                <td></td>
                <td colspan="2"><div id="objTO"></div></td>
            </tr>
            </table>
        </div>
    </div>
</fieldset>

<script type="text/javascript">
    /* http://nogray.com/calendar.php */
    var sFrom,
        sTo

    ng.ready(function(){
        sFrom = new ng.Calendar({
            input: 'FROM',
            start_date: 'year - 5',
            display_date: '<? print (isset($_DATA['FROM'])&&$_DATA['FROM']!="0000-00-00 00:00:00") ? $_DATA['FROM'] : $_TODAY ?>',
            visible: true,
            object: "objFROM",
            events: { 
                onLoad: function() {
                    ibe.calendarClick(
                        $("#objFROM .ng_cal_date_<? print ng_date(isset($_DATA['FROM'])&&$_DATA['FROM']!="0000-00-00 00:00:00" ? $_DATA['FROM'] : "") ?>")
                        );
                    ibe.page.height();
                }
            }
        });
        
        sTo = new ng.Calendar({
            input: 'TO',
            start_date: 'year - 5',
            display_date: '<? print (isset($_DATA['TO'])&&$_DATA['TO']!="0000-00-00 00:00:00") ? $_DATA['TO'] : $_TODAY ?>',
            visible: true,
            object: "objTO",
            events: { 
                onLoad: function() {
                    ibe.calendarClick(
                        $("#objTO .ng_cal_date_<? print ng_date(isset($_DATA['TO'])&&$_DATA['TO']!="0000-00-00 00:00:00" ? $_DATA['TO'] : "") ?>")
                    );
                    ibe.page.height();
                } 
            }
        });
    });
</script>

<style>
.country_group {
    margin-bottom:20px;
}
.country_group .gname {
    padding-bottom:10px;
}
</style>
<fieldset>
    <legend>Geo Targeting</legend>
    <div class="fieldset">
        <div class="label">
            <?php
            $TARGETS = array(
                "US" => "United States",
                "CA" => "Canada",
                "DO" => "Dominican Republic",
                "JM" => "Jamaica",
                "MX" => "Mexico",
                "GB" => "United Kingdom",
                "LA" => "Latin America",
                "EU" => "Europe",
                "--" => "Rest of the world",
            );
            ?>
            <table id="GeosPickList" class="pickList">
            <tr>
            <?php 
            $GEOS = isset($_DATA['GEOS']) ? $_DATA['GEOS'] : array();
            $cnt=0;
            foreach ($TARGETS as $CODE => $NAME) {
                if (in_array($CODE,$GEOS)===false && $check_all_rooms=="on") array_push($GEOS,$CODE);
                print "<td width='20%' nowrap class='pickListItem i{$cnt}'><input type='checkbox' value='{$CODE}' id='cb_{$CODE}' name='GEOS[]'".((in_array($CODE,$GEOS)===true)?"checked":"").">&nbsp;{$NAME}</td>";
                if (fmod(++$cnt,3)==0) print "</tr><tr>";
            }
            
            ?>
            </tr>
            </table>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend>Applicable to Rooms Type</legend>
    <div class="fieldset">
        <div class="label">
            <table width="100%" style='border-bottom:solid 1px #C0C0C0;margin-bottom:5px;'>
            <tr>
                <td><span><input id='check_all_rooms' name='check_all_rooms' type='checkbox' <? if ($check_all_rooms=="on") print "checked" ?>></span>&nbsp;Select all</div></td>
            </tr>
            </table><br style='clear:both'>
            
            <table class="pickList" width='100%' id='roomsTbl'>
            <tr>
            <?
            $ROOM_IDs = isset($_DATA['ROOM_IDs']) ? $_DATA['ROOM_IDs'] : array();

            $RSET = $clsRooms->getByProperty($db, array("PROP_ID"=>$PROP_ID));
            if ( $RSET['iCount'] != 0 ) {
                $cnt=0;
                while ($row = $db->fetch_array($RSET['rSet'])) {
                    $NAME = _d($row['NAME_'.$_IBE_LANG],$row['NAME_EN']);
                    if (in_array($row['ID'],$ROOM_IDs)===false && $check_all_rooms=="on") array_push($ROOM_IDs,$row['ID']);
                    print "<td width='50%' class='pickListItem i{$cnt}'><input type='checkbox' name='ROOM_IDs[]' value='{$row['ID']}' ".((in_array($row['ID'],$ROOM_IDs)===true)?"checked":"").">&nbsp;{$NAME}</td>";
                    if (fmod(++$cnt,2)==0) print "</tr><tr>";
                }
            }
            ?>
            </tr>
            </table>
        </div>
    </div>
</fieldset>

<script>
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