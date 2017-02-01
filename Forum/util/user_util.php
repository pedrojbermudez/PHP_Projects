<?php
    declare(strict_types=1);

    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/user.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/db/user_db.php');

    /**
     * Class to manage Thread object given an object directly or just a 
     * html code
     */
    class UserUtil
    {
        private $user_db;

        function __construct() {
            $this->user_db = new UserDB();
        }

        function new_user(string $json) {
            $this->user_db->new_user($json);
        }

        function edit_user(int $user_id, string $name, string $surname, string $country, string $state, 
            string $city, string $profile_picture = 'images/default.jpg') {
            $this->user_db->edit_user($user_id, $name, $surname, $country, $state, 
                $city, $profile_picture);
        }

        function delete_user(int $user_id) { $this->user_db->delete_user($user_id); }
        
        function get_user(int $user_id) {
            $user = $this->user_db->get_user($user_id);
            return json_encode($user); 
        }

        // Check if user name exists in database
        function user_exists(string $user_name): bool { return $this->user_db->user_exists($user_name); }
        
        // Check if email exists in database
        function email_exists(string $email): bool { return $this->user_db->email_exists($email); }
        
        // Return an User object with data filled.
        function get_user_login(string $user_name, string $password): User { 
            return $this->user_db->get_user_login($user_name, $password); 
        }

        // Check the password given in the database
        function check_passowrd(string $user_name, string $password): bool { 
            return $this->user_db->check_passowrd($user_name, $password);
        }
    } 
?>