<?
/*
 * Revised: Jun 23, 2011
 */

$ROOM_ID = isset($_GET['ROOM_ID']) ? $_GET['ROOM_ID'] : "";
$RES_DATE = isset($_GET['RES_DATE']) ? $_GET['RES_DATE'] : "";
$CODE = isset($_GET['CODE']) ? $_GET['CODE'] : "";
$YEAR = isset($_GET['YEAR']) ? $_GET['YEAR'] : "";
$TOP = isset($_GET['TOP']) ? $_GET['TOP'] : "650";
$RES_DATE = substr($RES_DATE,0,4)."-".substr($RES_DATE,4,2)."-".substr($RES_DATE,6,2);

$ROOM_IDs = array($ROOM_ID);
$FROM = $RES_DATE;
$TO = $RES_DATE;

ob_start();
include "mods/m.inventory.get.data.php";
while ($row = $db->fetch_array($R_RSET['rSet'])) {
    $ROOM_ID = $row['ID'];
    $ROOM_NAME = $row['NAME_'.$_IBE_LANG];
    $MAX_ROOMS = (int)$row['MAX_ROOMS'];
}
if (!isset($INVENTORY[$ROOM_ID])) $INVENTORY[$ROOM_ID] = array();
$SOLD = (isset($INVENTORY[$ROOM_ID][$RES_DATE])) ? $INVENTORY[$ROOM_ID][$RES_DATE] : 0;

if (!isset($OVERRIDE[$ROOM_ID])) $OVERRIDE[$ROOM_ID] = array();
$ADD_ROOMS = (isset($OVERRIDE[$ROOM_ID][$RES_DATE])) ? $OVERRIDE[$ROOM_ID][$RES_DATE] : 0;

$STATUS = (isset($BLACKOUT[$ROOM_ID][$RES_DATE])) ? "close" : "open";

?>
<fieldset>
    <legend>Daily Inventory Allotment&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick='ibe.inventory.close()'><img src="css/img/cross.png" width="16" height="16" border="0" alt=""></a></legend>
    <div class="fieldset">
        <div id='top' style='display:none'><? print $TOP ?></div>
        <b><? print $ROOM_NAME; ?></b>
        <input type="hidden" name="UPDATE_ROOM_ID" value="<? print $ROOM_ID ?>">
        <input type="hidden" name="UPDATE_RES_DATE" value="<? print $RES_DATE ?>">
        <table style='margin-top:20px'>
        <tr>
            <td nowrap>Date:</td>
            <td nowrap><? print date("l, F j, Y",strtotime($RES_DATE)) ?></td>
        </tr>
        <tr>
            <td nowrap>Sold:</td>
            <td nowrap><? print $SOLD ?></td>
        </tr>
        <tr>
            <td nowrap>Rooms:</td>
            <td nowrap><? print $MAX_ROOMS ?></td>
        </tr>
        <tr>
            <td nowrap>Status:</td>
            <td nowrap>
                <input type="radio" name="UPDATE_STATUS" value='open' <? if ($STATUS=="open") print "checked" ?> >&nbsp;Open
                &nbsp;&nbsp;&nbsp;
                <input type="radio" name="UPDATE_STATUS" value='close' <? if ($STATUS=="close") print "checked" ?> >&nbsp;Close
            </td>
        </tr>
        <tr>
            <td nowrap>Add Rooms:</td>
            <td nowrap><input type="text" name="UPDATE_ROOMS" value="<? print $ADD_ROOMS ?>" style="width:70px"></td>
        </tr>
        </table>
        <div class='frmBtns'>
            <a onclick="$('#ACTION').val('SUBMIT');$('#editfrm').submit()"><span class="button key">Save</span></a>
        </div>
    </div>
</fieldset>
<?
$OUT = ob_get_clean();

print $OUT;

?>
