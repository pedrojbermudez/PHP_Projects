<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/util/forum_util.php');

    session_start();

    $forum_util = new ForumUtil();
    $description = isset($_POST['description']) ? $_POST['description'] : '';

    $forum_id = isset($_POST['forum_id']) ? $_POST['forum_id'] : -1;
    if(!isset($_POST['delete'])) {
        if(isset($_POST['name']) && isset($_POST['category_id'])){
            $name = $_POST['name'];
            $category_id = $_POST['category_id'];
            
            if($forum_id >= 0) {
                $forum_util->edit_forum($forum_id, $name, $description, $category_id);
                echo '<script type="text/javascript">
                        window.alert("Your forum was edited.");
                        location.href = "../forum.php?fid='.$forum_id.'";
                    </script>';
            } else {
                if(!empty($name)) {
                    $forum_id = $forum_util->new_forum($name, $description, $category_id);
                    echo '<script type="text/javascript">
                            window.alert("Your forum was created.");
                            location.href = "../forum.php?fid='.$forum_id.'";
                        </script>';
                } else {
                    echo '<script type="text/javascript">
                            window.alert("The name and category can\'t be empty. Please introduce a name and/or a category");
                            window.history.back();
                        </script>';
                }
            }
        } else {
            echo '<script type="text/javascript">
                    window.alert("Name and category can\'t be empty. Please introduce a name and/or category");
                    window.history.back();
                </script>';
        }
    } else {
        if($forum_id >= 0) {
            $forum_util->delete_forum($forum_id);
            echo '<script type="text/javascript">
                    window.alert("Forum deleted");
                    window.location.href = "../index.php";
                </script>';
        } else {
            echo '<script type="text/javascript">
                    window.alert("Sorry but you can\t do that action.");
                    window.history.back();
                </script>';
        }
    }
?>