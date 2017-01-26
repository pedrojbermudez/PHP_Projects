<?php
    include_once('util/menu.php');
    include_once('util/user_util.php');
    include_once('util/other.php');
    
    if(session_status() == PHP_SESSION_NONE) { session_start(); }
    $other_util = new Other();
    $html = '';
    echo '<!DOCTYPE html>
        <html lang="en">
            <head>
                <title></title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                '.$other_util->get_bootstrap_css().'
            </head>
            <body style="padding-top: 70px;">
                '.$other_util->get_jquery().'
                '.$other_util->get_bootstrap_js().'
                <div class="container">
                    '.$menu_footer->get_menu().'
                    '.$html.'
                    '.$menu_footer->get_footer('Pedro').'    
                </div>
            </body>
        </html>';
?>