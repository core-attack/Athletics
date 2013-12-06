<?php

require_once('System/System_Controller.php');
require_once('System/System_Const.php');
require_once('System/System_DB.php');

class Main extends System_Controller {
	
    const CASH_LIVE_TIME = 1; // но пока будет одна секунда   //3600; // время жизни кеша (1 час)

    const TABLE_NAME_ADMIN = "admin_users";

    function preDispatch() {
        $this->view->_CASH_LIVE_TIME = self::CASH_LIVE_TIME; // установить время жизни кеша
        $this->view->url = md5('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    }

    function indexAction() {
        $layout = 'layout';
        // die('layout=' . $layout);
        $this->layout->setLayout($layout);
        $this->view->test = "test";
        $products = array();
        $products = DBunit::getProducts();

        $this->view->products = $products;
        return $this->render();
    }

    function signUpAction() {
        $layout = 'layout';
        $this->layout->setLayout($layout);
        $this->layout->setAction('signup');
        return $this->render();
    }

    function mainAction() {
        $layout = 'layout';
        $this->layout->setLayout($layout);
        $this->layout->setAction('main');
        return $this->render();
    }

    function registerAction() {
        $layout = 'layout';
        $this->layout->setLayout($layout);
        $this->layout->setAction('register');
        return $this->render();
    }

    function someAction() {
        $layout = 'layout';
        $this->layout->setLayout($layout);
        $this->layout->setAction('some');
        return $this->render();
    }

    // страница с админской частью
    function adminAction()
    {

    	$layout = 'admin_layout';
    	$this->layout->setLayout($layout);

        session_start();//создаёт сессию (или продолжает текущую на основе session id, переданного через GET-переменную или куку).
        $show_login = true;
        $val = $_SESSION['val'];

        if ($val != '')
        {
        	$show_login = false;
        	$this->view->admin_name = DBunit::getAdminName(self::TABLE_NAME_ADMIN, $val);
        }
        $this->view->show_login = $show_login;
    	return $this->render();
    }

    function checkloginAction()
    {
    	DBunit::ConnectToDB();
    	$val = $_GET['val'];
        if (DBunit::CheckLoginPassAdmin(self::TABLE_NAME_ADMIN, $val))
        {
        	session_start();
			$_SESSION['val']  = $val;
        	echo '1';
        }
        else
        {
            session_start();
            $_SESSION['val'] = '';
            echo '0';
        }
        DBunit::CloseConnection();
    }

    function addproductAction()
    {
        $name = $_GET['name'];
        $descr = $_GET['descr'];
        $description = $_GET['description'];
        $article = $_GET['article'];
        $cat_id = $_GET['cat_id'];
        $gen_id = $_GET['gen_id'];
        $price = $_GET['price'];
        $count = $_GET['count'];
        DBunit::ConnectToDB();
        $res = DBunit::SaveProductToBD($name,$descr,$description,$article,$cat_id,$gen_id,$price, $count);
        DBunit::CloseConnection();
        if($res)
            echo '1';
        else
            echo '0';
    }

    function createAction() {
        DBunit::ConnectToDB();
        DBunit::createTable();
        DBunit::CloseConnection();
    }
}
