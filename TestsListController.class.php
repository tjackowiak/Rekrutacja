<?php

class TestsListController extends Controller
{
	// protected $siteAction = 'add';
	public function run()
	{
		$this->setAction( 'add' );
		if( isset($this->__get['action']) )
		{
			switch( $this->__get['action'] )
			{
				case 'add':
				case 'update':
					$this->addTest();
				break;
				case 'edit':
					$this->editTest();
				break;
			}
		}

		$tests = new TestsList();

		// $this->__tpl->formAction = $this->formAction;
		$this->__tpl->setAction('AdminTestsList');
		$this->__tpl->tests = $tests->getlist();

		return $this->__tpl;
	}

	public function addTest()
	{
		try
		{
			$testId = false;
			// $this->__tpl->FormAction = 'add';
			if( !empty($this->__post['TestId']) )
			{
				$testId = $this->__post['TestId'];
				$this->setAction( 'update' );
				// $this->formAction = 'update';
			}
			$test = new Test($testId);

			$test->setName( $this->__post['TestName'] );
			$test->setDuration( $this->__post['TestDuration'] );
			$test->setDescription( $this->__post['TestDescription'] );
			$test->save();
			$this->__tpl->addMessageInfo('Test został dodany!');
		}
		catch(Exception $e)
		{
			$this->__tpl->addMessageError($e->getMessage());
			$this->__tpl->TestForm = array
			(
				'id'          => $this->__post['TestId'],
				'name'        => $this->__post['TestName'],
				'duration'    => $this->__post['TestDuration'],
				'description' => $this->__post['TestDescription']
			);
		}
	}

	public function editTest()
	{
		if( isset($this->__get['id']) )
		{
			$test = new Test($this->__get['id']);

			// $this->formAction = 'update';
			$this->setAction( 'update' );
			$this->__tpl->TestForm = $test;
		}
	}
}