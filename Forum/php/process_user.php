<?php
    declare(strict_types=1);
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/util/user_util.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/user.php');

    if(session_status() == PHP_SESSION_NONE) { session_start(); }

    // Getting the email, email is a required field and shouldn't throw an error 
    // just in case the email exists
    function email(): string {
        if(isset($_POST['email']) && !empty($_POST['email']) && !email_exists($_POST['email'])) {
            return $_POST['email'];
        } elseif (!isset($_POST['email']) || empty($_POST['email'])) {
            echo '<script type="text/javascript">
                    window.alert("Email field is required.");
                    window.history.back();
                </script>';
        } elseif(isset($_POST['email']) && email_exists($_POST['email'])) {
            echo '<script type="text/javascript">
                    window.alert("Email exists.");
                    window.history.back();
                </script>';
        } else {
            echo '<script type="text/javascript">
                    window.alert("There was a problem with the email. Please check your email.");
                    window.history.back();
                </script>';
        }
    }
    
    // Checking the password are the same in both fields.
    function password(): string {
        if(isset($_POST['password']) && isset($_POST['repeat_password']) 
            && strcmp($_POST['password'], $_POST['repeat_password']) == 0) {
                return $_POST['password'];
        } else {
            echo '<script type="text/javascript">
                    window.alert("Incorrect password. Please check your password.");
                    window.history.back();
                </script>';
        }
    }
    
    // CHecking user name, the only error should throw is just because the user_name is in database 
    function user_name(): string {
        if(isset($_POST['user_name']) && !empty($_POST['user_name']) && !user_exists($_POST['user_name'])) {
            return $_POST['user_name'];
        } elseif (!isset($_POST['user_name']) || empty($_POST['user_name'])) {
            echo '<script type="text/javascript">
                    window.alert("User name field is required.");
                    window.history.back();
                </script>';
        } elseif(isset($_POST['user_name']) && user_exists($_POST['user_name'])) {
            echo '<script type="text/javascript">
                    window.alert("User name exists.");
                    window.history.back();
                </script>';
        } else {
            echo '<script type="text/javascript">
                    window.alert("There was a problem with the user name. Please check your user name.");
                    window.history.back();
                </script>';
        }
    }

    // Function to help user_name() function return true if exists or false is 
    // doesn't exist the user name
    function user_exists(string $user_name): bool {
        $user_util = new UserUtil();
        return $user_util->user_exists($user_name);
    }

    // Function to help email() function return a bool if exists or not the email
    function email_exists(string $email): bool {
        $user_util = new UserUtil();
        return $user_util->email_exists($email);
    }

    function get_file_extension(string $filename): string {
        $filename = strrev($filename);
        $ext = strrev(substr($filename, 0, strpos($filename, '.')));
        return $ext;
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
                        window.location.href = "'.$url.'";
             </script>';
    }

    // Write a picture file in /images/ if the file doesn't exist and return its path
    function write_file(): string {
        if(!empty($_FILES['profile_picture']['name'])) {
                $ext = get_file_extension($_FILES['profile_picture']['name']);
                $filename = md5_file($_FILES['profile_picture']['tmp_name']).'.'.$ext;
                if(!file_exists('../images.'.$filename))
                    move_uploaded_file($_FILES['profile_picture']['tmp_name'], '../images/'.$filename);
                return 'images/'.$filename;
            } else {
                 return !empty($current_profile_picture) ? $current_profile_picture : 'images/default.jpg';
            }
    }
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : -1;
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $surname = isset($_POST['surname']) ? $_POST['surname'] : '';
    $country = isset($_POST['country']) ? $_POST['country'] : '';
    $state = isset($_POST['state']) ? $_POST['state'] : '';
    $city = isset($_POST['city']) ? $_POST['city'] : '';
    $current_profile_picture = isset($_POST['current_profile_picture']) ? $_POST['current_profile_picture'] : 'images/default.jpg';
    $profile_picture = write_file();
    $user_util = new UserUtil();
    // Checking if exists a session and the user wants to edit
    if(isset($_SESSION['user_id']) && intval($_SESSION['user_id']) > -1 && $user_id >= -1) {
        $user = json_decode($user_util->get_user($user_id), true);
        // Checking if the user wants to edit him/her user or not.
        if(isset($user['user_id']) && ($user['user_id'] 
                == intval($_SESSION['user_id']) || intval($_SESSION['user_id']) == 1)) {
            $user_util->edit_user($user_id, $name, $surname, $country, $state, $city, $profile_picture);
            $_SESSION['profile_picture'] = $profile_picture;
            display_window_alert_href('Your user was edited.', '../index.php');
        } else {
            display_window_alert_back('There was a problem. Please check your data.');
        }
    } else {
        // New user
        $user_name = user_name();
        $email = email();
        $password = password();
        $user = array(
            'user_name' => $user_name,
            'password' => $password,
            'email' => $email,
            'name' => $name,
            'surname' => $surname,
            'country' => $country,
            'state' => $state,
            'city' => $city,
            'profile_picture' => $profile_picture
        );
        $json_user = json_encode($user);
        $user_util->new_user($json_user);
        display_window_alert_href('Thanks for signing up.', '../index.php');
    }
?>