<?php

class Config
{
	private static $_instance = NULL;
	private $_baseDir = false;
	private $_coreClassMap = array(
		'Controller',
		'DB',
		'DBObject',
		'Router',
		'Template',
	);
	private $_coreLibsMap = array(
		'PHPTAL' => 'libs/PHPTAL/PHPTAL.php',
	);

	public static function getInstance()
	{
		if( self::$_instance === NULL )
		{
			self::$_instance = new Config();
		}
		return self::$_instance;
	}
	public function __construct()
	{
		/* Set internal character encoding to UTF-8 */
		mb_internal_encoding("UTF-8");		
		// error_reporting(E_ALL);
		error_reporting(-1);
		ini_set("display_errors", 1); 

		// pozwala m.in. wykryc problemy z parsowaniem zapytania
		// w czasie wywolywania mysqli->prepare()
		mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_INDEX);


		/**
		 * Config
		 */
		$this->_baseDir = dirname(__FILE__) . '/';
		spl_autoload_register(array($this, 'loadClass'));
	}

	private function loadClass($class_name)
	{
		// var_dump($class_name);
		
		$file = false;

		if( in_array($class_name, $this->_coreClassMap) )
		{
			$file = $this->_baseDir.'core/'.basename($class_name) . '.class.php';
		}
		elseif( isset($this->_coreLibsMap[$class_name]) )
		{
			$file = $this->_baseDir.$this->_coreLibsMap[$class_name];
		}
		elseif( preg_match('/\wDal$/', $class_name) )
		{
			$file = $this->_baseDir.'dal/'.basename($class_name) . '.class.php';;
		}
		elseif( preg_match('/\wController$/', $class_name) )
		{
			$file = $this->_baseDir.'controller/'.basename($class_name) . '.class.php';;
		}
		else//if( preg_match('/\wModule$/', $class_name) )
		{
			$file = $this->_baseDir.'module/'.basename($class_name) . '.class.php';;
		}

		if( $file !== false && file_exists($file) )
		{
			require $file;
		}
	}

	public function getBaseDir()
	{
		return $this->_baseDir;
	}
}