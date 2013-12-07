<?php

require_once('System/System_Const.php');

/**
 * Модуль для работы с БД
 * Требует PHP не ниже 5.3.0
 */
class DBunit {

    private static $_dbconn = NULL;

    // коннект в бд
    static public function ConnectToDB() {
        ini_set('display_errors',1);
        error_reporting(E_ALL);
        mysql_error();
        $host = 'localhost';
        $database = 'athletics';
        $user = 'core';
        $password = 'pass';
        $link = mysql_connect($host, $user, $password) or die('Not connect with MySQL!' . mysql_error());
        self::$_dbconn = mysql_select_db('athletics', $link) or die('Not connect to database' . mysql_error());
        //$link = mysql_connect('localhost', 'vasya', '111') or die("not connect!!!");
        //self::$_dbconn = mysql_select_db('athletics', $link);
        //var_dump(self::$_dbconn);
        mysql_query("SET NAMES utf8");
        mysql_query("SET COLLATION_CONNECTION=utf8_bin");

    }

    // вылогин от БД
    static public function CloseConnection() {

        //var_dump(self::$_dbconn);
        mysql_close(self::$_dbconn) ; // вылогиниться от БД //or die('Can not close connection' . mysql_error())
        self::$_dbconn = NULL;
    }

    // создать табличу
    static public function createTable()
    {
    	/*$query = "CREATE TABLE " . Main::TABLE_NAME_ADMIN . " (
    	 uid int NOT NULL AUTO_INCREMENT,
         PRIMARY KEY(uid),
         admin_login varchar(11) NOT NULL,
         admin_name varchar(11) NOT NULL,
         admin_password varchar(11) NOT NULL
        );";*/
        /*$query = "INSERT INTO " . Main::TABLE_NAME_ADMIN . "(admin_login, admin_name, admin_password) VALUES ('vasya', 'Vasya Pupkin', 'Gfhjkm27');";*/
        /*$query = "CREATE TABLE " . ConstUnit::TABLE_NAME_PRODUCTS . " (
    	 uid int NOT NULL AUTO_INCREMENT,
         PRIMARY KEY(uid),
         name varchar(255) NOT NULL,
         descr varchar(255) NOT NULL,
         description varchar(11) NOT NULL,
         cat_id int NOT NULL,
         genre_id int NOT NULL,
         count int NOT NULL,
         price int NOT NULL
        );";*/
        /*$query = "CREATE TABLE " . ConstUnit::TABLE_NAME_PRODUCTS . " (
    	 uid int NOT NULL AUTO_INCREMENT,
         PRIMARY KEY(uid),
         name varchar(255) NOT NULL,
         descr varchar(255) NOT NULL,
         description text NOT NULL,
         article varchar(255) NOT NULL,
         cat_id int NOT NULL,
         gen_id int NOT NULL,
         price int NOT NULL,
         count int NOT NULL
        );";*/
        //$result = mysql_query($query) or die('Query failed: ' . mysql_error());
        //print_r($result);
        //die(' done!!! ');
    }

    // получить имя админа
	static public function getAdminName($table_name, $val)
	{
		self::ConnectToDB();
        $query = "SELECT admin_login, admin_password, admin_name FROM " . $table_name;
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());//посылает запрос активной базе данных сервера, на который ссылается переданный указатель.
        $res = array();
        while ($data = mysql_fetch_object($result))//Обрабатывает ряд результата запроса, возвращая ассоциативный массив, численный массив или оба.
        {
            $res[] = array('admin_login' => $data->admin_login,
            			   'admin_password' => $data->admin_password,
            			   'admin_name' => $data->admin_name);
        }
        mysql_free_result($result);//Освобождает память от результата запроса
        foreach ($res as $admin)
        {
        	$str = $admin['admin_login'].":".$admin['admin_password'];
        	$str = md5($str);//???
        	if ($str == $val)
        	{
        		self::CloseConnection();
        		return $admin['admin_name'];
        	}
        }
        self::CloseConnection();
    	return 'no name admin';
	}

    // проверить логин/пароль админа
    static function CheckLoginPassAdmin($table_name, $val)
    {
        $query = "SELECT admin_login, admin_password, admin_name
				  FROM " . $table_name;
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        $res = array();
        while ($data = mysql_fetch_object($result))
        {
            $res[] = array('admin_login' => $data->admin_login,
            			   'admin_password' => $data->admin_password,
            			   'admin_name' => $data->admin_name);
        }
        mysql_free_result($result);
        foreach ($res as $admin)
        {
        	$str = $admin['admin_login'].":".$admin['admin_password'];
        	$str = md5($str);
        	if ($str == $val)
        		return true;
        }
    	return false;
    }

    //ниже будут описаны функции добавления записей в БД

    //аутентификация
    static function checkLoginPassword($login, $password)
    {
        self::ConnectToDB();
        $query = "SELECT login, password
				  FROM " . ConstUnit::TABLE_USERS .
                 " WHERE login = '" . $login . "' and password = '" . $password . "'";
        //echo '<br>' . $query . ' '. '<br>';
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        while ($data = mysql_fetch_object($result))
        {
            if ($data->login == $login && $data->password == $password)
            {
                //echo "Пользователь найден";
                return true;
            }
            //echo $data->login . ' ' . $data->password . '<br>';
        }
        mysql_free_result($result);
        self::CloseConnection();
        return false;
    }

    //проверка на наличие такого логина в БД
    static function checkLogin($login)
    {
        self::ConnectToDB();
        $query = "SELECT login, password
				  FROM " . ConstUnit::TABLE_USERS .
            " WHERE login = '" . $login . "'";
        echo '<br>' . $query . ' '. '<br>';
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        while ($data = mysql_fetch_object($result))
        {
            if ($data->login == $login)
                return true;
        }
        mysql_free_result($result);
        self::CloseConnection();
        return false;
    }

    //id пользователя
    static function getId($login, $password)
    {
        $query = "SELECT id
				  FROM " . ConstUnit::TABLE_USERS . " where login = '" . $login . "' and password = '" . $password . "'";
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        return mysql_fetch_object($result)->id;
    }

    //пароль пользователя
    static function getPasswordById($user_id)
    {
        $query = "SELECT password
				  FROM " . ConstUnit::TABLE_USERS . " where id = '" . $user_id . "'";
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        return mysql_fetch_object($result)->password;
    }

    //роль пользователя
    static function getRoleById($user_id)
    {
        $query = "SELECT role_id
				  FROM " . ConstUnit::TABLE_USERS . " where id = " . $user_id . "";
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        return mysql_fetch_object($result)->role_id;
    }

    //данные пользователя
    static function getUserInfo($user_id)
    {
        $query = "SELECT name, sname, lname, email, borndate, tin, passport, login
				  FROM " . ConstUnit::TABLE_USERS . " where id = '" . $user_id . "'";
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        while ($data = mysql_fetch_object($result))
        {
            return array(
                "name"     => $data->name,
                "sname"    => $data->sname,
                "lname"    => $data->lname,
                "email"    => $data->email,
                "borndate" => $data->borndate,
                "tin"      => $data->tin,
                "passport" => $data->passport,
                "login"    => $data->login
            );
        }




    }

    static function getPasswordByLogin($login)
    {
        $query = "SELECT password
				  FROM " . ConstUnit::TABLE_USERS . " where id = " . $login;
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        return mysql_fetch_object($result)->password;
    }

    static function requestToDB($query)
    {
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        mysql_free_result($result);
        return true;
    }

    static function createRelationshipBetweenAthleteAndCoach($athlete_id, $coach_id)
    {
        $query = "INSERT INTO " . ConstUnit::TABLE_ATHLETES_COACHES .
            " (athlete_id, coach_id) " .
            " VALUES ('" . $athlete_id . "', '" . $coach_id . "'  );";
        self::requestToDB($query);
    }

    static function createCategorie($name, $full_name)
    {
        $query = "INSERT INTO " . ConstUnit::TABLE_CATEGORIES .
            " (id, name, full_name) " .
            " VALUES ( '" . '' . "', '" . $name . "', '" . $full_name . "' );";
        self::requestToDB($query);
    }

    static function createClaim($athlete_id, $event_id)
    {
        $query = "INSERT INTO " . ConstUnit::TABLE_CLAIMS .
            " (id, athlete_id, event_id) " .
            " VALUES ( '" . '' . "', '" . $athlete_id . "', '" . $event_id . "' );";
        self::requestToDB($query);
    }

    static function createDiscipline($name, $time, $sporting_event_id, $category_id)
    {
        $query = "INSERT INTO " . ConstUnit::TABLE_DISCIPLINES .
            " (id, name, time, sporting_event_id, category_id) " .
            " VALUES ( '" . '' . "', '" . $name . "', '"  . $time . "', '" . $sporting_event_id . "', '" . $category_id . "'  );";
        self::requestToDB($query);
    }

    static function createResult($work, $result, $workout_id, $workout_plans_id, $discipline_id)
    {
        $query = "INSERT INTO " . ConstUnit::TABLE_RESULTS .
            " (id, work, result, workout_id, workout_plans_id, discipline_id) " .
            " VALUES ( '" . '' . "', '" . $work . "', '" . $result . "', '" . $workout_id . "', '" . $workout_plans_id . "', '" . $discipline_id . "'    );";
        self::requestToDB($query);
    }

    static function createRole($name)
    {
        $query = "INSERT INTO " . ConstUnit::TABLE_ROLES .
            " (id, name) " .
            " VALUES ( '" . '' . "', '" . $name . "' );";
        self::requestToDB($query);
    }

    static function createSportingEvent($name, $event_date, $close_date, $country, $city, $address)
    {
        $query = "INSERT INTO " . ConstUnit::TABLE_SPORTING_EVENTS .
            " (id, event_date, close_date, country, city, address) " .
            " VALUES ( '" . '' . "', '" . $name . "', '" . $event_date . "', '" . $close_date . "', '" . $country . "', '" . $city . "', '" . $address . "'   );";
        self::requestToDB($query);
    }

    static function createUser($name, $sname, $lname, $email, $category_id, /*$borndate,*/ $TIN, $passport, $role_id, $login, $password)
    {
        $query = "INSERT INTO " . ConstUnit::TABLE_USERS .
            " (id, name, sname, lname, email, category_id, tin, passport, role_id, login, password) " .
            " VALUES ( '" . '' . "', '" . $name . "', '" . $lname . "', '" . $sname . "', '" . $email . "', '"
            . $category_id . "' , "/*to_date('" . $borndate . "', dd.mm.yyyy) , '"*/
            . $TIN . " , '" . $passport . "', '". $role_id . "', '" .$login . "', '" .$password. "');";
        self::requestToDB($query);
    }
    static function createWorkoutPlan($week_day, $workout_week_plan_id)
    {
        $query = "INSERT INTO " . ConstUnit::TABLE_WORKOUT_PLANS .
        " (id, week_day, workout_week_plan_id) " .
        " VALUES ( '" . '' . "', '" . $week_day . "', '" . $workout_week_plan_id . "' );";
        self::requestToDB($query);
    }

    static function createWorkoutWeekPlan($week_begin_date, $week_end_date, $comments)
    {
        $query = "INSERT INTO " . ConstUnit::TABLE_WORKOUT_WEEK_PLANS .
            " (id, week_begin_date, week_end_date, comments) " .
            " VALUES ( '" . '' . "', '" . $week_begin_date . "', '" . $week_end_date . "', '" . $comments . "'  );";
        self::requestToDB($query);
    }


    static function createWorkout($workout_date, $athlete_id, $coach_id)
    {
        $query = "INSERT INTO " . ConstUnit::TABLE_WORKOUTS .
            " (id, workout_date, athlete_id, coach_id) " .
            " VALUES ( '" . '' . "', '" . $workout_date . "', '" . $athlete_id . "', '" . $coach_id . "'  );";
        self::requestToDB($query);
    }

    static function SaveProductToBD($name,$descr,$description,$article,$cat_id,$gen_id,$price, $count)
    {
        $query = "INSERT INTO " . ConstUnit::TABLE_NAME_PRODUCTS .
				 " (name, descr, description, article, cat_id, gen_id, price, count) " .
                 " VALUES ( '" . $name . "', '" . $descr . "', '" . $description . "', '" . $article . "', '" . $cat_id . "', '" . $gen_id . "', '" . $price . "', '" . $count . "' );";
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        mysql_free_result($result);
        return true;
    }

    //ниже будут функции получения данных из БД
    //получаем определенную тренировку
    static function getWorkout($workout_id)
    {
        self::ConnectToDB();
        $query = "select w.workout_date, r.work, r.result from " . ConstUnit::TABLE_WORKOUTS . " w," . ConstUnit::TABLE_RESULTS . " r where w.id = " . $workout_id .
        " and w.id = r.workout_id";
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        $i = 0;
        $res = array();
        while ($data = mysql_fetch_object($result))
        {
            $res[$i] = array('date'         => $data->workout_date,
                             'work'         => $data->work,
                             'result'       => $data->result
            );
            //echo '<br>workout_date=' . $data->workout_date;
            //echo '<br>work='         . $data->work;
            //echo '<br>result='       . $data->result;
            $i++;
        }
        self::CloseConnection();
        return $res;
    }

    static function getAllWorkouts($user_id)
    {
        self::ConnectToDB();
        $query = "select w.id, w.workout_date from " . ConstUnit::TABLE_WORKOUTS . " w,"
                                                     . ConstUnit::TABLE_USERS . " u" .
            " where u.id = " . $user_id .
            " and w.athlete_id  = u.id";
        //echo $query;
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        $numColumns = mysql_num_rows($result);
        $workouts = array($numColumns);
        $i = 0;
        while ($i <  $numColumns)
        {
            while ($data = mysql_fetch_object($result))
            {
                //echo "<br>workout_id=" . $data->id;
                //echo "<br>workout_date=" . $data->workout_date;
                $workout_id = $data->id;
                //$date = $data->workout_date;
                $workout = DBunit::getWorkout($workout_id);
                for ($j = 0; $j < count($workout); $j++)
                {
                    //echo "<br>" . $workout[$j]['date'];
                    //echo "<br>" . $workout[$j]['work'];
                    //echo "<br>" . $workout[$j]['result'];
                    $workouts[$i] = array(
                        'id'      => $workout_id ,
                        'date'    => $workout[$j]['date'],
                        'work'   => $workout[$j]['work'],
                        'result' => $workout[$j]['result']
                    );
                    $i++;
                }
            }

        }
        self::CloseConnection();
        return $workouts;
    }

    static function getProducts()
    {
        self::ConnectToDB();
        $query = "SELECT * FROM " . ConstUnit::TABLE_NAME_PRODUCTS;
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        $res = array();
        /*while ($data = mysql_fetch_object($result))
        {
            $res[] = array('name' => $data->name,
            			   'descr' => $data->descr,
            			   'description' => $data->description,
                           'cat_id' => $data->cat_id,
                           'genre_id' => $data->genre_id,
                           'count'  => $data->count,
                           'price'  => $data->price);
        }*/
        mysql_free_result($result);
        self::CloseConnection();
        return $res;
    }

    //ниже - функции обновления данных
    static function updateLogin($user_id, $login)
    {
        self::ConnectToDB();
        $query = "update " . ConstUnit::TABLE_USERS . " set login='" . $login . "' where id=" . $user_id;
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        self::CloseConnection();
    }

    static function updatePassword($user_id, $password)
    {
        self::ConnectToDB();
        $query = "update " . ConstUnit::TABLE_USERS . " set password='" . $password . "' where id=" . $user_id;
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        self::CloseConnection();
    }

    static function updateUser($user_id, $name, $sname, $lname, $borndate, $tin, $passport, $login, $password)
    {
        self::ConnectToDB();
        $query = "update " . ConstUnit::TABLE_USERS
            . " set name='" . $name . "'"
            . " , sname='" . $sname . "'"
            . " , lname='" . $lname . "'"
            . " , borndate='" . $borndate . "'"
            . " , tin=" . $tin . ""
            . " , passport='" . $passport . "'"
            . " , login='" . $login . "'"
            . " , password='" . $password . "'"
            . " where id=" . $user_id;
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        self::CloseConnection();
    }

    static function updateUserWithoutPassword($user_id, $name, $sname, $lname, $borndate, $tin, $passport, $login)
    {
        self::ConnectToDB();
        $query = "update " . ConstUnit::TABLE_USERS
            . " set name='" . $name . "'"
            . " , sname='" . $sname . "'"
            . " , lname='" . $lname . "'"
            . " , borndate='" . $borndate . "'"
            . " , tin=" . $tin . ""
            . " , passport='" . $passport . "'"
            . " , login='" . $login . "'"
            . " where id=" . $user_id;
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        self::CloseConnection();
    }

    static function getuser(){}



}
