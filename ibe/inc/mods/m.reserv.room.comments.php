<?
/*
 * Revised: Jul 11, 2011
 *          Oct 27, 2016
 */

$isEDIT = isset($isEDIT) ? $isEDIT : false;

if (!$isEDIT && isset($_SESSION['AVAILABILITY']['RES_REBOOKING']['CC_COMMENTS'])) {
    // Get previous CC Comments
    $RESERVATION['CC_COMMENTS'] = htmlspecialchars_decode($_SESSION['AVAILABILITY']['RES_REBOOKING']['CC_COMMENTS']);
} 
?>
<fieldset>
    <legend>Comments</legend>
    <div class="fieldset">
        <table width="100%" cellspacing="4" cellpadding="1">
        <tr>
            <td valign="top" nowrap>Comments and special requests<br><textarea style="width:100%;height:75px;" id="RES_GUEST_COMMENTS" name="RES_GUEST_COMMENTS"><? if (isset($RESERVATION['COMMENTS'])) print urldecode($RESERVATION['COMMENTS']) ?></textarea></td>
        </tr>
        <tr>
            <td valign="top" nowrap>Call Center Comments<br><textarea style="width:100%;height:75px;" id="CC_COMMENTS" name="CC_COMMENTS"><? if (isset($RESERVATION['CC_COMMENTS'])) print urldecode($RESERVATION['CC_COMMENTS']) ?></textarea></td>
        </tr>
        <tr>
            <td valign="top" nowrap>How did you hear about us?<br>
              <!-- <textarea style="width:100%;height:75px;" id="RES_GUEST_HEAR_ABOUT_US" name="RES_GUEST_HEAR_ABOUT_US"><? if (isset($RESERVATION['HEAR_ABOUT_US'])) print urldecode($RESERVATION['HEAR_ABOUT_US']) ?></textarea> -->
              <? print $clsGlobal->getHowDidYouHearAboutUs($db, array("HEAR_ABOUT_US"=>isset($RESERVATION['HEAR_ABOUT_US'])?urldecode($RESERVATION['HEAR_ABOUT_US']):"")) ?>
            </td>
        </tr>
        </table>
    </div>
</fieldset>
