<?php

class DB
{
	/**
	 * Config
	 */
	private static $instance = NULL;
	private $host = 'localhost';
	private $db   = 'rekrutacja';
	private $user = 'root';
	private $pass = 'root';
	private $dbh  = NULL;

	public static function getInstance()
	{
		if(NULL === self::$instance)
		{
			self::$instance = new DB();
		}

		return self::$instance;
	}

	public function __construct()
	{
		$this->dbh = new mysqli($this->host, $this->user, $this->pass, $this->db);
		if( $this->dbh->connect_error )
		{
			die('Connect Error (' . $this->dbh->connect_errno . ') ' . $this->dbh->connect_error);
		}		
	}

	public function query( $query )
	{
		// var_dump('[DB Query] '.$query);
		return $this->dbh->query($query);
	}

	public function getLastError()
	{
		return $this->dbh->error;
	}

	public function getInsertId()
	{
		return $this->dbh->insert_id;
	}
	
}