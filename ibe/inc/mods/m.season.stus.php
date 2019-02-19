<?
/*
 * Revised: May 01, 2011
 */
?>
<fieldset>
    <div class="fieldset">
        <div class="label">
            <table>
            <tr>
                <td width="80%" nowrap>&nbsp;</td>
                <td width="10%" nowrap>Active&nbsp;<input type="checkbox" id="IS_ACTIVE" name="IS_ACTIVE" value="1" <? print (isset($_DATA['IS_ACTIVE'])&&(int)$_DATA['IS_ACTIVE']==1) ? "checked" : "" ?>>&nbsp;&nbsp;&nbsp;</td>
                <td width="10%" nowrap>Archive&nbsp;<input type="checkbox" id="IS_ARCHIVE" name="IS_ARCHIVE" value="1" <? print (isset($_DATA['IS_ARCHIVE'])&&(int)$_DATA['IS_ARCHIVE']==1) ? "checked" : "" ?>></td>
            </tr>
            </table>                    
        
        
        </div>
    </div>
</fieldset>