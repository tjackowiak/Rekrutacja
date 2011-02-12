<?php
require 'libs/PHPTAL/PHPTAL.php';

class Template
{
	private $templates_path = '/templates/';
	private $tpl;
	private $argv = array();
	private $message = array();

	function __construct( $template_name )
	{
		$this->templates_path = dirname(__FILE__) . $this->templates_path;
		$this->tpl = new PHPTAL($this->templates_path . $template_name . '.html');
	}

	public function setController( &$controller )
	{
		$this->tpl->this = $controller;
	}

	public function setAction( $action )
	{
		if(strstr($action, '/'))
		{
			$action = str_replace('/', '.html/', $action);
		}
		else
		{
			$action = $action . '.html/' . $action;
		}
		$this->tpl->action = $action;
	}

	public function addMessageInfo( $message )
	{
		$this->messages['info'][] = $message;
	}
	public function addMessageWarning( $message )
	{
		$this->messages['warning'][] = $message;
	}
	public function addMessageError( $message )
	{
		$this->messages['error'][] = $message;
	}

	public function __set( $name, $value )
	{
		$this->argv[$name] = $value;
	}

	public function execute()
	{
		if( !empty($this->argv) )
		{
			$this->tpl->argv = $this->argv;
		}
		if( !empty($this->messages) )
		{
			$this->tpl->messages = $this->messages;
		}
		return $this->tpl->execute();
	}
}

class RouteHelper
{
	public static function getLink( $controller, $action = false, $params = array() )
	{
		$module = str_replace('Controller', '', get_class($controller));
		if( $action === false )
			$action = $controller->getAction();

		// var_dump($module, $action,$controller);exit;
		
		return '/index.php/'.$module.'/'.$action.'/';
	}
}