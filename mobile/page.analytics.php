<? if (!isset($isConfirmationPage)) { ?>



<? } else { ?>

	<script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', '<? print ($RES_PROP_ID==4) ? "UA-11517212-1" : "UA-142371-4" ?>']);
        _gaq.push(['_trackPageview']);
        _gaq.push(['_addTrans',
            '<?=$RESERVATION['RES_NUMBER'];?>', // order ID - required
            '', // affiliation or store name
            '<?=$RESERVATION['RES_TOTAL_CHARGE'];?>', // total - required
            '', // tax
            '', // shipping
            '', // city
            '', // state or province
            ''  // country
        ]);

	   // add item might be called for every item in the shopping cart
	   // where your ecommerce engine loops through each item in the cart and
	   // prints out _addItem for each
        <?
        foreach ($RESERVATION['ROOMS'] as $ind => $PROOM) {
            $ROOM_ID = $RESERVATION['RES_ROOMS_SELECTED'][$ind];
            $ROOM = $_SESSION['AVAILABILITY']["RES_ROOM_".($ind+1)."_ROOMS"][$ROOM_ID];    
	        ?>
            _gaq.push(['_addItem',
                '<?=$RESERVATION['RES_NUMBER'];?>', // order ID - required
                '<?=$RES_ITEMS['PROPERTY']['CODE'];?> - <?=$_SESSION['AVAILABILITY']['TMP']['ROOMS'][$ind]['TXT'];?>',  // SKU/code - required
                '<?=$RESERVATION['RES_ROOM_CHARGE'][$ind];?>', // unit price - required
                '1' // quantity - required
            ]); 
            <?
	    }
        ?>
	 
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
        var google_conversion_value = 0;
        if (<?=$RESERVATION['RES_TOTAL_CHARGE'];?>) {
          google_conversion_value = <?=$RESERVATION['RES_TOTAL_CHARGE'];?>;
        }
        /* ]]> */
    </script>

	<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js"></script>

	<noscript>
        <div style="display:inline;"> 
            <img height="1" width="1" style="border-style:none;" alt="" src="https://www.googleadservices.com/pagead/conversion/1039108475/?label=ZIe9CL_tWhD7kr7vAw&amp;guid=ON&amp;script=0"/>
        </div>
	</noscript>

<? } ?>

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

<? if ($RES_ITEMS['PROPERTY']['CODE']=='XOB') { 
  $IO_Code = "172804";
  ?>
  <!-- 1.	WIHP Tracking Script information -->
  <script type="text/javascript" src="https://secure-hotel-tracker.com/tics/log.php?act=conversion&ref=<?=$RESERVATION['RES_NUMBER'];?>&amount=<?=$RESERVATION['RES_TOTAL_CHARGE'];?>&currency=USD&idbe=3&idwihp=<?=$IO_Code;?>&checkin=<?=$RES_CHECK_IN?>&checkout=<?=$RES_CHECK_OUT?>&date_format=YYYY-MM-DD"></script>
  <?
}
?>
