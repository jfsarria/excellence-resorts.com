<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <title>Submit to Navision</title>
 </head>
 <body style="margin:30px">

    <form action="" method="post">
      Reservation number:<BR>
      <input type="text" name="RESERVATION" value="<?=$_POST["RESERVATION"]?>" style="width:200px">
      <br><br>
      Force:<br>
      <input name="FORCE" type="radio" value="" <?=$_POST["FORCE"]==""?"checked":""?>> No<br>
      <input name="FORCE" type="radio" value="RESERVAR" <?=$_POST["FORCE"]=="RESERVAR"?"checked":""?>> RESERVAR<br>
      <input name="FORCE" type="radio" value="UPDATE" <?=$_POST["FORCE"]=="UPDATE"?"checked":""?>> UPDATE<br>
      <input name="FORCE" type="radio" value="ELIMINAR" <?=$_POST["FORCE"]=="ELIMINAR"?"checked":""?>> DELETE<br>
      <BR>
      <input type="submit" value="Sunmit">
    </form>

    <?
      if (isset($_POST["RESERVATION"])) {
        $URL = "http://{$_SERVER['HTTP_HOST']}/ibe/index.php?PAGE_CODE=ws.navisionCall&NUM={$_POST['RESERVATION']}&FORCE={$_POST['FORCE']}";
        print "<hr>$URL<br><br>";
        print "Result: ".file_get_contents($URL);
      }

    ?>
  
 </body>
</html>
