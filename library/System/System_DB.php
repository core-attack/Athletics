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
        self::$_dbconn = mysql_connect($host, $user, $password) or die('Not connect with MySQL!' . mysql_error());
        mysql_select_db('athletics', self::$_dbconn) or die('Not connect to database' . mysql_error());
        mysql_query("SET NAMES utf8");
        mysql_query("SET COLLATION_CONNECTION=utf8_bin");

    }

    // вылогин от БД
    static public function CloseConnection() {

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
    	return NULL;
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
        self::ConnectToDB();
        $user_id = (int)$user_id;
        $query = "SELECT role_id FROM ".ConstUnit::TABLE_USERS." where id = ".$user_id;
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

    //предстоящие соревнования
    static function getNextEvents()
    {
        $query = "SELECT id, name, event_date, country, city, address, close_date
				  FROM " . ConstUnit::TABLE_SPORTING_EVENTS . " where event_date > sysdate()";
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        $res = array();
        $i = 0;
        while ($data = mysql_fetch_object($result))
        {
            $res[$i] = array(
                "id"     => $data->id,
                "name"    => $data->name,
                "event_date"    => $data->event_date,
                "country"    => $data->country,
                "city" => $data->city,
                "address"      => $data->address,
                "close_date" => $data->close_date
            );
            $i++;
        }
        return $res;
    }



    //план тренировок на неделю
    //(как-нибудь по другому надо запрашивать, потому что тренер публикует план, а спортсмены его просматривают
    //либо что-то вроде актуальной недели хранить, либо хз)
    //а вообще лучше по дате обращаться
    //static function getWeekPlan($id)
    static function getWeekPlan()
    {
        $query = "select wwp.week_begin_date, wwp.week_end_date, wwp.comments, wp.week_day, r.work, r.result " .
        "from "
        . ConstUnit::TABLE_RESULTS . " r, "
        . ConstUnit::TABLE_WORKOUT_PLANS . " wp, "
        . ConstUnit::TABLE_WORKOUT_WEEK_PLANS . " wwp " .
        "where r.workout_plans_id = wp.id
        and wwp.id = wp.workout_week_plan_id
        and wwp.week_end_date >= curdate()
        and wwp.week_begin_date < curdate()";
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        $res = array();
        $i = 0;
        while ($data = mysql_fetch_object($result))
        {
            $res[$i] = array(
                "week_begin_date" => $data->week_begin_date,
                "week_end_date"   => $data->week_end_date,
                "comments"        => $data->comments,
                "week_day"        => $data->week_day,
                "work"            => $data->work,
                "result"          => $data->result
            );
            $i++;
        }
        return $res;
    }

    static function getWeekPlanWithId($workout_week_plan_id)
    {
        $query = "select wwp.week_begin_date, wwp.week_end_date, wwp.comments, wp.week_day, r.work, r.result " .
            "from "
            . ConstUnit::TABLE_RESULTS . " r, "
            . ConstUnit::TABLE_WORKOUT_PLANS . " wp, "
            . ConstUnit::TABLE_WORKOUT_WEEK_PLANS . " wwp " .
            "where r.workout_plans_id = wp.id
            and wwp.id = wp.workout_week_plan_id
            and wwp.id = " . $workout_week_plan_id ;
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        $res = array();
        $i = 0;
        while ($data = mysql_fetch_object($result))
        {
            $res[$i] = array(
                "week_begin_date" => $data->week_begin_date,
                "week_end_date"   => $data->week_end_date,
                "comments"        => $data->comments,
                "week_day"        => $data->week_day,
                "work"            => $data->work,
                "result"          => $data->result
            );
            $i++;
        }
        return $res;
    }

    //Возвращает все планы тренировок для конкретного тренера
    static function getWeekPlans($coach_id)
    {
        $query = "select wwp.id, wwp.week_begin_date, wwp.week_end_date, wwp.comments, wp.week_day, r.work, r.result " .
            "from "
            . ConstUnit::TABLE_RESULTS . " r, "
            . ConstUnit::TABLE_WORKOUT_PLANS . " wp, "
            . ConstUnit::TABLE_WORKOUT_WEEK_PLANS . " wwp " .
            "where r.workout_plans_id = wp.id
            and wwp.id = wp.workout_week_plan_id
            and wwp.coach_id = " . $coach_id;
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        $res = array();
        $i = 0;
        while ($data = mysql_fetch_object($result))
        {
            $res[$i] = array(
                "id" => $data->id,
                "week_begin_date" => $data->week_begin_date,
                "week_end_date"   => $data->week_end_date,
                "comments"        => $data->comments,
                "week_day"        => $data->week_day,
                "work"            => $data->work,
                "result"          => $data->result
            );
            $i++;
        }
        return $res;
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
        //mysql_free_result($result);
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

    static function getProducts($role_id)
    {
        $role_id = (int)$role_id;
        self::ConnectToDB();
        $query = "SELECT ".ConstUnit::TABLE_NAME_PRODUCTS.".*, categories.name as category FROM ".ConstUnit::TABLE_NAME_PRODUCTS." 
            JOIN categories ON (".ConstUnit::TABLE_NAME_PRODUCTS.".category_id = categories.id) WHERE role_id = ".$role_id;
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        $res = array();
        while($item = mysql_fetch_array($result))
        {
            $res[] = array(
                "id" => $item["id"],
                "login" => $item["login"],
                "password" => $item["password"],
                "name" => $item["name"],
                "sname" => $item["sname"],
                "lname" => $item["lname"],
                "category" => $item["category"],
                "borndate" => $item["borndate"],
                "passport" => $item["passport"],
                "role_id" => $item["role_id"]
            );
        }
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
    
    static function createUserSession($user)
    {
        $_SESSION['hash'] = md5($user->password);
        $_SESSION['id'] = md5($user->id);
    }
    
    static function checkUserSession()
    {
        self::ConnectToDB();
        $id =  mysql_real_escape_string($_SESSION['id']);
        $hash = mysql_real_escape_string($_SESSION['hash']);
        $sql = 'SELECT * FROM users WHERE md5(id) = \''.$id.'\' AND md5(password) = \''.$hash.'\'';
        $result = mysql_query($sql);
        if(mysql_num_rows($result) == 1)
            return mysql_fetch_object ($result);
        else
            return null;
    }

    static function getUser($login, $password)
    {
        self::ConnectToDB();
        $login = mysql_real_escape_string($login);
        $password = mysql_real_escape_string($password);
        $sql = 'SELECT * FROM users WHERE login = \''.$login.'\' AND password = \''.$password.'\'';
        $result = mysql_query($sql);
        if(mysql_num_rows($result) == 1)
            return mysql_fetch_object ($result);
        else
            return null;
    }
    
    static function getSportEvents()
    {
        self::ConnectToDB();
        $sql = 'SELECT * FROM sporting_events';
        $result = mysql_query($sql);
        $res = array();
        while($item = mysql_fetch_array($result))
        {
            $res[] = array(
                "id" => $item["id"],
                "name" => $item["name"],
                "event_date" => $item["event_date"],
                "country" => $item["country"],
                "city" => $item["city"],
                "address" => $item["address"],
                "close_date" => $item["close_date"]
            );
        }
        return $res;
    }



}
