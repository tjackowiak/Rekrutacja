<?php

class DB
{
	/**
	 * Config
	 */
	private static $instance = NULL;
	private $host = 'localhost';
	private $db   = 'rekrutacja';
	// private $db   = 'recruitment';
	private $user = 'root';
	private $pass = 'root';
	// private $pass = '';
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

	public function queryBinded( $sql, $params, $close )
	{
		var_dump('[DB Query] '.$sql);

		$stmt = $this->dbh->prepare($sql) or die ("Failed to prepared the statement!");

		call_user_func_array(array($stmt, 'bind_param'), refValues($params));

		$stmt->execute();

		if($close){
		   $result = $this->dbh->affected_rows;
		} else {
		   $meta = $stmt->result_metadata();

		   while ( $field = $meta->fetch_field() ) {
		       $parameters[] = &$row[$field->name];
		   }  

		call_user_func_array(array($stmt, 'bind_result'), refValues($parameters));
		   
		while ( $stmt->fetch() ) {  
		   $x = array();  
		   foreach( $row as $key => $val ) {  
		      $x[$key] = $val;  
		   }  
		   $results[] = $x;  
		}

		$result = $results;
		}

		$stmt->close();

		return  $result;
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


    function refValues($arr){
        if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
        {
            $refs = array();
            foreach($arr as $key => $value)
                $refs[$key] = &$arr[$key];
            return $refs;
        }
        return $arr;
    }
