<?php

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
		try{
			$question = $qb->build($data);
		}
		catch(InvalidDataException $e)
		{
			// $qb->fill($data);
			$this->_testQuestion = QuestionFactory::getQuestion( $qb->question );
			
			throw $e;
		// var_dump(__FILE__, __LINE__,$this->_testQuestion);exit;
		}

		$question->testId = $this->_testBuilder->getTest()->id;
		$this->_testQuestion = QuestionFactory::getQuestion( $question );

		$question->save();

		return $this;
	}

	public function modifyQuestion( $questionId, array $data )
	{
		$questionsList = $this->_testBuilder->getTest()->getQuestionsList();
		if( isset($questionsList[$questionId]) )
		{
			$question = self::fillQuestion($questionsList[$questionId], $data);
			$this->_testQuestion = QuestionFactory::getQuestion( $question );
			$question->save();
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

class QuestionClosedBuilder
{
	public $question;

	public function build( array $data )
	{
		$this->fill($data);
		$this->validate();
		return $this->question;
	}


	private function validate()
	{
		$answers = json_decode($this->question->answer);
		if( !is_array($answers->correct) || empty($answers->correct) )
		{
			throw new InvalidDataException('answer',
				'Przynajmniej jedna odpowiedź musi być oznaczona jako poprawna');
		}

	}

	public function fill( array $data )
	{
		$question = new TestQuestionDal();

		// $question->id      = $data['TestQuestionId'];
		// $this->_testId  = $row['TestId'];
		$question->type       = 'closed';
		$question->text       = $data['TestQuestionText'];
		$question->points     = $data['TestQuestionPoints'];
		$question->answerTip  = $data['TestQuestionAnswerTip'];
		$question->enabled    = true;

		$answers = array();
		$corrent_answer = array();
		$row_id = 0;
		foreach($data['TestQuestionAnswer'] as $k => $v)
		{
			if( !empty($v) )
			{
				$answers[$row_id] = $v;
				if(isset($data['TestQuestionAnswerCheck'][$k]))
				{
					$corrent_answer[] = $row_id;
				}
				$row_id++;
			}
		}
		$question->answer = json_encode(array(
			'correct' => $corrent_answer,
			'answers' => $answers,
		));
		// var_dump($question->answer);exit;

		$this->question = $question;
	}
}
class QuestionOpenBuilder
{
	public function build( array $data )
	{
		$question = new TestQuestionDal();

		$question->type       = 'open';
		$question->text       = $data['TestQuestionText'];
		$question->points     = $data['TestQuestionPoints'];
		$question->answerTip  = $data['TestQuestionAnswerTip'];
		$question->answer     = $data['TestQuestionAnswer'];
		$question->enabled    = true;

		return $question;
	}
}
