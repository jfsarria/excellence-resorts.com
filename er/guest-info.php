
<input type="hidden" id="GUEST_ID" value="<? print $_COOKIE['GUEST_LOGGED']?$RES_GUEST['ID']:0 ?>">
<input type="hidden" id="TA_ID" value="<? print $_COOKIE['TA_LOGGED']?$RES_TA['ID']:0 ?>">
<input type="hidden" id="RES_LANGUAGE" value="<?=$results["RES_LANGUAGE"]?>">

<div id="guest-info" class="hidden">

    <div id="hello-guest" class="form_block" style="display:<? print $_COOKIE['GUEST_LOGGED']?"block":"none"; ?>">
        <div class="inner">
            <div class="descr">
                <p><?=ln("Hello","Hola")?> <span id="login_name"><? print $_COOKIE['GUEST_LOGGED']?$RES_GUEST['FIRSTNAME']." ".$RES_GUEST['LASTNAME']:""; ?></span>, <?=ln("before continuing please verify that all information below is correct","antes de continuar, por favor verifique que toda la información a continuación es correcta")?></p>
                <div class="logout"><a href="javascript:void(0)" onclick="logout()"><?=ln("LOG OUT","SALIR")?></a></div>
            </div>
        </div>
    </div>

    <div id="hello-ta" class="form_block" style="display:<? print $_COOKIE['TA_LOGGED']?"block":"none"; ?>">
        <div class="inner">
            <p>
                <?=ln("Hello","")?> <strong id="ta_login_name"><? print $_COOKIE['TA_LOGGED']?$RES_TA['FIRSTNAME']." ".$RES_TA['LASTNAME']:""; ?></strong>, <?=ln("please enter your client's contact information below, or","por favor ingrese la información de contacto de su cliente a continuación, o")?> <a href="javascript:void(0)" onclick="get_ta_clients()"><?=ln("select a client from previous booking","seleccionar un cliente de una reservación anterior")?></a>.
            </p>

            <p id="clients_data" style="display:none"></p>

            <p id="clear_data" style="display:none">
                <a href="javascript:void(0)" onclick="clear_data()"><?=ln("Click here to clear data</a> and be able to select new client","Haga clic aquí para borrar los datos</a> y ser capaz de seleccionar un nuevo cliente")?>.
            </p>

            <div class="logout"><a href="javascript:void(0)" onclick="logout()"><?=ln("LOG OUT","SALIR")?></a></div>
        </div>
    </div>

    <div id="login-guest" class="form_block" style="display:<? print !$_COOKIE['GUEST_LOGGED']&&!$_COOKIE['TA_LOGGED']?"block":"none"; ?>">
        <div class="inner">

            <div class="descr">
                <?=ln("Already have an account? Login for faster booking","¿Ya tienes una cuenta? Inicie una sesión para una reservación más rápida")?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span><a id="lbl-login" href="javascript:void(0)" onclick="$('#login_box').toggle();$('#lbl-login').hide()"><?=ln("LOG IN","ENTRAR")?></a>
            </div>

            <div style="display:none;" id="login_box">
                <table class="w470" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="w205">
                        <input type="text" id="EMAIL"/><br />
                        <span><?=ln("E-mail","E-mail")?></span>
                    </td>
                    <td class="w205">
                        <input type="password" id="PWD"/>
                        <span><?=ln("Password","Contraseña")?> | <a href="javascript:void(0)" onclick="popover_open($(this),'popover_pwd')"><?=ln("Forgot password","Olvidó la contraseña")?>?</a></span>
                    </td>
                    <td width="w60"><a href="javascript:void(0)" onclick="login(true)"><?=ln("LOG IN","ENTRAR")?></a></td>
                </tr>
                </table>

            </div>
        </div>
    </div>


    <div class="sec-lbl"><?=ln("GUEST INFORMATION","INFORMACIÓN DEL HUESPED")?></div>
    <div class="form_block">
        <div class="inner">

            <table class="w470" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td class="w60" valign="top">
                    <select id="GUEST_TITLE">
                        <option <? print $_COOKIE['GUEST_LOGGED']&&$RES_GUEST['TITLE']=="Mr."?"selected":"" ?>><?=ln("Mr.","Sr.")?></option>
                        <option <? print $_COOKIE['GUEST_LOGGED']&&$RES_GUEST['TITLE']=="Mrs."?"selected":"" ?>><?=ln("Mrs.","Sra.")?></option>
                        <option <? print $_COOKIE['GUEST_LOGGED']&&$RES_GUEST['TITLE']=="Ms."?"selected":"" ?>><?=ln("Ms.","Sra.")?></option>
                    </select>
                    <span></span>              
                </td>
                <td class="w205">
                    <input type="text" id="GUEST_FIRSTNAME" name="GUEST_FIRSTNAME" value="<? print $_COOKIE['GUEST_LOGGED']?$RES_GUEST['FIRSTNAME']:"" ?>">
                     <input type="hidden" name="T_ACCESO" value="<? print isset($results['T_ACCESO'])?$results['T_ACCESO']:''?>">
                         <input type="hidden" name="ENTORNO" value="<? print isset($results['ENTORNO'])?$results['ENTORNO']:''?>">
                    <span><?=ln("First Name","Nombre")?>*</span>
                </td>
                <td class="w205">
                    <input type="text" id="GUEST_LASTNAME" name="GUEST_LASTNAME" value="<? print $_COOKIE['GUEST_LOGGED']?$RES_GUEST['LASTNAME']:"" ?>">
                    <span><?=ln("Last Name","Apellido")?>*</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td class="w205">
                    <input type="text" id="GUEST_EMAIL" name="GUEST_EMAIL" value="<? print $_COOKIE['GUEST_LOGGED']?$RES_GUEST['EMAIL']:"" ?>"><br />
                    <span><?=ln("E-mail","Correo Electrónico")?>*</span>
                </td>
                <td class="w205">
                    <input type="text" id="GUEST_EMAIL_CONFIRM" name="GUEST_EMAIL_CONFIRM" value="">
                    <span><?=ln("Confirm E-mail","Confirmar Correo")?>*</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td class="w205">
                    <input type="text" id="GUEST_PHONE" name="GUEST_PHONE" value="<? print $_COOKIE['GUEST_LOGGED']?$RES_GUEST['PHONE']:"" ?>">
                    <span><?=ln("Phone Number","Teléfono")?>*</span>
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="w410" colspan="2">
                    <input type="text" id="GUEST_ADDRESS" name="GUEST_ADDRESS" value="<? print $_COOKIE['GUEST_LOGGED']?$RES_GUEST['ADDRESS']:"" ?>">
                    <span><?=ln("Address","Dirección")?>*</span>                
                </td>
            </tr>
            <tr>
                <td></td>
                <td class="w205">
                    <input type="text" id="GUEST_CITY" name="GUEST_CITY" value="<? print $_COOKIE['GUEST_LOGGED']?$RES_GUEST['CITY']:"" ?>">
                    <span><?=ln("City","Ciudad")?>*</span>
                </td>
                <td class="w205">
                    <select id="GUEST_COUNTRY" name="GUEST_COUNTRY" onchange="country_changed($(this))">
                        <? include "select-country.php"; ?>
                    </select>
                    <span><?=ln("Country","País")?>*</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td class="w205">
                    <? 
                        $isBilling = false;
                        include "select-state.php"; 
                    ?>
                    <input type="text" id="GUEST_STATE" name="GUEST_STATE" class="states-list hide" value="<? print $_COOKIE['GUEST_LOGGED']?$RES_GUEST['STATE']:"" ?>">
                    <span><?=ln("State / Province","Estado / Provincia")?>*</span>
                </td>
                <td class="w205">
                    <input type="text" id="GUEST_ZIPCODE" name="GUEST_ZIPCODE" value="<? print $_COOKIE['GUEST_LOGGED']?$RES_GUEST['ZIPCODE']:"" ?>">
                    <span><?=ln("Postal / ZIP Code","Código Postal")?>*</span>
                </td>
            </tr>
            </table>
            <div class="lbl-required">*<?=ln("Required","Requerido")?></div>

            <?
            if ($_COOKIE['GUEST_LOGGED']) {
                print "
                <script>
                    $('#GUEST_COUNTRY').val('{$RES_GUEST['COUNTRY']}');
                    $('#GUEST_COUNTRY').change();
                    $('#GUEST_STATE').val('{$RES_GUEST['STATE']}');
                    $('.states-list').val('{$RES_GUEST['STATE']}');
                </script>
                ";
            }
            ?>

        </div>
    </div>

    <div id='add_transfer_h2_lbl' class="sec-lbl"><?=ln("ADD PRIVATE AIRPORT TRANSFER","ADICIONE SERVICIO DE TRANSPORTACIÓN PRIVADA")?></div>
    <div class="form_block">
        <div class="inner">

            <div id="transfer_overview">
                <div class='transfer_overview_txt'></div>
                <div id="OVERVIEW_CARS_LIST"><div><center><img src="loading-small.gif"></center></div></div>
            </div>

            <div class="inner" id="transfer_make">

                <div class='transfer_overview_txt'></div>

                <div class="line add_pickup" style="overflow: visible" id="expected_arrival">
                    <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="tdlabel"><?=ln("Expected hotel arrival time","Hora de llegada")?></td>
                        <td style="padding-left:20px"><input type="text" id="RES_ARRIVAL_TIME" class="w57"></td>
                        <td>
                            <table class="ampm_tbl" border="0" cellpadding="0" cellspacing="0">
                            <tr><td><input name="arrival_time" id="RES_ARRIVAL_AM" type="radio" value="AM" checked></td><td>&nbsp;&nbsp;AM</td></tr>
                            <tr><td><input name="arrival_time" type="radio" value="PM"></td><td>&nbsp;&nbsp;PM</td></tr>
                            </table>                        
                        </td>
                    </tr>
                    </table>
                </div>

                <div class="line add_pickup" style="clear:both">
                    <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="tdlabel" nowrap><?=ln("Airport Pickup","Servicio de transporte")?></td>
                        <td style="padding:0 20px"><input id="airportpickup" type="checkbox" onclick="$('#airportpickup_open').css('display',($(this)[0].checked?'block':'none'));"></td>
                        <td class="tdlabel"><?=ln("Additional fee will apply. Land transfers must be requested at least 48 hours prior to arrival","Costo adicional. Servicio de transporte debe ser solicitado por lo menos 48 horas antes de la fecha de llegada")?>.</td>
                    </tr>
                    </table>
                </div>

                <div class="line transfer_field" style="margin-top:0px">
                    <table class="ampm_tbl transfer_opt_tbl " border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td nowrap><input onclick="doNotAddTransfer(false)" type="radio" value="" name="RES_TRANSFER_TYPE"></td>
                            <td nowrap><div class="chkbxlbl"><?=ln("Do not add transfer","No adicionar transportación")?></div></td>
                        </tr>
                        <tr>
                            <td nowrap><input onclick="getTransferCars(this.value)" type="radio" value="ROUNDT" id="ROUNDT" name="RES_TRANSFER_TYPE"></td>
                            <td nowrap><div class="chkbxlbl"><?=ln("Round Trip","Viaje redondo")?></div></td>
                        </tr>
                        <tr>
                            <td nowrap><input onclick="getTransferCars(this.value)" type="radio" value="ONEWAY" id="ONEWAY" name="RES_TRANSFER_TYPE"></td>
                            <td nowrap><div class="chkbxlbl"><?=ln("One Way","Del Aeropuerto al Hotel")?></div></td>
                        </tr>
                    </table>
                </div>

                <div class="line transfer_field" id="airportpickup_open" style="display:none">
                    <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="230"><input type="text" class="w205" id="RES_AIRLINE"><div><?=ln("Arrival Airline","Aerolinea de llegada")?></div></td>
                        <td width="92"><input type="text" class="w60" id="RES_FNUMBER"><div><?=ln("Flight","Número")?></div></td>
                        <td width="126"><input type="text" class="w60" id="RES_ARRIVAL"><div><?=ln("Time","Hora")?></div></td>
                        <td>
                            <table class="ampm_tbl" border="0" cellpadding="0" cellspacing="0">
                            <tr><td><input name="RES_ARRIVAL_AP" id="RES_A_AM" type="radio" checked value="AM"></td><td nowrap>&nbsp;&nbsp;&nbsp;AM</td></tr>
                            <tr><td><input name="RES_ARRIVAL_AP" type="radio" value="PM"></td><td nowrap>&nbsp;&nbsp;&nbsp;PM</td></tr>
                            </table>                        
                        </td>
                    </tr>
                    </table>
                </div>

                <div class="line transfer_field" id="DEPARTURE_INFO_TBL">
                    <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="230"><input type="text" class="w205"  id="RES_DEPARTURE_AIRLINE"><div><?=ln("Departure Airline","Aerolinea de Salida")?></div></td>
                        <td width="92"><input type="text" class="w60"  id="RES_DEPARTURE_FLIGHT"><div><?=ln("Flight","Vuelo")?></div></td>
                        <td width="126"><input type="text" class="w60" id="RES_DEPARTURE"><div><?=ln("Time","Hora")?></div></td>
                        <td>
                            <table class="ampm_tbl" border="0" cellpadding="0" cellspacing="0">
                            <tr><td><input name="RES_DEPARTURE_AP" id="RES_D_AM" type="radio" checked value="AM"></td><td nowrap>&nbsp;&nbsp;&nbsp;AM</td></tr>
                            <tr><td><input name="RES_DEPARTURE_AP" type="radio" value="PM"></td><td nowrap>&nbsp;&nbsp;&nbsp;PM</td></tr>
                            </table>                        
                        </td>
                    </tr>
                    </table>
                </div>

                <div class="line transfer_field" id="TRANSFER_CARS_LIST">
                    <div>
                        <center><img src="loading-small.gif"></center>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="sec-lbl"><?=ln("PAYMENT INFORMATION","INFORMACIÓN DE PAGO")?></div>
    <div class="form_block">
        <div class="inner">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td colspan="3" class="tdlabel">

                    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="ta_field" style="display:<? print "none"; //$_COOKIE['TA_LOGGED']?"block":"none"?>">
                    <tr>
                        <td><input type="checkbox" id="WIRE_TRANSFER" onclick="pay_with_wire($(this))"></td>
                        <td class="tdlabel" width="100%">&nbsp;&nbsp;<?=ln("Wire Transfer","Transferencia Electrónica")?></td>
                    </tr>
                    </table>

                </td>
                <td class="w235" rowspan="10">
                    <p><?=ln("Total reservation charge","Cargo total de la reservación")?> (USD): <span id="total_cc_charge_is"><span></p>
                    <div class="CC_DETAILS">
                    <?
                        $CCDETAILS = $results["RES_ITEMS"]["PROPERTY"]["EMAIL_CCDETAILS_EN"];
                        $CCDETAILS = str_replace("Terms and Conditions",'<a href="javascript:void(0)" onclick="popover_open($(this),\'popover_terms\')">Terms and Conditions</a>',$CCDETAILS);
                        print $CCDETAILS;
                    ?>
                    </div>
                </td>
            </tr>
            <tr class="CC_DETAILS">
                <td colspan="3">
                    <select id="CC_TYPE" name="CC_TYPE" class="w163">
                        <option value="Visa">Visa</option>
                        <option value="MasterCard">Master Card</option>
                        <option value="AmEx">American Express</option>
                    </select>                    
                </td>
            </tr>
            <tr class="CC_DETAILS">
                <td colspan="2">
                    <input type="text" id="CC_NUMBER" name="CC_NUMBER" class="w163">
                    <span><?=ln("Credit Card Number","Tarjeta de Crédito")?>*</span>                
                </td>
                <td>
                    <input type="text" id="CC_SECCODE" name="CC_SECCODE" class="w57">
                    <span><a href="javascript:void(0)" onclick="popover_open($(this),'popover_cards')">CVV*</a></span>
                </td>
            </tr>
            <tr class="CC_DETAILS">
                <td colspan="3">
                    <input type="text" id="CC_NAME" name="CC_NAME" class="w235">
                    <span><?=ln("Cardholder name (as it appears on card)","Nombre del Titular (Como aparece en la Tarjeta)")?>*</span>                
                </td>
            </tr>
            <tr class="CC_DETAILS">
                <td style="padding-top:10px"><?=ln("Expiration Date","Fecha de vencimiento")?>*</td>
                <td style="text-align:right">
                    <select id="CC_EXP_MONTH" name="CC_EXP_MONTH" class="w57">
                    <? for ($t=1;$t<=12;++$t) { print "<option value='".($t<10?"0":"")."$t'>".($t<10?"0":"")."$t</option>"; } ?>
                    </select>
                </td>
                <td>
                    <select id="CC_EXP_YEAR" name="CC_EXP_YEAR" class="w57">
                    <? for ($t=date("Y");$t<=date("Y")+5;++$t) { print "<option value='".($t-2000)."'>$t</option>"; } ?>
                    </select>                
                </td>
            </tr>
            <tr class="CC_DETAILS">
                <td colspan="3">
                    <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><input type="checkbox" checked="checked" id="billing_box" name="billing_box" onclick="billing_box()"></td>
                        <td class="tdlabel"><?=ln("Billing address same as contact address","Dirección de facturación coincide con la dirección de contacto")?>&nbsp;&nbsp;</td>
                        
                    </tr>
                    </table>
                    <span></span>
                </td>
                <td></td>
            </tr>
            </table>

            <table id="billing_tbl" class="hide" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td class="w410" colspan="2">
                    <input type="text" id="BILL-GUEST_ADDRESS" name="BILL-GUEST_ADDRESS">
                    <span><?=ln("Address","Dirección")?>*</span>                
                </td>
            </tr>
            <tr>
                <td class="w205">
                    <input type="text" id="BILL-GUEST_CITY" name="BILL-GUEST_CITY">
                    <span><?=ln("City","Ciudad")?>*</span>
                </td>
                <td class="w205">
                    <select id="BILL-GUEST_COUNTRY" onchange="country_changed($(this))">
                        <? include "select-country.php"; ?>
                    </select>
                    <span><?=ln("Country","País")?>*</span>
                </td>
            </tr>
            <tr>
                <td class="w205">
                    <? 
                        $isBilling = true;
                        include "select-state.php"; 
                    ?>
                    <input type="text" id="BILL-GUEST_STATE" name="BILL-GUEST_STATE" class="BILL-states-list hide">
                    <span><?=ln("State / Province","Estado / Provincia")?>*</span>
                </td>
                <td class="w205">
                    <input type="text" id="BILL-GUEST_ZIPCODE" name="BILL-GUEST_ZIPCODE">
                    <span><?=ln("Postal / ZIP Code","Código postal")?>*</span>
                </td>
            </tr>
            </table>
            <div class="lbl-required CC_DETAILS">*<?=ln("Required","Requerido")?></div>

        </div>
    </div>

    <div class="sec-lbl"><?=ln("OPTIONAL PREFERENCES","PREFERENCIAS OPCIONALES")?></div>
    <div class="form_block">
        <div class="inner">

        <? for ($ROOM_NUM=1; $ROOM_NUM <= (int)$results['RES_ROOMS_QTY']; ++$ROOM_NUM) { ?>
            <div class="room_pref_box" id="pref_room_<?=$ROOM_NUM?>">
                <?php
                if((int)$results['RES_ROOMS_QTY'] > 1) {
                ?>
                <div class="pref_room_name"><?=ln("Room","Habitación")?> <?=$ROOM_NUM?>: <span class="room_name"></span></div>
                <table class="w470" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="w60" valign="top">
                        <select id="ROOM_<?=$ROOM_NUM?>_GUEST_TITLE">
                            <option>Mr.</option>
                            <option>Mrs.</option>
                            <option>Ms.</option>
                        </select>
                        <span></span>              
                    </td>
                    <td class="w205">
                        <input type="text" id="ROOM_<?=$ROOM_NUM?>_GUEST_FIRSTNAME">
                        <span><?=ln("First Name","Nombre")?>*</span>
                    </td>
                    <td class="w205">
                        <input type="text" id="ROOM_<?=$ROOM_NUM?>_GUEST_LASTNAME">
                        <span><?=ln("Last Name","Apellido")?>*</span>
                    </td>
                </tr>
                </table>
                <?php } ?>
                <table class="" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="tdlabel" width="180px"><?=ln("Bed type preferences","Preferencia en tipo de cama")?></td>
                    <td>
                        <select class="w190" id="ROOM_<?=$ROOM_NUM?>_GUEST_BEDTYPE">
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="tdlabel"><?=ln("Smoking preferences","Preferencia de Habitación")?></td>
                    <td>
                        <select class="w190" id="ROOM_<?=$ROOM_NUM?>_GUEST_SMOKING">
                            <option value=""><?=ln("No preferences","Ninguna")?></option>
                            <option value="Non-smoking"><?=ln("Non-smoking","No Fumar")?></option>
                            <option value="Smoking"><?=ln("Smoking","Fumar")?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="tdlabel"><?=ln("Special Occasion","Ocasión Especial")?></td>
                    <td>
                        <select class="w190" id="ROOM_<?=$ROOM_NUM?>_GUEST_OCCASION">
                            <option value=""><?=ln("No preferences","Ninguna")?></option>
                            <option value="Anniversary"><?=ln("Anniversary","Aniversario")?></option>
                            <option value="Honeymoon"><?=ln("Honeymoon","Luna de Miel")?></option>
                            <option value="Birthday"><?=ln("Birthday","Cumpleaños")?></option>
                        </select>
                    </td>
                </tr>
                <input type="checkbox" id="ROOM_<?=$ROOM_NUM?>_GUEST_BABYCRIB" style="display:none">
                <!--                
                <tr class="field_vip field_vip_0 field_vip_2" style="display:none;">
                    <td class="tdlabel"><?=ln("Crib in the room","Cuna en el cuarto")?></td>
                    <td><input type="checkbox" id="ROOM_<?=$ROOM_NUM?>_GUEST_BABYCRIB"></td>
                </tr>
                <tr>
                    <td class="tdlabel">Stayed in Excellence Resorts</td>
                    <td><input type="checkbox" id="ROOM_<?=$ROOM_NUM?>_GUEST_REPEATED" value="5"></td>
                </tr>-->
                </table>


            </div>
            <hr class="hr">

        <? } ?>

            <table class="" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td width="180"><?=ln("Comments and Special Requests","Comentarios y solicitudes especiales")?></td>
                <td><textarea id="COMMENTS" style="width: 300px; height: 100px;"></textarea></td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>&nbsp;</p>
                    <?=ln("How did you hear about us","Como se entero de nosotros")?>?
                </td>
            </tr>
            <!--
            <tr>
                <td valign="top">
                    <table class="" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td><input type="radio" value="Repeat guest" name="HEAR_ABOUT_US" onclick="hear_txt($(this))"></td>
                        <td class="tdlabel">&nbsp;<?=ln("Repeat guest","Cliente repetitivo")?></td>
                    </tr>
                    <tr>
                        <td><input type="radio" value="Recommendation" name="HEAR_ABOUT_US" onclick="hear_txt($(this))"></td>
                        <td class="tdlabel">&nbsp;<?=ln("Recommendation","Recomendaciones")?></td>
                    </tr>
                    <tr>
                        <td><input type="radio" value="Tripadvisor" name="HEAR_ABOUT_US" onclick="hear_txt($(this))"></td>
                        <td class="tdlabel">&nbsp;<?=ln("Tripadvisor","Tripadvisor")?></td>
                    </tr>
                    </table>
                </td>
                <td valign="top">
                    <table class="" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td valign="top"><input type="radio" value="Internet" name="HEAR_ABOUT_US" onclick="hear_txt($(this))"></td>
                        <td class="tdlabel" style="padding-left:5px">
                            <?=ln("Internet","Internet")?>
                            <div id="Internet_txt" class="hear_txt" style="display: none;"><textarea></textarea></div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><input type="radio" value="Newspaper" name="HEAR_ABOUT_US" onclick="hear_txt($(this))"></td>
                        <td class="tdlabel" style="padding-left:5px">
                            <?=ln("Newspaper/Magazine/TV/Radio","Periódico/Revistas/TV/Radio")?>...
                            <div id="Newspaper_txt" class="hear_txt" style="display: none;"><textarea></textarea></div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><input type="radio" value="Other" name="HEAR_ABOUT_US" onclick="hear_txt($(this))" checked></td>
                        <td class="tdlabel" style="padding-left:5px">
                            <?=ln("Other","Otros")?>
                            <div id="Other_txt" class="hear_txt" style="display: block;"><textarea></textarea></div>
                        </td>
                    </tr>
                    </table>
                </td>
            </tr>
            -->
            <tr>
                <td valign="top">
                    <table class="" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td><input type="radio" value="Travel Agent" name="HEAR_ABOUT_US" onclick="hear_txt($(this))"></td>
                        <td class="tdlabel">&nbsp;<?=ln("Travel Agent","Agente de Viajes")?></td>
                    </tr>
                    <tr>
                        <td><input type="radio" value="Wedding" name="HEAR_ABOUT_US" onclick="hear_txt($(this))"></td>
                        <td class="tdlabel">&nbsp;<?=ln("Wedding","Matrimonio")?></td>
                    </tr>
                    <tr>
                        <td><input type="radio" value="Family" name="HEAR_ABOUT_US" onclick="hear_txt($(this))"></td>
                        <td class="tdlabel">&nbsp;<?=ln("Family","Familia")?></td>
                    </tr>
                    <tr>
                        <td><input type="radio" value="Online research" name="HEAR_ABOUT_US" onclick="hear_txt($(this))"></td>
                        <td class="tdlabel">&nbsp;<?=ln("Online research","Online research")?></td>
                    </tr>
                    </table>
                </td>
                <td valign="top">
                    <table class="" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td valign="top"><input type="radio" value="Repeat guest" name="HEAR_ABOUT_US" onclick="hear_txt($(this))"></td>
                        <td class="tdlabel" style="padding-left:5px">&nbsp;<?=ln("Repeat guest","Cliente repetitivo")?></td>
                    </tr>
                    <tr>
                        <td valign="top"><input type="radio" value="Tripadvisor" name="HEAR_ABOUT_US" onclick="hear_txt($(this))"></td>
                        <td class="tdlabel" style="padding-left:5px">&nbsp;<?=ln("Tripadvisor","Tripadvisor")?></td>
                    </tr>
                    <tr>
                        <td valign="top"><input type="radio" value="Other" name="HEAR_ABOUT_US" onclick="hear_txt($(this))" checked></td>
                        <td class="tdlabel" style="padding-left:5px">
                            <?=ln("Other","Otros")?>
                            <div id="Other_txt" class="hear_txt" style="display: block;"><textarea></textarea></div>
                        </td>
                    </tr>
                    </table>
                </td>
            </tr>                
            </table>

        </div>
    </div>

    <p>&nbsp;</p>
    <div class="form_block">
        <div class="inner">

            <table class="" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td><input type="checkbox" id="AGREE"></td>
                <td class="tdlabel">&nbsp;&nbsp;<?=ln("I have read and agree to the ","He leído y acepto los")?> <a href="javascript:void(0)" onclick="popover_open($(this),'popover_terms')"><?=ln("Terms and Conditions","Términos y condiciones establecidos")?></a>* </td>
            </tr>
            <tr>
                <td><input type="checkbox" id="MAILING_LIST" checked="checked"></td>
                <td class="tdlabel">&nbsp;&nbsp;<?=ln("I'd like to join Excellence Resorts mailing list","Deseo pertencer a la lista de contacto")?></td>
            </tr>
            </table>

        </div>
    </div>

</div>

