<?php
$xml = "
	<PRECIOS-13>
		<P_FEC>26/07/2016</P_FEC>
		<P_PRE>423</P_PRE>
	</PRECIOS-13>
	<PRECIOS-9>
		<P_FEC>26/07/2016</P_FEC>
		<P_PRE>423</P_PRE>
	</PRECIOS-9>
	<PRECIOS-14>
		<P_FEC>27/07/2016</P_FEC>
		<P_PRE>423</P_PRE>
	</PRECIOS-14>
";


      $xml = trim(preg_replace('/(\-\d+)*>/', '>', $xml));

print $xml;