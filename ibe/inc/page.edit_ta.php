<?
/*
 * Revised: Nov 02, 2011
 */

if (!is_array($_REQUEST)) $_REQUEST = array();

$ID = isset($_REQUEST['ID']) ? $_REQUEST['ID'] : "0";

$MODIFY = isset($_REQUEST['MODIFY']) ? $_REQUEST['MODIFY'] : "";
$SUBMIT = isset($_REQUEST['SUBMIT']) ? $_REQUEST['SUBMIT'] : "";

$showPWD = true;
?>

<div class='ListBtns'>
    <table>
    <tr>
        <td><h2>Travel Agent Edit</h2></td>
    </tr>
    </table>
</div>
<div class="aclear"></div>
<?
$RSET = $clsTA->getById($db, array("ID"=>$ID));
$ARRAY = array();
$TA = ($RSET['iCount']>0) ? $db->fetch_array($RSET['rSet']) : array();
?>
<form id="editfrm" method="post" enctype="multipart/form-data" action="?PAGE_CODE=edit_ta">
    <input type="hidden" name="SUBMIT" value="SUBMIT">
    <input type="hidden" name="MODIFY" value="MODIFY">
    <? if ((int)$ID!=0&&isset($TA['IS_CONFIRMED'])&&(int)$TA['IS_CONFIRMED']==1) { ?>
    <div style='text-align:center;margin-top:10px'>
        <a onclick="document.location='?PAGE_CODE=search_reserv&TA_ID=<? print $TA['ID'] ?>&SUBMIT=AUTO'"><span class="button key">View Reservations</span></a>
    </div>
    <? } ?>
    <? include "mods/m.reserv.forwhom.ta.form.php"; ?>
    <div style='text-align:center;margin-top:10px'>
        <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
        <? if (isset($TA['IS_CONFIRMED'])&&(int)$TA['IS_CONFIRMED']==0) { ?>
            <input type="hidden" id="IS_CONFIRMED" name="IS_CONFIRMED" value="0">
            <a onclick="$('#IS_CONFIRMED').val('1');$('#editfrm').submit()"><span class="button key">Submit & Approve</span></a>&nbsp;&nbsp;
            <a onclick="$('#IS_CONFIRMED').val('-1');$('#editfrm').submit()"><span class="button key">Deny</span></a>
        <? } ?>
    </div>
</form>
