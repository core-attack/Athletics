<?php

class System_Layout {

	protected $_path = 'views/layouts';
	protected $_layout = 'layout';
	protected $_view = '';
	protected $_action = 'index';

	function __construct($view)
	{
		$this->_view = $view;
	}
	
	function setAction($action)
	{
		$this->_action = $action;
	}

	function setLayout($scriptname)
	{
		$this->_layout = $scriptname;
	}
	
	function setDirectory($directory)
	{
		$this->_path = $directory;
	}
	
	function content()
	{
		$content = $this->_view->render($this->_action);
		
		return $content;
	}
	
	function renderScript($path = 'layout.phtml')
	{
		include_once($path);
	}
	
	function render()
	{
        return $this->renderScript($this->_path . '/' . $this->_layout . '.phtml');
	}
}