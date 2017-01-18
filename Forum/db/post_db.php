<?php
    declare(strict_types=1);

    include_once('db_connect.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/post.php');
    
    /**
     * Class to manage posts on the database
     */
    class PostDB {
        private $conn;

        function __construct(){}

        function new_post(string $post, int $thread_id, int $user_id) {
            $this->conn = new Connection();
            $mysqli = $this->conn->connect();
            $post_id;
            if($stmt = $mysqli->prepare('insert into post (post, thread_id, user_id, creation_date) 
                values (?,?,?, NOW())')) {
                $stmt->bind_param('sii', $post, $thread_id, $user_id);
                $stmt->execute();
                $post_id = $stmt->insert_id;
                $stmt->close();
            }
            $mysqli->close();
            return $post_id;
        }

        function edit_post(int $post_id, string $thread_id, string $post) {
            $this->conn = new Connection();
            $mysqli = $this->conn->connect();
            if($stmt = $mysqli->prepare('update post set modification_date=NOW(), 
                post=?, thread_id=? where post_id=?')) {
                $stmt->bind_param('sii', $post, $thread_id, $post_id);
                $stmt->execute();
                $stmt->close();
            }
            $mysqli->close();
        }

        function delete_post(int $post_id) {
            $this->conn = new Connection();
            $mysqli = $this->conn->connect();
            if($stmt = $mysqli->prepare('delete from post where post_id=?')) {
                $stmt->bind_param('i', $post_id);
                $stmt->execute();
                $stmt->close();
            }
            $mysqli->close();
        }

        function delete_posts(int $thread_id) {
            $this->conn = new Connection();
            $mysqli = $this->conn->connect();
            if($stmt = $mysqli->prepare('delete from post where thread_id=?')) {
                $stmt->bind_param('i', $thread_id);
                $stmt->execute();
                $stmt->close();
            }
            $mysqli->close();
        }

        function get_post(int $post_id): Post {
            $this->conn = new Connection();
            $mysqli = $this->conn->connect();
            $post_object;
            if($stmt = $mysqli->prepare('select post.thread_id,
                post.post as post, post.user_id as user_id, user.user_name, 
                thread.name as thread_name, post.creation_date, post.modification_date, 
                user.profile_picture from thread, user, post where 
                post.post_id=? and post.user_id=user.user_id and 
                post.thread_id=thread.thread_id')) {
                $stmt->bind_param('i', $post_id);
                $stmt->execute();
                $stmt->bind_result($thread_id, $post, $user_id, $user_name, $thread_name, 
                    $creation_date, $modification_date, $profile_picture);
                if($stmt->fetch()) {
                    $modification_date = isset($modification_date) ? $modification_date : '';
                    $post_object = new Post($post_id, $user_id, $thread_id, $post,
                        $creation_date, $modification_date, $profile_picture);
                    $post_object->set_user_name($user_name);
                    $post_object->set_thread_name($thread_name);
                }
                $stmt->close();
            }
            $mysqli->close();
            return $post_object;
        }

        function get_posts(int $thread_id): array {
            $this->conn = new Connection();
            $mysqli = $this->conn->connect();
            $posts = array();
            if($stmt = $mysqli->prepare('select post.post_id as post_id,
                post.post as post, post.user_id as user_id, user.user_name, 
                thread.name as thread_name, post.creation_date, post.modification_date, 
                user.profile_picture from thread, user, post where 
                post.thread_id=? and post.user_id=user.user_id and 
                post.thread_id=thread.thread_id')) {
                $stmt->bind_param('i', $thread_id);
                $stmt->execute();
                $stmt->bind_result($post_id, $post, $user_id, $user_name, $thread_name, $creation_date, 
                    $modification_date, $profile_picture);
                while($stmt->fetch()) {
                    $modification_date = isset($modification_date) ? $modification_date : '';
                    $post_object = new Post($post_id, $user_id, $thread_id, $post,
                        $creation_date, $modification_date, $profile_picture);
                    $post_object->set_user_name($user_name);
                    $post_object->set_thread_name($thread_name);
                    $posts[] = $post_object;
                }
                $stmt->close();
            }
            $mysqli->close();
            return $posts;
        }
    }
?>