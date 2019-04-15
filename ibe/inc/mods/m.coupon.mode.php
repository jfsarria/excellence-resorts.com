<?php
$check_all_rooms = "";

if (intval($_DATA['ID_CAB']) != 0 && (isset($_POST['export_excel']) && $_POST['export_excel'] == "export")) {
    ob_end_clean();

    $body = array(
        array(
            "PROMOCODE",
            "EMAIL"
        )
    );

    $result = $clsDiscounts->getByIdLin($db, array('ID_CAB' => $_DATA['ID_CAB']), "");
    while ($row = $db->fetch_array($result['rSet'])) {
        $body[] = array(
            $row['PROMOCODE'],
            $row['EMAIL']
        );
    }

    header("Content-Disposition: attachment; filename=mails-relation.xls");
    header('Content-type: application/vnd.ms-excel');
    header("Pragma: no-cache");
    header("Expires: 0");
    $out = fopen("php://output", 'w');
    foreach ($body as $row) {
        fputcsv($out, $row,"\t");
    }
    fclose($out);
    exit();
}

?>

<fieldset >
    <legend>Mode</legend>
    <div class="fieldset">
        <div class="label"></div>
        <div class="field">
             <input
                type='radio'
                name='multicode'
                id="multicode"
                value='multicode'
                <?php
                    if (isset($_DATA[0]['SYSTEM'])) {
                        if ($_DATA[0]['SYSTEM'] == "C_") {
                            echo "checked";
                        }
                    } else {
                        echo "checked";
                    }
                ?> />
                &nbsp;Group Code
             <input
                type='radio'
                name='multicode'
                id="unicode"
                value='unicode'
                <?php echo (isset($_DATA[0]['SYSTEM']) && $_DATA[0]['SYSTEM'] == "C_INF") ? "checked" : "" ?> />
                &nbsp;Individual Code
        </div>          
    </div>
</fieldset>
<fieldset id="excel_file_section" style="display: none;">
    <legend>Import Excel</legend>
    <div class="fieldset">
        <div class="label"></div>
        <div class="field">
            <input
                type="file"
                name="excel_file"
                accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
        </div>
    </div>
</fieldset>
<script type="text/javascript">
    $( function() {
        $('input[type=radio][name=multicode]').change(function(){
            if ($(this).val() == "unicode") {
                $('#excel_file_section').show();
                $(this).attr("checked", true);
                $('#multicode').removeAttr("checked");
            } else {
                $('#excel_file_section').hide();
                $(this).attr("checked", true);
                $('#unicode').removeAttr("checked");
            }
        });

        $('#exportButton').click(function(){
            $('#export_excel').val('export');
            $('#ACTION').val('SUBMIT');
            $('#editfrm').submit();
        });
    });
</script>







