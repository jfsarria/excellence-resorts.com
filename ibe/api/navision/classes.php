<?php
// http://kb.mediatemple.net/questions/1947/Configure+PHP+with+SOAP#dv_managed

ini_set('default_socket_timeout', 600); 

include_once $_SERVER["DOCUMENT_ROOT"] . "/ibe/api/navision/XMLParser-master/XMLParser.class.php";

class navision_cls {

    public $SOAPClient;
    public $NAVISION_VARS;

    public function __construct() {
      // http://216.152.171.246/vivaCRS.svc
      // http://216.152.171.246:8081/vivaCRS.svc

      $SERVER_NAME = $_SERVER['SERVER_NAME'];
      $isStaging = false;//$SERVER_NAME=="staging.finestresorts.com" || $SERVER_NAME=="hoopsydoopsy.com" || $SERVER_NAME=="locateandshare.com";

      //$this->SOAPClient = "http://216.152.171.246".($isStaging?":8081":"")."/vivaCRS.svc?wsdl";

      $this->SOAPClient = "http://190.112.225.243".($isStaging?":8081":"")."/vivaCRS.svc?wsdl";
      $this->set_navision_vars();
    }

    public function XMLParser($DATA=array()) {
      $obj_xml = XMLParser::encode($DATA,'ROOT');

      $xml = $obj_xml->asXML();
      $xml = preg_replace_callback(
          '|</*([0-9a-zA-Z_\-]+)/*>|',
          function ($matches) {
              //print "<pre>";print_r($matches);print "</pre>";
              return strtoupper($matches[0]);
          },
          $xml
      );
      $xml = trim(preg_replace('/(\-\d+)*>/', '>', $xml));
      $xml = preg_replace('|<\?xml version="1.0"\?>|',"",$xml);

      $xml = str_replace("&aacute;","á",$xml);
      $xml = str_replace("&Aacute;","Á",$xml);
      $xml = str_replace("&aacute;","á",$xml);
      $xml = str_replace("&Eacute;","É",$xml);
      $xml = str_replace("&eacute;","é",$xml);
      $xml = str_replace("&Iacute;","Í",$xml);
      $xml = str_replace("&iacute;","í",$xml);
      $xml = str_replace("&Oacute;","Ó",$xml);
      $xml = str_replace("&oacute;","ó",$xml);
      $xml = str_replace("&Ntilde;","Ñ",$xml);
      $xml = str_replace("&ntilde;","ñ",$xml);
      $xml = str_replace("&Uacute;","Ú",$xml);
      $xml = str_replace("&uacute;","ú",$xml);
      $xml = str_replace("&Uuml;","Ü",$xml);
      $xml = str_replace("&uuml;","ü",$xml);
      $xml = str_replace("&iexcl;","¡",$xml);
      $xml = str_replace("&ordf;","ª",$xml);
      $xml = str_replace("&iquest;","¿",$xml);
      $xml = str_replace("&ordm;","º",$xml);

      $pattern = '/\&([a-zA-Z])*\;/i';
      $replacement = '';
      $xml = preg_replace($pattern, $replacement, $xml);

      return trim($xml);
    }

    public function execute($DATA, $XML="") {
      try {
        $client = new SOAPClient($this->SOAPClient, array('trace' => 1, 'encoding'=>'UTF-8', 'connection_timeout' => 10, 'exceptions' => 0));
        //print "XML:\n\n".htmlspecialchars($XML);return;
        
        file_put_contents($_SERVER['DOCUMENT_ROOT']."/ibe/api/navision/XML_SENT/{$DATA['ID']}.xml",$XML) ;

        $response = $client->GetProcess(array("pPeticionXml" => $XML));
        return $response;
      } catch (Exception $e) {
        echo $e->getMessage();
      }

	  //return array();
    }

    public function removeXMLversion($str) {
      return preg_replace('|<\?xml version="1.0"\?>(\r\n)*|',"",$str);
    }

    public function str2json($str) {
      $str = $this->removeXMLversion($str);
      $arr = simplexml_load_string($str);
      return json_encode($arr);
    }

    public function switchDate($DATE) {
      return substr($DATE,8,2) . "/" . substr($DATE,5,2) . "/" . substr($DATE,0,4);
    }

    public function addDaysAndSwitchDate($DATE, $DAYS) {
        $date = strtotime($DATE);
        $date = strtotime("+{$DAYS} day", $date);
        return date('d/m/Y', $date);
    }

    public function set_navision_vars() {
      // NAVISION VARIABLES
      $this->NAVISION_VARS = array(
        "P3" => array( // XPC
          "CA" => array("TTOO" => "WEBEXC-CAN", "AGENCIA" => "CARRIBEAN", "CLIENTE" => "WEBEXC-USD"),
          "XX" => array("TTOO" => "WEBEXC-UK", "AGENCIA" => "CARRIBEAN", "CLIENTE" => "WEBEXC-USD"),
          "US" => array("TTOO" => "WEBEXC-USD", "AGENCIA" => "CARRIBEAN", "CLIENTE" => "WEBEXC-USD"),
          "DO" => array("TTOO" => "DIRECTOS WEB-DOP", "AGENCIA" => "DIRECTOS WEB-DOP", "CLIENTE" => "DIRECTO-DOP")
        ),
        "P2" => array( // XPM
          "CA" => array("TTOO" => "WEBEXC-CAN", "AGENCIA" => "LUXCARIBE", "CLIENTE" => "WEBEXC-USD"),
          "XX" => array("TTOO" => "WEBEXC-UK", "AGENCIA" => "LUXCARIBE", "CLIENTE" => "WEBEXC-USD"),
          "US" => array("TTOO" => "WEBEXC-USD", "AGENCIA" => "LUXCARIBE", "CLIENTE" => "WEBEXC-USD"),
          "MX" => array("TTOO" => "DIRECTOS WEB-MXN", "AGENCIA" => "DIRECTOS WEB-MXN", "CLIENTE" => "DIRECTO-MXN")
        ),
        "P1" => array( // XRC
          "CA" => array("TTOO" => "WEBEXC-CAN", "AGENCIA" => "CARRIBEAN", "CLIENTE" => "WEBEXC-USD"),
          "XX" => array("TTOO" => "WEBEXC-UK", "AGENCIA" => "CARRIBEAN", "CLIENTE" => "WEBEXC-USD"),
          "US" => array("TTOO" => "WEBEXC-USD", "AGENCIA" => "CARRIBEAN", "CLIENTE" => "WEBEXC-USD"),
          "MX" => array("TTOO" => "DIRECTOS WEB-MXN", "AGENCIA" => "DIRECTOS WEB-MXN", "CLIENTE" => "DIRECTO-MXN")
        ),
        "P7" => array( // XOB
          "CA" => array("TTOO" => "WEBEXC-CAN", "AGENCIA" => "LUXCARIBE", "CLIENTE" => "WEBEXC-USD"),
          "XX" => array("TTOO" => "WEBEXC-UK", "AGENCIA" => "LUXCARIBE", "CLIENTE" => "WEBEXC-USD"),
          "US" => array("TTOO" => "WEBEXC-USD", "AGENCIA" => "LUXCARIBE", "CLIENTE" => "WEBEXC-USD"),
          "JM" => array("TTOO" => "DIRECTOS WEB-JMD", "AGENCIA" => "DIRECTOS WEB-JMD", "CLIENTE" => "DIRECTOS WEB-JMD")
        ),
        "PCC" => array( // CALL CENTER
          //"CA" => array("TTOO" => "WEBEXC-CAN", "AGENCIA" => "LUXCARIBE", "CLIENTE" => "WEBEXC-USD"),
          //"UK" => array("TTOO" => "WEBEXC-UK", "AGENCIA" => "LUXCARIBE", "CLIENTE" => "WEBEXC-USD"),
          //"US" => array("TTOO" => "WEBEXC-USD", "AGENCIA" => "LUXCARIBE", "CLIENTE" => "WEBEXC-USD"),
          "XX" => array("TTOO" => "DIRECTOS WEB-MXN", "AGENCIA" => "DIRECTOS WEB-MXN", "CLIENTE" => "DIRECTO-MXN")
        ),
      );
      $this->NAVISION_VARS['P4'] = $this->NAVISION_VARS['P2'];
      $this->NAVISION_VARS['P5'] = $this->NAVISION_VARS['P2'];
      $this->NAVISION_VARS['P6'] = $this->NAVISION_VARS['P3'];
    }

    public function get_navision_var($VAR_NAME, $PROP_ID, $COUNTRY_CODE, $RES_SRC) {
      //$PROP_ID = $RES_SRC=="CC" ? "CC" : $PROP_ID;
      return isset($this->NAVISION_VARS["P{$PROP_ID}"][$COUNTRY_CODE]) ? $this->NAVISION_VARS["P{$PROP_ID}"][$COUNTRY_CODE][$VAR_NAME] : $this->NAVISION_VARS["P{$PROP_ID}"]["XX"][$VAR_NAME];
    }

    public function cancel_reservation($db, $arg) {
      extract($arg);

      ob_start();

      $call = "http://{$_SERVER['HTTP_HOST']}/ibe/index.php?PAGE_CODE=ws.navisionCall&RES_ID={$ID}";
      $output = file_get_contents($call);
      //$clsGlobal->sendEmail(array("IS_INTERNAL"=>1,"SUBJECT"=>"CANCEL NAVISION $CANCEL_NUM :: 3","MESSAGE"=>$output,"FROM"=>"jaunsarria@gmail.com","TO"=>"jaunsarria@gmail.com"));
      print $call."<BR><BR>$output<hr>";

      $OUT = ob_get_clean();

      //mail("jaunsarria@gmail.com","CANCEL",$OUT);

    }

    public function cleanUpStr($str) {
        $str = urldecode($str);

    }

    function hyphenize($string) {
        return  preg_replace(array('#[\\s-]+#', '#[^A-Za-z0-9\. -]+#'), array(' ', ''), urldecode($string));
    }
}