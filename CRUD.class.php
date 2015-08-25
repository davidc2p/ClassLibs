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
    class CRUD {
        var $model;
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
                
                print $date["error_count"];
                if ($date["error_count"] == 0 && checkdate($date["month"], $date["day"], $date["year"])) {
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
            $result = $this->model->insert($sql);
            if (!$result)
                print "erro!!!!!";
            print "<br/>".$sql; 
        }
    }
}
?>