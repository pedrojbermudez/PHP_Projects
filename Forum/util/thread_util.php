<?php
    declare(strict_types=1);
    
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/post.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/thread.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/db/thread_db.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/util/post_util.php');

    /**
     * Class to manage Thread object given an object directly or just a 
     * html code
     */
    class ThreadUtil
    {
        private $post_util;
        private $thread_db;

        function __construct() {
            $this->post_util = new PostUtil();
            $this->thread_db = new ThreadDB();
        }

        function new_thread(string $name, string $post, int $forum_id, 
                    int $user_id): int {
            $thread_id = $this->thread_db->new_thread($name, $forum_id, 
                        $user_id);
            $this->post_util->new_post($post, $thread_id, $user_id);
            return $thread_id;
        }    

        function edit_thread(int $thread_id, string $name, string $forum_id) {
            $this->thread_db->edit_thread($thread_id, $name, $forum_id);
        }

        function delete_thread(int $thread_id){ 
            $this->thread_db->delete_thread($thread_id); 
        }
        
        function get_thread(int $thread_id): Thread { 
            return $this->thread_db->get_thread($thread_id); 
        }

        function get_threads(int $forum_id): array { 
            return $this->thread_db->get_threads($forum_id); 
        }

        function get_30_threads_html(): array {
            return $this->thread_db->get_30_threads();
        }
    }    
?>