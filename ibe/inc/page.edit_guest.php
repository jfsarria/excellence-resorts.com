<?
/*
 * Revised: Dec 15, 2011
 */

if (!is_array($_REQUEST)) $_REQUEST = array();

$ID = isset($_REQUEST['ID']) ? $_REQUEST['ID'] : "0";

$MODIFY = isset($_REQUEST['MODIFY']) ? $_REQUEST['MODIFY'] : "";
$SUBMIT = isset($_REQUEST['SUBMIT']) ? $_REQUEST['SUBMIT'] : "";

$THIS_PAGE = "?PAGE_CODE=edit_guest&ID={$ID}";
$showPWD = true;
?>

<div class='ListBtns'>
    <table>
    <tr>
        <td><h2>Guest Edit</h2></td>
    </tr>
    </table>
</div>
<div class="aclear"></div>
<?
$RSET = $clsGuest->getById($db, array("ID"=>$ID));
$ARRAY = array();
if ($RSET['iCount']>0) {
    $GUEST = $db->fetch_array($RSET['rSet']);
    $MODIFY = "MODIFY";
    $TA_ID = (isset($_REQUEST['TA_ID'])) ? (int)$_REQUEST['TA_ID'] : 0;
    ?>
    <form id="editfrm" method="post" enctype="multipart/form-data" action="<? print $THIS_PAGE ?>">
        <input type="hidden" name="SUBMIT" value="SUBMIT">
        <input type="hidden" name="MODIFY" value="MODIFY">
        <div style='text-align:center;margin-top:10px'>
            <a onclick="document.location='?PAGE_CODE=search_reserv&GUEST_ID=<? print $GUEST['ID'] ?>&SUBMIT=AUTO'"><span class="button key">View Reservations</span></a>
            <? if ($TA_ID!=0) { ?>                
                &nbsp;&nbsp;&nbsp;&nbsp;
                <a onclick="document.location='?PAGE_CODE=edit_ta&ID=<? print $TA_ID ?>'"><span class="button key">View Travel Agent</span></a>
            <? } ?>
        </div>
        <? include "mods/m.edit_reserv.guest.php"; ?>
        <div style='text-align:center;margin-top:10px'>
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
        </div>
    </form>
    <?
} 

?>
