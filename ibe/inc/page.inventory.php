<?
/*
 * Revised: Jan 06, 2013
 *          Dec 12, 2016
 */

if (!is_array($_DATA)) $_DATA = array();

if (isset($_DATA['UPDATE_RES_DATE'])&&trim($_DATA['UPDATE_RES_DATE'])!="") $_DATA['FROM'] = $_DATA['UPDATE_RES_DATE'];

$check_all_rooms = isset($_DATA['check_all_rooms']) ? $_DATA['check_all_rooms'] : "";
$ROOM_IDs = isset($_DATA['ROOM_IDs']) ? $_DATA['ROOM_IDs'] : array();
$FROM = isset($_DATA['FROM']) && $_DATA['FROM']!="" ? $_DATA['FROM'] : $_TODAY;
$TO = addDaysToDate($FROM, 7);
$CODE = $_SESSION['AUTHENTICATION']['SETUP']['CODE'];
$YEAR = date("Y",strtotime($FROM));

$YEAR_START = date("Y", strtotime($FROM));
if ($YEAR_START<date("Y")) $YEAR_START = date("Y");

$YEAR_END = date("Y", strtotime($TO));
if ($YEAR_END>date("Y")+1) $YEAR_END = date("Y")+1;

$YEARS = array($YEAR_START,$YEAR_END);

$clsReserv->createReservationRoomInventoryTable($db, array("TABLENAME"=>"RESERVATIONS_{$CODE}_{$YEAR}_ROOM_INVENTORY"));

//ob_start();print_r($_DATA);$output = ob_get_clean();
//$output .= "\n1. ".date("Y-m-d h:i:sa");

if ($ACTION=="SUBMIT") {
    //print "<pre>";print_r($_DATA);print "<pre>";
    if (isset($_DATA['UPDATE_ROOM_ID'])) {
        $clsInventory->saveAllocation($db, array(
            'ROOM_ID'=>$_DATA['UPDATE_ROOM_ID'],
            'RES_DATE'=>$_DATA['UPDATE_RES_DATE'],
            'QTY'=>$_DATA['UPDATE_ROOMS'],
            'STATUS'=>$_DATA['UPDATE_STATUS']
        ));
    }
    //$output .= "\n2. ".date("Y-m-d h:i:sa");
    if (isset($_DATA['INVENTORY_EMAIL'])) {
        $clsInventory->saveEmailMin($db, array(
            'INVENTORY_EMAIL'=>$_DATA['INVENTORY_EMAIL'],
            'INVENTORY_MIN'=>$_DATA['INVENTORY_MIN'],
            'PROP_ID'=>$_SESSION['AUTHENTICATION']['SETUP']['ID']
        ));
    }
    //$output .= "\n3. ".date("Y-m-d h:i:sa");
    if (isset($_DATA['UPDATE_RES_DATE'])) {
        $PROP = array(
            "CODE" => array($_SESSION['AUTHENTICATION']['SETUP']['CODE']),
            "ID" => array($_SESSION['AUTHENTICATION']['SETUP']['ID'])
        );
        //$clsGlobal->updateMetaIO($_DATA['UPDATE_RES_DATE'], $PROP);
    }
} else {
    $check_all_rooms = "on";
}

//$output .= "\n4. ".date("Y-m-d h:i:sa");

//mail("jaunsarria@gmail.com","Inventory times",$output);

?>
<div class='ListBtns'>
    <table>
    <tr>
        <td><h2>View/Edit Daily Inventory Allotment</h2></td>
    </tr>
    </table>
</div>
<div class="aclear"></div>

<form id="editfrm" method="post" enctype="multipart/form-data" action="?#results">
    <input type="hidden" name="PAGE_CODE" id="PAGE_CODE" VALUE="inventory">
    <input type="hidden" name="PROP_ID" id="PROP_ID" VALUE="<? print $PROP_ID ?>">
    <input type="hidden" name="ACTION" id="ACTION" VALUE="">
    <div class="aclear"></div>
    <? include_once "inc/tpl.modules.php"; ?>
    <div style='text-align:center'>
        <a onclick="$('#ACTION').val('SUBMIT');$('#editfrm').submit()"><span class="button key">Show Inventory</span></a>
    </div>
</form>

<a name="results"></a>

<div style="text-align:center;padding:20px 0 10px 0">
    <b><? print date("D, F j, Y h:i:s A") ?> Inventory for <? print $_SESSION['AUTHENTICATION']['PROPERTIES'][$PROP_ID]['NAME'] ?> from <b><? print date("D, F j, Y",strtotime($FROM)) ?> to <b><? print date("D, F j, Y",strtotime($TO)) ?></b>
</div>

<fieldset>
    <div class="fieldset">
        <table width="100%" id='inventoryTbl' cellpadding="2" cellspacing="0">
        <?
        include "mods/m.inventory.get.data.php";

        $NIGHTS = dateDiff($FROM,$TO)+1;
        print "<tr><td>&nbsp;</td>";
        for ($t=0; $t < $NIGHTS; ++$t) {
            $THIS_DAY = addDaysToDate($FROM, $t);
            print "<th colspan='2'>";
            print date("M j",strtotime($THIS_DAY))."<br>".date("D",strtotime($THIS_DAY));
            print "</th>";
        }
        print "</tr>";
        if (isset($R_RSET['rSet'])) {
          while ($row = $db->fetch_array($R_RSET['rSet'])) {
              $ROOM_ID = $row['ID'];
              $ROOM_NAME = $row['NAME_'.$_IBE_LANG];
              print "
              <tr id='row{$ROOM_ID}'>
                  <td><div class='tdName'>{$ROOM_NAME}</div></td>";
                  for ($t=0; $t < $NIGHTS; ++$t) {
                      $THIS_DAY = addDaysToDate($FROM, $t);
                      $YEAR = date("Y",strtotime($THIS_DAY));
                      $CELLID = str_replace("-","",$THIS_DAY);
                      $isCLOSED = (isset($BLACKOUT[$row['ID']][$THIS_DAY])) ? " is_closed" : "";
                      $SOLD = (isset($INVENTORY[$row['ID']][$THIS_DAY])) ? $INVENTORY[$row['ID']][$THIS_DAY] : 0;
                      $PLUSQTY = (isset($OVERRIDE[$row['ID']][$THIS_DAY])) ? $OVERRIDE[$row['ID']][$THIS_DAY] : 0;
                      $MAX_ROOMS = (int)$row['MAX_ROOMS'];
                      $LEFT = ($MAX_ROOMS + $PLUSQTY) - $SOLD;
                      $isSOLD = ($LEFT==0)?" is_sold":"";
                      print "
                          <td width='25px' class='tdSold {$CELLID}{$isCLOSED}{$isSOLD}' rel='{$ROOM_ID}' code='{$CODE}' year='{$YEAR}'>{$SOLD}</td>
                          <td width='25px' class='tdLeft {$CELLID}{$isCLOSED}{$isSOLD}' rel='{$ROOM_ID}' code='{$CODE}' year='{$YEAR}'>{$LEFT}</td>
                      ";
                  }
                  print "
              </tr>
              ";
          }
        }
        print "<tr><td>&nbsp;</td>";
        for ($t=0; $t < $NIGHTS; ++$t) {
            print "<td><center>Sold</center></td><td><center>Left</center></td>";
        }
        print "</tr>";
        ?>
        </table>
    </div>
    <script>
        ibe.inventory.init();
    </script>
</fieldset>


