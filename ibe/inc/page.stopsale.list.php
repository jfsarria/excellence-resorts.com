<?
/*
 * Revised: Jun 07, 2016
 */

$_YEAR = isset($_REQUEST['_YEAR']) ? (int)$_REQUEST['_YEAR'] : date("Y");
$_IS_ARCHIVE = isset($_REQUEST['_IS_ARCHIVE']) ? (int)$_REQUEST['_IS_ARCHIVE'] : 0;

$DELETE_ID = isset($_REQUEST['DELETE_ID']) ? (int)$_REQUEST['DELETE_ID'] : 0;
if ($DELETE_ID!=0) {
    $result = $clsRooms->removeStopSale($db, array("DELETE_ID"=>$DELETE_ID));
}
?>
<form id="lstFrm">
<input type="hidden" name="PAGE_CODE" value="stopsale">
<input type="hidden" name="PROP_ID" value="<? print $PROP_ID ?>">
<input type="hidden" name="DELETE_ID" id="DELETE_ID" value="0">

<div class='ListBtns'>
    <table width="900">
    <tr>
        <td><h2>Stop Sale</h2></td>
        <td><a href="?PAGE_CODE=stopsale&PROP_ID=<? print $PROP_ID ?>&ID=0"><span class="button key">Add New</span></a></td>
        <td>Year:
            <select name="_YEAR" onchange='$("#lstFrm").submit()'>
                <? for ($YY=2016;$YY<=date("Y")+5;++$YY) print "<option value='$YY' ".($_YEAR==$YY?"selected":"").">$YY</option>" ?>
            </select>
        </td>
        <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" value="1" name="_IS_ARCHIVE" <? if ($_IS_ARCHIVE==1) print "checked" ?> onclick='$("#lstFrm").submit()'> Show Archived Specials</td>
    </tr>
    </table>
</div>
<div class="aclear"></div>

<?
$RSET = $clsRooms->getStopSaleByProperty($db, array("PROP_ID"=>$PROP_ID,"YEAR"=>$_YEAR,"_IS_ARCHIVE"=>$_IS_ARCHIVE));
if ( $RSET['iCount'] != 0 ) { ?>
    <div class="listTbl">
    <table>
    <tr class="listRowHdr">
        <td width="100%">Reference Name</td>
        <td>Year</td>
        <td colspan="2">Stop Sale Dates</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <?
    $cnt=1;
    while ($row = $db->fetch_array($RSET['rSet'])) {
        $NAME = $row['NAME'];
        $FROM = ($row['FROM'] != "0000-00-00 00:00:00") ? shortDate($row['FROM']) : "";
        $TO = ($row['TO'] != "0000-00-00 00:00:00") ? shortDate($row['TO']) : "";
        $EDIT = "?PAGE_CODE=stopsale&PROP_ID={$row['PROP_ID']}&ID={$row['ID']}&_IS_ARCHIVE={$_IS_ARCHIVE}";
        ?>
        <tr class='listRow<? print $cnt ?>'>
            <td><a href="<? print $EDIT ?>"><? print $NAME ?></a></td>
            <td nowrap><? print $row['YEAR'] ?></td>
            <td nowrap><? print $FROM ?></td>
            <td nowrap><? print $TO ?></td>
            <td><img src="css/img/remove_icon.gif" width="10" height="10" border="0" style="cursor:pointer" onclick="$('#DELETE_ID').val(<? print $row['ID'] ?>);$('#lstFrm').submit()"></td>
            <td><a href="<? print $EDIT."&IS_COPY=yes" ?>"><img title="Duplicate" src="css/img/copy_icon.gif" width="16" height="16" border="0"></a></td>
        </tr>
        <?
        $cnt*=-1;
    }
    ?>
    </table>
    </div>
<? } else { ?>

<? } ?>
</form>