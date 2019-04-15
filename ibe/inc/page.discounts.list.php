<?
/*
 * Revised: Dec 15, 2011
 */
//print "<pre>";print_r($_REQUEST);print "</pre>";
$_IS_ARCHIVE = isset($_REQUEST['_IS_ARCHIVE']) ? (int)$_REQUEST['_IS_ARCHIVE'] : 0;

$DELETE_ID = isset($_REQUEST['DELETE_ID']) ? (int)$_REQUEST['DELETE_ID'] : 0;
if ($DELETE_ID!=0) {
    $result = $clsDiscounts->remove($db, array("DELETE_ID"=>$DELETE_ID),true);
    if ((int)$result==-1) {
        print "<p class='s_notice top_msg'>Room is in use, please make sure that any class uses this room before trying to delete it again.</p>";
    }
}
/*
$COPY_ID = isset($_REQUEST['COPY_ID']) ? (int)$_REQUEST['COPY_ID'] : 0;
if ($COPY_ID!=0) {
    $result = $clsRooms->duplicate($db, array("COPY_ID"=>$COPY_ID));
    if ((int)$result==1) {
        print "<p class='s_notice top_msg'>Room has been duplicated.</p>";
    } else {
        print "<p class='s_notice top_msg'>Room could not be duplicated.</p>";
    }
}
*/
?>
<form id="lstFrm">
<input type="hidden" name="PAGE_CODE" value="discounts">
<input type="hidden" name="PROP_ID" value="<? print $PROP_ID ?>">
<input type="hidden" name="DELETE_ID" id="DELETE_ID" value="0">
<input type="hidden" name="COPY_ID" id="COPY_ID" value="0">

<div class='ListBtns'>
    <table width="940">
    <tr>
        <td><h2>Discounts</h2></td>
        <td><a href="?PAGE_CODE=discounts&PROP_ID=<? print $PROP_ID ?>&ID_CAB=0"><span class="button key">Add New</span></a></td>
        <td><input type="checkbox" value="1" name="_IS_ARCHIVE" <? if ($_IS_ARCHIVE==1) print "checked" ?> onclick='$("#lstFrm").submit()'> Show Archived Discounts</td>
    </tr>
    </table>
</div>
<div class="aclear"></div>

<?
$RSET = $clsDiscounts->getByProperty($db, array("PROP_ID"=>$PROP_ID,"WEHRE"=>"AND (SYSTEM LIKE 'D_%' or SYSTEM LIKE 'BO_%') AND SYSTEM<>'T_CUPON' AND IS_ARCHIVE='{$_IS_ARCHIVE}'"));
if ( $RSET['iCount'] != 0 ) { ?>
    <div class="listTbl">
    <table>
    <tr class="listRowHdr">
        <td width="45%">Discounts Name</td>
        <td>Type</td>
        <td>Priority</td>
        <td>Environment</td>
        <td>Access type</td>
        <td>From &nbsp;</td>
        <td>To &nbsp;</td>
        <td>&nbsp;</td>
        <!-- <td>&nbsp;</td> -->
    </tr>
    <?
    $cnt=1;
    while ($row = $db->fetch_array($RSET['rSet'])) {
        $NAME = _d($row['NAME_'.$_IBE_LANG],$row['NAME_EN']);
        //$VIP = ((int)$row['IS_VIP']==1) ? "VIP" : "";
        ?>
        <tr class='listRow<? print $cnt ?>'>
            <td><a href="?PAGE_CODE=discounts&PROP_ID=<? print $row['PROP_ID'] ?>&ID_CAB=<? print $row['ID_CAB'] ?>"><? print $NAME ?></a></td>
            <td align='right'><? print $row['SYSTEM'] ?></td>
            <td align='right'><? print $row['PRIORITY'] ?></td>
            <td align='right'><? print $row['ENVIRONMENT'] ?></td>
            <td align='right'><? print $row['CODE'] ?></td>
            <td align='right'><? 
             $fecha= new DateTime($row['CAB_FROM']);
             $form_fecha= $fecha->format('d-m-Y');  
            print $form_fecha; ?></td>

            <td align='right'><?
             $fecha= new DateTime($row['CAB_TO']);
             $form_fecha= $fecha->format('d-m-Y');  
            print $form_fecha;  ?></td>

            <td><img src="css/img/remove_icon.gif" width="10" height="10" border="0" style="cursor:pointer" onclick="$('#DELETE_ID').val(<? print $row['ID_CAB'] ?>);$('#lstFrm').submit()"></td>
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