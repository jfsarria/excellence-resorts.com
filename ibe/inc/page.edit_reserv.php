<?
/*
 * Revised: Nov 01, 2011
 */

if (!is_array($_REQUEST)) $_REQUEST = array();
if (isset($_SESSION['AVAILABILITY'])) unset($_SESSION['AVAILABILITY']);

$_BACK_DATA = isset($_REQUEST['_BACK_DATA']) ? $_REQUEST['_BACK_DATA'] : "";
//print "\nAQUI:\n".$_BACK_DATA."\n\n";
$_BACK_DATA = html_entity_decode($_BACK_DATA);
//print "\nAQUI:\n".$_BACK_DATA."\n\n";
$_BACK_DATA_ARR = json_decode($_BACK_DATA, true);
//print "<pre>";print_r($_BACK_DATA_ARR);print "</pre>";
$_BACK_DATA = urlencode($_BACK_DATA);
//print "\nAQUI:\n".$_BACK_DATA."\n\n";

$ID = isset($_REQUEST['ID']) ? $_REQUEST['ID'] : "0";
$CODE = isset($_REQUEST['CODE']) ? $_REQUEST['CODE'] : "";
$YEAR = isset($_REQUEST['YEAR']) ? $_REQUEST['YEAR'] : date("Y", strtotime($_TODAY));
$JSON = "{}";

$MODIFY = isset($_REQUEST['MODIFY']) ? $_REQUEST['MODIFY'] : "";
$SUBMIT = isset($_REQUEST['SUBMIT']) ? $_REQUEST['SUBMIT'] : "";

$THIS_PAGE = "?PAGE_CODE=edit_reserv&ID={$ID}&CODE={$CODE}&YEAR={$YEAR}";
$showPWD = true;
?>

<div class='ListBtns'>
    <table width="800">
    <tr>
        <td><h2>Reservations Details Screen</h2></td>
        <td>
            <a onclick="ibe.callcenter.sendConfirmation('<? print $ID ?>','<? print $CODE ?>','<? print $YEAR ?>')"><span class="button key">Send Confirmation</span></a>
        </td>
        <? 
        if ($_BACK_DATA!="") { 
            $THIS_PAGE .= "&_BACK_DATA=".$_BACK_DATA;
            $PAGE_CODE = $_BACK_DATA_ARR['PAGE_CODE'];
            if ($MODIFY=="") { ?>
            <td>
                <a href="?PAGE_CODE=<? print $PAGE_CODE ?>&_DATA=<? print $_BACK_DATA ?>"><span class="button plain">Go Back</span></a>
            </td>
            <?
            }
        } 
        ?>
    </tr>
    </table>
</div>
<div class="aclear"></div>
<?
$RSET = $clsReserv->getReservationById($db, array(
    "ID"=>$ID,
    "RES_TABLE"=>"RESERVATIONS_{$CODE}_{$YEAR}",
    "FIELDS"=>"ARRAY"
));
$ARRAY = array();
if ($RSET['iCount']>0) {
    $row = $db->fetch_array($RSET['rSet']);
    $VIEWNAME = "V_SEARCH_{$CODE}_{$YEAR}";
    $RSET = $clsReserv->getReservationById($db, array(
        "ID"=>$ID,
        "FIELDS"=>"{$VIEWNAME}.*",
        "RES_TABLE"=>$VIEWNAME
    ));
    $RESVIEW = $db->fetch_array($RSET['rSet']);
    $ARRAY = $row['ARRAY'];
    $JSON = $clsGlobal->jsonDecode($ARRAY);

	//print "<!-- $VIEWNAME \n JSON:\n $ARRAY \n <pre>";print_r($JSON);print "</pre> -->";

    extract($JSON);

    $_HOTEL_ID = $RES_PROP_ID;
    include_once "inc/tpl.modules.get.php";

    if (isset($GET_GEO) && (int)$GET_GEO==1 && isset($RES_GEO_COUNTRY_CODE)) {
        $GEO_INFO = appendToString($RES_GEO_CITY,", ").appendToString($RES_GEO_ZIPCODE,", ").appendToString($RES_GEO_COUNTRY_NAME,", ");
    } else {
        $GEO_INFO = appendToString($RES_STATE_CODE,", ").$RES_COUNTRY_CODE;
    }

    $GUEST = $clsGuest->get($db, array("ID"=>$RESVIEW['GUEST_ID'])); //$RESERVATION['GUEST'];
    $PAYMENT = $RESERVATION['PAYMENT'];

    if (file_exists("{$_APP_ROOT}inc/mods/{$_MODULES['header']}")) include_once "inc/mods/{$_MODULES['header']}"; 

    if ($MODIFY=="") { 
        //print " m.show_reserv.php ";
        include "mods/m.show_reserv.php";
    } else {
        $isEDIT = true;
        //print " m.edit_reserv.php ";
        include "mods/m.edit_reserv.php";
    }
} 

//print "<!-- JSON:<pre>";print_r($JSON);print "</pre> -->";
?>
