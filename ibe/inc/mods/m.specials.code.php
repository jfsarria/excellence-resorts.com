<?
/*
 * Revised: May 05, 2011
 */
?>
<fieldset>
    <div class="fieldset">
        <div class="label">
            <table width="100%">
            <tr>
                <td nowrap align="left">Private Special&nbsp;<span><input type="checkbox" id="IS_PRIVATE" name="IS_PRIVATE" value="1" <? print (isset($_DATA['IS_PRIVATE'])&&(int)$_DATA['IS_PRIVATE']==1) ? "checked" : "" ?>></span>&nbsp;&nbsp;&nbsp;</td>
                <td nowrap align="right">Access Code for this special&nbsp;<input type="text" id="ACCESS_CODE" name="ACCESS_CODE" value="<? print isset($_DATA['ACCESS_CODE']) ? $_DATA['ACCESS_CODE'] : "" ?>" style="width:100px"></td>
            </tr>
            </table>                    
        </div>
    </div>
</fieldset>