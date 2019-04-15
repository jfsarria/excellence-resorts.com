<?
    $B_WEBSERVER = "";
    $B_REDIRECT_URL = "";

    $B_SERVER_NAME = strtolower($_SERVER["SERVER_NAME"]);
    if (strstr($B_SERVER_NAME,"excellence-resorts")!==FALSE || strstr($B_SERVER_NAME,"laamadahotel")!==FALSE || strstr($B_SERVER_NAME,"beloved")!==FALSE) {
        $B_WEBSERVER = "https://secure-excellence-resorts.com/";
        //$B_REDIRECT_URL = "https://secure-excellence-resorts.com/";
    } else {
        $B_WEBSERVER = "http://locateandshare.com/";
        //$B_REDIRECT_URL = "https://www.smprojects2.com/";
    }
    //print "<!-- \nB_WEBSERVER:{$B_WEBSERVER}\nB_REDIRECT_URL:{$B_REDIRECT_URL}\n -->";

    $B_WEBSERVER = "https://secure-excellence-resorts.com/";
?>