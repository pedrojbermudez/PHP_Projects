<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/util/category_util.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/user.php');

    session_start();

    function check_user_id() : bool { 
        if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
            display_window_alert_href('Incorrect action', '../index.php');
            return false;
        }
        return true;
     }

     function get_name(): string {
        if(!isset($_POST['name']) || empty($_POST['name']))
            display_window_alert_back('The name can\'t be empty. Please introduce a name.');
        return $_POST['name'];
     }

    function edit_category(int $category_id, string $name, string $description, CategoryUtil $category_util) {
        $category = $category_util->get_category($category_id);
        if(isset($category) && $category->get_forum_id() > -1) {
            // The category exists and the category id is greater than -1
            $category_util->edit_category($category_id, $name, $description);
            display_window_alert_href('Your category was edited.', '../category.php?cid='.$category_id);
        } else {
            display_window_alert_back('Incorrect category.');
        }
    }

    function delete_category(int $category_id, CategoryUtil $category_util) {
        if($category_id >= 0) {
            $category = $category_util->get_category($category_id);
            if(isset($category) && $category->get_forum_id() > -1) {
                $category_util->delete_category($category_id);
                display_window_alert_href('Category deleted', '../index.php');
            } else {
                display_window_alert_href('Incorrect category.', '../index.php');
            }
        } else {
            display_window_alert_back('Sorry but you can\'t do that action.');
        }
    }

    function get_descritpion(): string {
        if(!isset($_POST['description']) || empty($_POST['description'])) {
            return '';
        } else {
            $description = str_replace('<', '&lt;', $_POST['description']);
            $description = str_replace('>', '&gt;', $description);
            return nl2br($description);
        }
    }

    function display_window_alert_back(string $message) {
        echo '<script type="text/javascript">
                        window.alert("'.$message.'");
                        window.history.back();
                    </script>';
    }

    function display_window_alert_href(string $message, string $url) {
        echo '<script type="text/javascript">
                        window.alert("'.$message.'");
                        window.location.href = "'.$url.'";
             </script>';
    }

    if(check_user_id()) {
        $category_util = new CategoryUtil();
        $description = get_descritpion();
        $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : -1;
        
        if(!isset($_POST['delete'])) {
            $name = get_name();
            if($category_id >= 0) {
                edit_category($category_id, $name, $description, $category_util);
            } else {
                $category_id = $category_util->new_category($name, $description);
                display_window_alert_href('Your category was created.', '../category.php?cid='.$category_id);
            }
        } else { 
            delete_category($category_id, $category_util);
        }
    }
?>