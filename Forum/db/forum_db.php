<?php
    declare(strict_types=1);

    include_once('db_connect.php');
    include_once('thread_db.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/category.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/forum.php');

    /**
     * Class to manage forums on the database
     */
    class ForumDB {
        private $conn;

        function __construct(){}

        function new_forum(string $name, string $description, int $category_id): int {
            $this->conn = new Connection();
            $mysqli = $this->conn->connect();
            $forum_id;
            if($stmt = $mysqli->prepare('insert into forum (name, description, category_id) values (?,?,?)')) {
                $stmt->bind_param('ssi', $name, $description, $category_id);
                $stmt->execute();
                $forum_id = $stmt->insert_id;
                $stmt->close();
            }
            $mysqli->close();
            return $forum_id;
        }

        function edit_forum(int $forum_id, string $name, string $description, int $category_id) {
            $this->conn = new Connection();
            $mysqli = $this->conn->connect();
            if($stmt = $mysqli->prepare('update forum set name=?, description=?, category_id=? where forum_id=?')) {
                $stmt->bind_param('ssii', $name, $description, $category_id, $forum_id);
                $stmt->execute();
                $stmt->close();
            }
            $mysqli->close();
        }

        function delete_forum(int $forum_id) {
            $this->conn = new Connection();
            $mysqli = $this->conn->connect();
            if($stmt = $mysqli->prepare('delete from forum where forum_id=?')) {
                $stmt->bind_param('i', $forum_id);
                $stmt->execute();
                $stmt->close();
            }
            $mysqli->close();
            $thread_db = new ThreadDB();
            $thread_db->delete_threads($forum_id);
        }

        function delete_forums(int $category_id) {
            $forums = $this->get_forums($category_id);
            foreach($forums as $forum) {
                $this->delete_forum($forum->get_forum_id());
            }
        }

        function get_forum(int $forum_id): Forum {
            $this->conn = new Connection();
            $mysqli = $this->conn->connect();
            $forum;
            if($stmt = $mysqli->prepare('select category_id, name, description from forum where forum_id=?')) {
                $stmt->bind_param('i', $forum_id);
                $stmt->execute();
                $stmt->bind_result($category_id, $name, $description);
                if($stmt->fetch()) {
                    $forum = new Forum($forum_id, $name, $description, $category_id);
                }
                $stmt->close();
            }
            $mysqli->close();
            return $forum;
        }

        function get_forums(int $category_id): array {
            $this->conn = new Connection();
            $mysqli = $this->conn->connect();
            $forums = array();
            if($stmt = $mysqli->prepare('select forum_id, name, description from forum where category_id=?')) {
                $stmt->bind_param('i', $category_id);
                $stmt->execute();
                $stmt->bind_result($forum_id, $name, $description);
                while($stmt->fetch()) {
                    $forums[] = new Forum($forum_id, $name, $description, $category_id);
                }
                $stmt->close();
            }
            $mysqli->close();
            return $forums;
        }
    }
?>