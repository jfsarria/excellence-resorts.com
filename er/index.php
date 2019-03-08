
<!-- <br><br><br><br><center><img src="http://www.excellence-resorts.com/er/top-logo-desktop.png"><h1>Under Maintenance<h1></center> -->

<?

//exit;


    //print "<pre>";print_r($_SERVER);print "</pre>";exit;
    include "mobile-redirect.php";
    include "secure-redirect.php";

    session_start();

    global $RES_LANGUAGE, $COUNTRY_CODE;

    date_default_timezone_set('America/New_York');

    include "app.fns.php";
    include "app.authentication.php";
    include "get_search.php";

    //print $results_json;
    //print "result:<pre>";print_r($results);print "</pre>";exit;

    $RES_LANGUAGE = $results['RES_LANGUAGE'];
?>
<!DOCTYPE html>
<html ng-app="ibe">
	<head>
		<title>Excellence Resorts - Booking</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"> 
		<meta name="Description" content="Excellents Resorts - Booking">
		<meta name="Keywords" content="">

        <script type="text/javascript" src="jquery-1.11.1.min.js"></script>
        <script src="js/core.js"></script>

        <link href="jquery-ui/jquery-ui.structure.css" rel="stylesheet">
        <link href="jquery-ui/jquery-ui.theme.css" rel="stylesheet">

        <script type="text/javascript" src="jquery-ui/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/jquery.customSelect.min.js"></script>
        <script type="text/javascript" src="js/hoverDelay.js"></script>
        <script type="text/javascript" src="garand-sticky/jquery.sticky.js"></script>

        <link href="tipr/tipr.css" rel="stylesheet">
        <script src="tipr/tipr.js"></script>

        <script type="text/javascript" src="app.main.js<?="?".time()?>"></script>
        <script type="text/javascript" src="transfers.js"></script>
        <link rel="stylesheet" type="text/css" href="css/fonts.css" />
        <link rel="stylesheet" type="text/css" href="app.main.css" />
        <link rel="stylesheet" type="text/css" href="app.search.css" />
        <link rel="stylesheet" type="text/css" href="transfers.css" />

        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

        <script>
            var lan = "<?=$RES_LANGUAGE?>",
                search_qry = "<?=$search_qry?>",
                data = <?=$results_json?>;
        </script>
        <!--
        <script>
        (function() {
          var _fbq = window._fbq || (window._fbq = []);
          if (!_fbq.loaded) {
            var fbds = document.createElement('script');
            fbds.async = true;
            fbds.src = '//connect.facebook.net/en_US/fbds.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(fbds, s);
            _fbq.loaded = true;
          }
          _fbq.push(['addPixelId', '816498078414637']); })(); window._fbq = window._fbq || []; window._fbq.push(['track', 'PixelInitialized', {}]);
        </script>
        <noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=816498078414637&amp;ev=PixelInitialized" /></noscript>
        -->

        <script src="lean-slider.js"></script>
        <link rel="stylesheet" href="lean-slider.css" type="text/css" />
        <link rel="stylesheet" href="slider-styles.css" type="text/css" />

        <!-- Hotjar Tracking Code for http://www.excellenceresorts.com/ -->
        <script>
            (function(h,o,t,j,a,r){
                h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                h._hjSettings={hjid:708726,hjsv:6};
                a=o.getElementsByTagName('head')[0];
                r=o.createElement('script');r.async=1;
                r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                a.appendChild(r);
            })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
        </script>

	</head>
	<body>

		<script>
				dataLayer = [{
						'Page' : 'IBE',
						'Prop_ID' : '<?=$results["RES_ITEMS"]["PROPERTY"]["NAME"]?>',
						'Checkin_date' : '<?=$results["RES_CHECK_IN"]?>',
						'Checkout_date' : '<?=$results["RES_CHECK_OUT"]?>',
						'Number_of_rooms' : '<?=$results["RES_ROOMS_QTY"]?>',
						'Guests' : '<?= ( (int)$results["RES_ROOMS_ADULTS_QTY"] + (int)$results["RES_ROOMS_CHILDREN_QTY"] ) ?>',
						'Country' : '<?=$results["RES_COUNTRY_CODE"]?>',
						'ibe_step' : 'step-1'
				}];

				var CURRENCY = <? print json_encode($results['CURRENCY']) ?>;

				var CURRENCY_SYMBOL = {"USDUSD":"$","USDAUD":"$","USDBRL":"R$","USDCAD":"$","USDEUR":"€","USDGBP":"£","USDMXN":"$"};

		</script>
		<? include "../GTM.php"; ?>

		<div id="wrapper">
            <? include "top.php"; ?>
            <div id="content">
                <div id="col-left">
                    <input type="hidden" id="RES_PROP_ID" value="<?=$results["RES_PROP_ID"]?>">
                    <? 
                    include "select-room.php";
                    include "guest-info.php";
                    include "bottom.php";
                    ?>
                </div> <!-- col-left -->
                <div id="col-right">
                    <div id="sticky" style="display:inline-block">
                        <div id="wrap-right">
                            <? include "summary.php";?>
                        </div>
                    </div>
                </div>
                <script>
                <? for ($ROOM_NUM=1; $ROOM_NUM <= (int)$results['RES_ROOMS_QTY']; ++$ROOM_NUM) { ?>
                    select_room($("#list-room-num-<?=$ROOM_NUM?> .room").not(".not-available").first().attr("id"), <?=$ROOM_NUM==1?"true":"false"?>)
                <? } ?>
                select_nav_step(1);
                </script>
            </div>
		</div>
        <!--
        <script type="text/javascript" src="angular.min.js"></script>
        <script type="text/javascript" src="app.angular.js"></script>
        -->
        <script>
            $("#sticky").sticky({ topSpacing: 20 });
        </script>

        <div class="popovers">
            <div id="popover_rate" style="display:none"><div class="inner"></div></div>
            <div id="popover_pwd" style="display:none">
                <div class="close"><a href="javascript:void(0)" onclick="$('#popover_pwd').hide();"><?=ln("Close","Cerrar")?></a></div>
                <p><?=ln("Enter the e-mail address associated with your Excellence Resorts account, then click Submit","Introduzca la dirección de correo electrónico asociada con su cuenta Excellence Resorts, a continuación, haga clic en Enviar")?>.</p>
                <input type="text">
                <span><?=ln("E-mail","Correo Electrónico")?></span> 
                <div style="text-align:right;padding-right:30px"><a href="javascript:void(0)" onclick="sendGuestPwd()"><?=ln("Submit","Enviar")?></a></div> 
            </div>
            <div id="popover_cards" class="popover" style="display:none">
                <div class="close"><a href="javascript:void(0)" onclick="$(this).parents('.popover:first').hide();"><?=ln("Close","Cerrar")?></a></div>
                <img src="step2_cards.png">
            </div>
            <div id="popover_terms" class="popover" style="display:none">
                <div class="close"><a href="javascript:void(0)" onclick="$(this).parents('.popover:first').hide();"><?=ln("Close","Cerrar")?></a></div>
                <div class="text">
                    <? include "terms_$RES_LANGUAGE.html"; ?>
                </div>
            </div>
            <div id="popover_transfer_rules" class="popover" style="display:none">
                <div class="close"><a href="javascript:void(0)" onclick="$(this).parents('.popover:first').hide();"><?=ln("Close","Cerrar")?></a></div>
                <div class="text">
                    <p><?=$results['RES_ITEMS']['TRANSFER_RULES']?></p>
                </div>
            </div>
            <div id="popover_cancellation_policy" class="popover" style="display:none">
                <div class="close"><a href="javascript:void(0)" onclick="$(this).parents('.popover:first').hide();"><?=ln("Close","Cerrar")?></a></div>
                <div class="text">
                    <p><?=$results['RES_ITEMS']['CANCELLATION_POLICY']?></p>
                </div>
            </div>

        </div>

        <?
        include "search-tracking.php";

        if (!isset($isConfirmationPage)) {
            $bannerApiUrl = "http://".$_SERVER["SERVER_NAME"]."/ibe/index.php?PAGE_CODE=ws.getBanner&PROP_ID=".$results["RES_ITEMS"]["PROPERTY"]["ID"]."&COUNTRY_CODE={$COUNTRY_CODE}&THIS_PAGE=/er/&LANG={$RES_LANGUAGE}&FORMAT=html";
            print "<!-- bannerApiUrl: $bannerApiUrl -->\n\n";
            $webBanner = file_get_contents($bannerApiUrl);
            if ($webBanner!="") { 
              $webBanner = str_replace('"',"'",$webBanner);
              print $webBanner; 
            } 
        }

        ?>

        <div>
          <br><br><br><br><br><br>
        </div>

	</body>
</html>