<?
/*
 * Revised: Jun 22, 2011
 */
?>
<!DOCTYPE html>
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> -->
<html>
<head>
    <title>Internet Booking Engine <? print $_SERVER['SERVER_ADDR']; ?></title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <meta charset="utf-8">
    
    <script src="js/jquery-1.5.2.min.js" type="text/javascript"></script>
    <script src="js/jquery.prettyPhoto.js" type="text/javascript"></script>
    <script src="js/jquery.ui.core.js" type="text/javascript"></script>
    <script src="js/jquery.ui.widget.js" type="text/javascript"></script>
    <script src="js/jquery.ui.mouse.js" type="text/javascript"></script>
    <script src="js/jquery.ui.sortable.js" type="text/javascript"></script>
    <script src="js/jquery.json-2.2.min.js" type="text/javascript"></script>
    <script src="js/ibe.js" type="text/javascript"></script>

    <link  href="css/prettyPhoto.css" rel="stylesheet" type="text/css"/>
    <link  href="css/ibe.css" rel="stylesheet" type="text/css"/>
    <link  href="css/skin_default.css" rel="stylesheet" type="text/css"/>
<? print $tmpl->scripts ?>
</head>
<body class="<? print $BROWSER." ".$PLATFORM ?>">

<script type="text/javascript" src="cal/ng_all.js"></script>
<script type="text/javascript" src="cal/components/calendar.js"></script>

<!-- 1028 wide -->
<div id="wrapper">
    <? if (!$clsAuthentication->isLoginPage() && $clsAuthentication->isAuthenticated() && isset($_SESSION['AUTHENTICATION']) && isset($_SESSION['AUTHENTICATION']['FIRSTNAME'])) { ?>
    <div id="hdr">
        <div id="cmslogo">&nbsp;</div>
        <div id="cmsname"><h1>Internet Booking Engine</h1></div>
        <div id="cmsuser">
        <? print $_SESSION['AUTHENTICATION']['FIRSTNAME']." ".$_SESSION['AUTHENTICATION']['LASTNAME']."&nbsp;&nbsp;&nbsp;<a href='login.php'>Log Out</a>"; ?>
        </div>
        <div class="aclear"></div>
    </div>
    <div id="subhdr">
        <? include_once "tpl.topnav.php" ?>
    </div>
    <div id="main">
        <div id="leftcol" style="width:200px">
            <? include_once "tpl.leftnav.php" ?>
        </div>
        <div id="maincol">
            <div class="editarea">
                <? print $tmpl->body ?>
            </div>
        </div>
        <div class="aclear"></div>
    </div>
    <? } else { 
        print '<div id="hdr">&nbsp;</div>';
        include_once "inc/ibe.login.php";
    } ?>

    <div id="tooltip"></div>
</div>

<script>
    ibe.init();
</script>

</body>
<?
   //print "<div style='clear:both;padding-top:500px'></div><hr><pre>";print_r($_SESSION);print "</pre>";
?>
</html>
