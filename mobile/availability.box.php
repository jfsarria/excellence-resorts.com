<?
    //print "<pre>";print_r($_GET);print "</pre>";

    $RES_LANGUAGE = (isset($_GET['RES_LANGUAGE'])&&$_GET['RES_LANGUAGE']!="") ? $_GET['RES_LANGUAGE'] : "EN";
    $RES_PROP_ID = (isset($_GET['RES_PROP_ID'])&&(int)$_GET['RES_PROP_ID']!=0) ? (int)$_GET['RES_PROP_ID'] : 4;

    $RES_CHECK_IN = (isset($_GET['RES_CHECK_IN'])&&$_GET['RES_CHECK_IN']!="") ? $_GET['RES_CHECK_IN'] : $_TODAY;
    $RES_CHECK_OUT = (isset($_GET['RES_CHECK_OUT'])&&$_GET['RES_CHECK_OUT']!="") ? $_GET['RES_CHECK_OUT'] : $_TOMORROW;
    $a_RES_CHECK_IN = explode("-",$RES_CHECK_IN);
    $a_RES_CHECK_OUT = explode("-",$RES_CHECK_OUT);
    $RES_NIGHTS = (isset($_GET['RES_NIGHTS'])&&$_GET['RES_NIGHTS']!="") ? $_GET['RES_NIGHTS'] : 1;

    $RES_ROOMS_QTY = (isset($_GET['RES_ROOMS_QTY'])&&(int)$_GET['RES_ROOMS_QTY']!=0) ? (int)$_GET['RES_ROOMS_QTY'] : 1;

    $RES_COUNTRY_CODE = (isset($_GET['RES_COUNTRY_CODE'])&&$_GET['RES_COUNTRY_CODE']!="") ? $_GET['RES_COUNTRY_CODE'] : "";
    $RES_SPECIAL_CODE = (isset($_GET['RES_SPECIAL_CODE'])&&$_GET['RES_SPECIAL_CODE']!="") ? $_GET['RES_SPECIAL_CODE'] : "";

 //   $RES_COUPON_CODE = (isset($_GET['RES_COUPON_CODE'])&&$_GET['RES_COUPON_CODE']!="") ? $_GET['RES_COUPON_CODE'] : "";

    $T_ACCESO = (isset($_GET['T_ACCESO'])&&$_GET['T_ACCESO']!="") ? $_GET['T_ACCESO'] : "";
    $ENTORNO = (isset($_GET['ENTORNO'])&&$_GET['ENTORNO']!="") ? $_GET['ENTORNO'] : "";

?>

<div data-role="header" data-theme="x">
    <h1><? print _l("Check Availability","Ver Disponibilidad",$RES_LANGUAGE) ?></h1>
    <a href="#" data-rel="back" data-direction="reverse" data-role="button" data-icon="back" data-iconpos="notext"></a>
</div>

<div data-role="content">

    <form id="frmCheckAvailability" action="/mobile/availability.php" method="get" data-ajax="false">

    <div id="check-availability" class="ui-collapsible-content ui-body-c ui-corner-all ui-collapsible-box">

        <input type="hidden" name="TS" value="<? print strtotime("now") ?>" />
        <input type="hidden" name="PAGE_CODE" id="PAGE_CODE" value="ws.availability" />
        <input type="hidden" name="ACTION" value="SUBMIT" />
        <input type="hidden" name="RES_IN_THE_FUTURE" value="0" />
        <input type="hidden" name="RES_USERTYPE[]" value="1" />
        <input type="hidden" name="RES_LANGUAGE" value="<? print $RES_LANGUAGE ?>" />
        <input type="hidden" name="RES_DATE" value="<? print $_TODAY ?>" />
        <input type="hidden" name="RES_NIGHTS" id="RES_NIGHTS" value="<? print $RES_NIGHTS ?>" />
        <input type="hidden" name="RES_CHECK_IN" id="RES_CHECK_IN" value="<? print $RES_CHECK_IN ?>" />
        <input type="hidden" name="RES_CHECK_OUT" id="RES_CHECK_OUT" value="<? print $RES_CHECK_OUT ?>" />
        <input type="hidden" name="T_ACCESO" value="<? print $T_ACCESO?>">
        <input type="hidden" name="ENTORNO" value="<? print $ENTORNO?>">
        <div style="<? if ($RES_PROP_ID==4) print "display:none" ?>">
            <label for="RES_PROP_ID" class="select"><? print _l("Select Destination","Destino",$RES_LANGUAGE) ?></label>
            <select name="RES_PROP_ID" id="RES_PROP_ID">
                <? if ($RES_PROP_ID==4) { ?>
                    <option value="4" <? if ($RES_PROP_ID==4) print "selected" ?>>The Beloved Hotel Playa Mujeres</option>
                <? } else { ?>
                    <option value="1" <? if ($RES_PROP_ID==1) print "selected" ?>>Riviera Cancun, MX</option>
                    <option value="2" <? if ($RES_PROP_ID==2) print "selected" ?>>Playa Mujeres, MX</option>
                    <option value="3" <? if ($RES_PROP_ID==3) print "selected" ?>>Punta Cana, DR</option>
                    <option value="6" <? if ($RES_PROP_ID==6) print "selected" ?>>El Carmen, DR</option>
                    <option value="7" <? if ($RES_PROP_ID==7) print "selected" ?>>Oyster Bay, JM</option>
                <? } ?>
            </select>
            <br>
        </div>

        <fieldset data-role="controlgroup" data-type="horizontal">
            <legend><? print _l("Check In","Llegada",$RES_LANGUAGE) ?></legend>
            
            <label for="check-in-month">Month</label>
            <select id="check-in-month">
                <?
                    for ($t=1;$t<=12;++$t) {
                        $selected = (int)$a_RES_CHECK_IN[1]==$t ? "selected" : "";
                        print "<option value='".($t<10?"0".$t:$t)."' {$selected}>"._fecha(date("M", strtotime(date("Y")."-{$t}-01")),$RES_LANGUAGE, true)."</option>";
                    }
                ?>
            </select>
            <label for="check-in-day">Day</label>
            <select id="check-in-day">
                <?
                    for ($t=1;$t<=31;++$t) {
                        $selected = (int)$a_RES_CHECK_IN[2]==$t ? "selected" : "";
                        print "<option value='".($t<10?"0".$t:$t)."' {$selected}>{$t}</option>";
                    }
                ?>
            </select>
            <label for="check-in-year">Year</label>
            <select id="check-in-year">
                <?
                    for ($t=date("Y");$t<date("Y")+3;++$t) {
                        $selected = (int)$a_RES_CHECK_IN[0]==$t ? "selected" : "";
                        print "<option value='{$t}' {$selected}>{$t}</option>";
                    }
                ?>
            </select>
        </fieldset>
        <fieldset data-role="controlgroup" data-type="horizontal">
            <legend><? print _l("Check Out","Salida",$RES_LANGUAGE) ?></legend>

            <label for="check-out-month">Month</label>
            <select id="check-out-month">
                <?
                    for ($t=1;$t<=12;++$t) {
                        $selected = (int)$a_RES_CHECK_OUT[1]==$t ? "selected" : "";
                        print "<option value='".($t<10?"0".$t:$t)."' {$selected}>"._fecha(date("M", strtotime(date("Y")."-{$t}-01")),$RES_LANGUAGE, true)."</option>";
                    }
                ?>
            </select>
            <label for="check-out-day">Day</label>
            <select id="check-out-day">
                <?
                    for ($t=1;$t<=31;++$t) {
                        $selected = (int)$a_RES_CHECK_OUT[2]==$t ? "selected" : "";
                        print "<option value='".($t<10?"0".$t:$t)."' {$selected}>{$t}</option>";
                    }
                ?>
            </select>
            <label for="check-out-year">Year</label>
            <select id="check-out-year">
                <?
                    for ($t=date("Y");$t<date("Y")+3;++$t) {
                        $selected = (int)$a_RES_CHECK_OUT[0]==$t ? "selected" : "";
                        print "<option value='{$t}' {$selected}>{$t}</option>";
                    }
                ?>
            </select>
        </fieldset>
        <br>

        <select name="RES_ROOMS_QTY" id="RES_ROOMS_QTY" onChange="ibemobile.availability.roomSelector()">
            <option value="1" <? if ($RES_ROOMS_QTY==1) print "selected" ?>>1 <? print _l("Room","Habitación",$RES_LANGUAGE) ?></option>
            <option value="2" <? if ($RES_ROOMS_QTY==2) print "selected" ?>>2 <? print _l("Rooms","Habitaciones",$RES_LANGUAGE) ?></option>
            <option value="3" <? if ($RES_ROOMS_QTY==3) print "selected" ?>>3 <? print _l("Rooms","Habitaciones",$RES_LANGUAGE) ?></option>
        </select>
        <br>

        <div id="room_qtys">

        <? for ($RNUM=1;$RNUM<=3;++$RNUM) { ?>
            <div class="room_box" id="room_box_<? print $RNUM ?>" style="<? print $RNUM!=1 ? "margin-top:20px;" : "" ?><? print $RNUM<=$RES_ROOMS_QTY ? "display:block" : "display:none" ?>">
                <?
                    $ROOM_ADULTS_QTY = (isset($_GET["RES_ROOM_{$RNUM}_ADULTS_QTY"])&&(int)$_GET["RES_ROOM_{$RNUM}_ADULTS_QTY"]!=0) ? (int)$_GET["RES_ROOM_{$RNUM}_ADULTS_QTY"] : 2;
                    $ROOM_CHILDREN_QTY = (isset($_GET["RES_ROOM_{$RNUM}_CHILDREN_QTY"])) ? (int)$_GET["RES_ROOM_{$RNUM}_CHILDREN_QTY"] : 0;
                    $ROOM_CHILD_AGE_5 = (isset($_GET["RES_ROOM_{$RNUM}_CHILD_AGE_5"])) ? (int)$_GET["RES_ROOM_{$RNUM}_CHILD_AGE_5"] : 0;
                ?>
                <? if ($RES_PROP_ID==4) { ?>
                    <fieldset data-role="controlgroup" data-type="horizontal" style="margin-bottom:5px;">
                        <select name="RES_ROOM_<? print $RNUM ?>_ADULTS_QTY" id="RES_ROOM_<? print $RNUM ?>_ADULTS_QTY" onChange="ibemobile.availability.roomAdultsSelector('<? print $RNUM ?>')">
                            <? 
                            for ($t=1;$t<=4;++$t) { 
                                $selected = $ROOM_ADULTS_QTY == $t ? "selected" : "";
                                print "<option value='{$t}' {$selected}>{$t} ".(_l("Adult","Adulto",$RES_LANGUAGE)).($t!=1?"s":"")."</option>";
                            } 
                            ?>
                        </select>
                        <select name="RES_ROOM_<? print $RNUM ?>_CHILDREN_QTY" id="RES_ROOM_<? print $RNUM ?>_CHILDREN_QTY" onChange="ibemobile.availability.roomChildrenSelector(<? print $RNUM ?>)">
                            <option value="0"><? print _l("Child","Niño",$RES_LANGUAGE) ?> 4-12</option>
                            <? 
                            for ($t=1;$t<=4;++$t) { 
                                $selected = $ROOM_CHILDREN_QTY == $t ? "selected" : "";
                                print "<option value='{$t}' {$selected}>{$t} ".(_l("Child","Niño",$RES_LANGUAGE)).($t!=1?_l("ren","s",$RES_LANGUAGE):"")."</option>";
                            } 
                            ?>
                        </select>
                        <select name="RES_ROOM_<? print $RNUM ?>_CHILD_AGE_5">
                            <option value="0"><? print _l("Infants","Bebés",$RES_LANGUAGE) ?></option>
                            <option value="1" <? if ($ROOM_CHILD_AGE_5==1) print "selected" ?>>1 <? print _l("Infant","Bebé",$RES_LANGUAGE) ?></option>
                        </select>
                    </fieldset>

                    <? for ($CNUM=1;$CNUM<=4;++$CNUM) { ?>
                        <div class="room_box_children_age" id="room_box_<? print $RNUM ?>_children_<? print $CNUM ?>_age" style="<? print $CNUM!=1 ? "margin-top:5px;" : "" ?><? print $CNUM<=$ROOM_CHILDREN_QTY ? "display:block" : "display:none" ?>"><?
                            $RES_ROOM_CHILD_AGE = (isset($_GET["RES_ROOM_{$RNUM}_CHILD_AGE_{$CNUM}"])) ? (int)$_GET["RES_ROOM_{$RNUM}_CHILD_AGE_{$CNUM}"] : 0;
                            $eID = "RES_ROOM_{$RNUM}_CHILD_AGE_{$CNUM}";
                            ?>
                            <fieldset data-role="controlgroup" data-type="horizontal">
                                <label for="<? print $eID ?>">Child <? print $CNUM ?> Age</label>
                                <select class="room_box_children_age" name="RES_ROOM_<? print $RNUM ?>_CHILD_AGE_<? print $CNUM ?>" id="<? print $eID ?>" >
                                    <? 
                                    for ($t=4;$t<=12;++$t) { 
                                        $selected = $RES_ROOM_CHILD_AGE == $t ? "selected" : "";
                                        print "<option value='{$t}' {$selected}>{$t} Year".($t!=1?"s":"")."</option>";
                                    }
                                    ?>
                                </select>
                            </fieldset>
                        </div>
                    <? } ?>
                <? } else { ?>
                    <select name="RES_ROOM_<? print $RNUM ?>_ADULTS_QTY" id="RES_ROOM_<? print $RNUM ?>_ADULTS_QTY">
                        <? 
                        for ($t=1;$t<=3;++$t) { 
                            $selected = $ROOM_ADULTS_QTY == $t ? "selected" : "";
                            print "<option value='{$t}' {$selected}>{$t} ".(_l("Guest","Huesped",$RES_LANGUAGE)).($t!=1?_l("s","es",$RES_LANGUAGE):"")."</option>";
                        } 
                        ?>
                    </select>
                <? } ?>
            </div>
        <? } ?>
        </div>

        <br>
        <label for="basic"><b><? print _l("Promo Code","Código Promocional",$RES_LANGUAGE) ?></b></label>
        <input type="text" name="RES_SPECIAL_CODE" id="RES_SPECIAL_CODE" value="<? print $RES_SPECIAL_CODE ?>"  />

        <br>
        <!--
        <label for="basic"><b><? print _l("Coupon", "Cupón", $RES_LANGUAGE) ?></b></label>
        <input type="text" name="RES_COUPON_CODE" id="RES_COUPON_CODE" value="<? print $RES_COUPON_CODE ?>"  />
        -->
        <br>
        <a href="javascript:void(0)" onClick="if (ibemobile.availability.submit()) $('#frmCheckAvailability').submit()" data-role="button" data-theme="x"><? print _l("Check Availability","Ver Disponibilidad",$RES_LANGUAGE) ?></a>

    </div>

    </form>

  

</div>