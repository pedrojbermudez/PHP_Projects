<?php
    include_once('util/menu.php');
    session_start();
    $menu_footer = new MenuFooter();
    echo '<!DOCTYPE html>
    <html lang="en">
        <head>
            <title>Home Page</title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link href="css/style.css" rel="stylesheet">
        </head>
        <body>
            '.$menu_footer->get_menu().'
            <p>aqui van los 30 hilos mas recientes con sus correspondientes foros y el autor del hilo</p>
            '.$menu_footer->get_footer('Pedro').'
        </body>
    </html>';
?>