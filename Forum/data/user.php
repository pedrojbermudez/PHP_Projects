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
        
        function __construct() { }
        
        function set_user_id(int $user_id) { $this->user_id = $user_id; }
        function get_user_id(): int { 
            return isset($this->user_id) ? $this->user_id : -1;
        }
        
        function set_user_name(string $user_name) { $this->user_name = $user_name; }
        function get_user_name(): string { 
            return isset($this->user_name) && !empty($this->user_name) ? $this->user_name : ''; 
        }

        function set_name(string $name) { $this->name = $name; }
        function get_name(): string { 
            return isset($this->name) && !empty($this->name) ? $this->name : ''; 
        }
        
        function set_surname(string $surname) { $this->surname = $surname; }
        function get_surname(): string { 
            return isset($this->surname) && !empty($this->surname) ? $this->surname : ''; 
        }
        
        function set_country(string $country) { $this->country = $country; }
        function get_country(): string { 
            return isset($this->country) && !empty($this->country) ? $this->country : ''; 
        }
        
        function set_state(string $state) { $this->state = $state; }
        function get_state(): string { 
            return isset($this->state) && !empty($this->state) ? $this->state : ''; 
        }
          
        function set_city(string $city) { $this->city = $city; }
        function get_city(): string { 
            return isset($this->city) && !empty($this->city) ? $this->city : ''; 
        }
        
        function set_profile_picture(string $profile_picture) { $this->profile_picture = $profile_picture; }
        function get_profile_picture(): string { 
            return isset($this->profile_picture) && !empty($this->profile_picture) ? $this->profile_picture : ''; 
        }
        
        function set_is_mod(int $is_mod) { $this->is_mod = $is_mod; }
        function get_is_mod(): int {
            return isset($this->is_mod) ? $this->is_mod : 0;
        }
        
        function set_deleted(int $deleted) { $this->deleted = $deleted; }
        function get_deleted(): int { 
            return isset($this->deleted) ? $this->deleted : 0;
        }
    }
?>