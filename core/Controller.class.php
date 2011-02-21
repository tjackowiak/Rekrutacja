<?php

abstract class Controller
{
	const ACCESS_PUBLIC = 1;
	const ACCESS_ADMIN  = 2;

	protected $_accessPrivileges = self::ACCESS_PUBLIC; #or 'admin' to restricted access
	protected $_action = false;

	// protected $_tpl  = NULL;
	protected $_get  = NULL;
	protected $_post = NULL;


	public function __construct( $get = false, $post = false )
	{
		// $tpl->setController($this);
		// $this->_tpl = $tpl;

		if( !empty($get) )
		{
			$this->_get = $get;
		}
		if( !empty($post) )
		{
			$this->_post = $post;
		}
	}

	public function getAction()
	{
		return $this->_action;
	}
	protected function setAction( $action )
	{
		$this->_action = $action;
	}

	public function getType()
	{
		return $this->_type;
	}
	public function getAccess()
	{
		return $this->_accessPrivileges;
	}

	abstract public function run();
}