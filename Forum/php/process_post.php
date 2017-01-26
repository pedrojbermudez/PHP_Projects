<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/util/post_util.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/user.php');
    
    session_start();

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

    function get_post(): string {
        $post = '';
        if(!isset($_POST['post']) || empty($_POST['post']))
            display_window_alert_back('Post can\'t be empty.');
        else {
            $post = str_replace('<', '&lt;', $_POST['post']);
            $post = str_replace('>', '&gt;', $post);
        }
            return nl2br($post);
    }

    function get_user(): int {
        if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] == -1)
            display_window_alert_back('You must be logged to created a post.');
        else
            return intval($_SESSION['user_id']);
    }

    function get_thread_id(): int {
        if(isset($_POST['thread_id']) && intval($_POST['thread_id']) > -1)
            return intval($_POST['thread_id']);
        else
            display_window_alert_back('Can\'t be created or edited your post. 
                Please check your post.');
    }

    $user_id = get_user();
    $post_util = new PostUtil();
    $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : -1;

    if(isset($_POST['deleted']) && !empty($_POST['deleted']) 
                && $_POST['deleted'] == 'true' && $post_id > -1 
                && $post_util->post_exists($post_id) 
                && $user_id > -1) {
        $post_object = $post_util->get_post($post_id);
        if($user_id == 1 || $user_id == $post_object->get_user_id()) {
            $post_util->delete_post($post_id);
            display_window_alert_back('Post was deleted.');
        } else display_window_alert_back('Incorrect action.');
    } else{ 
        $post = get_post();
        $thread_id = get_thread_id();
        if($post_id >= 0) {
            // User put a post id to edit it.
            if($post_util->post_exists($post_id)) {
                $post_util->edit_post($post_id, $thread_id, $post);
                display_window_alert_href('Your post was edited.', '../thread.php?tid='.$thread_id);
            } else
                display_window_alert_back('Incorrect post.');
        } else {
            // User just want to create a new post
            $post_util->new_post($post, $thread_id, $user_id);
            display_window_alert_href('Your post was created.', '../thread.php?tid='.$thread_id);
        }
    }
?>