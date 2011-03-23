<?php

class QuestionFactory
{
	static function getQuestionById( $questionId )
	{
		return self::getQuestion( new TestQuestionDal($questionId) );
	}

	static function getQuestion( TestQuestionDal $question )
	{
		switch($question->type)
		{
			case 'open':
				return new QuestionOpen($question);
				break;
			case 'closed':
				return new QuestionClosed($question);
				break;
			default:
				throw new Exception('Uknown question type: "'
					.$question->type.'"');
				break;
		}
	}
}


abstract class Question
{
	protected $_question;

	public function __construct( TestQuestionDal $question )
	{
		$this->_question = $question;
	}

	public function getId()
	{
		return $this->_question->id;
	}
	public function getTestId()
	{
		return $this->_question->testId;
	}
	public function getType()
	{
		return $this->_question->type;
	}
	public function getText()
	{
		return $this->_question->text;
	}
	public function getAnswerTip()
	{
		return $this->_question->answerTip;
	}
	public function getAnswer()
	{
		return $this->_question->answer;
	}
	public function getPoints()
	{
		return $this->_question->points;
	}
	public function isEnabled()
	{
		return $this->_question->enabled;
	}
}

class QuestionClosed extends Question
{
	protected $_answers;
	protected $_correctAnswers;

	public function __construct( TestQuestionDal $question )
	{
		parent::__construct($question);

		$answers = json_decode($this->_question->answer, true);
		$this->_correctAnswers = $answers['correct'];
		$this->_answers = $answers['answers'];
	}

	public function getAnswers()
	{
		return $this->_answers;
	}

	public function getCorrectAnswers()
	{
		return $this->_correctAnswers;
	}
	public function isCorrectAnswer( $answerId )
	{
		return in_array($answerId, $this->_correctAnswers, true);
	}
}


class QuestionOpen extends Question
{
	// private $_question;

	// public function __construct()
	// {
	// 	$this->_question = new TestQuestionDal();
	// 	$this->_question->_type = 'open';
	// }
}