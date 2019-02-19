<?
/*
 *          Feb 20, 2018
 */

    //mail("juan.sarria@everlivesolutions.com","QUERY_STRING ",$_SERVER["QUERY_STRING"],"Content-type:text/html;charset=UTF-8");
    //ob_start();print "\n\n" . $_SERVER['SERVER_NAME'] . "\n" . date("Y-m-d h:i:sa") . "\n";print_r($_REQUEST);$output = ob_get_clean();file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/ibe/cnf/cnf.txt", $output, FILE_APPEND);

    /*
    if (strstr($_SERVER["QUERY_STRING"], "useSlaveDB=1")!==false || (isset($_REQUEST['useSlaveDB']) && (int)$_REQUEST['useSlaveDB']==1)) {
        define("useSlaveDB", true);
        define("APP_DB_SERVER", "72.10.53.72");
        define("APP_DB_NAME", "admin_ibe");
    } else {
     */
        define("useSlaveDB", false);
        define("APP_DB_SERVER", "localhost");
        define("APP_DB_NAME", "admin_ibe");
    //}

    define("APP_DB_USER", "juandb");
    define("APP_DB_PASS", "V!113g@s");

    //ob_start();print "APP_DB_SERVER ::  ".constant("APP_DB_SERVER");print "\n";print_r($_REQUEST);print "\n\n";$output = ob_get_clean();file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/ibe/cnf/cnf.txt", $output, FILE_APPEND);