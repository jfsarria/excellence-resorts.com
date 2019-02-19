<?
/*
 * Revised: Apr 25, 2011
 */
?>
<fieldset>
    <div class="fieldset">
        <div class="label">
            <table>
            <tr>
                <td width="10%" nowrap>Room type is VIP (Excellence Club)&nbsp;<span><input type="checkbox" id="IS_VIP" name="IS_VIP" value="1" <? print (isset($_DATA['IS_VIP'])&&(int)$_DATA['IS_VIP']==1) ? "checked" : "" ?>></span></td>
                <td width="70%" nowrap>Display Order&nbsp;<span><input type="text" id="ORDER" name="ORDER" value="<? print isset($_DATA['ORDER']) ? $_DATA['ORDER'] : "" ?>" class="small"></span></td>
                <td width="10%" nowrap>Active&nbsp;<span><input type="checkbox" id="IS_ACTIVE" name="IS_ACTIVE" value="1" <? print (isset($_DATA['IS_ACTIVE'])&&(int)$_DATA['IS_ACTIVE']==1) ? "checked" : "" ?>></span>&nbsp;&nbsp;&nbsp;</td>
                <td width="10%" nowrap>Archive&nbsp;<span><input type="checkbox" id="IS_ARCHIVE" name="IS_ARCHIVE" value="1" <? print (isset($_DATA['IS_ARCHIVE'])&&(int)$_DATA['IS_ARCHIVE']==1) ? "checked" : "" ?>></span></td>
            </tr>
            </table>                    
        </div>
    </div>
</fieldset>