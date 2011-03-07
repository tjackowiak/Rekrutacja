<?php

class TestModule 
{
	private $_dbh = NULL;
	private $_test = NULL;
	private $_questionsList = NULL;

	public function __construct( $testId )
	{
		$this->_dbh = DB::getInstance();
		$this->_test = new TestDal( $testId );
	}

	public function __get( $name )
	{
		return $this->_test->__get( $name );
	}
	public function __set( $name, $value )
	{
		return $this->_test->__set( $name, $value );
	}

	public function getQuestionsList()
	{
		if( $this->_questionsList === NULL )
		{
			$result = $this->_dbh->query('select TestQuestionId from TestQuestions where TestId = '.$this->_test->id);
			while($row = $result->fetch_assoc())
			{
				$this->_questionsList[$row['TestQuestionId']] = new TestQuestionDal($row['TestQuestionId']);
			}
		}
		return $this->_questionsList === NULL ? array() : $this->_questionsList;
	}

	// public function addQuestion( TestQuestionDal $question)
	// {
	// 	$question->testId = $this->_test->id;
	// 	$question->save();
	// }

}