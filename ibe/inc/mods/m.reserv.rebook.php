<?
    $TRANSFER_FEE = isset($JSON['RESERVATION']['TRANSFER_FEE']) ? $JSON['RESERVATION']['TRANSFER_FEE'] : "";
    $TRANSFER_CAR = isset($JSON['RESERVATION']['TRANSFER_CAR']) ? $JSON['RESERVATION']['TRANSFER_CAR'] : "";
?>
<div style="text-align:center">
    <b>Redirecting to get Availability and Rates Screen...</b>
    <script>
        document.location.href = "?PAGE_CODE=availability&REBOOK_RES_ID=<? print $ID ?>&REBOOK_RES_NUM=<? print $RESVIEW['NUMBER'] ?>&REBOOK_RES_CODE=<? print $CODE ?>&REBOOK_RES_YEAR=<? print $YEAR ?>&REBOOK_PROP_ID=<? print $JSON['RES_PROP_ID'] ?>&REBOOK_GUEST_ID=<? print $RESVIEW['GUEST_ID'] ?>&REBOOK_CHECK_IN=<? print $RESVIEW['CHECK_IN'] ?>&REBOOK_ROOMS=<? print $RESVIEW['ROOMS'] ?>&REBOOK_TOTAL_CHARGE=<? print $JSON['RESERVATION']['RES_TOTAL_CHARGE'] ?>&REBOOK_NIGHTS=<? print $JSON['RES_NIGHTS'] ?>&REBOOK_TO_WHOM=<? print $JSON['RESERVATION']['FORWHOM']['RES_TO_WHOM'] ?>&REBOOK_GUEST_ID=<? print $JSON['RESERVATION']['FORWHOM']['RES_GUEST_ID'] ?>&REBOOK_TA_ID=<? print $JSON['RESERVATION']['FORWHOM']['RES_TA_ID'] ?>&REBOOK_CC_COMMENTS=<? print urlencode($RESVIEW['CC_COMMENTS']) ?>&REBOOK_TRANSFER_FEE=<? print $TRANSFER_FEE ?>&REBOOK_TRANSFER_CAR=<? print $TRANSFER_CAR ?>";
    </script>
</div>
