<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/util/post_util.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/user.php');
    
    session_start();

    $post_util = new PostUtil();
    $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : -1;

    if(isset($_POST['deleted']) && !empty($_POST['deleted']) 
        && $_POST['deleted'] == 'true' && $post_id > -1 
        && $post_util->post_exists($post_id) && isset($_SESSION['user'])){
        $post_object = $post_util->get_post($post_id);
        $user_id = $_SESSION['user']->get_user_id();
        if($user_id == 1 || $user_id == $post_object->get_user_id()) {
            $post_util->post_exists($post_id);
            $post_util->delete_post($post_id);
            echo '<script type="text/javascript">
                    window.alert("Post was deleted.");
                    window.history.back();
                </script>';
        } else echo '<script type="text/javascript">
                    window.alert("Incorrect action.");
                    window.history.back();
                </script>';
    } else{ 
        // Cheching if thread id and post exist
        if(isset($_POST['thread_id']) && isset($_POST['post'])){
            $thread_id = $_POST['thread_id'];
            $post = $_POST['post'];
            // Checking if post id is -1 or greaer
            if($post_id >= 0) {
                // User put a post id to edit it.
                $post_util->edit_post($post_id, $thread_id, $post);
                echo '<script type="text/javascript">
                        window.alert("Your post was edited.");
                        location.href = "../thread.php?tid='.$thread_id.'";
                    </script>';
            } else {
                // User just want to create a new post
                if(isset($_SESSION['user'])) {
                    $user_id = $_SESSION['user']->get_user_id();
                    $post_util->new_post($post, $thread_id, $user_id);
                    echo '<script type="text/javascript">
                            window.alert("Your post was created.");
                            location.href = "../thread.php?tid='.$thread_id.'";
                        </script>';
                } else {
                    echo '<script type="text/javascript">
                            window.alert("Can\'t be created or edited your post. Please check your post.");
                            window.history.back();
                        </script>';
                }
            }
        } else {
            echo '<script type="text/javascript">
                    window.alert("Can\'t be created or edited your post. Please check your post.");
                    window.history.back();
                </script>';
        }
    }
?>