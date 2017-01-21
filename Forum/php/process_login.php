<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/db/user_db.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/user.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/util/user_util.php');

    function get_user_name(UserUtil $user_util): string {
        if (!isset($_POST['login_user_name']) && !empty($_POST['login_user_name']))
            display_window_alert_back('User name can\'t be empty.');
        elseif(!$user_util->user_exists($_POST['login_user_name']))
            display_window_alert_back('Incorrect user name.');
        return $_POST['login_user_name'];
    }

    function get_password(UserUtil $user_util, string $user_name): string {
        if (!isset($_POST['login_password']) && !empty($_POST['login_password']))
            display_window_alert_back('Password can\'t be empty.');
        elseif ($user_util->check_passowrd($user_name, $_POST['login_password']))
            display_window_alert_back('Incorrect password.');
        return $_POST['login_password'];
    }

    function display_window_alert_back(string $message) {
        echo '<script type="text/javascript">
                        window.alert("'.$message.'");
                        window.history.back();
                    </script>';
    }

    $user_util = new UserUtil();
    $user_name = get_user_name($user_util);
    $password = get_password($user_util, $user_name);
    $user = $user_util->get_user_login($user_name, $password);
    if(isset($user) && $user->get_deleted() != 1) {
        session_start();
        $_SESSION['user'] = $user;
        session_write_close();
        echo '<script type="text/javascript">
            window.alert("You are login as '.$_SESSION['user']->get_user_name().'");
            window.location.href="../index.php";
        </script>';
    } else {
        echo '<script type="text/javascript">
            window.alert("Wrong user name and/or password");
            window.history.back();
        </script>';
    }
?>