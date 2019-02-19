<?
/*
 * Revised: Jul 16, 2011
 */

if (!$isWEBSERVICE) {
    include_once "cls/ibe.auth.cls.php";
    $isLogin = !$clsAuthentication->isAuthenticated() && !$clsAuthentication->isLoginPage();

    if ($isLogin) {
        header("Location: login.php?REDIRECT_TO=".urlencode($_SERVER['REQUEST_URI'])."");
    }
}
?>