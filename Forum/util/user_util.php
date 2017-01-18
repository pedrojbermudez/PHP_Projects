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

        function new_user(string $user_name, string $password, string $email, 
            string $name, string $surname, string $country, string $state, 
            string $city, string $profile_picture = 'images/default.jpg', 
            int $is_mod = 0, int $deleted = 0) {
            $this->user_db->new_user($user_name, $password, $email, 
                $name, $surname, $country, $state, $city, $profile_picture, 
                $is_mod, $deleted);
        }

        function edit_user(int $user_id, string $name, string $surname, string $country, string $state, 
            string $city, string $profile_picture = 'images/default.jpg') {
            $this->user_db->edit_user($user_id, $name, $surname, $country, $state, 
                $city, $profile_picture);
        }

        function delete_user(int $user_id) { $this->user_db->delete_user($user_id); }
        function get_user(int $user_id): User {
             $user = $this->user_db->get_user($user_id);
             return $user; 
        }
        function user_exists(string $user_name): bool { return $this->user_db->user_exists($user_name); }
        function email_exists(string $email): bool { return $this->user_db->user_exists($email); }
    } 
?>