<?
/*
 * Revised: Jul 07, 2011
 */

include_once "tpl.modules.get.php";
foreach($_MODULES as $MOD_KEY => $MOD_FILE) {
    if (file_exists("{$_APP_ROOT}inc/mods/{$MOD_FILE}")) {
        //print "mods/$MOD_FILE";
        include_once "mods/$MOD_FILE";
    }
}
?>