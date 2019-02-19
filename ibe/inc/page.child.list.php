<?
/*
 * Revised: May 08, 2011
 */
?>
<div class='ListBtns'>
    <table>
    <tr>
        <td><h2>Children Rates</h2></td>
        <td><a href="?PAGE_CODE=child&PROP_ID=<? print $PROP_ID ?>&CHILDRATE_ID=0"><span class="button key">Add New</span></a></td>
    </tr>
    </table>
</div>
<div class="aclear"></div>

<?
$RSET = $clsChildrate->getByProperty($db, array("PROP_ID"=>$PROP_ID));
if ( $RSET['iCount'] != 0 ) { ?>
    <div class="listTbl">
    <table>
    <tr class="listRowHdr">
        <td width="100%">Child Rate Name</td>
        <td colspan="2" nowrap>From - To <br> Years Old</td>
        <td>Counted</td>
        <td>Percentage</td>
    </tr>
    <?
    $cnt=1;
    while ($row = $db->fetch_array($RSET['rSet'])) {
        $NAME = $row['NAME'];
        $COUNTED = (int)$row['COUNTED']==1 ? "Yes" : "";
        ?>
        <tr class='listRow<? print $cnt ?>'>
            <td><a href="?PAGE_CODE=child&PROP_ID=<? print $row['PROP_ID'] ?>&CHILDRATE_ID=<? print $row['ID'] ?>"><? print $NAME ?></a></td>
            <td nowrap align="center"><? print $row['FROM'] ?></td>
            <td nowrap align="center"><? print $row['TO'] ?></td>
            <td nowrap align="center"><? print $COUNTED ?></td>
            <td nowrap align="right"><? print $row['PERCENTAGE'] ?>%</td>
        </tr>
        <?
        $cnt*=-1;
    }
    ?>
    </table>
    </div>
<? } else { ?>

<? } ?>