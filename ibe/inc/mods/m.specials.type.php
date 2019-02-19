<?
/*
 * Revised: May 04, 2011
 */
?>
<fieldset>
    <legend>Special Type</legend>
    <div class="fieldset">
        <table width="100%">
        <tr>
            <td width="10%" valign="top">
                <div>
                <select name="TYPE">
                <?
                $RSET = $clsSpecials->getTypes($db);
                while ($row = $db->fetch_array($RSET['rSet'])) {
                    $selected = (isset($_DATA['TYPE'])&&(int)$_DATA['TYPE']==(int)$row['ID']) ? "selected" : "";
                    print "<option value='{$row['ID']}' {$selected}>{$row['NAME']}</option>";
                }
                ?>
                </select>
                </div>
            </td>
            <td width="90%" style='padding-left:50px' valign="top">
                <div class="spcType<? print isset($_DATA['TYPE']) ? $_DATA['TYPE'] : "0" ?>">
                <?
                if (!isset($_DATA['TYPE']) || (isset($_DATA['TYPE']) && (int)$_DATA['TYPE']==0)) {
                    // Get X % Off
                    ?>
                        Get&nbsp;<input type="text" id="OFF" name="OFF" value="<? print isset($_DATA['OFF']) ? $_DATA['OFF'] : "" ?>" class="small">%&nbsp;off<br>
                        Deduction is applyed to the net rate, i.e. rate before markup
                    <?
                } else {
                    // Include here php for othet types
                }
                ?>
                </div>
            </td>
        </tr>
        </table>
    </div>
</fieldset>