<?php
        declare(strict_types=1);
    /**
     * Class to manage a thread
     */
    class Thread {
        private $thread_id;
        private $thread_name;
        private $forum_id;
        private $forum_name;
        private $user_id;
        private $user_name;
        
        function __construct() {
            $this->thread_id = -1;
            $this->thread_name = '';
            $this->forum_id = -1;
            $this->forum_name = '';
            $this->user_id = -1;
            $this->user_name = '';
         }

        function set_thread_id(int $thread_id) { $this->thread_id = $thread_id; }
        function get_thread_id(): int { return $this->thread_id; }
        
        function set_thread_name(string $thread_name) { $this->thread_name = $thread_name; }
        function get_thread_name(): string { return $this->thread_name; }
        
        function set_forum_id(int $forum_id) { $this->forum_id = $forum_id; }
        function get_forum_id(): int { return $this->forum_id; }
        
        function set_forum_name(string $forum_name) { $this->forum_name = $forum_name; }
        function get_forum_name(): string { return $this->forum_name; }

        function set_user_id(int $user_id) { $this->user_id = $user_id; }
        function get_user_id(): int { return $this->user_id; }
        
        function set_user_name(string $user_name) { $this->user_name = $user_name; }
        function get_user_name(): string { return $this->user_name; }
    }
?>