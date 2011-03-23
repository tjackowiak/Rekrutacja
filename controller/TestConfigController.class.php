<?php

class TestConfigController extends Controller
{
	protected $_accessPrivileges = self::ACCESS_ADMIN;
	private   $_test;

	public function run()
	{
		$this->_tplTemplate = 'AdminTestDetails';
		$this->_actionMap = array(
			// 'edit'           => 'editTestAction',
			// 'modify'         => 'modifyTestAction',
			'show'   => 'showTestAction',
			'modify' => 'modifyQuestionAction',
		);

		if( empty($this->_data['testId']) )
		{
			throw new Exception("Empty testId");
		}
		$this->_test = new TestModule($this->_data['testId']);

		return parent::run();
	}

	protected function showTestAction()
	{
		$tb  = new TestBuilder();
		$this->_tplParams['AvailableQuestionTypes'] = QuestionBuilder::getQuestionTypes();
		$this->_tplParams['Test'] = $this->_test;
	}

	protected function modifyQuestionAction()
	{
		try
		{
			$tb = new TestBuilder($this->_test->id);
			// var_dump($tb);
			if( !empty($this->_data['TestQuestionId']) )
			{
				die('edycja!');
				$tb->modifyQuestion($this->_data['TestQuestionId'], $this->_data);
			}
			else
			{
				var_dump($this->_data);
				$tb->editQuestion()
				->addQuestion($this->_data);
				// die('nowe!');
			}
			$this->_tplMessages['info'][] = 'Modyfikacja zakończona sukcesem!';

			
			// $test = new TestModule($this->_data['testId']);
			// $this->_tplParams['Test'] = $this->_test;
			
			// $question = $test->fillQuestion($this->_data);
			// var_dump(__LINE__,$test,$question);exit;
			// $test->addQuestion($question);

		}
		catch(InvalidDataException $e)
		{
			// var_dump($e);exit;
			$this->_tplMessages['error'][]     = 'Niepoprawne dane w formularzu - '.$e->getMessage();
			$this->_tplParams['TestDataError'] = array(
				'field' => ucfirst($e->fieldName),
				'error' => $e->getMessage(),
			);
		}
		catch(Exception $e)
		{
			// var_dump($e);exit;
			$this->_tplMessages['error'][] = $e->getMessage();
		}

		// $this->_tplParams['QuestionForm'] = $tb->getQuestion();
		$this->showTestAction();
	}
}