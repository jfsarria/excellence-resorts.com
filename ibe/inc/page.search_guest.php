<?
/*
 * Revised: Oct 07, 2011
 */

ob_start();

if (!is_array($_DATA)) $_DATA = array();

$EXCEL_NAME = "guests.xls";
$LASTNAME = isset($_DATA['LASTNAME']) ? $_DATA['LASTNAME'] : "";
$PHONE = isset($_DATA['PHONE']) ? $_DATA['PHONE'] : "";
$EMAIL = isset($_DATA['EMAIL']) ? $_DATA['EMAIL'] : "";
$MAILING_LIST = isset($_DATA['MAILING_LIST']) ? $_DATA['MAILING_LIST'] : "";

$pageNo = isset($_REQUEST['pageNo']) ? (int)$_REQUEST['pageNo'] : 1;
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : "LASTNAME";

?>
<div class='ListBtns'>
    <table>
    <tr>
        <td><h2>Guests</h2></td>
    </tr>
    </table>
</div>
<div class="aclear"></div>

<form id="editfrm" method="post" enctype="multipart/form-data" action="">
    <input type="hidden" name="PAGE_CODE" id="PAGE_CODE" VALUE="search_guest">
    <input type="hidden" name="ACTION" id="ACTION" VALUE="">
    <input type="hidden" name="EXPORT" id="EXPORT" VALUE="">

    <input type="hidden" name="pageNo" id="pageNo" VALUE="<? print $pageNo ?>">
    <input type="hidden" name="sortby" id="sortby" VALUE="<? print $sortby ?>">

    <div class="aclear"></div>
    <? include_once "inc/tpl.modules.php"; ?>
</form>

<a name="results"></a>

<fieldset>
    <div class="fieldset">
        <?
        if ($ACTION=="IMPORT") {

            $isMigration = true;
            include "ws.migration.server.php";
            $ARGS = array (
                "CODE"=>"searchGuest",
                "USER_TYPE_ID"=>2
            );
            $ARGS = array_merge($ARGS, $_REQUEST);
            //print "<pre>";print_r($ARGS);print "</pre>";
            ob_start();
                include "ws.migration.submit.php";
            $_JSON = ob_get_clean();
            //print "=> ".$_JSON;

            ob_start();

            $_GUEST = json_decode($_JSON, true);
            if (count($_GUEST)!=0) {
                ?>
                <table width="100%" id='searchReserTbl' cellpadding="2" cellspacing="0">
                <tr class="listRowHdr">
                    <th>&nbsp;</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                </tr>
                <?
                $cnt=1;
                $num=1;
                foreach ($_GUEST as $I=>$row) {
                    ?>
                    <tr class="row<? print $cnt ?>">
                        <td><? print $num ?></td>
                        <td nowrap><a href="?PAGE_CODE=migrate_guest&ID=<? print $row['ID'] ?>"><? print $row['NAME'] ?></a></td>
                        <td><? print $row['EMAIL'] ?></td>
                        <td><? print $row['PHONE'] ?></td>
                    </tr>
                    <?
                    $cnt *= -1;
                    ++$num;
                }
                ?>
                </table>
                <?
            }
            $REPORT = ob_get_clean();

            if (!$isEXPORT) print $REPORT;

        }
        if ($ACTION=="SUBMIT") {
            $arg = array(
                "LASTNAME"=>$LASTNAME,
                "PHONE"=>$PHONE,
                "EMAIL"=>$EMAIL,
                "MAILING_LIST"=>$MAILING_LIST,
                'COUNT'=>($isEXPORT)?'0':'1'
            );
            if (!$isEXPORT) {
                $RSET = $clsGuest->searchGuests($db, $arg);

                $totalItems = (int)$RSET['iCount'];
                $itemsPerPage = 20;
                $noPages = ceil($totalItems / $itemsPerPage);
                $startItem = ($pageNo-1) * $itemsPerPage;
                $pagination = paginationTbl($totalItems, $pageNo, $noPages, $startItem);

                $arg['COUNT'] = '0';
                $arg['sortBy'] = $sortby;
                $arg['startItem']=$startItem;
                $arg['itemsPerPage']=$itemsPerPage;
                $rowNum=$startItem+1;

                print "<div>".$pagination."</div>";
            } else {
                $rowNum = 1;
            }
            $RSET = $clsGuest->searchGuests($db, $arg);
            ob_start();
            ?>

            <table width="100%" id='searchReserTbl' cellpadding="2" cellspacing="0">
            <tr class="listRowHdr">
                <th>&nbsp;</th>
                <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('LASTNAME')" <? if (strstr($arg['sortBy'],"LASTNAME")!==FALSE) print " id='sortedByThis'" ?>>Name</a></th>
                <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('EMAIL')" <? if (strstr($arg['sortBy'],"EMAIL")!==FALSE) print " id='sortedByThis'" ?>>Email</a></th>
                <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('PHONE')" <? if (strstr($arg['sortBy'],"PHONE")!==FALSE) print " id='sortedByThis'" ?>>Phone</a></th>
                <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('CONTACT_LASTNAME')" <? if (strstr($arg['sortBy'],"CONTACT_LASTNAME")!==FALSE) print " id='sortedByThis'" ?>>Acc.Holder</a></th>
                <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('MAILING_LIST')" <? if (strstr($arg['sortBy'],"MAILING_LIST")!==FALSE) print " id='sortedByThis'" ?>>M.List</a></th>
            </tr>
            <?
            $cnt=1;
            while ($row = $db->fetch_array($RSET['rSet'])) {
                $isOwner = ($row['ID']==$row['OWNER_ID']);
                $isMList = $row['MAILING_LIST']==1 ? "Yes":"&nbsp;";
                $NAME = array($row['LASTNAME'],$row['FIRSTNAME']);
                $HOLDER = array($row['CONTACT_LASTNAME'],$row['CONTACT_FIRSTNAME']);
                ?>
                <tr class="row<? print $cnt ?>">
                    <td><? print $rowNum ?></td>
                    <td><a href="?PAGE_CODE=edit_guest&ID=<? print $row['ID'] ?><? if (!$isOwner) print "&TA_ID=".$row['CONTACT_ID'] ?>"><? print implode(", ",$NAME) ?></a></td>
                    <td><? print $row['EMAIL'] ?></td>
                    <td><? print $row['PHONE'] ?></td>
                    <td><a href="?PAGE_CODE=edit_<? print $isOwner ? "guest" : "ta" ?>&ID=<? print $row['CONTACT_ID'] ?>"><? print implode(", ",$HOLDER) ?></a></td>
                    <td><? print $isMList ?></td>
                </tr>
                <?
                $cnt *= -1;
                ++$rowNum;
            }
            ?>
            </table>
            <?
            $REPORT = ob_get_clean();

            if (!$isEXPORT) print $REPORT;

        }
        ?>
    </div>
</fieldset>
<?
$OUT = ob_get_clean();

print ($isEXPORT) ? $REPORT : $OUT;

?>

