<?php
    declare(strict_types=1);
    
    require_once('forum.php');

    /*
        Class to manage a category.
    */
    class Category extends Forum {
        private $forums;
        
        function __construct() {
            Forum::__construct();
            $this->forums = array();
        }

        function set_forums(array $forums) { $this->forums = $forums; }
        function get_forums(): array { return $this->forums; }
    }
?>