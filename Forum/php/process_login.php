<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/db/user_db.php');
    // Next version check with ajax and send the form to this php script
    $user_db = new UserDB();
    $user = $user_db->get_user_login($_POST['login_user_name'], $_POST['login_password']);
    if(isset($user) && $user->get_deleted() != 1) {
        session_start();
        $_SESSION['user_name'] = $user->get_user_name();
        $_SESSION['user_id'] = $user->get_user_id();
        $_SESSION['name'] = $user->get_name();
        $_SESSION['surname'] = $user->get_surname();
        $_SESSION['country'] = $user->get_country();
        $_SESSION['state'] = $user->get_state();
        $_SESSION['city'] = $user->get_city();
        $_SESSION['profile_picture'] = $user->get_profile_picture();
        $_SESSION['is_mod'] = $user->get_is_mod();
        $_SESSION['deleted'] = $user->get_deleted();
        echo '<script type="text/javascript">
            window.alert("You are login as '.$_SESSION['user_name'].'");
            window.location.href="../index.php";
        </script>';
    } else {
        echo '<script type="text/javascript">
            window.alert("Wrong user name and/or password");
            window.history.back();
        </script>';
    }
?>