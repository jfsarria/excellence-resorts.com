<?
    $isLoggedIn = false;
    $LOGIN_EMAIL = isset($_REQUEST['LOGIN_EMAIL']) ? $_REQUEST['LOGIN_EMAIL'] : "";
    $LOGIN_PWD = isset($_REQUEST['LOGIN_PWD']) ? $_REQUEST['LOGIN_PWD'] : "";
    $LOGOUT = isset($_REQUEST['LOGOUT']) ? (int)$_REQUEST['LOGOUT'] : 0;
    $GUEST = array();

    if ($LOGIN_EMAIL!="" && $LOGIN_PWD!="") {
        $JSON_URL = $_SERVER_URL."/ibe/index.php?PAGE_CODE=ws.getGuest&EMAIL={$LOGIN_EMAIL}&PWD={$LOGIN_PWD}";
        //PRINT "\n1=>\n$JSON_URL\n";
        $JSON = file_get_contents($JSON_URL);
        $GUEST = json_decode($JSON, true);
        //print_r($GUEST);
        if (count($GUEST)!=0) {
            $_SESSION['AVAILABILITY']['RESERVATION']['GUEST'] = array(
                'GUEST_ID' => $GUEST['ID'],
                'TITLE' => $GUEST['TITLE'],
                'FIRSTNAME' => $GUEST['FIRSTNAME'],
                'LASTNAME' => $GUEST['LASTNAME'],
                'LANGUAGE' => $GUEST['LANGUAGE'],
                'ADDRESS' => $GUEST['ADDRESS'],
                'CITY' => $GUEST['CITY'],
                'STATE' => $GUEST['STATE'],
                'COUNTRY' => $GUEST['COUNTRY'],
                'ZIPCODE' => $GUEST['ZIPCODE'],
                'PHONE' => $GUEST['PHONE'],
                'EMAIL' => $GUEST['EMAIL'],
                'MAILING_LIST' => $GUEST['MAILING_LIST']
            );
            $isLoggedIn = true;
        }
    }
    if ($LOGOUT==1) {
        if (isset($_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM'])) unset($_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']);
        if (isset($_SESSION['AVAILABILITY']['RESERVATION']['GUEST'])) unset($_SESSION['AVAILABILITY']['RESERVATION']['GUEST']);
    }
    $isLoggedIn = (isset($_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['GUEST_ID']) && (int)$_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['GUEST_ID']!=0) ? true : false;
    if (isset($_SESSION['AVAILABILITY']['RESERVATION']['GUEST'])) $GUEST = $_SESSION['AVAILABILITY']['RESERVATION']['GUEST'];

?>

<div data-role="header" data-theme="x">
    <h1><? print _l("Guest Information","Información del Huésped",$RES_LANGUAGE) ?></h1>
    <a href="#" data-rel="back" data-direction="reverse" data-role="button" data-icon="back" data-iconpos="notext"></a>
</div>

<div data-role="content">	

    <? if (isset($_SESSION['AVAILABILITY']['RESERVATION']['ERROR'])) { ?>
        <div class="ui-collapsible-content ui-body-c ui-corner-all ui-collapsible-box">
            <div style="color:#990000;text-align:center"><b><? print implode("<br>",$_SESSION['AVAILABILITY']['RESERVATION']['ERROR']) ?></b></div>
        </div>
    <? } ?>

    <? if (($LOGIN_EMAIL!="" || $LOGIN_PWD!="") && count($GUEST)==0){ ?>
        <div class="ui-collapsible-content ui-body-c ui-corner-all ui-collapsible-box">
            <div style="color:#990000;text-align:center"><b><? print _l("E-mail and Password combination could not be found","La cuenta no se pudo encontrar, por favor revise su correo electrónico y contraseña",$RES_LANGUAGE) ?></b></div>
        </div>
    <? } ?>

    <? if (!$isLoggedIn) { ?>
        <div data-role="collapsible" data-theme="c" data-content-theme="c">
            <h3><? print _l("Already have an account?","Ya tiene una cuenta?",$RES_LANGUAGE) ?></h3>
            <div class="ui-collapsible-box">
                <label for="basic"><b><? print _l("E-mail","Correo electrónico",$RES_LANGUAGE) ?></b></label>
                <input type="text" id="LOGIN_EMAIL" value="<? if ($LOGIN_EMAIL!="") print $LOGIN_EMAIL ?>"  />
                <br>

                <label for="basic"><b><? print _l("Password","Contraseña",$RES_LANGUAGE) ?></b></label>
                <input type="text" id="LOGIN_PWD" value="<? if ($LOGIN_PWD!="") print $LOGIN_PWD ?>"  />
                <br>

                <a href="javascript:void(0)" onClick="ibemobile.reservation.login.submit()" data-role="button" data-theme="x"><? print _l("Login","Entrar",$RES_LANGUAGE) ?></a>
            </div>
        </div>
    <? } else { ?>
        <div class="ui-collapsible-content ui-body-c ui-corner-all ui-collapsible-box">
            <? print _l("Hello","Hola",$RES_LANGUAGE) ?> <? print $GUEST['FIRSTNAME']." ".$GUEST['LASTNAME'] ?>, <? print _l("before continuing please verify that all information below is correct.","antes de continuar por favor verifique que la información indicada abajo es correcta.",$RES_LANGUAGE) ?> <a href="javascript:void(0)" onClick="ibemobile.reservation.logout()"><? print _l("Logout","Salir",$RES_LANGUAGE) ?></a>
        </div>
    <? } ?>

    <form id="frmBook" method="get" action="reservation.submit.php" data-ajax="false">

    <div class="ui-collapsible-content ui-body-c ui-corner-top ui-collapsible-box ui-btn-up-c ui-info-hdr">
        <? print _l("Guest Contact Information","Información de contacto",$RES_LANGUAGE) ?>
    </div>
    <div class="ui-collapsible-content ui-body-c ui-corner-bottom ui-collapsible-box">
            
            <input type="hidden" name="TS" value="<? print strtotime("now") ?>" />

            <div style="text-align:right">*<? print _l("Required","Requerido",$RES_LANGUAGE) ?></div>
            <br>

            <label><? print _l("Salutation","Trato",$RES_LANGUAGE) ?></label>
            <select name="TITLE" id="TITLE">
                <option value="Mr." <? if (isset($GUEST['TITLE'])&&$GUEST['TITLE']=="Mr.") print "selected" ?>><? print _l("Mr","Sr",$RES_LANGUAGE) ?>.</option>
                <option value="Mrs." <? if (isset($GUEST['TITLE'])&&$GUEST['TITLE']=="Mrs.") print "selected" ?>><? print _l("Mrs","Sra",$RES_LANGUAGE) ?>.</option>
                <option value="Ms." <? if (isset($GUEST['TITLE'])&&$GUEST['TITLE']=="Ms.") print "selected" ?>><? print _l("Ms","Srita",$RES_LANGUAGE) ?>.</option>
            </select>
            <br>
            <label><? print _l("First Name","Nombre",$RES_LANGUAGE) ?> *</label>
            <input type="text" name="FIRSTNAME" id="FIRSTNAME" value="<? if (isset($GUEST['FIRSTNAME'])) print $GUEST['FIRSTNAME'] ?>"  />
            <br>
            <label><? print _l("Last Name","Apellido",$RES_LANGUAGE) ?> *</label>
            <input type="text" name="LASTNAME" id="LASTNAME" value="<? if (isset($GUEST['LASTNAME'])) print $GUEST['LASTNAME'] ?>"  />
            <br>
            <label><? print _l("E-mail","Correo electrónico",$RES_LANGUAGE) ?> *</label>
            <input type="text" name="EMAIL" id="EMAIL" value="<? if (isset($GUEST['EMAIL'])) print $GUEST['EMAIL'] ?>"  />
            <br>
            <label><? print _l("Phone Number","Número telefónico",$RES_LANGUAGE) ?> *</label>
            <input type="text" name="PHONE" id="PHONE" value="<? if (isset($GUEST['PHONE'])) print $GUEST['PHONE'] ?>"  />
            <br>
            <label><? print _l("Address","Dirección",$RES_LANGUAGE) ?> *</label>
            <input type="text" name="ADDRESS" id="ADDRESS" value="<? if (isset($GUEST['ADDRESS'])) print $GUEST['ADDRESS'] ?>"  />
            <br>
            <label><? print _l("Country","País",$RES_LANGUAGE) ?> *</label>
            <? 
                print $clsGlobal->getCountriesDropDown($db, array("ELE_ID"=>"RES_GUEST_COUNTRY")); 
            ?>
            <br>
            <label><? print _l("State / Province","Estado / Provincia",$RES_LANGUAGE) ?> *</label>
            <input type="text" id="RES_GUEST_STATE" class="med" title="State/Province" name="STATE" value="<? print isset($GUEST['STATE'])?$GUEST['STATE']:"" ?>">
            <? 
                $RES_LANGUAGE = !isset($RES_LANGUAGE)||empty($RES_LANGUAGE) ? "EN" : $RES_LANGUAGE;
                print $clsGlobal->getStatesDropDown($db, array("ELE_ID"=>"US_GUEST_STATES","CODE"=>"US"),$RES_LANGUAGE)."\n";
                print $clsGlobal->getStatesDropDown($db, array("ELE_ID"=>"CA_GUEST_STATES","CODE"=>"CA"),$RES_LANGUAGE)."\n";
                print $clsGlobal->getStatesDropDown($db, array("ELE_ID"=>"MX_GUEST_STATES","CODE"=>"MX"),$RES_LANGUAGE)."\n";
            ?>
            <br>
            <label><? print _l("City","Ciudad",$RES_LANGUAGE) ?> *</label>
            <input type="text" name="CITY" id="CITY" value="<? if (isset($GUEST['CITY'])) print $GUEST['CITY'] ?>"  />
            <br>
            <label><? print _l("Postal / ZIP Code","Código postal",$RES_LANGUAGE) ?> *</label>
            <input type="text" name="ZIPCODE" id="ZIPCODE" value="<? if (isset($GUEST['ZIPCODE'])) print $GUEST['ZIPCODE'] ?>"  />
            <br>
    </div>


    <div class="ui-collapsible-content ui-body-c ui-corner-top ui-collapsible-box ui-btn-up-c ui-info-hdr">
        <? print _l("Payment Information","Información de Pago",$RES_LANGUAGE) ?>
    </div>
    <div class="ui-collapsible-content ui-body-c ui-corner-bottom ui-collapsible-box">
        <div>
            <? if ($RES_LANGUAGE=="EN") { ?>
                Credit card details are collected in order to guarantee your booking.
                Please note the maximum number of reservations guaranteed under the same credit card is five (5). The full amount corresponding to your reservation will be charged in full thirty (30) days prior to your arrival day to the credit card provided during the booking process. Please see <a href="#" class="simpleDialog" rel="terms_conditions">Terms and Conditions</a> for more information.
            <? } else { ?>
                Cada reserva debe estar garantizada con un número de tarjeta de crédito.
                Por favor considere que el número máximo de reservaciones que pueden ser garantizadas con el mismo número de tarjeta de crédito es cinco (5). El precio contratado incluyendo cualquier tasa adicional será cargado en su totalidad treinta 30) días antes de la fecha de llegada a la tarjeta de crédito proporcionada durante el proceso de reservación. Ver <a href="#" class="simpleDialog" rel="terms_conditions">términos y condiciones</a> para mayor información. 
            <? } ?>
        </div>
        <br>
        <label style="font-size:16px"><? print _l("Total Reservation Charge","Cargo total por reservación",$RES_LANGUAGE) ?>: <strong>$<? print number_format($RESERVATION['RES_TOTAL_CHARGE']) ?></strong></label>
        <br><br>

        <label><? print _l("Credit Card Type","Tarjeta de Crédito",$RES_LANGUAGE) ?> *</label>
        <select id="CC_TYPE" name="CC_TYPE">
            <option value="Visa" <? if (isset($RESERVATION['PAYMENT']['CC_TYPE'])&&$RESERVATION['PAYMENT']['CC_TYPE']=="Visa") print "selected" ?>>Visa</option>
            <option value="MasterCard" <? if (isset($RESERVATION['PAYMENT']['CC_TYPE'])&&$RESERVATION['PAYMENT']['CC_TYPE']=="MasterCard") print "selected" ?>>MasterCard</option>
            <option value="AmEx" <? if (isset($RESERVATION['PAYMENT']['CC_TYPE'])&&$RESERVATION['PAYMENT']['CC_TYPE']=="AmEx") print "selected" ?>>American Express</option>
        </select>   
        <br>
        <label><? print _l("Credit Card Number","Número de Tarjeta",$RES_LANGUAGE) ?> *</label>
        <input type="text" name="CC_NUMBER" id="CC_NUMBER" value="<? if (isset($RESERVATION['PAYMENT']['CC_NUMBER'])) print $RESERVATION['PAYMENT']['CC_NUMBER'] ?>" />
        <br>
        <label><? print _l("Name on Credit Card","Nombre en la Tarjeta",$RES_LANGUAGE) ?> *</label>
        <input type="text" name="CC_NAME" id="CC_NAME" value="<? if (isset($RESERVATION['PAYMENT']['CC_NAME'])) print $RESERVATION['PAYMENT']['CC_NAME'] ?>" />
        <br>
        <label><? print _l("Security Code","Código de Seguridad",$RES_LANGUAGE) ?> *</label>
        <input type="text" name="CC_CODE" id="CC_CODE" value="<? if (isset($RESERVATION['PAYMENT']['CC_CODE'])) print $RESERVATION['PAYMENT']['CC_CODE'] ?>" />
        <br>
        <label><? print _l("Expiration Date","Fecha de Expiración",$RES_LANGUAGE) ?> *</label>
        <fieldset data-role="controlgroup" data-type="horizontal">
            <? 
            $EXP = (isset($RESERVATION['PAYMENT']['CC_EXP'])) ? explode("/",$RESERVATION['PAYMENT']['CC_EXP']) : array();
            ?>
            <input type="hidden" name="CC_EXP" id="CC_EXP" value="<? if (isset($RESERVATION['PAYMENT']['CC_EXP'])) print $RESERVATION['PAYMENT']['CC_EXP'] ?>">
            <select id="CC_EXP-month">
                <? for ($t=1;$t<=12;++$t) print "<option value='".($t<10?"0".$t:$t)."' ".(isset($EXP[0])&&(int)$EXP[0]==$t ? "selected" : "").">{$t}-"._fecha(date("M", strtotime(date("Y")."-{$t}-01")),$RES_LANGUAGE, true)."</option>"; ?>
            </select>
            <select id="CC_EXP-year">
                <? for ($t=date("Y");$t<date("Y")+10;++$t) print "<option value='{$t}' ".(isset($EXP[1])&&(2000+(int)$EXP[1])==$t ? "selected" : "").">{$t}</option>"; ?>
            </select>
        </fieldset>
        <br>
    </div>

    <div data-role="collapsible" data-theme="c" data-content-theme="c">
        <h3><? print _l("Billing address if different","Differente dirección de facturación?",$RES_LANGUAGE) ?></h3>
        <div class="ui-collapsible-box">
            <label><? print _l("Address","Dirección",$RES_LANGUAGE) ?></label>
            <input type="text" name="CC_BILL_ADDRESS" id="CC_BILL_ADDRESS" value="<? if (isset($RESERVATION['PAYMENT']['CC_BILL_ADDRESS'])) print $RESERVATION['PAYMENT']['CC_BILL_ADDRESS'] ?>"  />
            <br>
            <label><? print _l("Country","País",$RES_LANGUAGE) ?></label>
            <? 
                print $clsGlobal->getCountriesDropDown($db, array("ELE_ID"=>"RES_CC_BILL_COUNTRY","firstEmpty"=>"1"),$RES_LANGUAGE); 
            ?>
            <br>
            <label><? print _l("State / Province","Estado / Provincia",$RES_LANGUAGE) ?></label>
            <input type="text" id="RES_CC_BILL_STATE" name="RES_CC_BILL_STATE" value="<? if (isset($RESERVATION['PAYMENT']['RES_CC_BILL_STATE'])) print $RESERVATION['PAYMENT']['RES_CC_BILL_STATE'] ?>">
            <? 
                print $clsGlobal->getStatesDropDown($db, array("ELE_ID"=>"US_PAYMENT_STATES","CODE"=>"US"),$RES_LANGUAGE)."\n";
                print $clsGlobal->getStatesDropDown($db, array("ELE_ID"=>"CA_PAYMENT_STATES","CODE"=>"CA"),$RES_LANGUAGE)."\n";
                print $clsGlobal->getStatesDropDown($db, array("ELE_ID"=>"MX_PAYMENT_STATES","CODE"=>"MX"),$RES_LANGUAGE)."\n";
            ?>
            <br>
            <label><? print _l("City","Ciudad",$RES_LANGUAGE) ?></label>
            <input type="text" name="CC_BILL_CITY" id="CC_BILL_CITY" value="<? if (isset($RESERVATION['PAYMENT']['CC_BILL_CITY'])) print $RESERVATION['PAYMENT']['CC_BILL_CITY'] ?>"  />
            <br>
            <label><? print _l("Postal / ZIP Code","Código postal",$RES_LANGUAGE) ?></label>
            <input type="text" name="CC_BILL_ZIPCODE" id="CC_BILL_ZIPCODE" value="<? if (isset($RESERVATION['PAYMENT']['CC_BILL_ZIPCODE'])) print $RESERVATION['PAYMENT']['CC_BILL_ZIPCODE'] ?>"  />
            <br>            
        </div>
    </div>

    <div data-role="collapsible" data-collapsed="true" data-theme="c" data-content-theme="c">
        <h3><? print _l("Optional Room Preferences","Preferencias",$RES_LANGUAGE) ?></h3>
        <div class="ui-collapsible-box">
        <? 
        $ROOM_NUM = 0;
        foreach ($RESERVATION['RES_ROOMS_SELECTED'] AS $ROOM_KEY => $ROOM_ID) {
            //$ROOM = $RES_ITEMS[$ROOM_ID]; 
            $STYLE = ($RES_ROOMS_QTY == 1) ? "style='display:none'" : "";
            $ROOM = (isset($RESERVATION['ROOMS']) && isset($RESERVATION['ROOMS'][$ROOM_NUM])) ? $RESERVATION['ROOMS'][$ROOM_NUM] : array();
            ?>
            <div class="ui-opt-room-hdr"><? print _l("Room","Habitación",$RES_LANGUAGE) ?> <? print ($ROOM_NUM+1).": ".$RES_ITEMS[$ROOM_ID]['NAME_'.$RES_LANGUAGE] ?></div>
            <div style='display:none'>
                <label><? print _l("Salutation","Trato",$RES_LANGUAGE) ?></label>
                <select name="GUEST_TITLE_ROOM_<? print $ROOM_KEY ?>">
                    <option value="Mr."><? print _l("Mr","Sr",$RES_LANGUAGE) ?>.</option>
                    <option value="Mrs."><? print _l("Mrs","Sra",$RES_LANGUAGE) ?>.</option>
                    <option value="Ms."><? print _l("Ms","Srita",$RES_LANGUAGE) ?>.</option>
                </select>
                <br>
                <label><? print _l("First Name","Nombre",$RES_LANGUAGE) ?></label>
                <input type="text" name="GUEST_FIRSTNAME_ROOM_<? print $ROOM_KEY ?>" value=""  />
                <br>
                <label><? print _l("Last Name","Apellido",$RES_LANGUAGE) ?></label>
                <input type="text" name="GUEST_LASTNAME_ROOM_<? print $ROOM_KEY ?>" value=""  />
                <br>
            </div>
            <label><? print _l("Bed type preferences","Preferencia en tipo de cama",$RES_LANGUAGE) ?></label>
            <? 
                print (isset($RES_ITEMS[$ROOM_ID])) 
                ? 
                $clsGlobal->getBedTypesDropDown($db, array("ELE_ID"=>"GUEST_BEDTYPE_ROOM_{$ROOM_KEY}","BEDS"=>$RES_ITEMS[$ROOM_ID]['BEDS'],"BED_TYPES"=>$RES_ITEMS['PROPERTY']['BED_TYPES'],"SELECTED"=>isset($ROOM['GUEST_BEDTYPE']) ? $ROOM['GUEST_BEDTYPE']:""),$RES_LANGUAGE)
                : 
                $clsRooms->getBedTypesDropDown($db, array("ELE_ID"=>"GUEST_BEDTYPE_ROOM_{$ROOM_KEY}","PROP_ID"=>$RES_PROP_ID,"SELECTED"=>isset($ROOM['GUEST_BEDTYPE']) ? $ROOM['GUEST_BEDTYPE']:""),$RES_LANGUAGE) 
            ?>
            <br>
            <? if ($RES_PROP_ID==4) { 
                $VALUE = isset($ROOM['GUEST_BABYCRIB']) ? $ROOM['GUEST_BABYCRIB']:""
                ?>
                <label><? print _l("Baby Crib","Cuna en el cuarto",$RES_LANGUAGE) ?></label>
                <select name='GUEST_BABYCRIB_<? print $ROOM_KEY ?>'>
                    <option value='' <? if ((int)$VALUE!=1) print "selected" ?>>No</option>
                    <option value='1' <? if ((int)$VALUE==1) print "selected" ?>><? print _l("Yes","Si",$RES_LANGUAGE) ?></option>
                </select>
                <br>
            <? } ?>
            <label><? print _l("Smoking Preference","Preferencia de Habitación",$RES_LANGUAGE) ?></label>
            <? print $clsGlobal->getSmokingPrefeDropDown($db, array("ELE_ID"=>"GUEST_SMOKING_ROOM_{$ROOM_KEY}","SELECTED"=>isset($ROOM['GUEST_SMOKING']) ? $ROOM['GUEST_SMOKING']:""),$RES_LANGUAGE) ?>
            <br>
            <label><? print _l("Special Occasion","Ocasión Especial",$RES_LANGUAGE) ?></label>
            <? print $clsGlobal->getSpecialOccasionDropDown($db, array("ELE_ID"=>"GUEST_OCCASION_ROOM_{$ROOM_KEY}","SELECTED"=>isset($ROOM['GUEST_OCCASION']) ? $ROOM['GUEST_OCCASION']:""),$RES_LANGUAGE) ?>
            <br>

            <hr style="color:white;border-color:#999999">
            <br>
            <?
            ++$ROOM_NUM;
        }
        ?>
        </div>
    </div>

    <div data-role="collapsible" data-collapsed="true" data-theme="c" data-content-theme="c">
        <h3><? print _l("Airport Pickup","Recoger del aeropuerto",$RES_LANGUAGE) ?></h3>
        <div class="ui-collapsible-box">
            <div><? print _l("Additional fee will apply. Land transfers must be requested at least 48 hours prior to arrival.","Costo adicional. Servicio de transporte debe ser solicitado por lo menos 48 horas antes de la fecha de llegada.",$RES_LANGUAGE) ?></div>
            <br>
            <table>
            <tr>
                <td>
                    <label><? print _l("Airline","Aerolínea",$RES_LANGUAGE) ?></label>
                    <input type="text" name="AIRLINE" value="<? if (isset($RESERVATION['AIRLINE'])) print $RESERVATION['AIRLINE'] ?>"  />            
                </td>
                <td>
                    <label><? print _l("Flight number","Número de Vuelo",$RES_LANGUAGE) ?></label>
                    <input type="text" name="FLIGHT" value="<? if (isset($RESERVATION['FLIGHT'])) print $RESERVATION['FLIGHT'] ?>"  />            
                </td>
            </tr>
            </table>
            <br>
        </div>
    </div>

    <div class="ui-collapsible-content ui-body-c ui-corner-all ui-collapsible-box">
        <label><? print _l("Expected hotel arrival time","Hora de llegada",$RES_LANGUAGE) ?></label>

        <input type="text" name="ARRIVAL_TIME" value="<? if (isset($RESERVATION['ARRIVAL_TIME'])) print $RESERVATION['ARRIVAL_TIME'] ?>" /><br>
        <select name="ARRIVAL_AMPM">
            <option <? print (isset($RESERVATION['ARRIVAL_AMPM'])&&$RESERVATION['ARRIVAL_AMPM']=='AM')?"selected":"" ?>>AM</option>
            <option <? print (isset($RESERVATION['ARRIVAL_AMPM'])&&$RESERVATION['ARRIVAL_AMPM']=='PM')?"selected":"" ?>>PM</option>
        </select>

    </div>

    <div class="ui-collapsible-content ui-body-c ui-corner-all ui-collapsible-box">
        <a href="#" class="simpleDialog" rel="terms_conditions"><? print _l("Tap here for","",$RES_LANGUAGE) ?> <? print $RES_ITEMS['PROPERTY']['NAME'] ?> <? print _l("Terms and Conditions","Términos y Condiciones",$RES_LANGUAGE) ?></a>
        <br><br>
		<input type="checkbox" name="checkbox-agree" id="checkbox-agree" class="custom" />
		<label for="checkbox-agree"><? print _l("I have read and agree to the","He leído y acepto los",$RES_LANGUAGE) ?> <? print $RES_ITEMS['PROPERTY']['NAME'] ?>  <? print _l("Terms and Conditions","Términos y Condiciones",$RES_LANGUAGE) ?></label>
    </div>
    <br>

    <a href="javascript:void(0)" onClick="$('#frmBook').submit()" data-role="button" data-theme="x" data-ajax="false"><? print _l("Book Now","Reserve Ahora",$RES_LANGUAGE) ?></a>

    </form>

    <div id="terms_conditions" style="display:none">
        <div style="text-align:right"><a href="#" class="close" data-role="button" data-icon="delete" data-inline="true" data-mini="true" data-iconpos="notext"></a></div>
        <div>
        <? include "reservation.terms-conditions.php"; ?>
        </div>
        <div style="text-align:right"><a href="#" class="close" data-role="button" data-icon="delete" data-inline="true" data-mini="true" data-iconpos="notext"></a></div>
    </div>

    <script>
        $('.simpleDialog').simpleDialog({
                            showCloseLabel: false,
                            width:"250px",
                            opacity:"0.8"
                        });
    </script>

    <script>
    $("body").live('pageinit', function(event) {
        <? include $_SERVER['DOCUMENT_ROOT']."/ibe/inc/mods/m.reserv.room.guest.scripts.php"; ?>

        // --- --- ---

        <? 
        if (isset($RESERVATION['PAYMENT']['CC_BILL_COUNTRY'])) print "$('#RES_CC_BILL_COUNTRY').val('{$RESERVATION['PAYMENT']['CC_BILL_COUNTRY']}') \n";
        ?>
        <? include $_SERVER['DOCUMENT_ROOT']."/ibe/inc/mods/m.reserv.room.payment.scripts.php"; ?>
        $('#frmBook').unbind("submit").bind("submit", function(e) {
            return ibemobile.validate.reservation();
        });
    });
    </script>

</div>
