<?php

class TestsList
{
	private $db = NULL;
	private $testsList = array();
	private $testsListModified = array();

	public function __construct()
	{
		$this->db = DB::getInstance();
	}

	public function getList()
	{
		if(empty($this->testsList))
		{
			$result = $this->db->query('select TestId from Tests');
			while($row = $result->fetch_assoc())
			{
				$this->testsList[$row['TestId']] = new Test($row['TestId']);
			}
		}
		return $this->testsList;
	}

	

	public function addTest()
	{
		
	}
}