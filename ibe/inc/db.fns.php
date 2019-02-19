<?
/*
 * Revised: Apr 21, 2017
 *          Feb 20, 2018
 */

    // Initialize db object
    global $db;
    $db = new db; 

    $db->byPages = false;
    $db->rows_per_page = 15;
    $db->pageno = 1;

    function dbInit($db) {
        $db->set_cred(constant("APP_DB_SERVER"),constant("APP_DB_USER"),constant("APP_DB_PASS"));
        $db->db_connect();
        $db->select_db(constant("APP_DB_NAME"));
    }

    function dbClose($db) {
        $db->db_close();
    }

    function dbQuery($db, $arg) {
        if (defined('useSlaveDB') && constant("useSlaveDB")) {
          $db->query("SET GLOBAL log_bin_trust_function_creators = 1");
        }

        //mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
        $query = $arg['query'];
        $rSet = $db->query($query); // execute query
        $iCount = $db->num_rows($rSet); // Get the number of rows returned or affected
        if ($db->byPages) {
            $iLastPage = ceil($iCount/$db->rows_per_page);
            if ($db->pageno > $iLastPage) $db->pageno = $iLastPage;
            if ($db->pageno < 1) $db->pageno = 1;
            $iItemFrom = ($db->pageno - 1) * $db->rows_per_page;
            $itemTo = ( ($iItemFrom + $db->rows_per_page) <= $iCount) ? $db->rows_per_page : $iCount - $iItemFrom;

            $limitCmd = 'LIMIT '.$iItemFrom.','.$db->rows_per_page;

            $query = $query." ".$limitCmd;
            $rSet = $db->query($query); // execute query with limit for pagination

            $retArr = array('rSet' => $rSet, 'iCount' => $iCount, 'iPageNo' => $db->pageno, 'iLastPage' => $iLastPage, 'iItemFrom' => $iItemFrom+1, 'iItemTo' => $itemTo);
        } else {
            $retArr = array('rSet' => $rSet, 'iCount' => $iCount);
        }
        

        return $retArr;
    }

    function dbExecute($db, $arg) {
      if (defined('useSlaveDB') && constant("useSlaveDB")) {
          //ob_start();print "useSlaveDB :: MySQL Not Executed :: " . $arg['query'];$output=ob_get_clean();mail("juan.sarria@everlivesolutions.com","MySQL Not Executed",$output,"Content-type:text/html;charset=UTF-8");
          return 1;
      } else {
          //ob_start();print "MySQL Executed :: " . $arg['query'];$output=ob_get_clean();mail("juan.sarria@everlivesolutions.com","MySQL Executed",$output,"Content-type:text/html;charset=UTF-8");
          $query = $arg['query'];
          return $db->query($query); // execute query
      } 
    }

    function dbTableExists($db, $TableName) {
        $result = $db->db_tableExists($TableName);
        return $result;
    }

    function dbTableList($db) {
        $result = $db->db_tableList();
        return $result;
    }

    function dbLastAutoId() {
        return mysql_insert_id();
    }

    function dbNextId($db) {
        $arg = array('query' => "SELECT * FROM IBE_NEXT_ID");
        $result = dbQuery($db, $arg);

        $row = $db->fetch_array($result['rSet']);
        $nextId = (int)$row['NEXT_ID'];

        if ($nextId < 100000) {
          mail("jaunsarria@gmail.com","*** ERROR WITH NEXT_ID: $nextId ***","dbNextId \nnextId: $nextId");
        }

        $arg = array('query' => "UPDATE IBE_NEXT_ID SET NEXT_ID=".($nextId+1));
        $result = dbExecute($db, $arg);

        return $nextId;
    }

    function query_string_2_array($str){
        $retval = array();
        $array = explode("&",$str);
        foreach( $array as $i => $param) {
            $var = explode("=",$param);
            $retval[$var[0]] = $var[1];
        }
        return $retval;
    }

    function array_2_query_string($array){
        $query_array = array();
        foreach( $array as $key => $value ){
            $query_array[] = $key . '=' . urlencode( $value );
        }
        return implode( '&', $query_array );
    }


?>