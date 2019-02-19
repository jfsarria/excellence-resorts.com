<?
/*
 * Revised: Feb 01, 2014
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
$PROP_IDs = isset($_DATA['PROP_IDs']) ? $_DATA['PROP_IDs'] : array();

$MONTH_AGO = strtotime(date("Y-m-d", strtotime($_TODAY)) . " -1 month");
$MONTH_AGO = date("Y-m-d", $MONTH_AGO);

$FROM = isset($_DATA['FROM']) && $_DATA['FROM']!="" ? addZeroToDate($_DATA['FROM']) : $MONTH_AGO;
$TO = isset($_DATA['TO']) && $_DATA['TO']!="" ? addZeroToDate($_DATA['TO']) : $_TODAY;
$YEARS = getYearsArr(date("Y",strtotime($FROM)), date("Y",strtotime($TO)));

$LASTNAME = isset($_DATA['LASTNAME']) ? trim($_DATA['LASTNAME']) : "";
$PHONE = isset($_DATA['PHONE']) ? trim($_DATA['PHONE']) : "";
$EMAIL = isset($_DATA['EMAIL']) ? trim($_DATA['EMAIL']) : "";
$RESNUM = isset($_DATA['RESNUM']) ? trim($_DATA['RESNUM']) : "";

$sortby = isset($_DATA['sortby']) ? $_DATA['sortby'] : "CREATED DESC";


?>
<div class='ListBtns'>
    <table>
    <tr>
        <td><h2>Simple Reservations Search Screen</h2></td>
    </tr>
    </table>
</div>
<div class="aclear"></div>

<div style="<? if ($isBack) print "display:none" ?>">
    <form id="editfrm" method="post" enctype="multipart/form-data" action="?#results" <? if ($SUBMIT=="AUTO") print "style='display:none'" ?>>
        <input type="hidden" name="PAGE_CODE" id="PAGE_CODE" VALUE="search">
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
                $HOTEL = array("1"=>"XRC","2"=>"XPM","3"=>"XPC","4"=>"LAM","5"=>"FPM","6"=>"XEC","7"=>"XOB");
                $arg = array(
                    "PROP_IDs"=>$PROP_IDs,
                    "FROM"=>$FROM,
                    "TO"=>$TO,
                    "YEARS"=>$YEARS,
                    "LASTNAME"=>$LASTNAME,
                    "PHONE"=>$PHONE,
                    "RESNUM"=>$RESNUM,
                    "EMAIL"=>$EMAIL,
                    'COUNT'=>($isEXPORT)?'0':'1'
                );
                $arg['sortBy'] = $sortby;

                if ($RESNUM!="" || $LASTNAME!="" || $PHONE!="" || $EMAIL!="") {
                    $arg["FROM"] = (date("Y")-2) . "/01/01";
                }

                $RSET = $clsReserv->searchSimple($db, $arg);

                ?>
                <style>
                  #searchReserTbl .cancelled {color:#6b6b6b}
                  #searchReserTbl .cancelled a {color:#990000}
                </style>
                <table width="100%" id='searchReserTbl' cellpadding="2" cellspacing="0">
                <tr class="listRowHdr">
                    <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('NUMBER')" <? if (strstr($arg['sortBy'],"NUMBER")!==FALSE) print " id='sortedByThis'" ?>>Reservation #</a></th>
                    <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('HOTEL')" <? if (strstr($arg['sortBy'],"HOTEL")!==FALSE) print " id='sortedByThis'" ?>>Hotel</a></th>
                    <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('LASTNAME')" <? if (strstr($arg['sortBy'],"HOTEL")!==FALSE) print " id='sortedByThis'" ?>>Guest</a></th>
                    <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('CHECK_IN')" <? if (strstr($arg['sortBy'],"CHECK_IN")!==FALSE) print " id='sortedByThis'" ?>>Arrival</a></th>
                    <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('CHECK_OUT')" <? if (strstr($arg['sortBy'],"CHECK_OUT")!==FALSE) print " id='sortedByThis'" ?>>Departure</a></th>
                    <th><A HREF="javascript:void(0)" onClick="ibe.callcenter.setOrderBy('R.CREATED')" <? if (strstr($arg['sortBy'],"CREATED")!==FALSE) print " id='sortedByThis'" ?>>Booking</a></th>
                </tr>
                <?
                if ($RSET) {
                    $cnt=1;
                    $ALREADY = array();
                    while ($row = $db->fetch_array($RSET['rSet'])) {
                        $YEAR = date("Y", strtotime($row['CREATED']));
                        $CODE = $HOTEL[$row['HOTEL']];
                        if (!in_array($row['NUMBER'], $ALREADY)) {
                          $ALREADY[] = $row['NUMBER'];
                          ?>
                          <tr class="row<? print $cnt;print (int)$row['STATUS']==0?" cancelled":"" ?>">
                              <td><a href="?PAGE_CODE=edit_reserv&ID=<? print $row['ID'] ?>&CODE=<? print $CODE ?>&YEAR=<? print $YEAR ?>&_BACK_DATA=<? print urlencode($_BACK_DATA) ?>"><? print $row['NUMBER'] ?></a></td>
                              <td><? print $CODE; ?></td>
                              <td><? print $row['LASTNAME'].", ".$row['FIRSTNAME'] ?></td>
                              <td nowrap><? print shortDate($row['CHECK_IN']) ?></td>
                              <td nowrap><? print shortDate($row['CHECK_OUT']) ?></td>
                              <td><? print shortDateTime($row['CREATED'],"<br>") ?></td>
                          </tr>
                          <?
                          $cnt *= -1;
                        }
                    }
                }
                ?>
                </table>
                <?
                //print "<pre>";print_r($arg);print "</pre>";
            } else {
              ?>
              <script>
                for (var p=0; p <= 7; ++p) {
                  var cb = jQuery("#PROP_IDs_"+p);
                  if (cb.length==1) {
                    cb[0].checked = true;
                  }
                }
              </script>                
              <?
            }
            ?>
        </div>
        <script>
            ibe.inventory.init();
        </script>
    </fieldset>
<? } ?>
