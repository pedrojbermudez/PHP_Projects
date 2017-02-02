<?php
    include_once('util/menu.php');
    include_once('util/user_util.php');
    include_once('util/other.php');
    
    if(session_status() == PHP_SESSION_NONE) { session_start(); }

    $other_util = new Other();
    $menu_footer = new MenuFooter();
    $user_util = new UserUtil();
    $level = isset($_GET['level']) && !empty($_GET['level']) ? intval($_GET['level']) : 2;
    $level_url ='';
    for($i=0; $i < $level; $i++) {
        $level_url .= '../';
    }
    $user = $user_util->get_user(intval($_GET['uid']), $level);
    $content_type = $_SERVER['HTTP_ACCEPT'];

    if(strpos($content_type,'application/json') !== false){
        echo_html($user);
    } else if(strpos($content_type,'text/html') !== false) {
        $html = '
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <title></title>
                <meta charset="UTF-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1" />
                '.$other_util->get_bootstrap_css($level).'
            </head>
            <body style="padding-top: 70px;">
                '.$other_util->get_jquery().'
                '.$other_util->get_bootstrap_js($level).'
                <div class="container">
                    '.$menu_footer->get_menu(-1, $level).'
                    '.$user.'
                    '.$menu_footer->get_footer('Pedro').' 
                </div>
            </body>
        </html>';
        echo_html($html);
    } else if(strpos($content_type,'application/xml') !== false) {
        echo_html($user);
    }

    function echo_html($html) {
        echo $html;    
    }
?>