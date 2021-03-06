<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/db/user_db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/user.php');
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

    function display_window_alert_href(string $message, string $url) {
        echo '<script type="text/javascript">
                window.alert("'.$message.'");
                window.location.href="'.$url.'";
            </script>';
    }

    $user_util = new UserUtil();
    // Checking user data form
    $user_name = get_user_name($user_util);
    $password = get_password($user_util, $user_name);

    // Getting User object
    $user = $user_util->get_user_login($user_name, $password);

    if(session_status() == PHP_SESSION_NONE) { session_start(); }
    
    // Setting user data
    $_SESSION['user_id'] = $user->get_user_id();
    $_SESSION['user_name'] = $user_name;    
    $_SESSION['city'] = $user->get_city();    
    $_SESSION['state'] = $user->get_state();    
    $_SESSION['country'] = $user->get_country();    
    $_SESSION['profile_picture'] = $user->get_profile_picture();    
    display_window_alert_href('You are login as '.$_SESSION['user_name'], '../index.php');
?>