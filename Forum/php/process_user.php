<?php
    declare(strict_types=1);
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/util/user_util.php');

    session_start();

    function email(): string {
        if(isset($_POST['email']) && !email_exists($_POST['email']) && !empty($_POST['email'])) {
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

    function user_exists(string $user_name): bool {
        $user_util = new UserUtil();
        return $user_util->user_exists($user_name);
    }

    function email_exists(string $email): bool {
        $user_util = new UserUtil();
        return $user_util->email_exists($email);
    }

    function get_file_extension(string $filename): string {
        $filename = strrev($filename);
        $ext = strrev(substr($filename, 0, strpos($filename, '.')));
        return $ext;
    }

    function write_file(): string {
        if(!empty($_FILES['profile_picture']['name'])) {
                $ext = get_file_extension($_FILES['profile_picture']['name']);
                $filename = md5_file($_FILES['profile_picture']['tmp_name']).'.'.$ext;
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
    $current_profile_picture = isset($_POST['current_profile_picture']) ? $_POST['current_profile_picture'] : '';
    $profile_picture;
    $user_util = new UserUtil();
    if($user_id > -1) {
        $user = $user_util->get_user($user_id);
        if(isset($user)) {
            $profile_picture = empty($_FILES['profile_picture']['name']) ? $current_profile_picture : write_file();
            $user_util->edit_user($user_id, $name, $surname, $country, $state, $city, $profile_picture);
            echo '<script type="text/javascript">
                    window.alert("Your user was edited.");
                    location.href = "../index.php";
                </script>';
        }
    } else {
        $profile_picture = write_file();
        $user_name = user_name();
        $email = email();
        $password = password();
        $user_util->new_user($user_name, $password, $email, 
            $name, $surname, $country, $state, $city, $profile_picture);
        echo '<script type="text/javascript">
                window.alert("Thanks for signing up.");
                window.location.href="../index.php";
            </script>';
    }
?>