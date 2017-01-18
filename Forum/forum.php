<?php
    include_once('util/menu.php');
    include_once('util/forum_util.php');

    $menu_footer = new MenuFooter();
    $forum_util = new ForumUtil();

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
            '.$forum_util->get_forum_html($_GET['fid']).'
        </body>
    </html>';
?>