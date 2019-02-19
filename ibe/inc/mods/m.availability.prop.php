<?
/*
 * Revised: May 11, 2011
 */
?>

<? if ($_DATA['RES_LANGUAGE']!="") { ?>
    <fieldset class='reserv_fieldset'>
        <legend>Reservation Property</legend>
        <div class="fieldset">
            <div class="label">
                <table width="100%" id='RES_PROPS'>
                <tr>
                    <td style='width:250px;padding-right:50px' valign='top'><? print $clsUsers->propertiesRadioBtns($db, $_DATA['RES_PROP_ID']) ?></td>
                    <td valign='top'><? print $clsUsers->propertiesDescription($db, array("LANGUAGE"=>$_DATA['RES_LANGUAGE'])) ?></td>
                </tr>
                </table>
            </div>
        </div>
    </fieldset>
    <script>
        ibe.callcenter.reserv.showdescr('<? print $_DATA['RES_PROP_ID'] ?>');
        $("#RES_PROPS input[type='radio']").click(function() {
            oRoom.RES_PROP_ID = $(this).val();
            $("#RES_ROOMS_QTY").val(1);
            ibe.callcenter.showRoomBoxes(1);
        });
    </script>
<? } ?>