<?php
require_once('System/System_Controller.php');
require_once('System/System_Const.php');
require_once('System/System_DB.php');
require_once('Logic/BusinessLogic.php');

class Main extends System_Controller {
	
    const CASH_LIVE_TIME = 1; // но пока будет одна секунда   //3600; // время жизни кеша (1 час)
    private $BusinessLogic = null;

    function preDispatch() {
        $this->view->_CASH_LIVE_TIME = self::CASH_LIVE_TIME; // установить время жизни кеша
        $this->view->url = md5('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        $this->BusinessLogic = new BusinessLogic($this->layout, $this->view);
    }

    function indexAction() {
        $layout = 'layout';
        $this->layout->setLayout($layout);
        $this->view->test = "test";
        $products = array();
        //$products = DBunit::getProducts();

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
        $user = DBunit::checkUserSession();
        $this->BusinessLogic->chooseInterface($user);
        return $this->render();
    }
    
    function ajaxAction()
    {
        $this->BusinessLogic->handleAjax();
    }
    
    function authAction()
    {
        if($this->BusinessLogic->authUser())
            $this->mainAction();
        else
            header('/');
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

    function setWorkoutAction()
    {
        DBunit::ConnectToDB();
        DBunit::setWorkout($_SESSION['id'], $_POST['date'], $_POST['work'], $_POST['result']);
    }

    function setWorkoutPlanAction()
    {
        DBunit::ConnectToDB();
        DBunit::setWorkoutPlan($_SESSION['id'],  $_POST['beginDate'], $_POST['endDate'], $_POST['comments'],
                                    $_POST['day'], $_POST['work'], $_POST['result'], $_POST['day_comments']);
    }

    function setWorkoutWeekPlanAction()
    {
        DBunit::ConnectToDB();
        DBunit::setWorkoutWeekPlan($_SESSION['id'], $_POST['beginDate'], $_POST['endDate'], $_POST['comments']);
    }

    function setSportingEventAction()
    {
        DBunit::ConnectToDB();
        DBunit::createSportingEvent($_POST['eventName'], $_POST['beginDate'], $_POST['closeDate'], $_POST['country'], $_POST['city'], $_POST['addressee'], $_SESSION['id']);
    }

    function sendClaimAction()
    {
        DBunit::ConnectToDB();
        DBunit::createClaim($_SESSION["id"], $_POST['event_id']);
    }

    function cancelClaimAction()
    {
        DBunit::ConnectToDB();
        DBunit::removeClaim($_SESSION["id"], $_POST['event_id']);
    }

    function createAction() {
        DBunit::ConnectToDB();
        DBunit::createTable();
        DBunit::CloseConnection();
    }
}
