<?error_reporting(E_ALL ^ E_NOTICE);

phpinfo();
/*
	$filename = 'Report.xls';
	


	//require_once "Spreadsheet/Excel/Writer.php"; 
	

	$workbook =& new Spreadsheet_Excel_Writer(); 
	
	$workbook->send($filename); 
	
	$workbook->setVersion(8);
	
	
	$workbook->close();
	exit;