<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/util/thread_util.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/util/forum_util.php');
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

    function get_user_id(): int { 
        if(!isset($_SESSION['user']) || $_SESSION['user']->get_user_id() == -1) {
            display_window_alert_href('You must be logged in.', '../index.php');
        }
        return $_SESSION['user']->get_user_id();
     }

    // Function to get the forum id, in case there is nothing or doesn't exist a message should be displayed.
    function get_forum_id(): int {
        if(isset($_POST['forum_id']) && !empty($_POST['forum_id']) && $_POST['forum_id'] > -1) {
            $forum_util = new ForumUtil();
            $forum = $forum_util->get_forum($_POST['forum_id']);
            if($forum->get_forum_id()) {
                return $_POST['forum_id'];
            } else {
                display_window_alert_back('Incorrect forum.');    
            }
        } else { 
            display_window_alert_back('Select a forum.');
        }
    }

    function get_name(): string {
        if(!isset($_POST['name']) || empty($_POST['name']))
            display_window_alert_back('The name can\'t be empty. Please introduce a name.');
        return $_POST['name'];
    }

    function get_post(): string {
        $post = '';
        if(!isset($_POST['post']) || empty($_POST['post']))
            display_window_alert_back('You must write something on the post field.');
        else {
            $post = str_replace('<', '&lt;', $_POST['post']);
            $post = str_replace('>', '&gt;', $post);
            return nl2br($post);
        }
    }

    function edit_thread(int $thread_id, string $name, int $forum_id, ThreadUtil $thread_util) {
        $thread = $thread_util->get_thread($thread_id);
        if(isset($thread) && $thread->get_forum_id() > -1 
            && $_SESSION['user']->get_user_id() == 1 
            || $_SESSION['user']->get_user_id() == $thread->get_user_id()) {
            // The thread exists and the thread id is greater than -1 and 
            // the user is an admin or the original author
            $thread_util->edit_thread($thread_id, $name, $forum_id);
            display_window_alert_href('Your thread was edited.', '../thread.php?tid='.$thread_id);
        } else {
            display_window_alert_back('Incorrect forum.');
        }
    }
    
    function delete_thread(ThreadUtil $thread_util, int $thread_id) {
        if($thread_id >= 0) {
            $thread = $thread_util->get_thread($thread_id);
            if(isset($thread) && $thread->get_forum_id() > -1 
                && $_SESSION['user']->get_user_id() == 1 
                || $_SESSION['user']->get_user_id() == $thread->get_user_id()) {
                $thread_util->delete_thread($thread_id);
                display_window_alert_href('Thread deleted', '../forum.php?fid='.get_forum_id());
            } else {
                display_window_alert_href('Incorrect action.', '../forum.php?fid='.get_forum_id());
            }
        } else {
            display_window_alert_back('Sorry but you can\t do that action.');
        }
    }

    $thread_util = new ThreadUtil();
    $thread_id = isset($_POST['thread_id']) ? $_POST['thread_id'] : -1;
    $user_id = get_user_id();

    if(!isset($_POST['delete'])) {
        $forum_id = get_forum_id();
        $name = get_name();
        // We check if thread id is greater than or equal to 0 
        if($thread_id >=0) {
            // Thread id is greater than or equal to 0 so we can edit
            edit_thread($thread_id, $name, $forum_id, $thread_util);
        } else {
            // Thread id is equal to -1 so we want to create a new thread.
            // First we check if post exists or not. 
            // In order to create a new thread we need to create a post.
            $post = get_post();
            $thread_id = $thread_util->new_thread($name, $post, $forum_id, $user_id);
            display_window_alert_href('Your thread was created', '../thread.php?tid='.$thread_id);
        }
    } else {
        // Next version check if the user who want to delete the thread is the author or administrator
        if($thread_id >= 0) {
            delete_thread($thread_util, $thread_id);
        } else {
            display_window_alert_back('You can\'t do action. Sorry.');
        }
    }
?>