<?php

class System_View
{	
	protected $_path = 'views';
	protected $_helperPath = 'views/helpers';
	protected $_partialsPath = 'views/partials';
	
	function setPath($path = 'views')
	{
		$this->setPath($path);
	}
	
	function renderScript($path = 'index.phtml')
	{
		ob_start();
		include($path);
		$content = ob_get_contents();
		ob_end_clean();
		
		return $content;
	}
	
	function render($scriptname = 'index')
	{
		return $this->renderScript($this->_path . '/' . $scriptname . '.phtml');
	}
	
	function getHelper($helper)
	{
		require_once($this->_helperPath . '/' . $helper . '.php');
		$helper = new $helper();
		return $helper;
	}
	
	function setHelperPath($path = 'helpers')
	{
		$this->_helperPath = $path;
	}
	
	function partial($scriptname, $params)
	{
		$view = new System_View();
		if (is_array($params)) $view->assign($params);
		elseif (is_object($params)) $view->assign(get_object_vars($params));
		else return null;
		return $view->renderScript($this->_partialsPath . '/' . $scriptname . '.phtml');		
	}
	
	function assign($array = array())
	{
		foreach ($array as $key=>$element)
		{
			$this->{$key} = $element;
		}
	}
}