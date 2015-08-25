<?php
if ($_SERVER['HTTP_HOST'] == 'localhost' || substr($_SERVER['HTTP_HOST'], 0, 9) == '127.0.0.1')
{
	$config = array('hostname' => '127.0.0.1', 'database' => 'casasdeouro', 'admin' => 'root', 'password' => '', 'prefix' => 'cdo_', 'path' => 'http://'.$_SERVER['HTTP_HOST'].'/templates/RentingHolidayHouse/');
	error_reporting(E_ALL);
} else {
	$config = array('hostname' => 'CasasdeOuro.db.4069051.hostedresource.com', 'database' => 'CasasdeOuro', 'admin' => 'CasasdeOuro', 'password' => 'Biggui69!', 'prefix' => 'cdo_', 'path' => 'http://RentingHolidayHouse.com/');
}
$config['title'] = 'Holiday Houses';

// not yet in use
$config['charsetpt'] = 'ISO-8859-1';
$config['charseten'] = 'UTF-8';
$config['charsetfr'] = 'UTF-8';
?>