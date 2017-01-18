<?php
    include_once('util/menu.php');
    include_once('util/thread_util.php');

    $menu_footer = new MenuFooter();
    $thread_util = new ThreadUtil();

    echo '<!DOCTYPE html>
    <html lang="en">
        <head>
            <title></title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link href="css/style.css" rel="stylesheet">
        </head>
        <body>
            '.$menu_footer->get_menu().'
            '.$thread_util->get_thread_html($_GET['tid']).'
            '.$menu_footer->get_footer('Pedro').'
        </body>
    </html>';
?>