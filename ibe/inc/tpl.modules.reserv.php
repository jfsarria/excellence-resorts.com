<?
/*
 * Revised: Jul 13, 2011
 */

include "tpl.modules.reserv.get.php";

foreach($_RMODULES as $RMOD_KEY => $RMOD_FILE) {
    if (file_exists("{$_APP_ROOT}inc/mods/{$RMOD_FILE}")) {
        include_once "mods/$RMOD_FILE";
    }
}

?>