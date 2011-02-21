<?php

class TestDal extends DBObject
{
	protected $_name;
	protected $_duration;
	protected $_description;
	protected $_questionsList = array();

	protected $config = array(
		'nameMaxLength'   => 50,
		'timeMaxDuration' => 120,
	);

	protected function fetchData()
	{
		$res = $this->_dbh->query('select * from Tests where TestId = '.$this->_id);
		$row = $res->fetch_assoc();
		$this->_id          = $row['TestId'];
		$this->_name        = $row['TestName'];
		$this->_duration    = $row['TestDuration'];
		$this->_description = $row['TestDescription'];
	}

	protected function validateName()
	{
		if( empty($this->_name) )
			throw new DBObjectException('name', 'Nazwa nie może być pusta');
		if( mb_strlen($this->_name) > $this->config['nameMaxLength'] )
			throw new DBObjectException('name', 'Nazwa jest za długa');
	}

	protected function validateDuration()
	{
		$duration = intval($this->_duration);
		if( $duration < 1 || $duration > $this->config['timeMaxDuration'] )
			throw new DBObjectException('duration', 'Błędny czas trwania');
		$this->_duration = $duration;
	}

	public function getQuestionsList()
	{
		if( empty($this->_questionsList) )
		{
			$result = $this->_dbh->query('select TestQuestionId from TestQuestions where TestId = '.$this->_id);
			while($row = $result->fetch_assoc())
			{
				$this->_questionsList[$row['TestQuestionId']] = new TestQuestion($row['TestQuestionId']);
			}
		}
		return $this->_questionsList;
	}

	public function create()
	{
		$res = $this->_dbh->query('insert into Tests
			(TestName, TestDuration, TestDescription)
			values ("'.$this->_name.'",
			'.$this->_duration.',
			"'.$this->_description.'")');
		$this->_id = $this->_dbh->getInsertId();
		return $this->_id;
	}

	public function update()
	{
		return $this->_dbh->query('update Tests set
			TestName = "'. $this->_name .'",
			TestDuration = "'. $this->_duration .'",
			TestDescription = "'. $this->_description .'"
			where TestId = '. $this->_id);
	}
}