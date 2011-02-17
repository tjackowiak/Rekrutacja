<?php

class DBObjectException extends Exception
{
	public $fieldName;
	// public $errorMessage;
	public function __construct($fieldName, $errorMessage)
	{
		$this->fieldName = $fieldName;
		// $this->errorMessage  = $errorMessage;

		parent::__construct($errorMessage);
	}
}


abstract class DBObject
{
	protected $_id;
	protected $_dbh;

	public function __construct( $id = false )
	{
		$this->_dbh = DB::getInstance();
		$this->_id = intval($id);

		if( !empty($this->_id) )
		{
			$this->fetchData();
		}
	}

	public function __get( $name )
	{
		$return = NULL;
		if( property_exists($this, '_'.$name ) )
		{
			$return = $this->{'_'.$name};
		}
		return $return;
	}

	public function __set( $name, $value )
	{
		if( property_exists($this, '_'.$name ) )
		{
			$this->{'_'.$name} = $value;
		}
	}

	abstract protected function fetchData();

	public function validate()
	{
		/**
		 * Run all methods that can validate data
		 */
		$reflection = new ReflectionClass($this);
		$methods = $reflection->getMethods(); 

		foreach( $methods as $method )
		{
			if( preg_match('/^validate[A-Z]\w+$/',$method->name) )
			{
				$this->{$method->name}();
			}
		}
	}

	public function save()
	{
		$this->validate();
		if( empty($this->_id) )
		{
			$res = $this->create();
		}
		else
		{
			$res = $this->update();
		}

		if( $res == false )
		{
			throw new Exception('['.__CLASS__.'] Błąd aktualizacji bazy (<i>'.$this->_dbh->getLastError().'</i>)');
		}
	}
}