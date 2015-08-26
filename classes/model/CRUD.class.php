<?php
// PHP Document
/** @class: Model (PHP5-Strict with comments)
  * @project: PHP Simple ORM
  * @date: 26-08-2015
  * @version: 1.0.0_php5
  * @author: David Domingues
  * @contributors: 
  * @copyright: David Domingues
  * @email: dadomingues@gmail.com
  * @license: GNU General Public License (GPL)
  */
namespace model {
    class CRUD {
        var $model;
        var $tableName;
        var $arr_fields = array();
		
        public function __set($name, $value) 
        { 
            $this->$name = $value; 
        } 
        
        function __construct($model, $tableName) 
        {
            $arr_tables = array();
            $tableFound = false;
            $this->model=$model;
            $arr_tables = $this->model->getTables();

            foreach ($arr_tables as $table) {
                if ($table == $tableName)
                    $tableFound=true;
            }
            if (!$tableFound)
                die("This table does not exists in the database!");
            
            $this->tableName = $tableName;
            $this::createFieldsProp($tableName);

            return 0;
        }
        
        // Quote variable to make safe 
	private function quote_smart($value) 
	{ 
            // Stripslashes 
            if (get_magic_quotes_gpc()) { 
                $value = stripslashes($value); 
            } 
            // Quote if not a number or a numeric string 
            if (!is_numeric($value) || strlen($value) >= 10) { 
                $value = "'" . mysql_real_escape_string($value) . "'"; 
            } 
            return $value; 
	}
        
        private function is_timestamp($timestamp) {
            if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $timestamp, $matches)) { 
                    if (checkdate($matches[2], $matches[3], $matches[1])) { 
                        return true; 
                    } 
                } 
            return false; 
        }
        
        private function createFieldsProp($tableName) 
        {
            $this->arr_fields = $this->model->getFields($tableName);

            foreach($this->arr_fields as $columns) {
                if(!property_exists($this, $columns['Field'])) { 
                    $this->$columns['Field'] = '';
                }
            }
        }
        
        private function fillingvalues() 
        {
            //erase all values 
            foreach($this->arr_fields as &$columns) {
               $columns['Value'] = ''; 
            }
            //filling with specified 
            foreach($this->arr_fields as &$columns) {
                if(property_exists($this, $columns['Field'])) { 
                    $columns['Value'] = $this->$columns['Field'];
                }
            }
        }
        
        private function validadeColumn($column)
        {
            $return = false;
            //char and varchar
           
            if (strpos($column['Type'], 'char') !== false)
            {
//                if (substr($column['Value'], 0, 1) != "'")
//                    $column['Value'] = "'".$column['Value'];        
//                if (substr($column['Value'], strlen($column['Value']) - 1, 1) != "'")
//                    $column['Value'] = $column['Value']."'";
                
                print "<br/>".$column['Value'];
                print strpos($column['Type'], 'char');
                $return = true;
            }
            
            //decimal
            if (strpos($column['Type'], 'decimal') !== false) 
            {
                if (is_float($column['Value']) || is_int($column['Value'])) {
                    $return = true;
                } else {
                    $return = false; //"There's a problem with the value entered for field $column['Field']"; 
                }
            }
            
            //dates
            if (strpos($column['Type'], 'date') !== false)
            {
                $date = date_parse($column['Value']);

                if ($date["error_count"] == 0 && checkdate($date["month"], $date["day"], $date["year"])) {
                    $return = true;
                } else {
                    $return = false; //"There's a problem with the value entered for field $column['Field']"; 
                }
            }
            
            //Timestamps
            if (strpos($column['Type'], 'timestamp') !== false)
            {
                if ($this::is_timestamp($column['Value'])) {
                    $return = true;
                } else {
                    $return = false; //"There's a problem with the value entered for field $column['Field']"; 
                }
            }
            
            return $return;
        }
		
        function insert($tableName)
        { 
            $this::fillingvalues();
            $sql = "INSERT INTO $tableName ";
            $sqlFields = "(";
            $sqlValues = "VALUES (";
			
            foreach($this->arr_fields as $columns) {
                if ($this::validadeColumn($columns))
                {
                    $sqlFields.= $columns['Field'].", ";
                    $sqlValues.= $this::quote_smart($columns['Value']).", ";
                }
            }
            $sqlFields=  substr($sqlFields, 0, strlen($sqlFields) - 2) . ") ";            
            $sqlValues=  substr($sqlValues, 0, strlen($sqlValues) - 2) . ") ";

            $sql .= $sqlFields.$sqlValues;
            $result = $this->model->executeDb($sql);

            if (!$result['return']) {
                print "<br/>Error: ".$result['error'];
                print "<br/>Description: ".$result['errordesc'];
            }
            print $sql;
        }
        
        function delete($tableName)
        { 
            $this::fillingvalues();
            
            $sql = "DELETE FROM $tableName WHERE ";
            $sqlFieldsValues = "";
 			
            foreach($this->arr_fields as $columns) {
                if ($this::validadeColumn($columns)) {
                    $sqlFieldsValues.= $columns['Field']." = " .$this::quote_smart($columns['Value'])." AND ";
                }
            }
            $sqlFieldsValues=  substr($sqlFieldsValues, 0, strlen($sqlFieldsValues) - 5);            

            $sql .= $sqlFieldsValues;
            $result = $this->model->executeDb($sql);

            if (!$result['return']) {
                print "<br/>Error: ".$result['error'];
                print "<br/>Description: ".$result['errordesc'];
            }
            print $sql;
        }
    }
}
?>