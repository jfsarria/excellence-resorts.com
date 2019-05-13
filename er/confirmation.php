<?
    //confirmation.php?{%22RES_ID%22:%22168798%22,%22RES_NUMBER%22:%225107432072852%22,%22RES_YEAR%22:%22FPM_2014%22}
    //error_reporting(E_ERROR | E_PARSE);
    //ini_set('display_errors', '1');

    date_default_timezone_set('America/New_York');

    include "secure-redirect.php";
    include $_SERVER['DOCUMENT_ROOT']."/ibe/inc/ibe.fns.php";

    $isConfirmationPage = true;
    $QRYSTR = urldecode($_SERVER['QUERY_STRING']);
    if (empty($QRYSTR)) {
        $QRYSTR = $_COOKIE["booked"];
    }
    $QRYSTR_ARR = json_decode($QRYSTR,true);
    if (empty($QRYSTR) || !isset($QRYSTR_ARR['RES_NUMBER'])) {
        exit;
    }
    $RES_ID = $QRYSTR_ARR['RES_ID'];
    $RES_NUMBER = $QRYSTR_ARR['RES_NUMBER'];
    $RES_YEAR = explode("_",$QRYSTR_ARR['RES_YEAR']);
    $RES_CODE = $RES_YEAR[0];
    $RES_YEAR = $RES_YEAR[1];

    $URL = "http://".$_SERVER['HTTP_HOST']."/ibe/index.php?PAGE_CODE=ws.getJSON&ID={$RES_ID}&CODE={$RES_CODE}&YEAR={$RES_YEAR}";
    $JSON = file_get_contents($URL);
    $DATA = json_decode($JSON,true);

    global $RES_LANGUAGE;
    $RES_LANGUAGE = $DATA['RES_LANGUAGE'];

    //print "<pre>";print_r($QRYSTR_ARR);print "</pre>";
    //print "$RES_LANGUAGE: <pre>";print_r($DATA);print "</pre>";exit;

    function printit($STR) {
        print str_replace(array("\n","\r\n"),array("<br>","<br>"),$STR);
    }
    extract($DATA);

    $TOTAL_CHARGE = (int)$RESERVATION['RES_TOTAL_CHARGE'];

    function ln($en, $sp) {
        global $RES_LANGUAGE;
        return $RES_LANGUAGE=="EN" ? $en : $sp;
    }
?>

<!DOCTYPE html>
<html ng-app="ibe">
	<head>
		<title></title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"> 
		<meta name="Keywords" content="">
		<meta name="Description" content="">

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js" ></script>

        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" type="text/css" href="css/fonts.css" />
		<link rel="stylesheet" type="text/css" href="app.main.css" />

        <style>
            .sec-lbl {
                text-align:left;
            }
        </style>


        <script>
            dataLayer = [];
            dataLayerStr = localStorage.getItem("dataLayerObj");
            dataLayerObj = JSON.parse(dataLayerStr);
            console.log("dataLayer step 3", dataLayerObj);
            dataLayer.push(dataLayerObj);
            localStorage.removeItem('dataLayerObj');
        </script>        

        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-TTL2Q6');</script>
        <!-- End Google Tag Manager -->   

	</head>
	<body>

        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-TTL2Q6"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->  
        
		<div id="wrapper">
			<? include "top.php"; ?>
            <div id="content" style="font-size:12px">
                <div id="col-left">
                    
                    <div id="confirmation-page" class="form_block">
                        <div class="inner">
                            
                            <div>
                                <?=ln("Thank You","Gracias")?> <?=$DATA['RESERVATION']['GUEST']['FIRSTNAME']?> <?=$DATA['RESERVATION']['GUEST']['LASTNAME']?>! <?=ln("Your reservation is complete","Su reservación esta completa")?>!<br>
                                <?=ln("Please print the information below for your reference","Por favor imprima la siguiente información para su referencia")?>.<br>
                                <?=ln("This information will also be sent to your e-mail","Esta información también se le enviará por correo electrónico")?>.<br>
                            </div>

                            <div class="sec-lbl"><? print _l("RESERVATION #","RESERVACIÓN #",$RES_LANGUAGE)." ".$RESERVATION['RES_NUMBER'] ?></div>
                            <div class="ui-collapsible-box">
                                Hotel: 
                                <? 
                                    $CHILDREN = (isset($RES_ROOMS_CHILDREN_QTY) && (int)$RES_ROOMS_CHILDREN_QTY!=0) ? (int)$RES_ROOMS_CHILDREN_QTY : 0;
                                    $INFANTS = (isset($RES_ROOMS_INFANTS_QTY) && (int)$RES_ROOMS_INFANTS_QTY!=0) ? (int)$RES_ROOMS_INFANTS_QTY : 0;

                                    $OUTOUT = "";

                                    print $RES_ITEMS['PROPERTY']['NAME']."<br>".
                                    _l("Booking Date","Fecha de reserva",$RES_LANGUAGE).": "._fecha(date("l, F j, Y", strtotime($DATA['RES_DATE'])),$RES_LANGUAGE)."<br>".
                                    _l("Number of Rooms","Número de Habitaciones",$RES_LANGUAGE).": {$RES_ROOMS_QTY}<br>".
                                    _l("Number of Adults","Número de Adultos",$RES_LANGUAGE).": {$RES_ROOMS_ADULTS_QTY}<br>";
                                
                                    if ($CHILDREN!=0 || $INFANTS!=0) {
                                        if ($CHILDREN!=0) print _l("Number of Children","Número de Niños",$RES_LANGUAGE).": ".($CHILDREN - $INFANTS)."<br>\n";
                                        if ($INFANTS!=0) print _l("Number of Infants","Número de Bebes",$RES_LANGUAGE).": ".$INFANTS."<br>\n";
                                    }

                                    print "<br>".
                                    _l("Check In","Llegada",$RES_LANGUAGE).": "._fecha(date("F j, Y", strtotime($RES_CHECK_IN)),$RES_LANGUAGE)."<br>".
                                    _l("Check Out","Salida",$RES_LANGUAGE).": "._fecha(date("F j, Y", strtotime($RES_CHECK_OUT)),$RES_LANGUAGE)."<br>".
                                    _l("Total Stay","Total de la estancia",$RES_LANGUAGE).": {$RES_NIGHTS} "._l("night","noche",$RES_LANGUAGE)."".($RES_NIGHTS!=1?"s":"")."<br>";

                                    $iRooms = count($RESERVATION['ROOMS']);
                                    $DATA['TMP']['ROOMS'][0]['TXT'] = "";
                                    $DATA['TMP']['ROOM_NAMES'] = array();
                                    
                                    foreach ($RESERVATION['RES_ROOMS_SELECTED'] as $ind => $ROOM_ID) { 
                                        $GUESTS_QTY = (int)$DATA["RES_ROOM_".($ind+1)."_GUESTS_QTY"];
                                        $ADULTS = (int)$DATA["RES_ROOM_".($ind+1)."_ADULTS_QTY"];
                                        $CHILDREN = isset($DATA["RES_ROOM_".($ind+1)."_CHILDREN_QTY"]) ? (int)$DATA["RES_ROOM_".($ind+1)."_CHILDREN_QTY"] : 0;
                                        $INFANTS = isset($DATA["RES_ROOM_".($ind+1)."_INFANTS_QTY"]) ? (int)$DATA["RES_ROOM_".($ind+1)."_INFANTS_QTY"] : 0;
                                        $ROOM_NAME = $DATA["RES_ROOM_".($ind+1)."_ROOMS"][$ROOM_ID]["NAME"];
                                        $DATA['TMP']['ROOM_NAMES'][] = $ROOM_NAME;
                                        $DATA['TMP']['ROOMS'][$ind]['TXT'] = ($iRooms>1?"<br><b>".ln("Room","Habitación")." ".($ind+1).":</b>":"")."<br>".$ROOM_NAME.",<br>";
                                        if ($CHILDREN!=0 || $INFANTS!=0) {
                                            $DATA['TMP']['ROOMS'][$ind]['TXT'] .= $ADULTS." "._l("Adult","Adulto",$RES_LANGUAGE).($ADULTS==1?"":"s").",<br>";
                                            if ($CHILDREN - $INFANTS!=0) $DATA['TMP']['ROOMS'][$ind]['TXT'] .= ($CHILDREN - $INFANTS).(($CHILDREN - $INFANTS == 1)?" "._l("Child","Niño",$RES_LANGUAGE):" "._l("Children","Niños",$RES_LANGUAGE)).",<br>";
                                            if ($INFANTS!=0) $DATA['TMP']['ROOMS'][$ind]['TXT'] .= $INFANTS." "._l("Infant","Bebé",$RES_LANGUAGE).($INFANTS==1?"":"s").",<br>";
                                        } else {
                                            $DATA['TMP']['ROOMS'][$ind]['TXT'] .= $GUESTS_QTY." "._l("Guest","Huesped",$RES_LANGUAGE).($GUESTS_QTY==1?"":_l("s","es",$RES_LANGUAGE)).",<br>";
                                        }
                                        
                                        $OUTOUT .= $DATA['TMP']['ROOMS'][$ind]['TXT'];
                                        if ($iRooms>1) {
                                            $OUTOUT .= "USD $".number_format($RESERVATION['RES_ROOM_CHARGE'][$ind])."<br>";
                                        }
                                        
                                        $DATA['TMP']['ROOMS'][$ind]['TXT'] = strip_tags($DATA['TMP']['ROOMS'][$ind]['TXT']);
                                    }

                                    $OUTOUT .= "
                                        <b>"._l("Total cost","Costo total",$RES_LANGUAGE).": USD $".number_format($RESERVATION['RES_TOTAL_CHARGE'])."</b>
                                    ";

                                    $CLASS_NAMES = array();
                                    $SPECIAL_NAMES = array();
                                    foreach ($RESERVATION['RES_ROOMS_SELECTED'] as $ind => $ROOM_ID) { 
                                        $ROOM = $DATA["RES_ROOM_".($ind+1)."_ROOMS"][$ROOM_ID];

                                        //if (is_array($ROOM["CLASS_NAMES"])) $CLASS_NAMES = array_merge_recursive($CLASS_NAMES,$ROOM["CLASS_NAMES"]);
                                        //if (is_array($ROOM["SPECIAL_NAMES"])) $SPECIAL_NAMES = array_merge_recursive($SPECIAL_NAMES,$ROOM["SPECIAL_NAMES"]);

                                        if (is_array($ROOM["CLASS_NAMES"])) {
                                            foreach ($ROOM["CLASS_NAMES"] as $KEY=>$REF) if (isset($RES_ITEMS[$KEY]['NAME_'.$RES_LANGUAGE])) array_push($CLASS_NAMES, $RES_ITEMS[$KEY]['NAME_'.$RES_LANGUAGE]);
                                        }
                                        if (is_array($ROOM["SPECIAL_NAMES"])) {
                                            foreach ($ROOM["SPECIAL_NAMES"] as $KEY=>$REF) if (isset($RES_ITEMS[$KEY]['NAME_'.$RES_LANGUAGE]))  array_push($SPECIAL_NAMES, $RES_ITEMS[$KEY]['NAME_'.$RES_LANGUAGE]);
                                        }
                                    }
                                    $CLASS_NAMES = array_unique($CLASS_NAMES);
                                    $SPECIAL_NAMES = array_unique($SPECIAL_NAMES);

                                    $OUTOUT .= implode(", ",$CLASS_NAMES).(count($SPECIAL_NAMES)!=0?", ".implode(", ",$SPECIAL_NAMES):"");

                                    if (isset($RESERVATION['TRANSFER_FEE'])&&(int)$RESERVATION['TRANSFER_FEE']!=0&&isset($RESERVATION['TRANSFER_CAR'])&&(int)$RESERVATION['TRANSFER_CAR']!=0) { 
                                        $ROUNDT = $RES_LANGUAGE=='EN' ? "Round Trip" : "Viaje Redondo";
                                        $ONEWAY = $RES_LANGUAGE=='EN' ? "One Way" : "De ida al hotel";
                                        $TOTAL_CHARGE += (int)$RESERVATION['TRANSFER_FEE'];
                                        ob_start();
                                        ?>
                                        <p>
                                        <b><?if($RES_LANGUAGE=='EN') {?>Transfer<?} else {?>Transportación Privada<?}?> <? print $RESERVATION['TRANSFER_TYPE']=="ROUNDT" ? $ROUNDT : $ONEWAY ?></b>
                                        <?if($RES_LANGUAGE=='EN') {?>Transfer cost<?} else {?>Costo del transporte<?}?> (USD) $<? print number_format($RESERVATION['TRANSFER_FEE']) ?> <br>
                                        <span style='font-size:16px'><?if($RES_LANGUAGE=='EN') {?>Total Charge (USD)<?} else {?>Cargo Total<?}?> $<? print number_format($TOTAL_CHARGE) ?></span>
                                        </p><? 
                                        $OUTOUT .= ob_get_clean();
                                    } //else {$OUTOUT .= "NO TRASNFER";}

                                    /*
                                    if (isset($RESERVATION['CURRENCY_CODE'])&&!empty($RESERVATION['CURRENCY_CODE'])&&$RESERVATION['CURRENCY_CODE']!="USDUSD") {
                                        $CURRENCY_SYMBOL = array("USDUSD"=>"$","USDAUD"=>"$","USDBRL"=>"R$","USDCAD"=>"$","USDEUR"=>"€","USDGBP"=>"£","USDMXN"=>"$");
                                        $TOTAL_CONVERSION = ceil($TOTAL_CHARGE * (double)$RESERVATION['CURRENCY_QUOTE']);
                                        ob_start();
                                        ?><span style='font-size:16px'><?if($RES_LANGUAGE=='EN') {?>Equivalent cost at the time of reservation<?} else {?>Costo equivalente en el momento de la reserva<?}?> (<? print str_replace("USD","",$RESERVATION['CURRENCY_CODE']) ?>) <?=$CURRENCY_SYMBOL[$RESERVATION['CURRENCY_CODE']]?> <? print number_format($TOTAL_CONVERSION) ?></span><?
                                        $OUTOUT .= ob_get_clean();
                                    }
                                    */

                                    $OUTOUT .= "\n\n".html_entity_decode($RES_ITEMS['CANCELLATION_POLICY']);

                                    if ($RESERVATION['FORWHOM']['RES_TO_WHOM']=="GUEST" && (int)$RESERVATION['FORWHOM']['RES_NEW_GUEST']==1) {
                                        $OUTOUT .= "\n
                                            <b>This is the login information that you can use to access your account</b>

                                            <b>login</b>: {$RESERVATION['GUEST']['EMAIL']}
                                            <b>password</b>: {$RESERVATION['GUEST']['PASSWORD']}

                                            Please remember that you will need this information in order to make changes to your reservation.
                                        ";
                                    }

                                    print printit($OUTOUT);
                                ?>
                            </div>

                            <div class="sec-lbl"><? print _l("Optional Preferences","Preferencias",$RES_LANGUAGE) ?></div>
                            <div class="ui-collapsible-box">
                                <?
                                    $OUTOUT = "";

                                    $ROOM_NUM = 1;
                                    foreach ($RESERVATION['ROOMS'] as $ind => $PROOM) {
                                        $ROOM_ID = $RESERVATION['RES_ROOMS_SELECTED'][$ind];
                                        $ROOM = $DATA["RES_ROOM_".($ind+1)."_ROOMS"][$ROOM_ID];
                                        if ($iRooms>1) {
                                            $OUTOUT .= _l("Room Type","Habitación",$RES_LANGUAGE).": {$ROOM['NAME']} <br>";
                                        }
                                        $OUTOUT .= ""._l("Bed preference","Tipo de cama",$RES_LANGUAGE).": ".((isset($PROOM['GUEST_BEDTYPE'])&&$PROOM['GUEST_BEDTYPE']!=""&&isset($RES_ITEMS['PROPERTY']['BED_TYPES'])&&isset($RES_ITEMS['PROPERTY']['BED_TYPES'][$PROOM['GUEST_BEDTYPE']]))?_pref($RES_ITEMS['PROPERTY']['BED_TYPES'][$PROOM['GUEST_BEDTYPE']],$RES_LANGUAGE):_l("No preference","Sin preferencias",$RES_LANGUAGE))."";
                                        if (isset($PROOM['GUEST_BABYCRIB']) && (int)$PROOM['GUEST_BABYCRIB']==1) $OUTOUT .= "\n"._l("Baby Crib","Cuna para bebé",$RES_LANGUAGE).": "._l("Yes","Si",$RES_LANGUAGE);
                                        $OUTOUT .= "
                                            "._l("Smoking Preference","Habitación",$RES_LANGUAGE).": ".((isset($PROOM['GUEST_SMOKING'])&&$PROOM['GUEST_SMOKING']!="")?_pref($PROOM['GUEST_SMOKING'],$RES_LANGUAGE):_l("No preference","Sin preferencias",$RES_LANGUAGE))."
                                            "._l("Special Occasion","Ocasión Especial",$RES_LANGUAGE).": ".((isset($PROOM['GUEST_SMOKING'])&&$PROOM['GUEST_OCCASION']!="")?_pref($PROOM['GUEST_OCCASION'],$RES_LANGUAGE):"No")."<br>
                                        ";
                                        ++$ROOM_NUM;
                                    } 

                                    print printit($OUTOUT);
                                ?>
                            </div>

                            <div class="sec-lbl"><? print _l("Guest Information","Información sobre el huésped",$RES_LANGUAGE) ?></div>
                            <div class="ui-collapsible-box">
                                <?
                                    $GUEST = $DATA['RESERVATION']['GUEST'];
                                    $OUTOUT = _title($GUEST['TITLE'], $RES_LANGUAGE)." {$GUEST['FIRSTNAME']} {$GUEST['LASTNAME']}
                                        "._l("Email","Correo Electrónico",$RES_LANGUAGE).": {$GUEST['EMAIL']}
                                        "._l("Phone","Teléfono",$RES_LANGUAGE).": {$GUEST['PHONE']}
                                        {$GUEST['ADDRESS']}
                                        ".appendToString($GUEST['CITY'],", ").appendToString($GUEST['STATE']," ").appendToString($GUEST['ZIPCODE'],", ").$GUEST['COUNTRY']."
                                    ";

                                    print printit($OUTOUT);
                                ?>
                            </div>

                            <div class="sec-lbl"><? print _l("Hotel Information","Información sobre el hotel",$RES_LANGUAGE) ?></div>
                            <div class="ui-collapsible-box">
                                <?
                                    $INFO = $RES_ITEMS['PROPERTY']['INFO_'.$RES_LANGUAGE];
                                    $INFO = html_entity_decode($INFO);
                                    $OUTOUT = "{$RES_ITEMS['PROPERTY']['NAME']}<br>{$INFO}";

                                    print printit($OUTOUT);
                                ?>
                            </div>
                        </div>
                        <script>
                            $("#top-nav ul li.step-3").addClass("selected");
                        </script>
                    </div>

                    <? include "bottom.php"; ?>

                </div> <!-- col-left -->
                <div id="col-right">
                    &nbsp;
                </div>
            </div>
		</div>

        <?php
            include "confirmation-tracking.php";
        ?>
	</body>
</html>