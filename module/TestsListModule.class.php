<?php

class TestsListModule
{
	private $_dbh = NULL;
	private $_testsList = NULL;
	// private $_testsListModified = array();

	public function __construct()
	{
		$this->_dbh = DB::getInstance();
	}

	public function getList()
	{
		if( $this->_testsList === NULL )
		{
			$result = $this->_dbh->query('select TestId from Tests order by TestName');
			while($row = $result->fetch_assoc())
			{
				$this->_testsList[$row['TestId']] = new TestDal($row['TestId']);
			}
		}
		return $this->_testsList === NULL ? array() : $this->_testsList;
	}

}