<?php
// ************************************************
// This file has been written by David Domingues
// you are free to use it and change it as you need
// but i will ask you to keep this header on the file
// and never remove it.
// webrickco@gmail.com
// ************************************************
// PHP Document
namespace webrickco\model {
    class database {
        var $hostname;
        var $database;
        var $admin;
        var $password;
        var $prefix;
        var $db;
        var $path;
        var $arr_tables = array();
        
        function __construct($hostname, $database, $admin, $password, $prefix) 
	{
            $this->hostname		= $hostname;
            $this->database		= $database;
            $this->admin		= $admin;
            $this->password		= $password;
            $this->prefix		= $prefix;

            $db = mysql_connect ($this->hostname, $this->admin, $this->password) or die('Database access denied!');
            mysql_select_db ($this->database, $db);
            mysql_query("SET NAMES 'utf8'");
            mysql_query('SET character_set_connection=utf8');
            mysql_query('SET character_set_client=utf8');
            mysql_query('SET character_set_results=utf8');	

            $this->db = $db;

            $this->generic = new \webrickco\utils\Generic($this->hostname, $this->database, $this->admin, $this->password, $this->prefix);

            return 0;
        }
        
        function getTables()
        { 
            $arr_tables = array();
            $sql = "SHOW TABLES FROM $this->database";
            $result = mysql_query($sql);

            if (!$result) {
                echo "DB Error, could not list tables\n";
                echo 'MySQL Error: ' . mysql_error();
                exit;
            }

            while ($row = mysql_fetch_row($result)) {
		array_push($arr_tables, $row[0]);
				
                //echo "Table: {$row[0]}<br/>";
            }

            mysql_free_result($result);
            return $arr_tables;
        }
		
	function viewTables()
        { 
            $sql = "SHOW TABLES FROM $this->database";
            $result = mysql_query($sql);

            if (!$result) {
                echo "DB Error, could not list tables\n";
                echo 'MySQL Error: ' . mysql_error();
                exit;
            }

            while ($row = mysql_fetch_row($result)) {
                print_r($row[0]);
                print '<br/>';
            }

            mysql_free_result($result);
        }
		
        function getFields($tableName)
        {
            $arr_fields = array();
            $result = mysql_query("SHOW COLUMNS FROM $tableName");
            if (!$result) {
                echo 'Could not run query: ' . mysql_error();
                exit;
            }

            if (mysql_num_rows($result) > 0) {
                while ($row = mysql_fetch_assoc($result)) {
                        $row['Value'] = '';
                        array_push($arr_fields, $row);
                }
                //foreach($arr_fields as $key => $val)
                //       $arr_fields[$key]['value'] = '';
            }
            //print_r($arr_fields);
            return $arr_fields;
        }

        function viewFields($tableName)
        {
            $result = mysql_query("SHOW COLUMNS FROM $tableName");
            if (!$result) {
                echo 'Could not run query: ' . mysql_error();
                exit;
            }

            if (mysql_num_rows($result) > 0) {
                while ($row = mysql_fetch_assoc($result)) {
                    $row['Value'] = '';
                    print_r($row);
                    print '<br/><br/>';
                }
            }
        }
        
        function insert($sql)
        {
            $response=mysql_query($sql, $this->db);
		
            if (!$response) {
                print mysql_error($this->db);
		$ret = false;
            }
            else
            {
                $ret = true;
            }
            return $ret;
        }
    }
}
?>