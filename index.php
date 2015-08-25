<?php 
    session_start();
    include_once dirname(__FILE__).'/config.php';
    require dirname(__FILE__).'/class.inputfilter.php';
    require dirname(__FILE__).'/generic.class.php';
    $generic = new webrickco\utils\Generic($config['hostname'], $config['database'], $config['admin'], $config['password'], $config['prefix']);
    require dirname(__FILE__).'/model.class.php';
    $model = new webrickco\model\database($config['hostname'], $config['database'], $config['admin'], $config['password'], $config['prefix']);
    require dirname(__FILE__).'/CRUD.class.php';
    

    //sanitizing everything
    $myFilter = new InputFilter();
    $_POST = $myFilter->process($_POST);
    $_SERVER['PHP_SELF'] = filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_STRING);
?>
<!DOCTYPE HTML>
<html lang="<?php print $_SESSION['lang']; ?>">
<head>
<title>Testing Model</title>
<meta charset="utf-8" />

<!-- SEO & Semantics -->
<link rel="canonical" href="/">

</head>
<body>
<?php 
	//view tables and fields
	$model->viewTables();
	$model->viewFields('cdo_house_pricing');
	
	$table1 = new webrickco\model\CRUD($model, 'cdo_house_pricing');
        $table1->id_house = 3;
        $table1->id_user = 'ddomingues';
        $table1->id_season = 9;
        $table1->dt_init = '2015-01-01';
        $table1->dt_end = '2015-09-01';
        
//Array ( [Field] => dt_init [Type] => date [Null] => NO [Key] => [Default] => [Extra] => [Value] => ) 
//
//Array ( [Field] => dt_end [Type] => date [Null] => NO [Key] => [Default] => [Extra] => [Value] => ) 
//
//Array ( [Field] => monthprice [Type] => decimal(10,2) [Null] => YES [Key] => [Default] => [Extra] => [Value] => ) 
//
//Array ( [Field] => weekprice [Type] => decimal(10,2) [Null] => YES [Key] => [Default] => [Extra] => [Value] => ) 
//
//Array ( [Field] => nightweekprice [Type] => decimal(10,2) [Null] => YES [Key] => [Default] => [Extra] => [Value] => ) 
//
//Array ( [Field] => weekendprice [Type] => decimal(10,2) [Null] => YES [Key] => [Default] => [Extra] => [Value] => ) 
//
//Array ( [Field] => extranightweekendprice [Type] => decimal(10,2) [Null] => YES [Key] => [Default] => [Extra] => [Value] => ) 
//
//Array ( [Field] => extranightprice [Type] => decimal(10,2) [Null] => YES [Key] => [Default] => [Extra] => [Value] => ) 
//
//Array ( [Field] => specialeventprice [Type] => decimal(10,2) [Null] => YES [Key] => [Default] => [Extra] => [Value] => ) 
//
//Array ( [Field] => minimumunit [Type] => varchar(20) [Null] => NO [Key] => [Default] => [Extra] => [Value] => ) 
//
//Array ( [Field] => minimumvalue [Type] => int(11) [Null] => NO [Key] => [Default] => [Extra] => [Value] => ) 
//
//Array ( [Field] => dateupdate [Type] => timestamp [Null] => NO [Key] => [Default] => CURRENT_TIMESTAMP [Extra] => on update CURRENT_TIMESTAMP [Value] => ) 
//
//Array ( [Field] => dateinsert [Type] => timestamp [Null] => NO [Key] => [Default] => 0000-00-00 00:00:00 [Extra] => [Value] => ) 


        
	$table1->insert('cdo_house_pictures');

?>	
</body>
</html>