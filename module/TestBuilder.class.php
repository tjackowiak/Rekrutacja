<?php

class TestBuilder
{
	private $_test;

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

	public function addQuestion( array $data )
	{
		$qb = new QuestionBuilder($this);
		return $qb->addQuestion($data);
	}

	public function modifyQuestion( array $data )
	{
		$qb = new QuestionBuilder($this);
		return $qb->addQuestion($data);
	}
}

class QuestionBuilder
{
	private $_testBuilder;
	private $_testQuestion;
	private static $_availableTests = array
	(
		'open'   => array(
			'fullName' => 'Otwarte',
			'builder'  => 'QuestionOpenBuilder',
			),
		'closed' => array(
			'fullName' => 'Zamknięte',
			'builder'  => 'QuestionClosedBuilder',
			),
	);

	public function __construct( TestBuilder $tb )
	{
		$this->_testBuilder = $tb;
	}

	public static function getQuestionTypes()
	{
		$types = array();
		foreach( self::$_availableTests as $type => $info )
		{
			$types[] = array(
				'shortName'=> $type,
				'fullName' => $info['fullName']);
		}
		return $types;
	}

	private function getTypeBuilder( $type )
	{
		if( isset(self::$_availableTests[$type]) )
		{
			return new self::$_availableTests[$type]['builder']();
		}
		else
		{
			throw new Exception('Unknown question type');
		}
	}
	public function addQuestion( array $data )
	{
		$qb = $this->getTypeBuilder($data['TestQuestionType']);
		$question = $qb->build($data);
		// $question = self::fillQuestion(new TestQuestionDal(), $data);
		$question->testId = $this->_testBuilder->getTest()->id;

		$this->_testQuestion = $question;
		$this->_testQuestion->save();

		return $this;
	}

	public function modifyQuestion( $questionId, array $data )
	{
		$questionsList = $this->_testBuilder->getTest()->getQuestionsList();
		if( isset($questionsList[$questionId]) )
		{
			$question = self::fillQuestion($questionsList[$questionId], $data);
			$this->_testQuestion = $question;
			$this->_testQuestion->save();
		}

		return $this;
	}


	public function getQuestion()
	{
		return $this->_testQuestion;
	}

	public function getTest()
	{
		return $this->_testBuilder->getTest();
	}
}

class QuestionOpenBuilder
{
	public function build( array $data )
	{
		$question = new TestQuestionDal();

		$question->id      = $data['TestQuestionId'];
		// $this->_testId  = $row['TestId'];
		// $question->type    = $data['TestQuestionType'];
		$question->text    = $data['TestQuestionText'];
		$question->answer  = $data['TestQuestionAnswer'];
		$question->points  = $data['TestQuestionPoints'];
		$question->enabled = true;

		return $question;
	}
}

class QuestionOpenModel // extends TestQuestionDal
{
	private $_question;

	public function __construct()
	{
		$this->_question = new TestQuestionDal();
		$this->_question->_type = 'open';
	}
}