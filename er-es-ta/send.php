<?
    
    $mailsubject="IATA#".$_POST["IATA"].": My vacation";
    
 
    $mailheader.= "From: ".$_POST["NAME"]." <".$_POST["EMAIL"].">\n";
    $mailheader.= "Bcc: mirek@artbymobile.com \n";
    /* mirek@artbymobile.com */
 
    $mailbody = $_POST["VACATION"];
 
    print mail("info@excellence-resorts.com", $mailsubject, $mailbody, $mailheader);
?>