<?php
session_start();
require '../config.php';
Config::getInstance();
// var_dump($_SERVER);
// var_dump(Router::getRoute('test', 'action', array('id'=>123)));

class AdminController extends Controller
{
	private $_tpl;

	public function run()
	{
		$this->_tpl        = new Template('AdminMainFrame');
		$router            = new Router($_SERVER);
		$defaultController = 'TestsConfigController';
		

		$routeTo = $router->determineRoute();
		// var_dump($routeTo);
		if( $routeTo !== false 
			&& class_exists($routeTo['controller'])
			&& is_subclass_of($routeTo['controller'], 'Controller') )
		{
			$controllerName = $routeTo['controller'];
		}
		else
		{
			$controllerName = $defaultController;
		}

		try
		{
			$controller = new $controllerName($this->_get, $this->_post);
			$controller->setAction($routeTo['action']);
			$this->fillBlock('main', $controller);
			
			echo $this->_tpl->execute();
		}
		catch(Exception $e)
		{
			var_dump($e);
		}
	}

	private function fillBlock( $blockName, Controller $controller )
	{
		$data = array
		(
			'controller' => false,
			'template'   => false,
			'parameters' => false,
		);

		$blockData = $controller->run();
		// var_dump($blockData);
		if( !empty($blockData['template']) )
		{
			$data['controller'] = $controller;
			$data['template']   = Template::getBlockName($blockData['template']);

			//TODO: implement better messages
			if( !empty($blockData['messeges']['info']) )
				foreach($blockData['messeges']['info'] as $msg)
					$this->_tpl->addMessageInfo($msg);
			if( !empty($blockData['messeges']['warn']) )
				foreach($blockData['messeges']['warn'] as $msg)
					$this->_tpl->addMessageWarning($msg);
			if( !empty($blockData['messeges']['error']) )
				foreach($blockData['messeges']['error'] as $msg)
					$this->_tpl->addMessageError($msg);
			
			if( !empty($blockData['parameters']) )
			{
				$data['parameters'] = $blockData['parameters'];
			}
		}
		$this->_tpl->blocks[$blockName] = $data;
	}
}

$controller = new AdminController($_GET, $_POST);
$controller->run();

// var_dump($controller->getAccess());