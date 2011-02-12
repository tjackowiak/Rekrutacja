<?php

class DB
{
	/**
	 * Config
	 */
	private static $host = 'localhost';
	private static $db = 'rekrutacja';
	private static $user = 'rekrutacja';
	private static $pass = 'rekrutacja';
	private static $conn = NULL;

	public static function getInstance()
	{
		if(NULL === self::$conn)
		{
			self::$conn = new mysqli(self::$host, self::$user, self::$pass, self::$db);
			if (self::$conn->connect_error) {
				die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
			}	
		}

		return self::$conn;
	}
}