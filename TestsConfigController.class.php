<?php

class TestsConfigController extends Controller
{
	protected $_accessPrivileges = self::ACCESS_ADMIN;
	private   $_testsList = NULL;

	private   $_tplParams  = array();
	private   $_tplMessages = array();
	private   $_tplTemplate = false;

	public function run()
	{
		$this->_tplTemplate = 'AdminTestsList';
		$actionMap = array(
			'edit'   => 'editTestAction',
			'modify' => 'modifyTestAction',
			'show'   => 'showTestAction',
		);
		if( isset($actionMap[$this->_action]) )
		{
			$this->{$actionMap[$this->_action]}();
		}

		return array(
			'template'   => $this->_tplTemplate,
			'parameters' => $this->_tplParams,
			'messeges'   => $this->_tplMessages,
		);
	}

	protected function modifyTestAction()
	{
		$this->_tplTemplate = 'AdminTestsList';
		try
		{
			$testId = false;
			if( !empty($this->_post['TestId']) )
			{
				$testId = $this->_post['TestId'];
			}
			$test = new Test($testId);
			$test->name        = $this->_post['TestName'];
			$test->duration    = $this->_post['TestDuration'];
			$test->description = $this->_post['TestDescription'];
			$test->save();

			$this->_tplMessages['info'][] = 'Modyfikacja zakończona sukcesem!';
		}
		catch(DBObjectException $e)
		{
			$this->_tplMessages['error'][]     = 'Niepoprawne dane w formularzu';
			$this->_tplParams['TestData']      = $test;
			$this->_tplParams['TestDataError'] = array(
				'field' => $e->fieldName,
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
		$this->_tplTemplate = 'AdminTestsList';
		if( isset($this->_get['id']) )
		{
			$test = new Test($this->_get['id']);
			$this->_tplParams['TestData'] = $test;
		}
	}

	protected function showTestAction()
	{
		$this->_tplTemplate = 'AdminTestDetails';
		if( isset($this->_get['id']) )
		{
			$test = new Test($this->_get['id']);
			$this->_tplParams['Test'] = $test;
			$this->_tplParams['TestMaxPoints'] = 48;
		}
	}

	public function getTestsList()
	{
		if( $this->_testsList === NULL )
		{
			$this->_testsList = new TestsList();
		}

		return $this->_testsList->getList();
	}

}