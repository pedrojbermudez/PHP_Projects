<?php
    declare(strict_types=1);
    
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/forum.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/thread.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/db/forum_db.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/util/thread_util.php');
    

    /**
     * Class to manage Forum object given an object directly or just a 
     * html code
     */
    class ForumUtil
    {
        private $forum_db;
        private $thread_util;

        function __construct() {
            $this->forum_db = new ForumDB();
            $this->thread_util = new ThreadUtil();
        }

        function new_forum(string $name, string $description, int $category_id): int {
            return $this->forum_db->new_forum($name, $description, $category_id); 
        }
        function edit_forum(int $forum_id, string $name, string $description, int $category_id) { 
            $this->forum_db->edit_forum($forum_id, $name, $description, $category_id); 
        }

        function delete_forum(int $forum_id) { $this->forum_db->delete_forum($forum_id); }
        function get_forum(int $forum_id): Forum { return $this->forum_db->get_forum($forum_id); }
    }    
?>