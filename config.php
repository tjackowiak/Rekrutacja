<?php
error_reporting(E_ALL);
ini_set("display_errors", 1); 

/* Set internal character encoding to UTF-8 */
mb_internal_encoding("UTF-8");

function RekrutacjeAutoload( $class_name )
{
	$file = dirname(__FILE__) . '/' . basename($class_name) . '.class.php';
	if(file_exists($file))
	{
		require $file;
	}
}
spl_autoload_register('RekrutacjeAutoload');