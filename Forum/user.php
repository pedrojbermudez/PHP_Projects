<?php
    include_once('util/menu.php');
    include_once('util/user_util.php');
    include_once('util/other.php');
    
    if(session_status() == PHP_SESSION_NONE) { session_start(); }
    $other_util = new Other();
    $menu_footer = new MenuFooter();
    $user_util = new UserUtil();
    $user = $user_util->get_user(intval($_GET['uid']));
    $user = json_decode($user, true);
    $html;
    if(isset($user) && isset($user['user_id']) && $user['user_id'] == intval($_GET['uid'])) {
        $html = '<h1>'.$user['user_name'].(
                    isset($user['deleted']) && intval($user['deleted']) == 1 
                    ? ' DELETED' : '').'</h1>';
        $html .= '<img src="../../'.($user['profile_picture']).'"  width="200" />';        
        $html .= '<p>Name: '.($user['name']).'</p>';
        $html .= '<p>Surname: '.($user['surname']).'</p>';
        $html .= '<p>Country: '.($user['country']).'</p>';
        $html .= '<p>State: '.($user['state']).'</p>';
        $html .= '<p>City: '.($user['city']).'</p>';
    } else {
        $html = '<p>Wrong user.</p>';
    }
    echo '<!DOCTYPE html>
        <html lang="en">
            <head>
                <title></title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                '.$other_util->get_bootstrap_css(isset($_GET['level']) && !empty($_GET['level']) ? intval($_GET['level']) : 2).'
            </head>
            <body style="padding-top: 70px;">
                '.$other_util->get_jquery().'
                '.$other_util->get_bootstrap_js(isset($_GET['level']) && !empty($_GET['level']) ? intval($_GET['level']) : 2).'
                <div class="container">
                    '.$menu_footer->get_menu(-1, isset($_GET['level']) && !empty($_GET['level']) ? intval($_GET['level']) : 2).'
                    '.$html.'
                    '.$menu_footer->get_footer('Pedro').'    
                </div>
            </body>
        </html>';
?>