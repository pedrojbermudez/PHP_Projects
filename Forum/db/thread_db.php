<?php
    declare(strict_types=1);

    include_once('db_connect.php');
    include_once('post_db.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/thread.php');
    
    /**
     * Class to manage threads on the database
     */
    class ThreadDB {
        private $conn;

        function __construct(){}

        function new_thread(string $name, int $forum_id, int $user_id) {
            $this->conn = new Connection();
            $mysqli = $this->conn->connect();
            $thread_id;
            if($stmt = $mysqli->prepare('insert into thread (name, forum_id, user_id) values (?,?,?)')) {
                $stmt->bind_param('sii', $name, $forum_id, $user_id);
                $stmt->execute();
                $thread_id = $stmt->insert_id;
                $stmt->close();
            }
            $mysqli->close();
            return $thread_id;
        }

        function edit_thread(int $thread_id, string $name, string $forum_id) {
            $this->conn = new Connection();
            $mysqli = $this->conn->connect();
            if($stmt = $mysqli->prepare('update thread set name=?, forum_id=? where thread_id=?')) {
                $stmt->bind_param('sii', $name, $forum_id, $thread_id);
                $stmt->execute();
                $stmt->close();
            }
            $mysqli->close();
        }

        function delete_thread(int $thread_id) {
            $this->conn = new Connection();
            $mysqli = $this->conn->connect();
            if($stmt = $mysqli->prepare('delete from thread where thread_id=?')) {
                $stmt->bind_param('i', $thread_id);
                $stmt->execute();
                $stmt->close();
            }
            $mysqli->close();
            $post_db = new PostDB();
            $post_db->delete_posts($thread_id);
        }

        function delete_threads(int $forum_id) {
            $threads = $this->get_threads_id($forum_id);
            foreach ($threads as $thread) {
                $this->delete_thread($thread);
            }
        }

        function get_thread(int $thread_id): Thread {
            $this->conn = new Connection();
            $mysqli = $this->conn->connect();
            $thread;
            if($stmt = $mysqli->prepare('select thread.thread_id as thread_id, 
                thread.name thread_name, thread.user_id as user_id, 
                user.user_name as user_name, thread.forum_id as forum_id, 
                forum.name as forum_name from thread, user, forum where 
                thread.thread_id=? and thread.user_id=user.user_id and 
                thread.forum_id=forum.forum_id')) {
                $stmt->bind_param('i', $thread_id);
                $stmt->execute();
                $stmt->bind_result($thread_id, $thread_name, $user_id, $user_name, $forum_id, $forum_name);
                if($stmt->fetch()) {
                    $thread = new Thread($thread_id, $thread_name, $forum_id,
                        $forum_name, $user_id, $user_name);
                }
                $stmt->close();
            }
            $mysqli->close();
            return $thread;
        }

        private function get_threads_id(int $forum_id): array {
            $this->conn = new Connection();
            $mysqli = $this->conn->connect();
            $threads = array();
            if($stmt = $mysqli->prepare('select thread_id from thread where forum_id=?')) {
                $stmt->bind_param('i', $forum_id);
                $stmt->execute();
                $stmt->bind_result($thread_id);
                while($stmt->fetch()) {
                    $threads[] = $thread_id;
                }
                $stmt->close();
            }
            $mysqli->close();
            return $threads;
        }

        function get_threads(int $forum_id): array {
            $this->conn = new Connection();
            $mysqli = $this->conn->connect();
            $threads = array();
            echo 'forum id get threads '.$forum_id.'<br />';
            if($stmt = $mysqli->prepare('select thread.thread_id as thread_id, 
                thread.name thread_name, thread.user_id as user_id, 
                user.user_name as user_name, thread.forum_id as forum_id, 
                forum.name as forum_name from thread, user, forum where 
                thread.forum_id=? and thread.user_id=user.user_id and 
                thread.forum_id=forum.forum_id')) {
                $stmt->bind_param('i', $forum_id);
                $stmt->execute();
                $stmt->bind_result($thread_id, $thread_name, $user_id, $user_name, $forum_id, $forum_name);
                while($stmt->fetch()) {
                    $threads[] = new Thread($thread_id, $thread_name, $forum_id,
                        $forum_name, $user_id, $user_name);
                }
                $stmt->close();
            }
            $mysqli->close();
            return $threads;
        }
    }
?>