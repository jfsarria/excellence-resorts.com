<?php
class db {
    var $hostname;// Hostname that the object should connect to
    var $username;// Username that the object should use
    var $password;// Password that the object should use
    var $database;// Database that the object should use
    var $query_num;// counts the total queries that the object has done. Some BBs do this. Might be of use for optimization etc

    var $byPages;
    var $rows_per_page;
    var $pageno;

    function set_cred($hostname,$username,$password) {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
    }

    function db_connect() {
        $result = mysql_connect($this->hostname,$this->username,$this->password); 
        if (!$result) {
            echo 'Connection to database server at: '.$this->hostname.' failed.';
            return false;
        }
                                                    
        return $result;
    }

    function db_close() {
        mysql_close();                               
    }

    function db_pconnect() {
        $result = mysql_pconnect($this->hostname,$this->username,$this->password); 
        if (!$result) {
            echo 'Connection to database server at: '.$this->hostname.' failed.';
            return false;
        }
                                                    
        return $result;
    }

    function db_tableList() {
        $result = mysql_list_tables(constant("APP_DB_NAME"));
        return $result;
        return "";
    }

    function db_tableExists($TableName) {
        $bRetVal = false;
        $result = mysql_list_tables(constant("APP_DB_NAME"));
        while ($row = mysql_fetch_array($result)) {
            //print "<br>$row[0]";
            // +001 RTS 01.03.2019
            if ($row[0] ==  strtolower($TableName) || $row[0] == strtoupper($TableName)) {
                $bRetVal = true;
                break;
            }
            // -001
        }
        mysql_free_result($result);
        return ($bRetVal);
    }

    function select_db($database) {
        $this->database = $database;
        if (!mysql_select_db($this->database)) {
            echo 'Selection of database: '.$this->database.' failed.';
            return false;
        }
    }

    function query($query) {
        $result = mysql_query($query) or $result = mysql_error();
        return $result;
    }
                            
    function fetch_array($result) {
        return mysql_fetch_array($result);
    }
                            
    function return_query_num() {
        return $this->query_num;
    }

    function num_rows($result) {
        return mysql_num_rows($result);
    }
};

?>