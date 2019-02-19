<?
/*
 * Revised: Jul 22, 2016
 */
?>
<div class='ListBtns'>
    <table>
    <tr>
        <td><h2>Markups</h2></td>
        <td><!-- <a href="?PAGE_CODE=markup&PROP_ID=<? print $PROP_ID ?>&MARKUP_ID=0"><span class="button key">Add New</span></a> --></td>
    </tr>
    </table>
</div>
<div class="aclear"></div>

<?
$RSET = $clsMarkups->getByProperty($db, array("PROP_ID"=>$PROP_ID));
if ( $RSET['iCount'] != 0 ) { ?>
    <div class="listTbl">
    <table>
    <tr class="listRowHdr">
        <td width="100%">Year</td>
        <td>Global Markup</td>
    </tr>
    <?
    $cnt=1;
    while ($row = $db->fetch_array($RSET['rSet'])) {
        $NAME = $row['YEAR'];
        ?>
        <tr class='listRow<? print $cnt ?>'>
            <td><a href="?PAGE_CODE=markup&PROP_ID=<? print $row['PROP_ID'] ?>&MARKUP_ID=<? print $row['ID'] ?>&YEAR=<? print $row['YEAR'] ?>"><? print $NAME ?></a></td>
            <td nowrap align="right"><? print $row['MARKUP'] ?>%</td>
        </tr>
        <?
        $cnt*=-1;
    }
    ?>
    </table>
    </div>
<? } else { ?>

<? } ?>