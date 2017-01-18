<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/util/thread_util.php');
    session_start();

    $thread_util = new ThreadUtil();
    $thread_id = isset($_POST['thread_id']) ? $_POST['thread_id'] : -1;

    if(!isset($_POST['delete'])) {
        // Checking if forum id, name and user id exists.
        if(isset($_POST['forum_id']) && isset($_POST['name']) && isset($_SESSION['user_id'])){
            // Forum id, name and user name exist and store them values.
            $forum_id = $_POST['forum_id'];
            $name = $_POST['name'];
            $user_id = $_SESSION['user_id'];

            // We check if thread id is greater than or equal to 0 
            if($thread_id >=0) {
                // Thread id is greater than or equal to 0 so we can edit
                $thread_util->edit_thread($thread_id, $name, $forum_id, $user_id);
                echo '<script type="text/javascript">
                        window.alert("Your thread was edited.");
                        location.href = "../thread.php?tid='.$thread_id.'";
                    </script>';
            } else {
                // Thread id is equal to -1 so we want to create a new thread.
                // First we check if post exists or not. 
                // In order to create a new thread we need to create a post.
                if(isset($_POST['post'])) {
                    // Post exists and we can create thread and post both.
                    $post = $_POST['post'];
                    $thread_id = $thread_util->new_thread($name, $post, $forum_id, $user_id);
                    echo '<script type="text/javascript">
                            window.alert("Your thread was created.");
                            location.href = "../thread.php?tid='.$thread_id.'";
                        </script>';
                } else {
                    // Post does not exist so we need to go back
                    echo '<script type="text/javascript">
                            window.alert("You must write something on the post field.");
                            window.history.back();
                        </script>';
                }
            }
        } else {
            // Forum id, name and user id don't exist
            echo '<script type="text/javascript">
                    window.alert("Can\'t be created or edited your thread. Please check your thread.");
                    window.history.back();
                </script>';
        }
    } else {
        // Next version check if the user who want to delete the thread is the author or administrator
        if($thread_id >= 0) {
            $thread_util->delete_thread($thread_id);
            echo '<script type="text/javascript">
                    window.alert("Your thread was deleted.");
                    window.location.href = "../index.php";
                </script>';
        } else {
            echo '<script type="text/javascript">
                    window.alert("You can\'t do action. Sorry.");
                    window.history.back();
                </script>';
        }
    }
?>