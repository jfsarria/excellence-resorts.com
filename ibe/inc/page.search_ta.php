<?
/*
 * Revised: Oct 07, 2011
 */

ob_start();

if (!is_array($_DATA)) $_DATA = array();

$EXCEL_NAME = "travelagents.xls";
$IATA = isset($_DATA['IATA']) ? $_DATA['IATA'] : "";
$PHONE = isset($_DATA['PHONE']) ? $_DATA['PHONE'] : "";
$AGENCY = isset($_DATA['AGENCY']) ? $_DATA['AGENCY'] : "";
$LASTNAME = isset($_DATA['LASTNAME']) ? $_DATA['LASTNAME'] : "";
$EMAIL = isset($_DATA['EMAIL']) ? $_DATA['EMAIL'] : "";+
$IS_CONFIRMED = isset($_DATA['IS_CONFIRMED']) ? $_DATA['IS_CONFIRMED'] : "";

$pageNo = isset($_REQUEST['pageNo']) ? (int)$_REQUEST['pageNo'] : 1;
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : "LASTNAME";

?>
<div class='ListBtns'>
    <table>
    <tr>
        <td><h2>Travel Agents</h2></td>
    </tr>
    </table>
</div>
<div class="aclear"></div>

<form id="editfrm" method="post" enctype="multipart/form-data" action="">
    <input type="hidden" name="PAGE_CODE" id="PAGE_CODE" VALUE="search_ta">
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
                "USER_TYPE_ID"=>3
            );
            $ARGS = array_merge($ARGS, $_REQUEST);
            //print "<pre>";print_r($ARGS);print "</pre>";
            ob_start();
                include "ws.migration.submit.php";
            $_JSON = ob_get_clean();
            //print "=> ".$_JSON;

            ob_start();

            $_TA = json_decode($_JSON, true);
            if (count($_TA)!=0) {
                ?>
                <table width="100%" id='searchReserTbl' cellpadding="2" cellspacing="0">
                <tr class="listRowHdr">
                    <th>&nbsp;</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Agency</th>
                    <th>IATA</th>
                </tr>
                <?
                $cnt=1;
                $num=1;
                foreach ($_TA as $I=>$row) {
                    ?>
                    <tr class="row<? print $cnt ?>">
                        <td><? print $num ?></td>
                        <td nowrap><a href="?PAGE_CODE=migrate_TA&ID=<? print $row['ID'] ?>"><? print $row['NAME'] ?></a></td>
                        <td><? print $row['EMAIL'] ?></td>
                        <td><? print $row['AGENCY_NAME'] ?></td>
                        <td><? print $row['IATA'] ?></td>
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
                "IATA"=>$IATA,
                "PHONE"=>$PHONE,
                "AGENCY"=>$AGENCY,
                "LASTNAME"=>$LASTNAME,
                "EMAIL"=>$EMAIL,
                "IS_CONFIRMED"=>$IS_CONFIRMED,
                'COUNT'=>($isEXPORT)?'0':'1'
            );
            if (!$isEXPORT) {
                $RSET = $clsTA->searchTA($db, $arg);

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
            $RSET = $clsTA->searchTA($db, $arg);
            ob_start();
            ?>
            <table width="100%" id='searchReserTbl' cellpadding="2" cellspacing="0">
            <tr class="listRowHdr">
                <th>&nbsp;</th>
                <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('LASTNAME')" <? if (strstr($arg['sortBy'],"LASTNAME")!==FALSE) print " id='sortedByThis'" ?>>Name</a></th>
                <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('EMAIL')" <? if (strstr($arg['sortBy'],"EMAIL")!==FALSE) print " id='sortedByThis'" ?>>Email</a></th>
                <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('AGENCY_NAME')" <? if (strstr($arg['sortBy'],"AGENCY_NAME")!==FALSE) print " id='sortedByThis'" ?>>Agency</a></th>
                <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('IATA')" <? if (strstr($arg['sortBy'],"IATA")!==FALSE) print " id='sortedByThis'" ?>>IATA</a></th>
            </tr>
            <?
            $cnt=1;
            $num=1;
            while ($row = $db->fetch_array($RSET['rSet'])) {
                ?>
                <tr class="row<? print $cnt ?>">
                    <td><? print $num ?></td>
                    <td><a href="?PAGE_CODE=edit_ta&ID=<? print $row['ID'] ?>"><? print $row['LASTNAME'].", ".$row['FIRSTNAME'] ?></a></td>
                    <td><? print $row['EMAIL'] ?></td>
                    <td><? print $row['AGENCY_NAME'] ?></td>
                    <td><? print $row['IATA'] ?></td>
                </tr>
                <?
                $cnt *= -1;
                ++$num;
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

