<?
/*
 * Revised: Aug 02, 2011
 *          Feb 11, 2014
 */

?>
<form id="editfrm" method="post" enctype="multipart/form-data" action="<? print $THIS_PAGE ?>">
    <input type="hidden" name="SUBMIT" value="SUBMIT">
    <input type="hidden" name="MODIFY" value="<? print $MODIFY ?>">
    <?
    switch ($MODIFY) {
        case "CANCEL":
            include_once "inc/mods/{$_MODULES['reserv']}";
            break;
        case "NOSHOW":
            include_once "inc/mods/{$_MODULES['reserv']}";
            break;
        case "SUPPLEMENT":
            include_once "inc/mods/{$_MODULES['reserv']}";
            break;
        case "TRANSFER":
            // inc/mods/m.edit_reserv.reserv.php
            include_once "inc/mods/{$_MODULES['reserv']}";
            break;
        case "TRANSFER_CANCEL":
            include_once "inc/mods/{$_MODULES['reserv']}";
            break;
        case "RESERV":
            include_once "inc/mods/{$_MODULES['reserv']}";
            break;
        case "GUEST":
            include_once "inc/mods/{$_MODULES['guest']}";
            break;
        case "PAYMENT":
            include_once "inc/mods/{$_MODULES['payment']}";
            break;
        case "COMMENTS":
            include_once "inc/mods/{$_MODULES['comments']}";
            break;
        case "OPTIONALS":
            include_once "inc/mods/{$_MODULES['prefer']}";
            break;
        case "INVENTORY":
            include_once "inc/mods/m.edit_reserv.inventory.php";
            break;
    }
    if ($SUBMIT=="" && $MODIFY!="RESERV") {?>
        <div style='text-align:center;margin-top:10px'>
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="<? print $THIS_PAGE ?>"><span class="button plain">Go Back</span></a>
        </div>
    <? } ?>
</form>
