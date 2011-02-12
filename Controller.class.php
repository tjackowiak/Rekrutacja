<?php

abstract class Controller
{
	protected $__tpl = NULL;
	protected $__get = NULL;
	protected $__post = NULL;

	protected $__siteAction;

	public function __construct( $tpl, $get = false, $post = false )
	{
		$this->__tpl = $tpl;
		$this->__tpl->setController($this);
		if( !empty($get) )
		{
			$this->__get = $get;
		}
		if( !empty($post) )
		{
			$this->__post = $post;
		}
	}

	public function getAction()
	{
		return $this->__siteAction;
	}
	protected function setAction( $action )
	{
		$this->__siteAction = $action;
	}

	abstract public function run();
}