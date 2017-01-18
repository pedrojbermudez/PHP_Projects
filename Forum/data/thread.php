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
        
        function __construct(int $thread_id, string $thread_name, int $forum_id,
            string $forum_name, int $user_id, string $user_name) {
            $this->thread_id = $thread_id;
            $this->thread_name = $thread_name;
            $this->forum_id = $forum_id;
            $this->forum_name = $forum_name;
            $this->user_id = $user_id;
            $this->user_name = $user_name;
        }

        function get_thread_id(): int { return $this->thread_id; }
        function get_thread_name(): string { return $this->thread_name; }
        function get_forum_id(): int { return $this->forum_id; }
        function get_forum_name(): string { return $this->forum_name; }
        function get_user_id(): int { return $this->user_id; }
        function get_user_name(): string { return $this->user_name; }
    }
?>