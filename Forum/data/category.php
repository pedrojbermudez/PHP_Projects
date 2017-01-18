<?php
    declare(strict_types=1);
    
    require_once('forum.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/Forum/db/category_db.php');

    /*
        Class to manage a category.
    */
    class Category extends Forum {
        private $forums;
        private $category_db;
        
        function __construct(int $category_id, string $name, string $description, array $forums) {
            Forum::__construct($category_id, $name, $description , -1);
            $this->category_db = new CategoryDB();
            $this->forums = $forums;
        }

        function set_forums(array $forums) {
            $this->forums = $forums;
        }

        function get_forums(): array {
            return $this->forums;
        }
    }
?>