<?php
    include_once('data/category.php');
    include_once('data/forum.php');
    include_once('util/category_util.php');
    include_once('util/menu.php');

    $menu_footer = new MenuFooter();
    $cat_util = new CategoryUtil();

    echo $menu_footer->get_menu();

    $category_html_code = $cat_util->get_category_html($_GET['cid']);

    echo '<!DOCTYPE html>
    <html lang="en">
        <head>
            <title></title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link href="css/style.css" rel="stylesheet">
        </head>
        <body>
        '.$category_html_code.'
        </body>
    </html>';
?>
