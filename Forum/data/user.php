<?php
    declare(strict_types=1);
    /**
     * Class to manage a user
     */
    class User {
        private $user_id;
        private $user_name;
        private $name;
        private $surname;
        private $country;
        private $state;
        private $city;
        private $profile_picture;
        private $is_mod;
        private $deleted;
        
        function __construct() {
            $this->user_id = -1;
            $this->user_name = '';
            $this->name = '';
            $this->surname = '';
            $this->country = '';
            $this->state = '';
            $this->city = '';
            $this->profile_picture = '';
            $this->is_mod = 0;
            $this->deleted = 0;
         }

        function set_user_id(int $user_id) { $this->user_id = $user_id; }
        function get_user_id(): int { return $this->user_id; }
        
        function set_user_name(string $user_name) { $this->user_name = $user_name; }
        function get_user_name(): string { return $this->user_name; }

        function set_name(string $name) { $this->name = $name; }
        function get_name(): string { return $this->name ; }
        
        function set_surname(string $surname) { $this->surname = $surname; }
        function get_surname(): string { return $this->surname;  }
        
        function set_country(string $country) { $this->country = $country; }
        function get_country(): string { return $this->country;}
        
        function set_state(string $state) { $this->state = $state; }
        function get_state(): string { return $this->state; }
          
        function set_city(string $city) { $this->city = $city; }
        function get_city(): string { return $this->city; }
        
        function set_profile_picture(string $profile_picture) { $this->profile_picture = $profile_picture; }
        function get_profile_picture(): string { return $this->profile_picture; }

        function set_is_mod(int $is_mod) { $this->is_mod = $is_mod; }
        function get_is_mod(): int { return $this->is_mod; }
            
        function set_deleted(int $deleted) { $this->deleted = $deleted; }
        function get_deleted(): int { return $this->deleted;}
    }
?>