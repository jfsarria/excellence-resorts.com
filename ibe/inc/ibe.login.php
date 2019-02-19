<?
/*
 * Revised: Apr 30, 2011
 */

$ACTION = (isset($_REQUEST['ACTION'])) ? $_REQUEST['ACTION'] : "";
$isOk = false;

if ($ACTION == "POST") {
    if ($clsAuthentication->login($db, $_POST)) {
        $isOk = true;
    }
} else {
    $clsAuthentication->logOff();
}

//print "<pre>";print_r($_SESSION['AUTHENTICATION']);print "</pre>";

if (!$isOk) { ?>
    <div id="loginWrap">
        <form id="loginFrm" method="post">
            <input type="hidden" name="ACTION" value="POST">
            <div id="loginForm">
                <h2>Sign in <span><? print $_SERVER['SERVER_ADDR']; ?></span></h2>
                <div class="box">
                    <input type="hidden" name="REDIRECT_TO" value="<? print (isset($_GET['REDIRECT_TO']))?$_GET['REDIRECT_TO']:""?>">
                    <input type="text" name="P_ADMIN_USER" style="width:200px">
                    <input type="password" name="P_ADMIN_PWD" style="width:200px">
                    <div class="aclear"></div>
                </div>
                <div>
                    <a onclick="$('#loginFrm').submit()"><span class="button key">Sign In</span></a>
                </div>
            </div>
        </form>
        <!--
        <span class="button">Standard</span>
        <span class="button positive">positive</span>
        <span class="button negative">negative</span>
        <span class="button key">negative</span>
        -->
    </div>
<? } else { ?>
    <script>
        document.location.href = "<? print (isset($_GET['REDIRECT_TO']))?$_GET['REDIRECT_TO']:"index.php?start"?>";
    </script>
<? } ?>