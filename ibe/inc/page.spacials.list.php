<?
/*
 * Revised: Dec 16, 2011
 */

$_YEAR = isset($_REQUEST['_YEAR']) ? (int)$_REQUEST['_YEAR'] : date("Y");
$_MONTH = isset($_REQUEST['_MONTH']) ? (int)$_REQUEST['_MONTH'] : date("n");
$_IS_ARCHIVE = isset($_REQUEST['_IS_ARCHIVE']) ? (int)$_REQUEST['_IS_ARCHIVE'] : 0;

$DELETE_ID = isset($_REQUEST['DELETE_ID']) ? (int)$_REQUEST['DELETE_ID'] : 0;
if ($DELETE_ID!=0) {
    $result = $clsSpecials->remove($db, array("DELETE_ID"=>$DELETE_ID));
    if ((int)$result==-1) {
        print "<p class='s_notice top_msg'>Special is in use.</p>";
    }
}
?>
<form id="lstFrm">
<input type="hidden" name="PAGE_CODE" value="specials">
<input type="hidden" name="PROP_ID" value="<? print $PROP_ID ?>">
<input type="hidden" name="DELETE_ID" id="DELETE_ID" value="0">

<div class='ListBtns'>
    <table width="780px">
    <tr>
        <td width="100%"><h2>Specials</h2></td>
        <td nowrap><a href="?PAGE_CODE=specials&PROP_ID=<? print $PROP_ID ?>&SPECIAL_ID=0"><span class="button key">Add New</span></a>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td nowrap>Year:
            <select name="_YEAR" onchange='$("#lstFrm").submit()'>
                <? for ($YY=2011;$YY<=date("Y")+5;++$YY) print "<option value='$YY' ".($_YEAR==$YY?"selected":"").">$YY</option>" ?>
            </select>
        </td>
        <td nowrap>Month:
            <select name="_MONTH" onchange='$("#lstFrm").submit()'>
                <option value="0">All</option>
                <? for ($MM=1;$MM<=12;++$MM) print "<option value='$MM' ".($_MONTH==$MM?"selected":"").">".date("F", strtotime(date("Y")."/".$MM."/01"))."</option>" ?>
            </select>
        </td>
        <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" value="1" name="_IS_ARCHIVE" <? if ($_IS_ARCHIVE==1) print "checked" ?> onclick='$("#lstFrm").submit()'> Show Archived Specials</td>
    </tr>
    </table>
</div>
<div class="aclear"></div>

<div class="listTbl">
<table>
<tr class="listRowHdr">
    <td>ID</td>
    <td width="100%">Reference</td>
    <td>Name</td>
    <td colspan="2">Travel Period</td>
    <td>&nbsp;</td>
    <? if ($_IS_ARCHIVE==0) print "<td>&nbsp;</td>"; ?>
</tr>
<?
$RANGE = "
    AND IS_ARCHIVE='{$_IS_ARCHIVE}' 
";

if ((int)$_MONTH==0) {
    $RANGE .= "
        AND {$_YEAR} >= YEAR(TRAVEL_FROM) AND {$_YEAR} <= YEAR(TRAVEL_TO)
    ";
} else {
    $DATE = "{$_YEAR}-{$_MONTH}-01";
    $RANGE .= "
        AND DATE('{$DATE}') >= DATE(CONCAT(YEAR(TRAVEL_FROM),\"-\",MONTH(TRAVEL_FROM),\"-01\")) 
        AND LAST_DAY('{$DATE}') <= LAST_DAY(TRAVEL_TO)
    ";
}

$RSET = $clsSpecials->getByProperty($db, array("PROP_ID"=>$PROP_ID,"WEHRE"=>$RANGE));

if ( $RSET['iCount'] != 0 ) { ?>
    <?
    $cnt=1;
    while ($row = $db->fetch_array($RSET['rSet'])) {
        $NAME = _d($row['NAME_'.$_IBE_LANG],$row['NAME_EN']);
        $TITLE = (trim($row['REFERENCE'])!="") ? $row['REFERENCE'] : $NAME;
        $EDIT = "?PAGE_CODE=specials&PROP_ID={$row['PROP_ID']}&SPECIAL_ID={$row['ID']}&_YEAR={$_YEAR}&_MONTH={$_MONTH}&_IS_ARCHIVE={$_IS_ARCHIVE}";
        ?>
        <tr class='listRow<? print $cnt ?>'>
            <td><? print $row['ID'] ?></td>
            <td><a href="<? print $EDIT ?>"><? print $TITLE ?></a></td>
            <td nowrap><a href="<? print $EDIT ?>"><? print $NAME ?></a></td>
            <td nowrap><? print shortDate($row['TRAVEL_FROM']) ?></td>
            <td nowrap><? print shortDate($row['TRAVEL_TO']) ?></td>
            <td><a href="<? print $EDIT."&IS_COPY=yes" ?>"><img title="Duplicate" src="css/img/copy_icon.gif" width="16" height="16" border="0"></a></td>
            <? 
            if ($_IS_ARCHIVE==0) {
                $lbl = (int)$row['IS_ACTIVE'] ? "<a href='javascript:if (confirm(\"Do you want to close this special?\")) ibe.setActive(\"SPECIALS\",\"0\",\"{$row['ID']}\")'><span style='color:#006600'>OPEN</span></a>" : "<a href='javascript:if (confirm(\"Do you want to open this special?\")) ibe.setActive(\"SPECIALS\",\"1\",\"{$row['ID']}\")'><span style='color:#990000'>CLOSED</span></a>";
                print "<td>{$lbl}</td>"; 
            }
            ?>
        </tr>
        <?
        $cnt*=-1;
    }
    ?>
<? } else { ?>

<? } ?>
</table>
</div>
</form>
