<?
/*
 * Revised: Jun 10, 2011
 *          Feb 07, 2014
 *          Aug 15, 2016
 */

// http://locateandshare.com/ibe/index.php?PAGE_CODE=reserv&PAGE_SECTION=forwhom

if (isset($_SESSION['AVAILABILITY'])) {

    if ($_PAGE_SECTION=="forwhom") {
        if (!isset($_SESSION['AVAILABILITY']['RESERVATION'])) $_SESSION['AVAILABILITY']['RESERVATION'] = array();

        $RES_ROOMS_SELECTED = explode(",",$_POST['RES_ROOMS_SELECTED']);
        $_SESSION['AVAILABILITY']['RESERVATION']['RES_ROOMS_SELECTED'] = $RES_ROOMS_SELECTED;

        $ROOM = 1;
        $RES_TOTAL_CHARGE = 0;
        $_SESSION['AVAILABILITY']['RESERVATION']['RES_ROOM_CHARGE'] = array();
        foreach ($_SESSION['AVAILABILITY']['RESERVATION']['RES_ROOMS_SELECTED'] as $IND=>$ROOM_ID) {
            $RES_ROOM_CHARGE = (int)$_SESSION['AVAILABILITY']["RES_ROOM_{$ROOM}_ROOMS"][$ROOM_ID]['TOTAL']['FINAL'];
            $RES_TOTAL_CHARGE += $RES_ROOM_CHARGE;
            array_push($_SESSION['AVAILABILITY']['RESERVATION']['RES_ROOM_CHARGE'],$RES_ROOM_CHARGE);
            ++$ROOM;
        }
        $_SESSION['AVAILABILITY']['RESERVATION']['RES_TOTAL_CHARGE'] = $RES_TOTAL_CHARGE;

        $TYPES = array_flip($_SESSION['AVAILABILITY']['RESERVATION']['RES_ROOMS_SELECTED']);
        foreach ($TYPES as $ROOM_ID=>$IND) {
            $TYPES[$ROOM_ID] = $_SESSION['AVAILABILITY']['RES_ITEMS'][$ROOM_ID]['NAME_'.$_SESSION['AVAILABILITY']['RES_LANGUAGE']];
        }
        $_SESSION['AVAILABILITY']['RESERVATION']['RES_ROOMS_SELECTED_NAMES'] = $TYPES;
    }

    include_once "page.availability.callcenter.hdr.php";

    $err = array();
    $errMsg = array();
    $isOk = true;
    $isDone = isset($_SESSION['AVAILABILITY']['RESERVATION']['DONE']['RESERVATION']);

    print "
        <div id='reservation_wrapper'>
            <div class='reserv_left_col'>
                <form id='reservForm' method='post'>
                    ";
                    ?>
                    <input type="hidden" name="CURRENCY_CODE" id="CURRENCY_CODE" value="<?=$_REQUEST['CURRENCY_CODE']?>">
                    <input type="hidden" name="CURRENCY_QUOTE" id="CURRENCY_QUOTE" value="<?=$_REQUEST['CURRENCY_QUOTE']?>">
                    <?
                    switch ($_PAGE_SECTION) {
                        case "forwhom":
                            include "page.reserv.forwhom.php";
                            break;
                        case "rooms":
                            include "page.reserv.room.php";
                            break;
                        case "make":
                            include "page.reserv.make.php";
                            break;
                        default:
                            break;
                    }
                    extract($_SESSION['AVAILABILITY']);
                    include_once "tpl.modules.reserv.php";
                    print "
                </form>";

                if (count($err)!=0) {
                    print "<p class='s_error top_msg'>".implode("<br>",$err)."</p>";
                    //mail("juan.sarria@everlivesolutions.com,mirek@artbymobile.com", "*** Reservation Error ***", implode("\n",$errMsg)."\n\n=================\n\n".json_encode($_SESSION['AVAILABILITY'])."\n\n".json_encode($_SESSION['AUTHENTICATION']));
                }

                print "
            </div>";
            if (!$isDone) {
                print "
                <div class='reserv_right_col'>";
                    include_once "mods/m.reserv.summary.php";
                    print "
                </div>";
            }
            print "
        <div class='aclear'></div>
    ";
} else {
    ?>
    <script>
        document.location.href = "?PAGE_CODE=availability";
    </script>
    <?
}
?>