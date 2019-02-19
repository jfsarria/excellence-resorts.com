<?
/*
 * Revised: Oct 08, 2015
 */

$_YEAR = isset($_REQUEST['_YEAR']) ? (int)$_REQUEST['_YEAR'] : date("Y");

$DELETE_ID = isset($_REQUEST['DELETE_ID']) ? (int)$_REQUEST['DELETE_ID'] : 0;
if ($DELETE_ID!=0) {
    $result = $clsTransferCarSeason->remove($db, array("DELETE_ID"=>$DELETE_ID));
    if ((int)$result==-1) {
        print "<p class='s_notice top_msg'>Season is in use, please make sure that any class uses this season before trying to delete it again.</p>";
    }
}
?>
<form id="lstFrm">
<input type="hidden" name="PAGE_CODE" value="transfer_car_season">
<input type="hidden" name="PROP_ID" value="<? print $PROP_ID ?>">
<input type="hidden" name="DELETE_ID" id="DELETE_ID" value="0">

<div class='ListBtns'>
    <table width="800">
    <tr>
        <td><h2>Transfer Seasonal Prices</h2></td>
        <td><a href="javascript:void(0)" onclick="document.location.href='?PAGE_CODE=transfer_car_season&PROP_ID=<? print $PROP_ID ?>&ID=0&YEAR='+jQuery('#_YEAR').val()"><span class="button key">Add New</span></a></td>
        <td>Year:
            <select id="_YEAR" name="_YEAR" onchange='$("#lstFrm").submit()'>
                <? for ($YY=2015;$YY<=date("Y")+5;++$YY) print "<option value='$YY' ".($_YEAR==$YY?"selected":"").">$YY</option>" ?>
            </select>
        </td>
    </tr>
    </table>
</div>
<div class="aclear"></div>

<?
$RSET = $clsTransferCarSeason->getByProperty($db, array("PROP_ID"=>$PROP_ID,"YEAR"=>$_YEAR));
if ( $RSET['iCount'] != 0 ) { ?>
    <div class="listTbl">
    <table>
    <tr class="listRowHdr">
        <td width="100%">Season Name</td>
        <td width="100%">Car Name</td>
        <td>Year</td>
        <td colspan="2">Season Dates</td>
        <td>One Way</td>
        <td>Round Ttrip</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <?
    $cnt=1;
    while ($row = $db->fetch_array($RSET['rSet'])) {
        $SEASON_NAME = $row['NAME'];
        $CAR_NAME = $row['NAME_EN'];
        $FROM = ($row['FROM'] != "0000-00-00 00:00:00") ? shortDate($row['FROM']) : "";
        $TO = ($row['TO'] != "0000-00-00 00:00:00") ? shortDate($row['TO']) : "";
        $EDIT = "?PAGE_CODE=transfer_car_season&PROP_ID={$row['PROP_ID']}&YEAR={$_YEAR}&ID={$row['ID']}";
        $PRICE_ONEWAY = $row['PRICE_ONEWAY'];
        $PRICE_ROUNDT = $row['PRICE_ROUNDT'];
        ?>
        <tr class='listRow<? print $cnt ?>'>
            <td><a href="<? print $EDIT ?>"><? print $SEASON_NAME ?></a></td>
            <td nowrap><? print $CAR_NAME ?></td>
            <td nowrap><? print $row['YEAR'] ?></td>
            <td nowrap><? print $FROM ?></td>
            <td nowrap><? print $TO ?></td>
            <td nowrap>$<? print $PRICE_ONEWAY ?></td>
            <td nowrap>$<? print $PRICE_ROUNDT ?></td>
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