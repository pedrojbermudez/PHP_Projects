<?php
    declare(strict_types=1);
    
    /**
     * Class to manage a forum
     */
    class Forum
    {
        // Declaring variables
        private $name;
        private $description;
        private $forum_id;
        private $category_id;

        function __construct() {
            $this->name = '';
            $this->description = '';
            $this->forum_id = -1;
            $this->category_id = -1; 
        }

        function set_name(string $name) { $this->name = $name; }
        function get_name(): string { return $this->name; }

        function set_description(string $description) { $this->description = $description; }
        function get_description(): string { return $this->description; }

        function set_forum_id(int $forum_id) { $this->forum_id = $forum_id; }
        function get_forum_id(): int { return $this->forum_id; }

        function set_category_id(int $category_id) { $this->category_id = $category_id; }
        function get_category_id(): int { return $this->category_id; }
    }
?>