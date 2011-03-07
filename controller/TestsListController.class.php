<?php

class TestsListController extends Controller
{
	protected $_accessPrivileges = self::ACCESS_ADMIN;
	private   $_testsList = NULL;

	public function run()
	{
		$this->_tplTemplate = 'AdminTestsList';
		$this->_actionMap = array(
			'edit'           => 'editTestAction',
			'modify'         => 'modifyTestAction',
			// 'show'           => 'showTestAction',
			// 'modifyQuestion' => 'modifyQuestionAction',
		);

		return parent::run();
	}

	protected function modifyTestAction()
	{
		// $this->_tplTemplate = 'AdminTestsList';
		try
		{
			$testId = false;
			if( !empty($this->_data['TestId']) )
			{
				$testId = $this->_data['TestId'];
			}
			$test = new TestDal($testId);
			$test->name        = $this->_data['TestName'];
			$test->duration    = $this->_data['TestDuration'];
			$test->description = $this->_data['TestDescription'];
			$test->save();

			$this->_tplMessages['info'][] = 'Modyfikacja zakończona sukcesem!';
		}
		catch(DBObjectException $e)
		{
			$this->_tplMessages['error'][]     = 'Niepoprawne dane w formularzu';
			$this->_tplParams['TestData']      = $test;
			$this->_tplParams['TestDataError'] = array(
				'field' => ucfirst($e->fieldName),
				'error' => $e->getMessage(),
			);
		}
		catch(Exception $e)
		{
			$this->_tplMessages['error'][] = $e->getMessage();
			$this->_tplParams['TestData']  = $test;
		}
	}

	protected function editTestAction()
	{
		// $this->_tplTemplate = 'AdminTestsList';
		if( isset($this->_data['id']) )
		{
			$test = new TestDal($this->_data['id']);
			$this->_tplParams['TestData'] = $test;
		}
	}

	public function getTestsList()
	{
		if( $this->_testsList === NULL )
		{
			$this->_testsList = new TestsListModule();
		}

		return $this->_testsList->getList();
	}

}