<?php
    declare(strict_types=1);
    
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/category.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/forum.php');
    include_once('db_connect.php');
    include_once('forum_db.php');

    /**
     * 
     */
    class CategoryDB {
        private $connection; 

        function __constructor() { }

        // Create a new category from the database
        function new_category(string $name, string $description):int {
            $category_id;
            $this->connection = new Connection();
            $mysqli = $this->connection->connect();
            if($stmt=$mysqli->prepare('insert into forum (name, description, category_id) values (?,?,?)')) {
                $category_id = -1;
                $stmt->bind_param('ssi', $name, $description, $category_id);
                $stmt->execute();
                $category_id = $stmt->insert_id;
                $stmt->close();
            }
            $mysqli->close();
            return $category_id;
        }

        // Edit an existing category from the database
        function edit_category(int $category_id, string $name, string $description) {
            $this->connection = new Connection();
            $mysqli = $this->connection->connect();
            if($stmt = $mysqli->prepare('update forum set name=?, description=? where forum_id=?')) {
                $stmt->bind_param('ssi', $name, $description, $category_id);
                $stmt->execute();
                $stmt->close();
            }
            $mysqli->close();
        }

        // Delete a category from the database
        function delete_category(int $category_id) {
            $this->connection = new Connection();
            $mysqli = $this->connection->connect();
            if($stmt = $mysqli->prepare('delete from forum where forum_id=?')) {
                $stmt->bind_param('i', $category_id);
                $stmt->execute();
                $stmt->close();
            }
            $mysqli->close();
            $forum_db = new ForumDB();
            $forum_db->delete_forums($category_id);
        }

        // Get a single category
        function get_category(int $category_id): Category {
            $this->connection = new Connection();
            $mysqli = $this->connection->connect();
            $category;
            if($stmt = $mysqli->prepare('select forum_id, name, description from forum where forum_id=?')) {
                $stmt->bind_param('i', $category_id);
                $stmt->execute();
                $stmt->bind_result($category_id, $category_name, $category_description);
                $forum_db = new ForumDB();
                if($stmt->fetch()) {
                    $forums = $forum_db->get_forums($category_id);
                    $category = new Category();
                    $category->set_forum_id($category_id);
                    $category->set_name($category_name);
                    if(isset($category_description) && !empty($category_description))
                        $category->set_description($category_description);
                    if(sizeof($forums) > 0)
                        $category->set_forums($forums);
                }
            }
            $stmt->close();
            $mysqli->close();
            return $category;
        }

        // Get all categories from database
        function get_categories(): array {
            $this->connection = new Connection();
            $mysqli = $this->connection->connect();
            $categories = array();
            if($stmt = $mysqli->prepare('select forum_id, name, description from forum where category_id=?')) {
                $tmp_category_id = -1;
                $stmt->bind_param('i', $tmp_category_id);
                $stmt->execute();
                $stmt->bind_result($category_id, $category_name, $category_description);
                $forum_db = new ForumDB();
                while($stmt->fetch()) {
                    $forums = $forum_db->get_forums($category_id);
                    $category = new Category();
                    $category->set_forum_id($category_id);
                    $category->set_name($category_name);
                    if(isset($category_description) && !empty($category_description))
                        $category->set_description($category_description);
                    if(sizeof($forums) > 0)
                        $category->set_forums($forums);
                    $categories[] = $category;
                }
            }
            $stmt->close();
            $mysqli->close();
            return $categories;
        }
    }
?>