<?
/*
 * Revised: Jun 23, 2011
 */

$PARAM = isset($_GET['PARAM']) ? $_GET['PARAM'] : "";
$lista=explode("-", $PARAM);


?>
<fieldset>
    <legend>Select Email Address&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick='ibe.flashsale.close()'><img src="css/img/cross.png" width="16" height="16" border="0" alt=""></a></legend>
    <div class="fieldset">
     <fieldset>
    <input type="hidden" name="ACTION" id="ACTION" VALUE="SAVE">
    <input type="hidden" name="RELACION" id="RELACION" VALUE="1">
        <input type="hidden" name="PROP_ID" id="PROP_ID" VALUE="<? print (isset($lista[0])) ? $lista[0] : "1" ?>">
        <input type="hidden" name="ID_CAB" id="ID_CAB" VALUE="<? print (isset($lista[2])) ? $lista[2] : "0" ?>">
        <input type="hidden" name="ID_LIN" id="ID_LIN" VALUE="<? print (isset($lista[1])) ? $lista[1] : "0" ?>">


    <div class="fieldset RES_TO_WHOM" id='callcenter_RES_TO_WHOM_GUEST'>
        <script>ibe.flashsale.open("RES_TO_WHOM_GUEST")</script>
        <table>
        
        <tr>
            <td><input type="text" id="RES_SEARCH_GUEST_BY_EMAIL" ></td>
            <td nowrap><a href="javascript:void(0)" onclick="ibe.reserv.searchContact('#RES_SEARCH_GUEST_BY_EMAIL','EMAIL','searchGuest')">Search by Email</a></td>
        </tr>
        </table>
        <div id='searchGuestResult' class="RES_TO_WHOM"></div>
        <div id="m_reserv_forwhom_guest_next" class="RES_TO_WHOM" style='display:none'>
           <!-- <span class="button" onclick="ibe.reserv.forWhom.Next_ExistingGuest()">Next Step &#187;</span>-->
           <a onclick="$('#ACTION').val('SUBMIT');$('#editfrm').submit()"><span class="button key">Save</span></a>
        </div>
    </div>
</fieldset>   
    <!--
        <div id='top' style='display:none'><? print $TOP ?></div>
        <b><? print "Coupon"; ?></b>
        <input type="hidden" name="UPDATE_ROOM_ID" value="<? print $ROOM_ID ?>">
        <input type="hidden" name="UPDATE_RES_DATE" value="<? print $RES_DATE ?>">
        <table style='margin-top:20px'>
        <tr>
            <td nowrap>Date:</td>
            <td nowrap><? print date("l, F j, Y",strtotime($RES_DATE)) ?></td>
        </tr>
        <tr>
            <td nowrap>Sold:</td>
            <td nowrap><? print $SOLD ?></td>
        </tr>
        <tr>
            <td nowrap>Rooms:</td>
            <td nowrap><? print $MAX_ROOMS ?></td>
        </tr>
        <tr>
            <td nowrap>Status:</td>
            <td nowrap>
                <input type="radio" name="UPDATE_STATUS" value='open' <? if ($STATUS=="open") print "checked" ?> >&nbsp;Open
                &nbsp;&nbsp;&nbsp;
                <input type="radio" name="UPDATE_STATUS" value='close' <? if ($STATUS=="close") print "checked" ?> >&nbsp;Close
            </td>
        </tr>
        <tr>
            <td nowrap>Add Rooms:</td>
            <td nowrap><input type="text" name="UPDATE_ROOMS" value="<? print $ADD_ROOMS ?>" style="width:70px"></td>
        </tr>
        </table>
        <div class='frmBtns'>
            <a onclick="$('#ACTION').val('SUBMIT');$('#editfrm').submit()"><span class="button key">Save</span></a>
        </div>
    -->
    </div>
</fieldset>
<?
$OUT = ob_get_clean();

print $OUT;

?>
