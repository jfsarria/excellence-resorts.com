<?

do {
  $m = isset($m) ? $m : 1;
  $START = isset($START) ? $START : $_TODAY;
  $STOP = addMonthsToDate($START, 1);
  $INIT = $m==1 ? $START : addDaysToDate($START, 1);

  print $START." - ".$STOP." - ".$m."<br>";

  // EXECUTE THE COMMAND IN THE BACGROUND
  $URL = "http://".$_SERVER["HTTP_HOST"];
  $command = "wget --background -q '{$URL}/ibe/meta_io/generate.php?START=$INIT&STOP=$STOP&RETURN=$m' -O /dev/null";
  print "$command <br>";
  //mail("jaunsarria@gmail.com","metaio",$command);
  //exec($command);

  $START = $STOP;

} while (++$m <= 12);
