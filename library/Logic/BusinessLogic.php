<?php
//require_once 'Models/Actions.php';

class BusinessLogic
{
    const ROLE_ADMIN = 1;
    const ROLE_SPORTSMEN = 2;
    const ROLE_TRENER = 3;
    
    const AJAX_PERSON_ACTION = 'person';
    const AJAX_EVENT_ACTION = 'event';
    
    private $layout = null;
    private $view = null;
    private $errorAjax = '';

    function __construct($layout, $view) {
        $this->layout = $layout;
        $this->view = $view;
    }
    
    public function authUser()
    {
        $login = mysql_real_escape_string($_POST['login']);
        $password = mysql_real_escape_string($_POST['password']);
        if(DBunit::checkLoginPassword($login, $password)){
            DBunit::createUserSession (DBunit::getUser($login, $password));
            return true;
        }
        else {
            return false;
        }
    }
    
    public function chooseInterface($user)
    {
        $this->layout->setLayout('interface');
        switch(DBunit::getRoleById($user->id))
        {
            case 1:
                $this->view->sportsmens = DBunit::getProducts(self::ROLE_SPORTSMEN);
                $this->view->treners = DBunit::getProducts(self::ROLE_TRENER);
                $this->view->events = DBunit::getSportEvents();
                $this->layout->setAction('adminInterface');
            break;
            case 2:
                $this->layout->setAction('sportsmenInterface');
            break;
            case 3:
                $this->layout->setAction('trenerInterface');
            break;
            default :
                $this->layout->setAction('notAccess');
            break;
        }
    }
    
    public function handleAjax()
    {
        $this->checkFields($_POST);
        if(!empty($this->errorAjax))
        {
            echo json_encode(array('status' => 'error', 'message' => $this->errorAjax));
            exit;
        }
        switch($_POST["type"])
        {
            case self::AJAX_PERSON_ACTION:
                $id = (int)$_POST["type"];
                if($id > 0){
                    $sql = "UPDATE ".ConstUnit::TABLE_NAME_PRODUCTS." SET ";
                    $end = "WHERE id = ".$id;
                }
                else{
                    $sql = "INSERT ".ConstUnit::TABLE_NAME_PRODUCTS." SET ";
                    $end = "";
                }
                $sql .= "name = '".  mysql_real_escape_string($_POST["name"])."',"
                        ."sname = '".  mysql_real_escape_string($_POST["sname"])."',"
                        ."lname = '".  mysql_real_escape_string($_POST["lname"])."',"
                        ."category_id = ".  (int)$_POST["category"].","
                        ."borndate = '".  $this->modifyDate($_POST["borndate"])."',"
                        ."passport = ".  (int)$_POST["passport"].","
                        ."login = '".  mysql_real_escape_string($_POST["login"])."',"
                        ."password = '".  mysql_real_escape_string($_POST["password"])."',"
                        ."role_id = ".  (int)$_POST["role"]
                        .$end;
                DBunit::ConnectToDB();
                DBunit::requestToDB($sql);
                echo json_encode(array('status' => 'success'));
                exit;
            break;
            case self::AJAX_EVENT_ACTION:
                
            break;
        }
    }
    
    private function checkFields($post)
    {
        if(!$this->checkDate($post['borndate']))
            $this->errorAjax .= 'Некорректная дата<br>';
        if(!$this->checkString($post['login']))
            $this->errorAjax .= 'Некорректный логин<br>';
        if(!$this->checkString($post['password']))
            $this->errorAjax .= 'Некорректный пароль<br>';
        if(!$this->checkNum($post['passport']))
            $this->errorAjax .= 'Некорректный паспорт<br>';
    }
    
    private function checkDate($date)
    {
        $arr = explode('-', $date);
        if(count($arr) == 3 and checkdate($arr[1], $arr[0], $arr[2]))
            return true;
        else
            return false;
    }
    
    private function modifyDate($date)
    {
        $arr = explode('-', $date);
        $arr = array_reverse($arr);
        return implode('-', $arr);
    }
    
    private function checkString($str)
    {
        return preg_match('/^[-_0-9a-zA-Z]{3,12}$/isU', $str);
    }
    
    private function checkNum($str)
    {
        return preg_match('/^[0-9]{4,12}$/isU', $str);
    }

}