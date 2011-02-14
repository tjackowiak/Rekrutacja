<?php

class Router
{
	private static $_config = array(
		'base_dir' => '/rekrutacja/admin',
		'start_dir' => '/',
		'start_file' => 'index.php',
	);
	private $_url = NULL;
	private $_path = NULL;

	function __construct( $data )
	{
		if( isset($data['SCRIPT_NAME']) )
			$this->_url = $data['SCRIPT_NAME'];
		if( isset($data['PATH_INFO']) )
			$this->_path = $data['PATH_INFO'];
	}

	/**
	 * Search for controller name and astion name in request string
	 *
	 * Returns false on error or array of
	 *  controller - controller class name
	 *  action     - action to run
	 */
	public function determineRoute()
	{
		$return = false;

		if( !empty($this->_path) )
		{
			list(,$controller,$action) = explode( '/', $this->_path );
			if( !empty($controller) )
			{
				$return = array(
					'controller' => $controller . 'Controller',
					'action'     => false,
				);
				if( !empty($action) )
				{
					$return['action'] = $action;
				}
			}
		}

		return $return;
	}

	public static function getRoute( $controllerName, $action = false, array $params = array() )
	{
		$link = implode(self::$_config);
		$link .= '/' . str_replace('Controller', '', $controllerName);
		if( $action !== false )
		{
			$link .= '/' . $action;
		}
		if( !empty($params) )
		{
			$link .= '?' . http_build_query($params,'', '&');
		}
		// var_dump($link);

		return $link;
	}

	// public function validateController( $controller )
	// {
	// 	if( true )
	// 	{
	// 		return true;
	// 	}		
	// }
}