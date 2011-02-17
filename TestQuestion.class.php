<?php

class TestQuestion extends DBObject
{
	protected $_testId;
	protected $_type;
	protected $_text;
	protected $_answers;
	protected $_points;
	protected $_enabled;

	protected function fetchData()
	{
		$res = $this->_dbh->query('select * from TestQuestions where TestQuestionId = '.$this->_id);
		$row = $res->fetch_assoc();
		$this->_id      = $row['TestQuestionId'];
		$this->_testId  = $row['TestId'];
		$this->_type    = $row['TestQuestionType'];
		$this->_text    = $row['TestQuestionText'];
		$this->_answers = $row['TestQuestionAnswers'];
		$this->_points  = $row['TestQuestionPoints'];
		$this->_enabled = $row['TestQuestionEnabled'];
	}

	public function create()
	{
		$res = $this->_dbh->query('insert into TestQuestions (
			TestQuestionId,
			TestId,
			TestQuestionType,
			TestQuestionText,
			TestQuestionAnswers,
			TestQuestionPoints,
			TestQuestionEnabled)
			values (
				"'.$this->_id      .'",
				"'.$this->_testId  .'",
				"'.$this->_type    .'",
				"'.$this->_text    .'",
				"'.$this->_answers .'",
				"'.$this->_points  .'",
				"'.$this->_enabled .'")');
				
		$this->_id = $this->_dbh->getInsertId();
		return $this->_id;
	}

	public function update()
	{
		return $this->_dbh->query('update TestQuestions set
			TestId              = "'.$this->_testId  .'",
			TestQuestionType    = "'.$this->_type    .'",
			TestQuestionText    = "'.$this->_text    .'",
			TestQuestionAnswers = "'.$this->_answers .'",
			TestQuestionPoints  = "'.$this->_points  .'",
			TestQuestionEnabled = "'.$this->_enabled .'"
			where
			TestQuestionId      = "'.$this->_id      .'"');
	}
}