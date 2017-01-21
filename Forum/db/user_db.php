<?php
    declare(strict_types=1);
    
    include_once('db_connect.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/user.php');

    /**
     * 
     */
    class UserDB
    {
        private $conn_db;
        
        function __construct(){}
        
        function new_user(string $user_name, string $password, string $email, 
            string $name, string $surname, string $country, string $state, 
            string $city, string $profile_picture = 'images/default.jpg', int $is_mod = 0, 
            int $deleted = 0) {
            $this->conn_db = new Connection();
            $mysqli = $this->conn_db->connect();
            $sql = 'insert into user (user_name, password, email, name, surname, 
                country, state, city, profile_picture, is_mod, deleted) values (?,MD5(?),?,?,?,?,?,?,?,?,?)';
            if($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param('sssssssssii', $user_name,  $password, $email, $name, $surname, 
                    $country, $state, $city, $profile_picture, $is_mod, $deleted);
                $stmt->execute();
                $stmt->close();
            }
            $mysqli->close();
        }

        function edit_user(int $user_id, string $name, string $surname, string $country, string $state, 
            string $city, string $profile_picture = 'images/default.jpg') {
            $this->conn_db = new Connection();
            $mysqli = $this->conn_db->connect();
            $sql = 'update user set name=?, surname=?, country=?, state=?, city=?, profile_picture=? where user_id=?';
            if($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param('ssssssi', $name, $surname, $country, $state, $city, 
                    $profile_picture, $user_id);
                $stmt->execute();
                $stmt->close();
            }
            $mysqli->close();
        }

        function delete_user(int $user_id) {
            $deleted = $this->get_user_deleted($user_id);
            $this->conn_db = new Connection();
            $mysqli = $this->conn_db->connect();
            if($stmt = $mysqli->prepare('update user delete=? where user_id=?')) {
                $deleted = $user->get_deleted() == 1 ? 0 : 1; 
                $stmt->bind_param('ii', $deleted, $user->get_user_id());
                $stmt->execute();
                $stmt->close();
            }
            $mysqli->close();
        }

        function get_user_login(string $user_name, string $password): User {
            $this->conn_db = new Connection();
            $mysqli = $this->conn_db->connect();
            $fields = 'user_id, name, surname, country, state, city, profile_picture, is_mod, deleted';
            $user;
            if($stmt = $mysqli->prepare('select '.$fields.' from user where user_name=? and password=MD5(?)')) {
                $stmt->bind_param('ss', $user_name, $password);
                $stmt->execute();
                $stmt->bind_result($user_id, $name, $surname, $country, $state, $city, $profile_picture, $is_mod, $deleted);
                if($stmt->fetch()){
                    $user = new User();
                    $user->set_user_id($user_id);
                    $user->set_user_name($user_name);
                    $user->set_profile_picture($profile_picture);
                    $user->set_is_mod($is_mod);
                    $user->set_deleted($deleted);
                    if(isset($name) && !empty($name))
                        $user->set_name($name);
                    if(isset($surname) && !empty($surname))
                        $user->set_surname($surname);
                    if(isset($country) && !empty($country))
                        $user->set_country($country);
                    if(isset($state) && !empty($state))
                        $user->set_state($state);
                    if(isset($city) && !empty($city))
                        $user->set_city($city);
                }
                 $stmt->close();
            }
            $mysqli->close();
            return $user;
        }

        function get_user(int $user_id): User {
            $this->conn_db = new Connection();
            $mysqli = $this->conn_db->connect();
            $sql = 'select user_name, name, surname, country, state, city, profile_picture, is_mod, deleted 
                    from user where user_id=?';
            $user = new User();
            if($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $stmt->bind_result($user_name, $name, $surname, $country, $state, $city, $profile_picture, $is_mod, 
                    $deleted);
                if($stmt->fetch()){
                    $user->set_user_id($user_id);
                    $user->set_user_name($user_name);
                    $user->set_profile_picture($profile_picture);
                    $user->set_is_mod($is_mod);
                    $user->set_deleted($deleted);
                    if(isset($name) && !empty($name))
                        $user->set_name($name);
                    if(isset($surname) && !empty($surname))
                        $user->set_surname($surname);
                    if(isset($country) && !empty($country))
                        $user->set_country($country);
                    if(isset($state) && !empty($state))
                        $user->set_state($state);
                    if(isset($city) && !empty($city))
                        $user->set_city($city);
                }
                 $stmt->close();
            }
            $mysqli->close();
            return $user;
        }

        private function get_user_deleted(int $user_id): int {
            $this->conn_db = new Connection();
            $mysqli = $this->conn_db->connect();
            $deleted;
            if($stmt = $mysqli->prepare('select deleted from user where user_id=?')) {
                $stmt->bind_param('i', $user_name);
                $stmt->execute();
                $stmt->bind_result($del);
                if($stmt->fetch()) {
                    $deleted = $del;
                }
                $stmt->close();
            }
            $mysqli->close();
            return $deleted;
         }

         function user_exists(string $user_name): bool {
             $this->conn_db = new Connection();
             $mysqli = $this->conn_db->connect();
             $exist;
             if($stmt = $mysqli->prepare('select user_name from user where user_name=?')) {
                $stmt->bind_param('s', $user_name);
                $stmt->execute();
                $stmt->bind_result($user_name);
                if($stmt->fetch())
                    $exist = true;
                else
                    $exist = false;
                $stmt->close();
            }
            $mysqli->close();
            return $exist;
         }

         function email_exists(string $email): bool {
             $this->conn_db = new Connection();
             $mysqli = $this->conn_db->connect();
             $exist;
             if($stmt = $mysqli->prepare('select email from user where email=?')) {
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $stmt->bind_result($email_sql);
                if($stmt->fetch())
                    $exist = true;
                else
                    $exist = false;
                $stmt->close();
            }
            $mysqli->close();
            return $exist;
         }

         function check_passowrd(string $user_name, string $password): bool {
             $this->conn_db = new Connection();
             $mysqli = $this->conn_db->connect();
             $password = md5($password);
             $exist;
             if($stmt = $mysqli->prepare('select password from user where user_name=?')) {
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $stmt->bind_result($password_sql);
                if($stmt->fetch())
                    if(strcmp($password, $password_sql) == 0)
                        $exist = true;
                    else
                        $exist = false;
                else
                    $exist = false;
                $stmt->close();
            }
            $mysqli->close();
            return $exist;
         }
    } 
?>