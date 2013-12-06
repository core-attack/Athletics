<?php

require_once('System/System_View.php');
require_once('System/System_Layout.php');

class System_Controller {

	public $root = '';
	protected $view = '';
        /**
         *
         * @var System_Layout 
         */
	protected $layout = '';
	protected $_action = 'index';
	
	function __construct($root = '')
	{
		$this->root = $root;
		$this->loadConfig();
		$this->initView();
		$this->initLayout();		
		$this->preDispatch();
		$this->_action = $this->getParam('action', 'index');
		if (!is_null($this->_action))
		{
			return $this->{$this->_action . 'Action'}();
		}		
	}
	
	function preDispatch()
	{
		
	}
	
	function getParam($param, $default = null)
	{
		$array = array_merge($_POST, $_GET);
		if (isset($array[$param]))
            return $array[$param];
		else return $default;
	}	
	
	function getParams()
	{
		return array_merge($_POST, $_GET);		
	}

	function loadConfig()
	{
	}

	function initView()
	{
		$this->view = new System_View();
	}
	
	function initLayout()
	{
		$this->layout = new System_Layout($this->view);
	}	

	function renderScript($path = 'index.phtml')
	{
		return $this->view->renderScript($path);
	}
	
	function render($scriptname = null)
	{
		return $this->layout->render();
	}	
	
}