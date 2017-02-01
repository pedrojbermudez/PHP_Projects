<?php
    include_once('util/user_util.php');
    include_once('util/menu.php');
    include_once('data/user.php');
    include_once('util/other.php');
        
    if(session_status() == PHP_SESSION_NONE) { session_start(); }

    $user_util = new UserUtil();
    $menu_footer = new MenuFooter();
    $other_util = new Other();
    
    $title;
    $submit_button;
    $current_profile_picture;
    $user_id;
    $name;
    $user_name;
    $surname;
    $email;
    $password;
    $repeat_password;
    $country;
    $state;
    $city;
    $profile_picture;
    $url_action;
    if(isset($_SESSION['user_id'])) {
        $user = json_decode($user_util->get_user(intval($_SESSION['user_id'])), true);
        if(isset($user['user_id']) && $user['user_id'] > -1) {
            // Edit a user
            $title = 'Edit '.$user['user_name'];
            $user_id = '<input type="hidden" name="user_id" value="'.$user['user_id'].'" />';
            $current_profile_picture = '<input type="hidden" name="current_profile_picture" value="'.$user['profile_picture'].'" />';
            $name = '<p><input type="text" name="name" placeholder="Name" value="'.$user['name'].'" /></p>';
            $surname = '<p><input type="text" name="surname" placeholder="Surname" value="'.$user['surname'].'" /></p>';
            $user_name = '';
            $email = '';
            $password = '';
            $repeat_password = '';
            $country = '<p><input type="text" name="country" placeholder="Country" value="'.$user['country'].'" /></p>';
            $state = '<p><input type="text" name="state" placeholder="State" value="'.$user['state'].'" /></p>';
            $city = '<p><input type="text" name="city" placeholder="City" value="'.$user['city'].'" /></p>';
            $profile_picture = '<div>Current picture: <img src="'.$user['profile_picture'].'" width="200" />
                <p>New profile picture: <input type="file" name="profile_picture" /></p></div>';
            $url_action = 'user/edit';
            $submit_button = '<p><input type="submit" value="Edit" /></p>';
        } else {
            // Wrong user id
            $title = 'Wrong user';
            $name = '<p>The user you want to edit doesn\'t exists</p>';
            $user_id = '';
            $current_profile_picture = '';
            $surname = '';
            $user_name = '';
            $email = '';
            $password = '';
            $repeat_password = '';
            $country = '';
            $state = '';
            $city = '';
            $profile_picture = '';
            $url_action = '';
            $submit_button = '<input type="button" onclick="history.back();" value="Back" /></p>';
        }
    } else {
        // New User
        $title = 'New User';
        $user_id = '';
        $current_profile_picture = '';
        $name = '<p><input type="text" name="name" placeholder="Name" /></p>';
        $surname = '<p><input type="text" name="surname" placeholder="Surname" /></p>';
        $user_name = '<p><input type="text" name="user_name" placeholder="User name" required /></p>';
        $email = '<p><input type="email" name="email" placeholder="Email" required /></p>';
        $password = '<p><input type="password" name="password" placeholder="Passowrd" required /></p>';
        $repeat_password = '<p><input type="password" name="repeat_password" placeholder="Repeat password" required /></p>';
        $country = '<p><input type="text" name="country" placeholder="Country" /></p>';
        $state = '<p><input type="text" name="state" placeholder="State" /></p>';
        $city = '<p><input type="text" name="city" placeholder="City" /></p>';
        $profile_picture = '<p>Profile picture: <input type="file" name="profile_picture" /></p>';
        $url_action = 'user/new';
        $submit_button = '<p><input type="submit" value="Create" /></p>';
    } 

    echo '
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <title>'.$title.'</title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                '.$other_util->get_bootstrap_css().'
            </head>
            <body style="padding-top: 70px;">
                '.$other_util->get_jquery().'
                '.$other_util->get_bootstrap_js().'
                '.$menu_footer->get_menu().'
                <div class="container">
                    <form action="'.$url_action.'" method="POST" enctype="multipart/form-data">'.
                        $user_id.
                        $current_profile_picture.
                        $user_name.
                        $password.
                        $repeat_password.
                        $email.
                        $name.
                        $surname.
                        $country.
                        $state.
                        $city.
                        $profile_picture.
                        $submit_button.' 
                    </form>
                    '.$menu_footer->get_footer('Pedro').'
                </div>
            </body>
        </html>';
?>