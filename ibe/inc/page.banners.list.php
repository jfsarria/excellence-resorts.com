<?
/*
 * Revised: Jun 08, 2015
 */

$_IS_ARCHIVE = isset($_REQUEST['_IS_ARCHIVE']) ? (int)$_REQUEST['_IS_ARCHIVE'] : 0;

$DELETE_ID = isset($_REQUEST['DELETE_ID']) ? (int)$_REQUEST['DELETE_ID'] : 0;
if ($DELETE_ID!=0) {
    $result = $clsBanners->remove($db, array("DELETE_ID"=>$DELETE_ID));
    if ((int)$result==-1) {
        //print "<p class='s_notice top_msg'>Banner is in use, please make sure that any class uses this banner before trying to delete it again.</p>";
    }
}
/*
$COPY_ID = isset($_REQUEST['COPY_ID']) ? (int)$_REQUEST['COPY_ID'] : 0;
if ($COPY_ID!=0) {
    $result = $clsBanners->duplicate($db, array("COPY_ID"=>$COPY_ID));
    if ((int)$result==1) {
        print "<p class='s_notice top_msg'>Banner has been duplicated.</p>";
    } else {
        print "<p class='s_notice top_msg'>Banner could not be duplicated.</p>";
    }
}
*/
?>
<form id="lstFrm">
<input type="hidden" name="PAGE_CODE" value="banners">
<input type="hidden" name="PROP_ID" value="<? print $PROP_ID ?>">
<input type="hidden" name="DELETE_ID" id="DELETE_ID" value="0">
<input type="hidden" name="COPY_ID" id="COPY_ID" value="0">

<div class='ListBtns'>
    <table width="940">
    <tr>
        <td><h2>Banner</h2></td>
        <td><a href="?PAGE_CODE=banners&PROP_ID=<? print $PROP_ID ?>&BANNER_ID=0"><span class="button key">Add New</span></a></td>
    </tr>
    </table>
</div>
<div class="aclear"></div>
<?
$RSET = $clsBanners->getByProperty($db, array("PROP_ID"=>$PROP_ID));
if ( $RSET['iCount'] != 0 ) { ?>
    <div class="listTbl">
    <table>
    <tr class="listRowHdr">
        <td width="100%">Banner Name</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <!-- <td>&nbsp;</td> -->
    </tr>
    <?
    $cnt=1;
    while ($row = $db->fetch_array($RSET['rSet'])) {
        $NAME = _d($row['NAME_'.$_IBE_LANG],$row['NAME_EN']);
        $IS_ACTIVE = (int)$row['IS_ACTIVE']==1 ? true : false;
        ?>
        <tr class='listRow<? print $cnt ?>'>
            <td><a href="?PAGE_CODE=banners&PROP_ID=<? print $row['PROP_ID'] ?>&BANNER_ID=<? print $row['ID'] ?>"><? print $NAME ?></a></td>
            <td><? print $IS_ACTIVE ? 'ACTIVE' : "&nbsp;" ?></td>
            <td><img src="css/img/remove_icon.gif" width="10" height="10" border="0" style="cursor:pointer" onclick="$('#DELETE_ID').val(<? print $row['ID'] ?>);$('#lstFrm').submit()"></td>
            <!-- <td><img src="css/img/copy_icon.gif" width="10" height="10" border="0" style="cursor:pointer" onclick="$('#COPY_ID').val(<? print $row['ID'] ?>);$('#lstFrm').submit()"></td> -->
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