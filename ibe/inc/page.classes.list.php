<?
/*
 * Revised: Dec 16, 2011
 */
$_IS_ARCHIVE = isset($_REQUEST['_IS_ARCHIVE']) ? (int)$_REQUEST['_IS_ARCHIVE'] : 0;
$_YEAR = isset($_REQUEST['_YEAR']) ? (int)$_REQUEST['_YEAR'] : date("Y");
$_SEASON = isset($_REQUEST['_SEASON']) ? (int)$_REQUEST['_SEASON'] : 0;
$_ROOMTYPE = isset($_REQUEST['_ROOMTYPE']) ? (int)$_REQUEST['_ROOMTYPE'] : 0;

$pageNo = isset($_REQUEST['pageNo']) ? (int)$_REQUEST['pageNo'] : 1;
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : "`YEAR` DESC, `NAME_{$_IBE_LANG}`";

?>
<form id="lstFrm">
<input type="hidden" name="PAGE_CODE" value="classes">
<input type="hidden" name="PROP_ID" value="<? print $PROP_ID ?>">
<input type="hidden" name="DELETE_ID" id="DELETE_ID" value="0">

<div class='ListBtns'>
    <table width="940">
    <tr>
        <td><h2>Rate Classes</h2></td>
        <td><a href="?PAGE_CODE=classes&PROP_ID=<? print $PROP_ID ?>&CLASS_ID=0"><span class="button key">Add New</span></a></td>
        <td><input type="checkbox" value="1" name="_IS_ARCHIVE" <? if ($_IS_ARCHIVE==1) print "checked" ?> onclick='$("#lstFrm").submit()'> Show Archived Classes</td>
    </tr>
    </table>
</div>
<div class="aclear"></div>

<div>
    <table>
    <tr>
        <td>Year:
            <select name="_YEAR" onchange='$("#lstFrm").submit()'>
                <? for ($YY=2011;$YY<=date("Y")+5;++$YY) print "<option value='$YY' ".($_YEAR==$YY?"selected":"").">$YY</option>" ?>
            </select>        
        </td>
        <td>Season:
            <select name="_SEASON" onchange='$("#lstFrm").submit()'>
                <option value=''></option>
                <?
                $SSET = $clsSeasons->getByProperty($db, array("PROP_ID"=>$PROP_ID,"YEAR"=>$_YEAR));
                while ($srow = $db->fetch_array($SSET['rSet'])) {
                    print "<option value='{$srow['ID']}' ".($_SEASON==(int)$srow['ID']?"selected":"").">{$srow['NAME']}</option>";
                }
                ?>
            </select>        
        </td>
    </tr>
    <tr>
        <td colspan="2">Room Type:
            <select NAME="_ROOMTYPE" onchange='$("#lstFrm").submit()'>
                <option value=''></option>
                <?
                $RSET = $clsRooms->getByProperty($db, array("PROP_ID"=>$PROP_ID));
                while ($rrow = $db->fetch_array($RSET['rSet'])) {
                    print "<option value='{$rrow['ID']}' ".($_ROOMTYPE==(int)$rrow['ID']?"selected":"").">{$rrow['NAME_EN']}</option>";
                }
                ?>
            </select>        
        </td>
    </tr>
    </table>
</div>
<?
$arg = array(
    "PROP_ID"=>$PROP_ID,
    "YEAR"=>$_YEAR,
    "SEASON"=>$_SEASON,
    "ROOM"=>$_ROOMTYPE,
    "WHERE"=>"AND CLASSES.IS_ARCHIVE='{$_IS_ARCHIVE}'",
    'COUNT'=>0
);
$RSET = $clsClasses->getByFilters($db, $arg); // getByProperty

$totalItems = (int)$RSET['iCount'];
$itemsPerPage = 20;
$noPages = ceil($totalItems / $itemsPerPage);
$startItem = ($pageNo-1) * $itemsPerPage;
$pagination = paginationTbl($totalItems, $pageNo, $noPages, $startItem, "", true);

$arg['COUNT'] = '0';
$arg['sortBy'] = $sortby;
$arg['startItem']=$startItem;
$arg['itemsPerPage']=$itemsPerPage;
$rowNum=$startItem+1;

print "<br><div>".$pagination."</div>";

//print "<pre>";print_r($arg);print "</pre>";

$RSET = $clsClasses->getByFilters($db, $arg); // getByProperty
//print "count: ".$RSET['iCount']; 
if ( $RSET['iCount'] != 0 ) { ?>
    <div class="listTbl">
    <table width="100%">
    <tr class="listRowHdr">
        <td>id</td>
        <td width="100%">Reference Name</td>
        <td>Room Type</td>
        <td>Year</td>
        <td>PPPN</td>
        <td>&nbsp;</td>
    </tr>
    <?
    $cnt=1;
    while ($row = $db->fetch_array($RSET['rSet'])) {
        $NAME = _d($row['NAME_'.$_IBE_LANG],$row['NAME_EN']);
        $TITLE = (trim($row['REFERENCE'])!="") ? $row['REFERENCE'] : $NAME;
        $EDIT = "?PAGE_CODE=classes&PROP_ID={$row['PROP_ID']}&CLASS_ID={$row['ID']}";
        ?>
        <tr class='listRow<? print $cnt ?>'>
            <td><? print $row['ID'] ?></td>
            <td><a href="<? print $EDIT ?>"><? print $TITLE ?></a></td>
            <td nowrap><? print $row['ROOM_NAME_EN'] ?></td>
            <td nowrap><? print $row['YEAR'] ?></td>
            <td align='right'><? print "$".number_format($row['RATE_PER_RP']) ?></td>
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
