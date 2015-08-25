<?php
// ************************************************
// This file has been written by David Domingues
// you are free to use it and change it as you need
// but i will ask you to keep this header on the file
// and never remove it.
// comLogin downloaded at http://www.webrickco.com
// webrickco@gmail.com
// ************************************************
// PHP Document
namespace webrickco\utils {
	class Generic
	{
		var $hostname;
		var $database;
		var $admin;
		var $password;
		var $prefix;
		var $db;
		
		var $language;
		
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
			
			return $this->db;
		}	
		
		// Quote variable to make safe 
		function quote_smart($value) 
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
	 
		protected function generatecode($length)
		{
			$str = '';
			for ($i=1; $i<=$length; $i++)
			{
				$set = array(rand (65,90));
				$str .= chr($set[rand(0,0)]);
			}
			return $str;
		}
		
		function generatecodeloweraZ($length)
		{
			$str = '';
			for ($i=1; $i<=$length; $i++){
			$set = array(rand (65,90),rand(97,122));
			$str .= chr($set[rand(0,1)]);
			}
			return $str;
		}
		function generatecodeupperAZ($length)
		{
			$str = '';
			for ($i=1; $i<=$length; $i++){
			$set = array(rand (65,90));
			$str .= chr($set[rand(0,0)]);
			}
			return $str;
		}

		function textcutting($text, $len) {
			$retorno = '';
			$split = array();
			
			if (strlen($text) <= $len) {	
				$retorno = $text;
			} else {
				$split = explode(' ', $text);
				$i = 0;
				while (strlen($retorno) <= $len && $i < count($split)) {
					$retorno .= $split[$i].' ';
					$i++;
				}
				$retorno .= '...';
			}	
			return $retorno;	
		}
		
		/*
		Sanitize class
		Copyright (C) 2007 CodeAssembly.com  
		This program is free software: you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation, either version 3 of the License, or
		(at your option) any later version.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program.  If not, see http://www.gnu.org/licenses/
		*/
		
		/**
		 * Sanitize only one variable .
		 * Returns the variable sanitized according to the desired type or true/false 
		 * for certain data types if the variable does not correspond to the given data type.
		 * 
		 * NOTE: True/False is returned only for telephone, pin, id_card data types
		 *
		 * @param mixed The variable itself
		 * @param string A string containing the desired variable type
		 * @return The sanitized variable or true/false
		 */
		
		function sanitizeOne($var, $type)
		{       
			$var = str_replace("<script>", "", $var);
			$var = str_replace("</script>", "", $var);
			$var = str_replace("alert", "", $var);
			$var = str_replace("document", "", $var);
			$var = str_replace("<", "", $var);
			$var = str_replace(">", "", $var);
			
			//echo $var;
			
			switch ( $type ) {
				case 'int': // integer
					$var = (int) $var;
					break;

				case 'str': // trim string
					$var = trim ( $var );
					break;
					
				case 'nocode': // trim string
					$var = strip_tags( $var );
					break;

				case 'nohtml': // trim string, no HTML allowed
					$var = htmlentities ( trim ( $var ), ENT_QUOTES );
					break;

				case 'plain': // trim string, no HTML allowed, plain text
					$var =  htmlentities ( trim ( $var ) , ENT_NOQUOTES )  ;
					break;

				case 'upper_word': // trim string, upper case words
					$var = ucwords ( strtolower ( trim ( $var ) ) );
					break;

				case 'ucfirst': // trim string, upper case first word
					$var = ucfirst ( strtolower ( trim ( $var ) ) );
					break;

				case 'lower': // trim string, lower case words
					$var = strtolower ( trim ( $var ) );
					break;
		
				case 'urle': // trim string, url encoded
					$var = urlencode ( trim ( $var ) );
					break;

				case 'trim_urle': // trim string, url decoded
					$var = urldecode ( trim ( $var ) );
					break;
						
				case 'telephone': // True/False for a telephone number
					$size = strlen ($var) ;
					for ($x=0;$x<$size;$x++)
					{
						if ( ! ( ( ctype_digit($var[$x] ) || ($var[$x]=='+') || ($var[$x]=='*') || ($var[$x]=='p')) ) )
						{
							return false;
						}
					}
					return true;
					break;
				
				case 'pin': // True/False for a PIN
					if ( (strlen($var) != 13) || (ctype_digit($var)!=true) )
					{
						return false;
					}
					return true;
					break;
			
				case 'id_card': // True/False for an ID CARD
					if ( (ctype_alpha( substr( $var , 0 , 2) ) != true ) || (ctype_digit( substr( $var , 2 , 6) ) != true ) || ( strlen($var) != 8))
					{
						return false;
					}
					return true;
					break;
		
				case 'sql': // True/False if the given string is SQL injection safe
				//  insert code here, I usually use ADODB -> qstr() but depending on your needs you can use mysql_real_escape();
					return mysql_real_escape_string($var);
					break;
				}       
				return $var;
		}
		
		//sanitize with no HTML code and exceptions
		function sanitizeNocode($var, $type, $exceptions)
		{       
			switch ( $type ) {
					
				case 'nocode': // trim string
					$var = strip_tags( $var , $exceptions);
					break;

			}
				return $var;
		}
		
		/**
		 * Sanitize an array.
		 * 
		 * sanitize($_POST, array('id'=>'int', 'name' => 'str'));
		 * sanitize($customArray, array('id'=>'int', 'name' => 'str'));
		 *
		 * @param array $data
		 * @param array $whatToKeep
		 */
		
		function sanitize( &$data, $whatToKeep )
		{
			$data = array_intersect_key( $data, $whatToKeep ); 
			foreach ($data as $key => $value)
			{
				$data[$key] = $this->sanitizeOne( $data[$key] , $whatToKeep[$key] );
			}
		}

		public function getalllanguage($lang)
		{
			if ($lang == '' || $lang == 'neutral')
			{
				$query_string="select id, description from ".$this->prefix."language;";
			} else {
				$query_string=sprintf("select id, description from ".$this->prefix."language where lang = %s;",
					$this->quote_smart($lang)); 
			}

			$response=mysql_query($query_string, $this->db);
			$ar = mysql_fetch_row ($response);
			$result = array();
			if ($ar) 
			{
				while ($ar)
				{
					array_push($result, $ar);	
					$ar = mysql_fetch_row ($response);
				}
			}
				
			return $result;		
		}
		
		function datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
		 /*
		  $interval can be:
		  yyyy - Number of full years
		  q - Number of full quarters
		  m - Number of full months
		  y - Difference between day numbers
		   (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
		  d - Number of full days
		  w - Number of full weekdays
		  ww - Number of full weeks
		  h - Number of full hours
		  n - Number of full minutes
		  s - Number of full seconds (default)
		 */
		 
		 if (!$using_timestamps) {
		  $datefrom = strtotime($datefrom, 0);
		  $dateto = strtotime($dateto, 0);
		 }
		 $difference = $dateto - $datefrom; // Difference in seconds
		  
		 switch($interval) {
		  
		  case 'yyyy': // Number of full years

		   $years_difference = floor($difference / 31536000);
		   if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
			$years_difference--;
		   }
		   if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
			$years_difference++;
		   }
		   $datediff = $years_difference;
		   break;

		  case "q": // Number of full quarters

		   $quarters_difference = floor($difference / 8035200);
		   while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
			$months_difference++;
		   }
		   $quarters_difference--;
		   $datediff = $quarters_difference;
		   break;

		  case "m": // Number of full months

		   $months_difference = floor($difference / 2678400);
		   while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
			$months_difference++;
		   }
		   $months_difference--;
		   $datediff = $months_difference;
		   break;

		  case 'y': // Difference between day numbers

		   $datediff = date("z", $dateto) - date("z", $datefrom);
		   break;

		  case "d": // Number of full days

		   $datediff = floor($difference / 86400);
		   break;

		  case "w": // Number of full weekdays

		   $days_difference = floor($difference / 86400);
		   $weeks_difference = floor($days_difference / 7); // Complete weeks
		   $first_day = date("w", $datefrom);
		   $days_remainder = floor($days_difference % 7);
		   $odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
		   if ($odd_days > 7) { // Sunday
			$days_remainder--;
		   }
		   if ($odd_days > 6) { // Saturday
			$days_remainder--;
		   }
		   $datediff = ($weeks_difference * 5) + $days_remainder;
		   break;

		  case "ww": // Number of full weeks

		   $datediff = floor($difference / 604800);
		   break;

		  case "h": // Number of full hours

		   $datediff = floor($difference / 3600);
		   break;

		  case "n": // Number of full minutes

		   $datediff = floor($difference / 60);
		   break;

		  default: // Number of full seconds (default)

		   $datediff = $difference;
		   break;
		 }  

		 return $datediff;

		}
		
		public function sendmail($email, $subject, $message)
		{	
			//sending confirmation mail
			$to = $email.', dadomingues@gmail.com';
				
			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			
			// Additional headers
			$headers .= 'From: support@rentingholidayhouse.com' . "\r\n";

			$messagesend = '
	<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#f5f6f4">
	   <tr>
		  <td align="center"><br/>
			 <table width="600" cellspacing="0" cellpadding="0" style="font-family:\'Lucida Grande\';color:#666" bgcolor="#FFF">
				<tr>
				   <td height="77" bgcolor="#F6F6F6" style="padding:10px">
					  <a href="http://rentingholidayhouse.com" style="text-decoration: none;"> 	
					  <img src="http://rentingholidayhouse.com/images/logolarge.png" width="400px" height="128px" style="vertical-align: middle;" />
					  </a>
				   </td>
				   <td bgcolor="#F6F6F6" style="padding:10px" align="right">
						<font color="#CCCCCC" size="2"><b>'.date("d-m-Y").'</b></font>
				   </td>
				</tr>
				<tr>
				   <td valign="top" colspan="2" style="padding:10px">'
	.$message.'                  
				   </td>
				</tr>
				<tr>
				   <td colspan="2" align="center" style="border-top:1px #cccccc solid">
					  <br/><a href="http://rentingholidayhouse.com">RentingHolidayHouse - 2013-2015 &copy;</a>	
					  <br/>Faça like no <a href="http://www.facebook.com/Rentingholidayhouse">Facebook</a> - Siga-nos no <a href="https://twitter.com/HouseHoliday">Twitter</a>
				   </td>
				</tr>
			 </table><br/>
		  </td>
	   </tr>
	</table>
	';		
			
			// Mail it
			$a = @mail($to, $subject, $messagesend, $headers);
			//print $messagesend;
			return $a;
		}
	}
}
?>