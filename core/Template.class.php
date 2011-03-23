<?php
// require 'libs/PHPTAL/PHPTAL.php';

class Template
{
	private $templates_path = '/templates/';
	private $tpl;
	private $argv    = array();
	private $message = array();
	public $blocks   = array();

	function __construct( $template_name )
	{
		$this->templates_path = Config::getInstance()->getBaseDir() . $this->templates_path;
		$this->tpl = new PHPTAL($this->templates_path . $template_name . '.tpl.html');
	}

	// public function setController( &$controller )
	// {
	// 	$this->tpl->this = $controller;
	// }

	public static function getBlockName( $block )
	{
		if(strstr($block, '/'))
		{
			$block = str_replace('/', '.tpl.html/', $block);
		}
		else
		{
			$block = $block . '.tpl.html/' . $block;
		}
		return $block;
	}

	public function addMessageInfo( $message )
	{
		$this->messages['info'][] = $message;
	}
	public function addMessageWarning( $message )
	{
		$this->messages['warning'][] = $message;
	}
	public function addMessageError( $message )
	{
		$this->messages['error'][] = $message;
	}

	public function __set( $name, $value )
	{
		$this->argv[$name] = $value;
	}

	public function execute()
	{
		if( !empty($this->argv) )
		{
			$this->tpl->argv = $this->argv;
		}
		if( !empty($this->messages) )
		{
			$this->tpl->messages = $this->messages;
		}
		if( !empty($this->blocks) )
		{
			$this->tpl->blocks = $this->blocks;
		}

		/* DEBUG */ $this->tpl->setForceReparse(true);
		return $this->tpl->execute();
	}
}

class RouteHelper
{
	public static function getLink( $controller, $action = false, $params = array() )
	{
		$module = false;
		if( is_object($controller) )
			$module = get_class($controller);
		elseif( is_string($controller) )
			$module = $controller;
		// if( $action === false )
		// 	$action = $controller->getAction();
		
		return Router::getRoute( $module, $action, $params );
	}
}

/**
 * Dodaje modyfikator PHPTALES "link:"
 *
 * Modyfikator sluzy do tworzenia linkow do konkretnego 
 * kontrolera i akcji z parametrami.
 *
 * Sposob uzycia:
 *   ${link: <controller>, <action>, <params>}
 * gdzie:
 *   <controller> to nazwa kontrolera do ktorego chcemy linkowac.
 *                Jako parametr mozna podać obiekt klasy danego kontrolera
 *                (np. this) lub string (tekst w apostrofach).
 *   <action>     (opcjonalny) to nazwa akcji. Jako parametr mozna podać
 *                zmienna dostepna w szablonie lub string (tekst w apostrofach).
 *   <params>     (opcjonalny) to tablica asocjacyjna z parametrami. Dostepne sa
 *                dwa formaty "array('id'=>x)" i "['id'=>x]"
 * Przyklad uzycia:
 *  ${link: this}                    - linkuje do glownej akcji biezacego kontolera
 *  ${link: this, 'list'}            - biezacy kontroler z akcja 'list'
 *  ${link:'Test','edit',['id'=>1]}  - kontroler TestController, akcja 'edit'
 *                                     z parametrem id = 1
 */
function phptal_tales_link( $src, $nothrow )
{
	$src = explode(',', $src, 3);

	$controller = phptal_tales($src[0], $nothrow);
	$action     = 'false';
	$params     = 'array()';
	if( !empty($src[1]) )
	{
		$action = phptal_tales($src[1], $nothrow);
	}
	if( !empty($src[2]) )
	{
		$params = trim($src[2]);
		if( 0===strpos($params, 'array') )
		{
			$params = 'php:'.$params;
		}
		elseif( 0===strpos($params, '[') && ']'===mb_substr($params, -1) )
		{
			$params = 'php:array('.trim($params, '[]').')';
		}
		$params = phptal_tales($params, $nothrow);
	}

	$return = 'RouteHelper::getLink('.$controller.','.$action.','.$params.')';

	return $return;
}


function phptal_tales_ifeq($src, $nothrow)
{
    $src = explode(',', $src);
    $onTrue  = empty($src[2]) ? 'true' : phptal_tales(trim($src[2]), $nothrow);
    $onFalse = empty($src[3]) ? 'null' : phptal_tales(trim($src[3]), $nothrow);
    return '('
        . phptal_tales(trim($src[0]), $nothrow) . '===' . phptal_tales(trim($src[1]), $nothrow)
        . ' ? ' . $onTrue . ' : ' . $onFalse . ')';
}