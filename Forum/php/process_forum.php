<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/util/forum_util.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/util/category_util.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/user.php');
    
    session_start();

    // Function to check if $_POST['name'] doesn't exist or is empty.
    // In that case an error message must be throw. 
    function get_name(): string {
        if(!isset($_POST['name']) || empty($_POST['name']))
            display_window_alert_back('The name can\'t be empty. Please introduce a name.');
        return $_POST['name'];
    }

    // Function to get the category id in case there is nothing or doesn't exist a message should be displayed.
    function get_category_id(): int {
        if(isset($_POST['category_id']) && !empty($_POST['category_id']) && $_POST['category_id'] > -1) {
            $category_util = new CategoryUtil();
            $category = $category_util->get_category($_POST['category_id']);
            if($category->get_forum_id()) {
                return $_POST['category_id'];
            } else {
                display_window_alert_back('Incorrect category.');    
            }
        } else { 
            display_window_alert_back('Select a category.');
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

    // Function to display a window alert with a message and back to the before page
    function display_window_alert_back(string $message) {
        echo '<script type="text/javascript">
                        window.alert("'.$message.'");
                        window.history.back();
                    </script>';
    }

    // Function to display a window alert and go to a specify url
    function display_window_alert_href(string $message, string $url) {
        echo '<script type="text/javascript">
                        window.alert("'.$message.'");
                        window.location.href = "'.$url.'";
             </script>';
    }

    function edit_forum(int $forum_id, string $name, int $category_id, string $description, ForumUtil $forum_util) {
        $forum = $forum_util->get_forum($forum_id);
        if(isset($forum) && $forum->get_forum_id() > -1) {
            // The forum exists and the forum id is greater than -1
            $forum_util->edit_forum($forum_id, $name, $description, $category_id);
            display_window_alert_href('Your forum was edited.', '../forum.php?fid='.$forum_id);
        } else {
            display_window_alert_back('Incorrect forum.');
        }
    }
    
    function delete_forum(ForumUtil $forum_util, int $forum_id) {
        if($forum_id >= 0) {
            $forum = $forum_util->get_forum($forum_id);
            if(isset($forum) && $forum->get_forum_id() > -1) {
                $forum_util->delete_forum($forum_id);
                display_window_alert_href('Forum deleted', '../index.php');
            } else {
                display_window_alert_href('Incorrect action.', '../index.php');
            }
        } else {
            display_window_alert_back('Sorry but you can\'t do that action.');
        }
    }

    $forum_util = new ForumUtil();
    $description = get_descritpion;
    $forum_id = isset($_POST['forum_id']) ? $_POST['forum_id'] : -1;
    
    if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1) {
        if(!isset($_POST['delete'])) {
            $name = get_name();
            $category_id = get_category_id();
            if($forum_id >= 0) {
                // Editing Forum
                edit_forum($forum_id, $name, $category_id, $description, $forum_util);
            } else {
                // Creating forum
                $forum_id = $forum_util->new_forum($name, $description, $category_id);
                display_window_alert_href('Your forum was created.', '../forum.php?fid='.$forum_id);
            }
        } else {
            delete_forum($forum_util, $forum_id);
        }
    } else {
        display_window_alert_href('Sorry but you can\t do that action.', '../index.php');
    }
?>