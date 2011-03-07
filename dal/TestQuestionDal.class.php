<?php

class TestQuestionDal extends DBObject
{
	protected $_testId;
	protected $_type;
	protected $_text;
	protected $_answerTip;
	protected $_answer;
	protected $_points;
	protected $_enabled;

	protected function fetchData()
	{
		$res = $this->_dbh->query('select * from TestQuestions where TestQuestionId = '.$this->_id);
		$row = $res->fetch_assoc();
		$this->_id        = $row['TestQuestionId'];
		$this->_testId    = $row['TestId'];
		$this->_type      = $row['TestQuestionType'];
		$this->_text      = $row['TestQuestionText'];
		$this->_answerTip = $row['TestQuestionAnswerTip'];
		$this->_answer    = $row['TestQuestionAnswer'];
		$this->_points    = $row['TestQuestionPoints'];
		$this->_enabled   = $row['TestQuestionEnabled'];
	}

	protected function validateData()
	{
		if( empty($this->_testId) )
			throw new InvalidDataException('testId', 'Brak powiązanego testu');
		if( empty($this->_type) )
			throw new InvalidDataException('type', 'Nieokreślony typ pytania');
		if( empty($this->_text) )
			throw new InvalidDataException('text', 'Brak treści pytania');
		if( empty($this->_points) )
			throw new InvalidDataException('points', 'Brak punktacji');
		if( empty($this->_enabled) )
			throw new InvalidDataException('enabled', 'Pytanie musi być aktywne lub zablokowane');
	}

	public function create()
	{
		$res = $this->_dbh->query('insert into TestQuestions (
			TestId,
			TestQuestionType,
			TestQuestionText,
			TestQuestionAnswerTip,
			TestQuestionAnswer,
			TestQuestionPoints,
			TestQuestionEnabled)
			values (
				"'.$this->_testId    .'",
				"'.$this->_type      .'",
				"'.$this->_text      .'",
				"'.$this->_answerTip .'",
				"'.$this->_answer    .'",
				"'.$this->_points    .'",
				"'.$this->_enabled   .'")');
				
		$this->_id = $this->_dbh->getInsertId();
		return $this->_id;
	}

	public function update()
	{
		return $this->_dbh->query('update TestQuestions set
			TestId                = "'.$this->_testId    .'",
			TestQuestionType      = "'.$this->_type      .'",
			TestQuestionText      = "'.$this->_text      .'",
			TestQuestionAnswerTip = "'.$this->_answerTip .'",
			TestQuestionAnswer    = "'.$this->_answer    .'",
			TestQuestionPoints    = "'.$this->_points    .'",
			TestQuestionEnabled   = "'.$this->_enabled   .'"
			where
			TestQuestionId        = "'.$this->_id        .'"');
	}
}