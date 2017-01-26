<?php
    declare(strict_types=1);
    
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/post.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/db/post_db.php');

    /**
     * Class to manage Thread object given an object directly or just a 
     * html code
     */
    class PostUtil
    {
        private $post_db;

        function __construct() {
            $this->post_db = new PostDB();
        }

        function new_post(string $post, int $thread_id, int $user_id): int {
            return $this->post_db->new_post($post, $thread_id, $user_id);
        }

        function edit_post(int $post_id, string $thread_id, string $post) {
            $this->post_db->edit_post($post_id, $thread_id, $post);
        }

        function delete_post(int $post_id) { $this->post_db->delete_post($post_id); }
        function get_post(int $post_id): Post { return $this->post_db->get_post($post_id); }
        function get_posts(int $thread_id): array { return $this->post_db->get_posts($thread_id); }
        function post_exists(int $post_id): bool { return $this->post_db->post_exists($post_id); }
    }    
?>