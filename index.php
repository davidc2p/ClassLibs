<?php 
    session_start();
    include_once dirname(__FILE__).'/config.php';
    
    function my_autoload ($pClassName) {
        include("/classes/" . $pClassName . ".class.php");
    }

    spl_autoload_register('my_autoload');
    
//    require dirname(__FILE__).'/class.inputfilter.php';
//    require dirname(__FILE__).'/generic.class.php';
//    require dirname(__FILE__).'/model.class.php';
//    require dirname(__FILE__).'/CRUD.class.php';
    
    $model = new \model\Model($config['hostname'], $config['database'], $config['admin'], $config['password'], $config['prefix']);

    //sanitizing everything
    $myFilter = new \utils\InputFilter();
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

    $table1 = new model\CRUD($model, 'cdo_house_pricing');
    $table1->id_house = 3;
    $table1->id_user = 'ddomingues';
    $table1->id_season = 9;
    $table1->dt_init = '2015-01-01';
    $table1->dt_end = '2015-09-01';
    $table1->monthprice = 20;
    $table1->weekprice = 20;
    $table1->nightweekprice = 20;
    $table1->weekendprice = 20;
    $table1->extranightweekendprice = 20;
    $table1->specialeventprice = 20;
    $table1->minimumunit = "Week(s)";
    $table1->minimumvalue = 20;
    $table1->dateinsert = '2012-01-27 22:36:09';

    $table1->insert('cdo_house_pricing');

    $table1->id_house = 3;
    $table1->id_user = 'ddomingues';
    $table1->id_season = 9;
    $table1->delete('cdo_house_pricing');
?>	
</body>
</html>