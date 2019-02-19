<?
/*
 * Revised: Dec 15, 2011
 */

$_IS_ARCHIVE = isset($_REQUEST['_IS_ARCHIVE']) ? (int)$_REQUEST['_IS_ARCHIVE'] : 0;

$DELETE_ID = isset($_REQUEST['DELETE_ID']) ? (int)$_REQUEST['DELETE_ID'] : 0;
if ($DELETE_ID!=0) {
    $result = $clsTransfer->removeCar($db, array("DELETE_ID"=>$DELETE_ID));
    if ((int)$result==-1) {
        print "<p class='s_notice top_msg'>Car is in use, please make sure that no one uses this car before trying to delete it again.</p>";
    }
}

?>
<form id="lstFrm">
<input type="hidden" name="PAGE_CODE" value="transfer_cars">
<input type="hidden" name="PROP_ID" value="<? print $PROP_ID ?>">
<input type="hidden" name="DELETE_ID" id="DELETE_ID" value="0">
<input type="hidden" name="COPY_ID" id="COPY_ID" value="0">

<div class='ListBtns'>
    <table width="940">
    <tr>
        <td><h2>Transfers Cars</h2></td>
        <td><a href="?PAGE_CODE=transfer_cars&PROP_ID=<? print $PROP_ID ?>&CAR_ID=0"><span class="button key">Add New</span></a></td>
        <td><input type="checkbox" value="1" name="_IS_ARCHIVE" <? if ($_IS_ARCHIVE==1) print "checked" ?> onclick='$("#lstFrm").submit()'> Show Archived Cars</td>
    </tr>
    </table>
</div>
<div class="aclear"></div>

<?
$RSET = $clsTransfer->getCarsByProperty($db, array("PROP_ID"=>$PROP_ID,"WEHRE"=>"AND IS_ARCHIVE='{$_IS_ARCHIVE}'"));
if ( $RSET['iCount'] != 0 ) { ?>
    <div class="listTbl">
    <table>
    <tr class="listRowHdr">
        <td width="100%">Cars</td>
        <td>Max.PAX</td>
        <td>&nbsp;</td>
    </tr>
    <?
    $cnt=1;
    while ($row = $db->fetch_array($RSET['rSet'])) {
        $NAME = _d($row['NAME_'.$_IBE_LANG],$row['NAME_EN']);
        ?>
        <tr class='listRow<? print $cnt ?>'>
            <td><a href="?PAGE_CODE=transfer_cars&PROP_ID=<? print $row['PROP_ID'] ?>&CAR_ID=<? print $row['ID'] ?>"><? print $NAME ?></a></td>
            <td align='right'><? print $row['MAX_PAX'] ?></td>
            <td><img src="css/img/remove_icon.gif" width="10" height="10" border="0" style="cursor:pointer" onclick="$('#DELETE_ID').val(<? print $row['ID'] ?>);$('#lstFrm').submit()"></td>
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