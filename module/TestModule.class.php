<?php

class TestModule 
{
	private $_test = NULL;
	private $_questionsList = NULL;

	public function __construct( $testId )
	{
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
			$result = DB::getInstance()->query('select TestQuestionId from TestQuestions where TestId = '.$this->_test->id);
			while($row = $result->fetch_assoc())
			{
				$this->_questionsList[$row['TestQuestionId']] = QuestionFactory::getQuestionById($row['TestQuestionId']);
			}
		}
		return $this->_questionsList === NULL ? array() : $this->_questionsList;
	}

	public function getQuestionsCount()
	{
		return count($this->getQuestionsList());
	}

	// public function addQuestion( TestQuestionDal $question)
	// {
	// 	$question->testId = $this->_test->id;
	// 	$question->save();
	// }

}