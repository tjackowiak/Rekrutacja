<?php

class TestBuilder
{
	private $_test;
	private $_questionBuilder;

	public function __construct( $testId = false )
	{
		$this->_test = new TestModule($testId);
	}

	public function build( array $data )
	{
		
		return $this;
	}

	public function getTest()
	{
		return $this->_test;
	}

	public function editQuestion()
	{
		if( $this->_questionBuilder === NULL )
		{
			$this->_questionBuilder = new QuestionBuilder($this);
		}

		return $this->_questionBuilder;
	}

	// public function addQuestion( array $data )
	// {
	// 	$qb = new QuestionBuilder($this);
	// 	return $qb->addQuestion($data);
	// }

	// public function modifyQuestion( array $data )
	// {
	// 	$qb = new QuestionBuilder($this);
	// 	// return $qb->addQuestion($data);
	// }
}

