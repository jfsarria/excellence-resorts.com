<?
/*
 * Revised: Aug 10, 2011
 */

if (!is_array($_REQUEST)) $_REQUEST = array();

$ID = isset($_REQUEST['ID']) ? $_REQUEST['ID'] : "0";

$MODIFY = isset($_REQUEST['MODIFY']) ? $_REQUEST['MODIFY'] : "";
$SUBMIT = isset($_REQUEST['SUBMIT']) ? $_REQUEST['SUBMIT'] : "";

$THIS_PAGE = "?PAGE_CODE=edit_ccagent";
$showPWD = true;
?>

<div class='ListBtns'>
    <table>
    <tr>
        <td><h2>Call Center Agent Edit</h2></td>
    </tr>
    </table>
</div>
<div class="aclear"></div>
<?
$RSET = $clsUsers->getById($db, array("ID"=>$ID));
if ($RSET['iCount']>0) $CCAGENT = $db->fetch_array($RSET['rSet']);
?>
<form id="editfrm" method="post" enctype="multipart/form-data" action="<? print $THIS_PAGE ?>">
    <input type="hidden" name="SUBMIT" value="SUBMIT">
    <input type="hidden" name="MODIFY" value="MODIFY">
    <? include "mods/m.edit_ccagent.php"; ?>
    <div style='text-align:center;margin-top:10px'>
        <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
    </div>
</form>

