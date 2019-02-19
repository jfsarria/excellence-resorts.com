<?
/*
 * Revised: Jul 11, 2011
 */

?>

<table id='reservDetailScreen' width="100%" cellspacing="2">
<tr>
    <td valign='top' width="50%">
        <? if (file_exists("{$_APP_ROOT}inc/mods/{$_MODULES['reserv']}")) include_once "inc/mods/{$_MODULES['reserv']}"; ?>
        <? if (file_exists("{$_APP_ROOT}inc/mods/{$_MODULES['prefer']}")) include_once "inc/mods/{$_MODULES['prefer']}"; ?>
    </td>
    <td valign='top' width="50%">
        <? if (file_exists("{$_APP_ROOT}inc/mods/{$_MODULES['guest']}")) include_once "inc/mods/{$_MODULES['guest']}"; ?>
        <? if (file_exists("{$_APP_ROOT}inc/mods/{$_MODULES['ta']}")) include_once "inc/mods/{$_MODULES['ta']}"; ?>
        <? if (file_exists("{$_APP_ROOT}inc/mods/{$_MODULES['payment']}")) include_once "inc/mods/{$_MODULES['payment']}"; ?>
        <? if (file_exists("{$_APP_ROOT}inc/mods/{$_MODULES['comments']}")) include_once "inc/mods/{$_MODULES['comments']}"; ?>
        <? if (file_exists("{$_APP_ROOT}inc/mods/{$_MODULES['history']}")) include_once "inc/mods/{$_MODULES['history']}"; ?>
    </td>
</tr>
</table>