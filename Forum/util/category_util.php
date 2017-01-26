<?php
    declare(strict_types=1);

    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/category.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/db/category_db.php');

    /**
     * Class to manage Category object given an object directly or just a 
     * html code
     */
    class CategoryUtil
    {
        private $category_db;

        function __construct() {
            $this->category_db = new CategoryDB();
        }

        function new_category(string $name, string $description):int { 
            return $this->category_db->new_category($name, $description);
        }

        function edit_category(int $category_id, string $name, string $description) {
            $this->category_db->edit_category($category_id, $name, $description);
        }

        function delete_category(int $category_id) { $this->category_db->delete_category($category_id); }
        function get_category(int $category_id): Category { return $this->category_db->get_category($category_id); }
        function get_categories(): array { return $this->category_db->get_categories(); }

        function get_category_html(int $category_id): string {
            $category = $this->category_db->get_category($category_id);
            $html;
            if(isset($category)) {
                $tmp = '<hr />';
                $forums = $category->get_forums();
                if(isset($forums)) {
                    // Category has some forum
                    $max = sizeof($forums);
                    for ($i=0; $i < $max; $i++) { 
                        $tmp .= '<div class="div_forum_list"><p><a href="forum.php?fid='
                            .$forums[$i]->get_forum_id().'">'
                            .$forums[$i]->get_name().'</a></p><q>'
                            .$forums[$i]->get_description().'</q>'
                            .$this->edit_forum_html($forums[$i]->get_forum_id())
                            .$this->delete_forum_form($forums[$i]->get_forum_id())
                            .'</div><hr />';
                    }
                } else {
                    // No forum in that category
                    $tmp .= '<p>There is not any forum in this category.</p>';
                }
                // Creating html block
                $html = '
                    <div id="div_category">
                        <h1>'.$category->get_name().'</h1>
                        <q>'.$category->get_description().'</q>
                        '.$this->edit_category_html($category->get_forum_id()).'
                        '.$this->delete_category_form($category->get_forum_id()).'
                        '.$tmp.'
                    </div>';
            } else {
                // Wrong category id
                $html = '<p>Wrong category</p>';
            }
            return $html;
        }

        // Method to create a menu
        function get_categories_menu(): string {
            $categories = $this->category_db->get_categories();
            $html = '';  
            $max = sizeof($categories);
            for($i=0;$i < $max;$i++) {
                $html .= '<div class="col-md-2">
                    <a href="category.php?cid='.$categories[$i]->get_forum_id().'">
                    '.$categories[$i]->get_name().'</a></div>';
            }
            return $html;
        }

        // Method to create a list of categories and its forums
        // you can edit it.
        function get_categories_list(): string {
            $categories = $this->category_db->get_categories();
            $html = '<ul id="ul_category_list">';  
            $max = sizeof($categories);
            for($i=0;$i < $max;$i++) {
                $html .= '<li class="li_menu_content">
                    <a href="category.php?cid='.$categories[$i]->get_forum_id().'">
                    '.$categories[$i]->get_name().'</a> | 
                    <a href="ne_category.php?cid='.$categories[$i]->get_forum_id().'">
                    Edit</a><ul class="ul_forum_list">';
                $forums = $categories[$i]->get_forums();
                $max_forum = sizeof($forums);
                for ($j=0; $j < $max_forum; $j++) { 
                    $html .= '<li class="li_forum_content">
                        <a href="forum.php?fid='.$forums[$j]->get_forum_id().'">
                        '.$forums[$j]->get_name().'</a> | 
                    <a href="ne_category.php?cid='.$forums[$j]->get_forum_id().'">
                    Edit</a>'.$this->delete_forum_form($forums[$i]->get_forum_id()).'</li>';
                }
                $html .= '</ul></li>';
            }
            $html .= '</ul>';
            return $html;
        }

        private function edit_category_html(int $category_id): string {
            return isset($_SESSION['user']) && $_SESSION['user']->get_user_id() == 1 ? 
                    '<p><a href="ne_category.php?cid='.$category_id.'">Edit category</a></p>' : '';
        }

        private function edit_forum_html(int $forum_id): string {
            return isset($_SESSION['user']) && $_SESSION['user']->get_user_id() == 1 ? 
                    '<p><a href="ne_forum.php?fid='.$forum_id.'">Edit Forum</a></p>' : '';
        }

        private function delete_forum_form(int $forum_id): string {
            return isset($_SESSION['user']) && $_SESSION['user']->get_user_id() == 1 ? 
                    '<form action="php/process_forum.php" method="POST">
                        <input type="hidden" name="forum_id" value="'.$forum_id.'" />
                        <input type="hidden" name="delete" value="true">
                        <input type="submit" value="Delete Forum">
                    </form>' : '';
        }

        private function delete_category_form(int $category_id): string {
            return isset($_SESSION['user']) && $_SESSION['user']->get_user_id() == 1 ?
                    '<form action="php/process_category.php" method="POST">
                        <input type="hidden" name="category_id" value="'.$category_id.'" />
                        <input type="hidden" name="delete" value="true">
                        <input type="submit" value="Delete Category">
                    </form>' : '';
        }
    }
?>