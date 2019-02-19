<?
/*
 * Revised: Jun 23, 2011
 */

?>
<fieldset>
    <div class="fieldset">
        <div class="label">
            <table>
            <tr>
                <td>Send email to </td>
                <td><input type="text" name="INVENTORY_EMAIL" value="<? print (isset($_DATA['INVENTORY_EMAIL'])) ? $_DATA['INVENTORY_EMAIL'] : $_SESSION['AUTHENTICATION']['SETUP']['INVENTORY_EMAIL'] ?>" style='width:400px'></td>
                <td>When any room allotment falls below</td>
                <td><input type="text" name="INVENTORY_MIN" value="<? print (isset($_DATA['INVENTORY_MIN'])) ? $_DATA['INVENTORY_MIN'] : $_SESSION['AUTHENTICATION']['SETUP']['INVENTORY_MIN'] ?>" class='small'></td>
            </tr>
            </table>
        </div>
    </div>
</fieldset>

<div id='inventoryEditBox'></div>