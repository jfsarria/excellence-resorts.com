<?
/*
 * Revised: Oct 17, 2012
 *          Mar 08, 2018
 */
?>

<div style="text-align:center;padding-bottom:10px">
    <span style='font-size:16px;'>Today's date: <? print date("l, F j, Y", strtotime($_TODAY)) ?></span>
</div>

<fieldset>
    <?
    if ($_DATA['RES_REBOOKING']['RES_NUM']!="") {
        print "
            <legend style='color:black'>
                Rebooking reservation # <b>{$_DATA['RES_REBOOKING']['RES_NUM']}</b>
            </legend>
        ";
    }
    ?>
    <div class="fieldset">
        <div class="label">
            <table width='750px'>
            <tr>
                <td width='33%'><span class="button" onClick='ibe.callcenter.reserv.startNew("EN")'>Start <? print ($_DATA['RES_REBOOKING']['RES_NUM']=="") ? "New Reservation" : "Rebooking" ?></span><br></td>
                <td width='33%'><span class="button" onClick='ibe.callcenter.reserv.startNew("SP")'><? print ($_DATA['RES_REBOOKING']['RES_NUM']=="") ? "New Reservation" : "Rebooking" ?> in Spanish</span><br></td>
                <? if (!$isSPECIAL) { ?>
                <td width='33%' align='right'><span class="button" onClick='ibe.callcenter.reserv.checkFuture()'>Check Future Date</span><br></td>
                <? } ?>
            </tr>
            </table>
            <div style="display:none">
                <input type="text" id="RES_SRC" name="RES_SRC" value="CC" />
                <input type="text" id="RES_LANGUAGE" name="RES_LANGUAGE" value="<? print $_DATA['RES_LANGUAGE'] ?>" />
                <input type="text" id="RES_IN_THE_FUTURE" name="RES_IN_THE_FUTURE" value="<? print $_DATA['RES_IN_THE_FUTURE'] ?>" />
                <input type="text" id="RES_DATE" name="RES_DATE" value="<? print $_DATA['RES_DATE'] ?>" />
                <?
                    foreach ($_DATA['RES_REBOOKING'] AS $KEY => $VALUE) {
                        print "<input type='text' name='REBOOK_{$KEY}' VALUE='{$VALUE}'>\n";
                    }
                ?>
            </div>
        </div>
    </div>
</fieldset>
