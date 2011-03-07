<?php

abstract class Controller
{
	const ACCESS_PUBLIC = 1;
	const ACCESS_ADMIN  = 2;

	// protected $_tpl  = NULL;
	// protected $_get  = NULL;
	// protected $_post = NULL;
	protected $_data = NULL;
	
	protected $_accessPrivileges = self::ACCESS_PUBLIC; #or 'admin' to restricted access
	protected $_action = false;
	protected $_actionMap = array();

	protected $_tplParams  = array();
	protected $_tplMessages = array();
	protected $_tplTemplate = false;

	public function __construct( $data = array() )
	{
		// $tpl->setController($this);
		// $this->_tpl = $tpl;

		if( !empty($data) )
		{
			$this->_data = $data;
		}
		// if( !empty($post) )
		// {
		// 	$this->_post = $post;
		// }
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

	public function run()
	{
		if( isset($this->_actionMap[$this->_action]) )
		{
			echo "Runing: ".$this->_actionMap[$this->_action];
			$this->{$this->_actionMap[$this->_action]}();
		}
		elseif(!empty($this->_action))
		{
			die('Action: '.$this->_action);
		}

		return array(
			'template'   => $this->_tplTemplate,
			'parameters' => $this->_tplParams,
			'messeges'   => $this->_tplMessages,
		);
	}
}