<?
/*
 * Revised: Dec 01, 2011
 */

$MAX_AGE = $clsChildrate->getMaxAge($db, array("noAdults"=>true));

ob_start();
    for ($a=1; $a <= 5; ++$a) print "<option value='{$a}' ".($a==2?"selected":"").">{$a}</option>";
$ROOM_ADULTS_NUM = ob_get_clean();

ob_start();
    for ($a=0; $a <= 5; ++$a) print "<option value='{$a}'>{$a}</option>";
$ROOM_CHILDREN_NUM = ob_get_clean();

ob_start();
    for ($a=0; $a <= $MAX_AGE; ++$a) print "<option value='{$a}'>{$a}</option>";
$ROOM_CHILDREN_AGE = ob_get_clean();

ob_start();
    print "
        <div id='room_box_%room_num%' class='room_box_tpl' style='float:left'>
            Adults in room %room_num%:
            <select id='RES_ROOM_%room_num%_ADULTS_QTY' name='RES_ROOM_%room_num%_ADULTS_QTY' class='room_box_select' rel='0'>
                {$ROOM_ADULTS_NUM}
            </select>
        </div>
    ";
$ROOM_BOX_TPL[0] = ob_get_clean();

ob_start();
    print "
        <div id='room_box_%room_num%' class='room_box_tpl'>
            <div style='float:left'>
                Adults in room %room_num%:
                <select id='RES_ROOM_%room_num%_ADULTS_QTY' name='RES_ROOM_%room_num%_ADULTS_QTY' class='room_box_select' rel='0'>
                    {$ROOM_ADULTS_NUM}
                </select>
            </div>
            <div style='float:left;padding-left:10px'>
                Children in room %room_num%
                <select id='RES_ROOM_%room_num%_CHILDREN_QTY' name='RES_ROOM_%room_num%_CHILDREN_QTY' class='room_box_select children_in_room' rel='0' room_num='%room_num%'>
                    {$ROOM_CHILDREN_NUM}
                </select>
            </div>
            <div id='RES_ROOM_%room_num%_CHILDREN_AGES' style='float:left;padding-left:10px'>";
                for ($c=1; $c <= 5; ++$c) {
                    print "
                        <div id='RES_ROOM_%room_num%_CHILDREN_AGES_{$c}' class='RES_ROOM_%room_num%_CHILDREN_AGES' style='padding-bottom:3px'>
                            Child {$c} age 
                            <select id='RES_ROOM_%room_num%_CHILD_AGE_{$c}' name='RES_ROOM_%room_num%_CHILD_AGE_{$c}' class='room_box_select room_age_select' rel='0'>
                                {$ROOM_CHILDREN_AGE}
                            </select>
                        </div>
                    ";
                }
                print "
            </div>
            <div style='clear:both'></div>
        </div>
    ";
$ROOM_BOX_TPL[4] = ob_get_clean();
?>

<? if ($_DATA['RES_LANGUAGE']!="") { ?>
<fieldset class='reserv_fieldset'>
    <legend>Rooms</legend>
    <div class="fieldset">
        <table width="100%">
        <tr>
            <td nowrap valign="top" style="padding-right:10px">
                Rooms <select id='RES_ROOMS_QTY' name='RES_ROOMS_QTY' onchange='ibe.callcenter.showRoomBoxes(this.value)'><? for ($t=1; $t <= 20; ++$t) print "<option value='{$t}' ".(($_DATA['RES_ROOMS_QTY']==$t)?"selected":"").">{$t}</option>"; ?></select>
            </td>
            <td width="100%" valign="top">
                <div id="ROOM_BOX_TPL_0"><? print $ROOM_BOX_TPL[0]; ?></div>
                <div id="ROOM_BOX_TPL_4"><? print $ROOM_BOX_TPL[4]; ?></div>
                <div id="ROOM_BOXES"></div>
                <div style='clear:both'></div>
                </div>
            </td>
        </tr>
        </table>
    </div>
    <script>
        var oRoom = {};
        <? foreach ($_DATA as $key => $val) if ( (strpos($key,"RES_ROOM")!==false && strpos($key,"%")===false) || $key=='RES_PROP_ID') print "oRoom.{$key} = '{$val}';\n"; ?>
        ibe.callcenter.showRoomBoxes();
    </script>
    <script>
        var isPropeSelected = false,
            oPropIDs = $("input[name='RES_PROP_ID']");
        oPropIDs.each(function() {
            if ($(this)[0].checked) isPropeSelected = true;
        });
        if (!isPropeSelected) {
            oPropIDs.first().click();
            oPropIDs.first()[0].checked = true;
        }
    </script>
</fieldset>
<? } ?>
