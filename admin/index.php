<?php
session_start();
require '../config.php';
var_dump($_SERVER);
$controller_name = 'TestsListController';
$controller_action = false;
if( !empty($_SERVER['PATH_INFO']) )
{
	list(,$controller_name_tmp,$controller_action_tmp) = explode( '/', $_SERVER['PATH_INFO'] );
	var_dump($controller_action_tmp);
	$controller_name_tmp .= 'Controller';
	if( class_exists($controller_name_tmp) && is_subclass_of($controller_name_tmp, 'Controller') )
	{
		$controller_name = $controller_name_tmp;
		unset($controller_name_tmp);
	}
}


$tpl = new Template('AdminMainFrame');
$controller = new $controller_name($tpl, $_GET, $_POST);
$tpl = $controller->run();

try
{
	echo $tpl->execute();
}
catch(Exception $e)
{
     echo $e;
}

