<?php
    declare(strict_types=1);
    /**
     * Class to manage a post
     */
    class Post {
        private $post_id;
        private $user_id;
        private $thread_id;
        private $post;
        private $thread_name;
        private $creation_date;
        private $modification_date;
        private $user_name;
        private $profile_picture;

        function __construct(int $post_id, int $user_id, int $thread_id, 
            string $post, string $creation_date, 
            string $modification_date, string $profile_picture) {
            $this->post_id = $post_id;
            $this->user_id = $user_id;
            $this->thread_id = $thread_id;
            $this->post = $post;
            $this->creation_date = $creation_date;
            $this->modification_date = $modification_date;
            $this->profile_picture = $profile_picture;
        }

        function get_post_id():int { return $this->post_id; }
        function get_user_id():int { return $this->user_id; }
        function get_thread_id():int { return $this->thread_id; }
        function get_post():string { return $this->post; }
        function set_thread_name(string $thread_name) { $this->thread_name = $thread_name; }
        function get_thread_name():string { return $this->thread_name; }
        function get_creation_date():string { return $this->creation_date; }
        function get_modification_date():string { return $this->modification_date; }
        function set_user_name(string $user_name) { $this->user_name = $user_name; }
        function get_user_name():string { return $this->user_name; }
        function get_profile_picture():string { return $this->profile_picture; }
    }
    
?>