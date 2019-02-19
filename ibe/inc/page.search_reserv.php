<?
/*
 * Revised: Mar 11, 2013
 *          Oct 04, 2017
 */

if (!is_array($_DATA)) $_DATA = array();

//print "DATA <pre>";print_r($_DATA);print "</pre>";
$_BACK_DATA = json_encode($_DATA);

$isBack = false;
if (isset($_REQUEST['_DATA']) && $_REQUEST['_DATA']!="") {
    $_DATA = html_entity_decode($_REQUEST['_DATA']);
    //print $_DATA;
    $_DATA = json_decode($_DATA,true);
    //print "DATA <pre>";print_r($_DATA);print "</pre>";
    $isBack = true;
}

$SUBMIT = isset($_DATA['SUBMIT']) ? $_DATA['SUBMIT'] : "";
$GUEST_ID = isset($_DATA['GUEST_ID']) ? trim($_DATA['GUEST_ID']) : "";
$TA_ID = isset($_DATA['TA_ID']) ? trim($_DATA['TA_ID']) : "";
$OWNER_ID = "";

$PROP_IDs = isset($_DATA['PROP_IDs']) ? $_DATA['PROP_IDs'] : array();
$RESTYPE_IDs = isset($_DATA['RESTYPE_IDs']) ? $_DATA['RESTYPE_IDs'] : array();
$AGENT_IDs = isset($_DATA['AGENT_IDs']) ? $_DATA['AGENT_IDs'] : array();
$MADEBY_IDs = isset($_DATA['MADEBY_IDs']) ? $_DATA['MADEBY_IDs'] : ((count($AGENT_IDs)!=0) ? array() : array('1','2','3'));

$MONTH_AGO = strtotime(date("Y-m-d", strtotime($_TODAY)) . " -1 month");
$MONTH_AGO = date("Y-m-d", $MONTH_AGO);

$FROM = isset($_DATA['FROM']) && $_DATA['FROM']!="" ? addZeroToDate($_DATA['FROM']) : $MONTH_AGO;
$TO = isset($_DATA['TO']) && $_DATA['TO']!="" ? addZeroToDate($_DATA['TO']) : $_TODAY;
//$YEARS = array(date("Y",strtotime($FROM)),date("Y",strtotime($TO)));
//$YEARS = array_unique($YEARS);
$YEARS = getYearsArr(date("Y",strtotime($FROM)), date("Y",strtotime($TO)));
//print "$FROM - $TO <pre>";print_r($YEARS);print "</pre>";

$VIEWBY = isset($_DATA['VIEWBY']) ? $_DATA['VIEWBY'] : "activity";

$LASTNAME = isset($_DATA['LASTNAME']) ? trim($_DATA['LASTNAME']) : "";
$PHONE = isset($_DATA['PHONE']) ? trim($_DATA['PHONE']) : "";
$EMAIL = isset($_DATA['EMAIL']) ? trim($_DATA['EMAIL']) : "";
$RESNUM = isset($_DATA['RESNUM']) ? trim($_DATA['RESNUM']) : "";

if ($GUEST_ID!="" || $TA_ID!="") {
    $OWNER_ID = $GUEST_ID!=""?$GUEST_ID:$TA_ID;

    if ($GUEST_ID!="") {
        $RSET = $clsGuest->getById($db, array("ID"=>$GUEST_ID));
    }
    if ($TA_ID!="") {
        $RSET = $clsTA->getById($db, array("ID"=>$TA_ID));
    }
    if ($RSET['iCount']>0) {
        $row = $db->fetch_array($RSET['rSet']);
        $LASTNAME = $row['LASTNAME'];
        $EMAIL = $row['EMAIL'];
        $PHONE = isset($row['PHONE']) ? $row['PHONE'] : $row['AGENCY_PHONE'];
    }
}


$pageNo = isset($_DATA['pageNo']) ? (int)$_DATA['pageNo'] : 1;
//$sortby = isset($_DATA['sortby']) ? $_DATA['sortby'] : "ID DESC, NUMBER";
$sortby = isset($_DATA['sortby']) ? $_DATA['sortby'] : "CREATED DESC"; //"NUMBER, ID DESC";

?>
<div class='ListBtns'>
    <table>
    <tr>
        <td><h2>Reservations Aggregate and Search Screen</h2></td>
    </tr>
    </table>
</div>
<div class="aclear"></div>

<div style="<? if ($isBack) print "display:none" ?>">
    <form id="editfrm" method="post" enctype="multipart/form-data" onsubmit="return reviewDates()" action="?#results" <? if ($SUBMIT=="AUTO") print "style='display:none'" ?>>
        <input type="hidden" name="PAGE_CODE" id="PAGE_CODE" VALUE="search_reserv">
        <input type="hidden" name="ACTION" id="ACTION" VALUE="">
        <input type="hidden" name="GUEST_ID" id="GUEST_ID" VALUE="">
        <input type="hidden" name="TA_ID" id="TA_ID" VALUE="">
        <input type="hidden" name="pageNo" id="pageNo" VALUE="<? print $pageNo ?>">
        <input type="hidden" name="sortby" id="sortby" VALUE="<? print $sortby ?>">
        <div class="aclear"></div>
        <? include_once "inc/tpl.modules.php"; ?>
    </form>
</div>

<? 
if ($isBack) { 
    ?>
    <script>
        $('#ACTION').val('SUBMIT');
        $('#editfrm').submit();
    </script>
    <? 
} else if ($SUBMIT=="AUTO") { 
    ?>
    <script>
        $('#GUEST_ID').val('<? print $GUEST_ID ?>');
        $('#TA_ID').val('<? print $TA_ID ?>');
        $('#ACTION').val('SUBMIT');
        $('#editfrm').submit();
    </script>
    <? 
} else { 
    ?>
    <a name="results"></a>

    <fieldset>
        <div class="fieldset">
            <?
            if ($ACTION=="SUBMIT") {
                $arg = array(
                    "OWNER_ID"=>$OWNER_ID,
                    "PROP_IDs"=>$PROP_IDs,
                    "RESTYPE_IDs"=>$RESTYPE_IDs,
                    "MADEBY_IDs"=>$MADEBY_IDs,
                    "AGENT_IDs"=>$AGENT_IDs,
                    "FROM"=>$FROM,
                    "TO"=>$TO,
                    "YEARS"=>$YEARS,
                    "VIEWBY"=>$VIEWBY,
                    "LASTNAME"=>$LASTNAME,
                    "PHONE"=>$PHONE,
                    "RESNUM"=>$RESNUM,
                    "EMAIL"=>$EMAIL,
                    "GROUPED"=>1,
                    'COUNT'=>($isEXPORT)?'0':'1'
                );
                if (!$isEXPORT) {
                    $arg['COUNT'] = '0';
                    $arg['sortBy'] = $sortby;
                    if ($RESNUM=="" && $LASTNAME=="" && $PHONE=="" && $EMAIL=="") {
                        //$RSET = $clsReserv->searchReservation($db, $arg);

                        $totalItems = 1000; //(int)$RSET['iCount'];
                        $itemsPerPage = 20;
                        $noPages = ceil($totalItems / $itemsPerPage);
                        $startItem = ($pageNo-1) * $itemsPerPage;
                        $pagination = paginationTbl($totalItems, $pageNo, $noPages, $startItem, null, false);

                        $arg['startItem']=$startItem;
                        $arg['itemsPerPage']=$itemsPerPage;
                        $rowNum=$startItem+1;

                        print "<div>".$pagination."</div>";
                    } else {
                        unset($arg['startItem']);
                        unset($arg['itemsPerPage']);
                    }
                } else {
                    $rowNum = 1;
                }

                //print "<pre>";print_r($arg);print "</pre>";

                if ($RESNUM=="") {
                    $RSET = $clsReserv->searchReservation($db, $arg);
                } else {
                    $RSET = $clsReserv->searchIndividual($db, $arg);
                }
                ?>
                <style>
                  #searchReserTbl .cancelled {color:#6b6b6b}
                  #searchReserTbl .cancelled a {color:#990000}
                </style>
                <table width="100%" id='searchReserTbl' cellpadding="2" cellspacing="0">
                <tr class="listRowHdr">
                    <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('NUMBER')" <? if (strstr($arg['sortBy'],"NUMBER")!==FALSE) print " id='sortedByThis'" ?>>Reservation #</a></th>
                    <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('STATUS_STR')" <? if (strstr($arg['sortBy'],"STATUS_STR")!==FALSE) print " id='sortedByThis'" ?>>Status</a></th>
                    <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('HOTEL')" <? if (strstr($arg['sortBy'],"HOTEL")!==FALSE) print " id='sortedByThis'" ?>>Hotel</a></th>
                    <? if ($RESNUM=="") { ?>
                    <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('SECOND_SOURCE_STR')" <? if (strstr($arg['sortBy'],"SECOND_SOURCE_STR")!==FALSE) print " id='sortedByThis'" ?>>Src</a></th>
                    <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('CONTACT_LASTNAME')" <? if (strstr($arg['sortBy'],"CONTACT_LASTNAME")!==FALSE) print " id='sortedByThis'" ?>>Contact Name</a></th>
                    <? } ?>
                    <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('CHECK_IN')" <? if (strstr($arg['sortBy'],"CHECK_IN")!==FALSE) print " id='sortedByThis'" ?>>Arrival</a></th>
                    <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('CHECK_OUT')" <? if (strstr($arg['sortBy'],"CHECK_OUT")!==FALSE) print " id='sortedByThis'" ?>>Departure</a></th>
                    <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('CREATED')" <? if (strstr($arg['sortBy'],"CREATED")!==FALSE) print " id='sortedByThis'" ?>>Booking</a></th>
                    <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('MODIFIED')" <? if (strstr($arg['sortBy'],"MODIFIED")!==FALSE) print " id='sortedByThis'" ?>>Last Action</a></th>
                    <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('ROOMS')" <? if (strstr($arg['sortBy'],"ROOMS")!==FALSE) print " id='sortedByThis'" ?>>#R</a></th>
                    <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('PARENT_ID')" <? if (strstr($arg['sortBy'],"PARENT_ID")!==FALSE) print " id='sortedByThis'" ?>>Rebooked</a></th>
                </tr>
                <?
                if ($RSET) {
                    $cnt=1;
                    while ($row = $db->fetch_array($RSET['rSet'])) {
                        $YEAR = date("Y", strtotime($row['CREATED']));
                        ?>
                        <tr class="row<? print $cnt;print " ".$row['STATUS_STR'] ?>">
                            <td><a href="?PAGE_CODE=edit_reserv&ID=<? print $row['ID'] ?>&CODE=<? print $row['HOTEL'] ?>&YEAR=<? print $YEAR ?>&_BACK_DATA=<? print urlencode($_BACK_DATA) ?>"><? print $row['NUMBER'] ?></a></td>
                            <td><? print ucwords($row['STATUS_STR']) ?></td>
                            <td><? print $row['HOTEL'] ?></td>
                            <? if ($RESNUM=="") { ?>
                            <td><? print $row['SECOND_SOURCE_STR'] ?></td>
                            <td><? print $row['CONTACT_LASTNAME'].", ".$row['CONTACT_FIRSTNAME'] ?></td>
                            <? } ?>
                            <td nowrap><? print shortDate($row['CHECK_IN']) ?></td>
                            <td nowrap><? print shortDate($row['CHECK_OUT']) ?></td>
                            <td><? print shortDateTime($row['CREATED'],"<br>") ?></td>
                            <td><? print shortDateTime($row['MODIFIED'],"<br>") ?></td>
                            <td><? print $row['ROOMS'] ?></td>
                            <td><? print ((int)$row['PARENT_ID']==0)?"&nbsp;":"Rebooked" ?></td>
                        </tr>
                        <?
                        $cnt *= -1;
                    }
                }
                ?>
                </table>
                <?
                //print "<pre>";print_r($arg);print "</pre>";
            } else {
            }
            ?>
        </div>
        <script>
            ibe.inventory.init();
        </script>
    </fieldset>
<? } ?>

