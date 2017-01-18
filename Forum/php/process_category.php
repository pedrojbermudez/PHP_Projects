<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/util/category_util.php');

    session_start();

    $category_util = new CategoryUtil();
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : -1;
    if(!isset($_POST['delete'])) {
        if(isset($_POST['name'])){
            $name = $_POST['name'];
            if($category_id >= 0) {
                $category_util->edit_category($category_id, $name, $description);
                echo '<script type="text/javascript">
                        window.alert("Your category was edited.");
                        location.href = "../category.php?cid='.$category_id.'";
                    </script>';
            } else {
                if(!empty($name)) {
                    $category_id = $category_util->new_category($name, $description);
                    echo '<script type="text/javascript">
                            window.alert("Your category was created.");
                            location.href = "../category.php?cid='.$category_id.'";
                        </script>';
                } else {
                    echo '<script type="text/javascript">
                            window.alert("The name can\'t be empty. Please introduce a name");
                            window.history.back();
                        </script>';
                }
            }
        } else {
            echo '<script type="text/javascript">
                    window.alert("Name can\'t be empty. Please introduce a name");
                    window.history.back();
                </script>';
        }
    } else { 
        if($category_id >= 0) {
            $category_util->delete_category($category_id);
            echo '<script type="text/javascript">
                    window.alert("Category deleted");
                    window.location.href = "../index.php";
                </script>';
        } else {
            echo '<script type="text/javascript">
                    window.alert("You can\'t be here");
                    window.history.back();
                </script>';
        }
    }
?>