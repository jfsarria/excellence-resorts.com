<?
if (!isset($RES_ITEMS['PROPERTY']['CODE'])) {
    print "
        <script>
            document.location.href = '{$_SERVER_URL}';
        </script>
    ";
} else {
?>
<div class="reservation-summary-top" data-role="collapsible" data-collapsed="<? print $data_collapsed ?>" data-theme="c" data-content-theme="c">
    <h3><? print _l("Reservation Summary","Información de Reserva",$RES_LANGUAGE) ?></h3>
    <div class="ui-collapsible-box">
        <h5 id="prop_name">
            <b>Excellence <span class="prop_color_<? print $RES_ITEMS['PROPERTY']['CODE'] ?>"><? print str_replace("Excellence","",ucwords($RES_ITEMS['PROPERTY']['NAME'])) ?></span></b>
        </h5>
        <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td><? print _l("Check In","Llegada",$RES_LANGUAGE) ?>:&nbsp;</td>
            <td><b><? print date("M j, Y", strtotime($RES_CHECK_IN)) ?></b></td>
        </tr>
        <tr>
            <td><? print _l("Check Out","Salida",$RES_LANGUAGE) ?>:&nbsp;</td>
            <td><b><? print date("M j, Y", strtotime($RES_CHECK_OUT)) ?></b></td>
        </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td><b><? print $RES_NIGHTS ?></b></td>
            <td>&nbsp;<? print _l("Night","Noche",$RES_LANGUAGE) ?><? if ((int)$RES_NIGHTS!=1) print "s" ?></td>
        </tr>
        <tr>
            <td><b><? print $RES_ROOMS_QTY ?></b></td>
            <td>&nbsp;<? print _l("Room","Habitaci",$RES_LANGUAGE) ?><? print ((int)$RES_ROOMS_QTY!=1) ? _l("s","ones",$RES_LANGUAGE) : _l("","ón",$RES_LANGUAGE) ?></td>
        </tr>
        <tr>
            <td><b><? print $RES_ROOMS_ADULTS_QTY ?></b></td>
            <td>&nbsp;<?print ((int)$RES_PROP_ID==4) ? _l("Adult","Adulto",$RES_LANGUAGE) : _l("Guest","Huesped",$RES_LANGUAGE) ?><? if ((int)$RES_ROOMS_ADULTS_QTY!=1) print ((int)$RES_PROP_ID==4) ? _l("s","",$RES_LANGUAGE) : _l("s","es",$RES_LANGUAGE) ?></td>
        </tr>
        <?
        $CHILDREN = (isset($RES_ROOMS_CHILDREN_QTY) && (int)$RES_ROOMS_CHILDREN_QTY!=0) ? (int)$RES_ROOMS_CHILDREN_QTY : 0;
        $INFANTS = (isset($RES_ROOMS_INFANTS_QTY) && (int)$RES_ROOMS_INFANTS_QTY!=0) ? (int)$RES_ROOMS_INFANTS_QTY : 0;

        if ($CHILDREN!=0 || $INFANTS!=0) {
            if ($CHILDREN!=0) {
                ?>
                <tr>
                    <td><b><? print ($CHILDREN - $INFANTS) ?></b></td>
                    <td>&nbsp;<? print _l("Child","Niño",$RES_LANGUAGE) ?><? if (($CHILDREN - $INFANTS)!=1) print _l("ren","s",$RES_LANGUAGE) ?></td>
                </tr>
                <?
            }
            if ($INFANTS!=0) {
                ?>
                <tr>
                    <td><b><? print $INFANTS ?></b></td>
                    <td>&nbsp;<? print _l("Infant","Bebé",$RES_LANGUAGE) ?><? if ((int)$INFANTS!=1) print "s" ?></td>
                </tr>
                <?
            }
        }
        ?>
        </table>
    </div>
    <form id="frmModify" action="/mobile/availability.php" method="get" data-ajax="false">

        <input type="hidden" name="START" value="1" />
        <input type="hidden" name="TS" value="<? print strtotime("now") ?>" />
        <input type="hidden" name="MODIFY" value="1" />
        <input type="hidden" name="RES_COUNTRY_CODE" value="<? print $RES_COUNTRY_CODE ?>" />
        <input type="hidden" name="RES_LANGUAGE" value="<? print $RES_LANGUAGE ?>" />
        <input type="hidden" name="RES_PROP_ID" value="<? print $RES_PROP_ID ?>" />
        <input type="hidden" name="RES_CHECK_IN" value="<? print $RES_CHECK_IN ?>" />
        <input type="hidden" name="RES_CHECK_OUT" value="<? print $RES_CHECK_OUT ?>" />
        <input type="hidden" name="RES_ROOMS_QTY" value="<? print $RES_ROOMS_QTY ?>" />
        <input type="hidden" name="RES_SPECIAL_CODE" value="<? print $RES_SPECIAL_CODE ?>" />
<!--    <input type="hidden" name="RES_COUPON_CODE" value="<? print $RES_COUPON_CODE ?>" /> -->
        <input type="hidden" name="T_ACCESO" value="<? print $T_ACCESO?>">
        <input type="hidden" name="ENTORNO" value="<? print $ENTORNO?>">
        <? 
        for ($RNUM=1;$RNUM<=$RES_ROOMS_QTY;++$RNUM) { 
            $aID = "RES_ROOM_".$RNUM."_ADULTS_QTY";
            if (isset($_SESSION['AVAILABILITY'][$aID])) {
                $aQTY = $_SESSION['AVAILABILITY'][$aID];
                print "<input type='hidden' name='{$aID}' value='{$aQTY}' />";
                $cID = "RES_ROOM_".$RNUM."_CHILDREN_QTY";
                if (isset($_SESSION['AVAILABILITY'][$cID])) {
                    $cQTY = (int)$_SESSION['AVAILABILITY'][$cID];
                    for ($CNUM=1;$CNUM<=4;++$CNUM) { 
                        $caID = "RES_ROOM_".$RNUM."_CHILD_AGE_".$CNUM;
                        $caQTY = $_SESSION['AVAILABILITY'][$caID];
                        if ($caQTY!=1) {
                            print "<input type='hidden' name='{$caID}' value='{$caQTY}' />";
                        } else {
                            print "<input type='hidden' name='RES_ROOM_{$RNUM}_CHILD_AGE_5' value='1' />";
                            $cQTY -= 1;
                        }
                    }
                    print "<input type='hidden' name='{$cID}' value='{$cQTY}' />";
                }
            }
        }    
        ?>

        <a href="javascript:void(0)" onClick="$('#frmModify').submit()" data-role="button" data-theme="c" data-mini="true" data-ajax="false"><? print _l("Modify","Modificar",$RES_LANGUAGE) ?></a>

    </form>
</div>
<?
}
?>