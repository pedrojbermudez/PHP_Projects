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

        function __construct(int $forum_id, string $name, string $description, int $category_id)
        {
            $this->name = $name;
            $this->description = $description;
            $this->forum_id = $forum_id;
            $this->category_id = $category_id;
        }

        function get_name(): string{
            return $this->name;
        }

        function get_description(): string{
            return $this->description;
        }

        function get_forum_id(): int{
            return $this->forum_id;
        }

        function get_category_id(): int{
            return $this->category_id;
        }
    }
?>