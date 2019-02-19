<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-142371-4']);
    _gaq.push(['_trackPageview']);
    _gaq.push(['_addTrans',
        '<?=$RESERVATION['RES_NUMBER']?>',  // order ID - required
        '',                                 // affiliation or store name
        '<?=$TOTAL_CHARGE?>',               // total - required
        '',                                 // tax
        '',                                 // shipping
        '',                                 // city
        '',                                 // state or province
        ''                                  // country
    ]);

    // add item might be called for every item in the shopping cart
    // where your ecommerce engine loops through each item in the cart and
    // prints out _addItem for each

    <? foreach ($RESERVATION['RES_ROOMS_SELECTED'] as $ind => $ROOM_ID) { ?>

        _gaq.push(['_addItem',
            '<?=$RESERVATION['RES_NUMBER']?>', // order ID - required
            '<?=$RES_CODE?> - <?=$DATA['TMP']['ROOMS'][$ind]['TXT'];?>', // SKU/code - required
            '<?=$RESERVATION['RES_ROOM_CHARGE'][$ind];?>', // unit price - required
            '1' // quantity - required
        ]); 

    <? } ?> 
	 
    _gaq.push(['_trackTrans']); //submits transaction to the Analytics servers

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>

<!-- Google Code for Purchase/Sale Conversion Page -->
<script type="text/javascript">
	/* <![CDATA[ */
	var google_conversion_id = 1039108475;
	var google_conversion_language = "en";
	var google_conversion_format = "3";
	var google_conversion_color = "FFFFFF";
	var google_conversion_label = "ZIe9CL_tWhD7kr7vAw";
	var google_conversion_value = <?=$TOTAL_CHARGE;?>;
	/* ]]> */
</script>
<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
    <div style="display:inline;"> 
        <img height="1" width="1" style="border-style:none;" alt="" src="https://www.googleadservices.com/pagead/conversion/1039108475/?value=<?=$$TOTAL_CHARGE;?>&amp;label=ZIe9CL_tWhD7kr7vAw&amp;guid=ON&amp;script=0"/>
    </div>
</noscript>

<?php
    /*
    XRC - 499896
    XPM - 649432
    XPC - 218524
    XEC - ??????
    TBH - 1180633
    */

    $thehotelsnetwork = array("hotel_id"=>array(),"property_id"=>array());
    $hotel_id = $RES_PROP_ID;
    $CODE = $RES_ITEMS['PROPERTY']['CODE'];
    $hotel_name = $RES_ITEMS['PROPERTY']['NAME'];
    if($CODE=='XRC') {
      $IO_Code = "172003";
      $thehotelsnetwork["hotel_id"] = "1076291";
      $thehotelsnetwork["property_id"] = "1011106";
    } elseif ($CODE=='XPM') {
      $IO_Code = "172000";
      $thehotelsnetwork["hotel_id"] = "1076292";
      $thehotelsnetwork["property_id"] = "1011107";
    } elseif ($CODE=='XPC') {
      $IO_Code = "172001";
      $thehotelsnetwork["hotel_id"] = "1013746";
      $thehotelsnetwork["property_id"] = "1011104";
    } elseif ($CODE=='XEC') {
      $IO_Code = "172804";
      $thehotelsnetwork["hotel_id"] = "1076290";
      $thehotelsnetwork["property_id"] = "1011105";
    } elseif ($CODE=='XOB') {
      $IO_Code = "179964";
      $thehotelsnetwork["hotel_id"] = "1088815";
      $thehotelsnetwork["property_id"] = "1013196";
    } else {
      $IO_Code = "172002";
      $thehotelsnetwork["hotel_id"] = "";
      $thehotelsnetwork["property_id"] = "";
    }

    $revenue = $TOTAL_CHARGE;
    $order_reference = $RESERVATION['RES_NUMBER'];
    $number_nights_booked = $RES_NIGHTS;
    $cache_buster = time();
?>

<iframe src="https://pfa.levexis.com/tripadvisor/tman.cgi/tmpageid=1&tmtag=iframe&booked_hotel_id=<?php print $hotel_id ?>&booked_hotel_name=<?php print $hotel_name ?>&levrev=<?php print $revenue ?>&levordref=<?php print $order_reference ?>&levresdes=USD&number_nights_booked=<?php print $number_nights_booked ?>&levyouruid=<?php print $cache_buster ?>" style="border: 0px none ; width: 0px; height: 0px;"></iframe> 

<script type="text/javascript"> if (!window.mstag) mstag = {loadTag : function(){},time : (new Date()).getTime()};</script> <script id="mstag_tops" type="text/javascript" src="//flex.atdmt.com/mstag/site/2006ca8e-623e-43e3-a8e6-13be70c6fa81/mstag.js"></script> <script type="text/javascript"> mstag.loadTag("analytics", {dedup:"1",domainId:"2155682",type:"1",revenue:"<?php print $TOTAL_CHARGE ?>",actionid:"112653"})</script> 
<noscript> 
    <iframe src="//flex.atdmt.com/mstag/tag/2006ca8e-623e-43e3-a8e6-13be70c6fa81/analytics.html?dedup=1&domainId=2155682&type=1&revenue=<?php print $TOTAL_CHARGE ?>&actionid=112653" frameborder="0" scrolling="no" width="1" height="1" style="visibility:hidden;display:none"></iframe> 
</noscript>

<!-- Adform Tracking Code BEGIN -->
<!-- Tracking Code: Standard (Asynchronous) "Excellence Group 2|Excellence TYP" -->
<script type="text/javascript">
    var _adftrack = {
        pm: 405108,
        divider: encodeURIComponent('|'),
        pagename: encodeURIComponent('Excellence Group 2|Excellence TYP'),
        order : {
            sales: '<?=$TOTAL_CHARGE?>',
            orderid: '<?=$order_reference;?>',            
            sv1: '<?=$RES_CHECK_IN?>',
            sv2: '<?=$RES_CHECK_OUT?>',
            sv3: '<?=$hotel_name?>',
            sv4: '<?=$RES_NIGHTS?>',
            sv5: '<?=((int)$RES_ROOMS_ADULTS_QTY + (int)$RES_ROOMS_CHILDREN_QTY)?>',
            sv6: '<?=implode(", ", $RESERVATION["RES_ROOMS_SELECTED_NAMES"])?>',
            sv8: '<?=$RESERVATION["GUEST"]["EMAIL"]?>'
        }
    };
    (function () { var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = 'https://track.adform.net/serving/scripts/trackpoint/async/'; var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x); })();

</script>
<noscript>
    <p style="margin:0;padding:0;border:0;">
        <img src="https://track.adform.net/Serving/TrackPoint/?pm=405108&ADFPageName=Excellence%20Group%202%7CExcellence%20TYP&ADFdivider=|" width="1" height="1" alt="" />
    </p>
</noscript>
<!-- Adform Tracking Code END -->



<script type="text/javascript">
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-142371-4', 'auto', {'allowLinker': true});
    //ga('require', 'linker');
    //ga('linker:autoLink', ['excellenceresorts.com'] );
    ga('send', 'pageview');
</script>

<!-- 1.	WIHP Tracking Script information -->
<script type="text/javascript" src="https://secure-hotel-tracker.com/tics/log.php?act=conversion&ref=<?=$RESERVATION['RES_NUMBER'];?>&amount=<?=$TOTAL_CHARGE;?>&currency=USD&idbe=3&idwihp=<?=$IO_Code;?>&checkin=<?=$RES_CHECK_IN?>&checkout=<?=$RES_CHECK_OUT?>&date_format=YYYY-MM-DD"></script>

<? if ($CODE=='XOB') { ?>
  <!-- 2.	Google HPA Tracking Script information -->
  <img height="1" width="1" border="0" alt=""src="https://www.googletraveladservices.com/travel/clk/pagead/conversion/75/?label=HPA&guid=ON&script=0&ord=<?=mt_rand()?>&data=hct_partner_hotel_id%3D<?=$IO_Code;?>%3Bhct_base_price%3D<?=$TOTAL_CHARGE;?>%3Bhct_total_price%3D<?=$TOTAL_CHARGE;?>%3Bhct_currency_code%3DUSD%3Bhct_checkin_date%3D<?=$RES_CHECK_IN?>%3Bhct_checkout_date%3D<?=$RES_CHECK_OUT?>%3Bhct_length_of_stay%3D<?=$RES_NIGHTS?>%3Bhct_date_format%3D%Y-%m-%d%3Bhct_booking_xref%3D<?=$RESERVATION['RES_NUMBER'];?>%3Bhct_ver%3D1.0.i"/>
<? } ?>

<iframe src="https://4745566.fls.doubleclick.net/activityi;src=4745566;type=sales;cat=s4hoqvtj;qty=1;cost=<?=$TOTAL_CHARGE;?>;ord=<?=$RESERVATION['RES_NUMBER'];?>?" width="1" height="1" frameborder="0" style="display:none"></iframe>
<!-- End of DoubleClick Floodlight Tag: Please do not remove -->


<script>(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"4022355"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");</script>
<noscript><img src="//bat.bing.com/action/0?ti=4022355&Ver=2" height="0" width="0" style="display:none; visibility: hidden;" /></noscript>

<script src='https://www.thehotelsnetwork.com/js/hotel_price_widget.js?hotel_id=<?=$thehotelsnetwork["hotel_id"]?>&property_id=<?=$thehotelsnetwork["property_id"]?>&account_key=FFE895D3DDDD5B887404088E861E3D23'></script>

<? //if ($CODE=='XPC') { ?>

	<script>
		(function(f,a,c,d,g,b,h,e){if(f.taq){return}b=f.taq=function(){b.queue.push(arguments)};b.queue=[];if(/bot|googlebot|crawler|spider|robot|crawling/i.test(c.userAgent)){return}h=a.createElement(d);h.async=true;h.src=g;e=a.getElementsByTagName(d)[0];e.parentNode.insertBefore(h,e)})(window,document,navigator,"script","//static.tacdn.com/js3/taevents-c.js");

		taq('init', '279942816');

		taq('track', 'BOOKING_CONFIRMATION', { 
			'partner' : "ExcellenceResorts",
			'refid' : "<?=$refid?>", 
			'gbv' : <?=$TOTAL_CHARGE?>00, 
			'bbv' : <?=$TOTAL_CHARGE?>00, 
			'currency' : "USD", 
			'order_id' : "<?=$RESERVATION['RES_NUMBER']?>", 
			'is_app' : 0 
		})

	</script>

<? //} ?>

