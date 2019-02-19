<? include "header.php" ?>
<body> 

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TTL2Q6"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<div id="wrapper" data-role="page" data-dom-cache="false" class="PROP_ID_<? print $RES_PROP_ID ?>">
    <? if (isset($BODY)) print $BODY ?>

    <? include $_SERVER['DOCUMENT_ROOT']."/mobile/page.analytics.php"; ?>

    <? //if (isset($_SESSION['AVAILABILITY'])) { print "<pre>";print_r($_SESSION['AVAILABILITY']);print "</pre>"; } ?>
</div>
</body>
</html>
