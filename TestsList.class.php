<?php

class TestsList
{
	private $_dbh = NULL;
	private $testsList = array();
	private $testsListModified = array();

	public function __construct()
	{
		$this->_dbh = DB::getInstance();
	}

	public function getList()
	{
		if(empty($this->testsList))
		{
			$result = $this->_dbh->query('select TestId from Tests');
			while($row = $result->fetch_assoc())
			{
				$this->testsList[$row['TestId']] = new Test($row['TestId']);
			}
		}
		return $this->testsList;
	}

}