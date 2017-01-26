<?php
    include_once('util/category_util.php');
    include_once('util/menu.php');
    include_once('util/other.php');

    $category_util = new CategoryUtil();
    $menu_footer = new MenuFooter();
    $other_util = new Other();
    
    $category_id;
    $title;
    $name;
    $description;
    $submit_button;
    // Checking if someone wants to edit a category
    if(isset($_GET['cid'])) {
        $category_id = $_GET['cid'];
        $category = $category_util->get_category($category_id);
        // Checking if category exists
        if(isset($category)) {
            // Category exists
            $title = 'Edit '.$category->get_name();
            $category_id = '<input type="hidden" name="category_id" value="'.$category->get_forum_id().'">';
            $name = '<input type="text" name="name" value="'.$category->get_name().'" required>';
            $description = '<textarea name="description">'.$category->get_description().'</textarea>';
            $submit_button = '<input type="submit" value="Edit">';
        } else {
            // Wrong category id
            $title = 'Wrong Category';
            $category_id = '';
            $name = '<p>The category you want to edit doesn\'t exists</p>';
            $description = '';
            $submit_button = '<input type="button" onclick="history.back();" value="Back">';
        }
    } else {
        // New Category
        $title = 'New Category';
        $category_id = '';
        $name = '<input type="text" name="name" placeholder="Category name" required>';
        $description = '<textarea name="description">Insert a description for the new category</textarea>';
        $submit_button = '<input type="submit" value="Create">';
    }

    echo '
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <title>'.$title.'</title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <link href="css/style.css" rel="stylesheet">
                '.$other_util->get_bootstrap_css().'
            </head>
            <body style="padding-top: 70px;">
                '.$other_util->get_jquery().'
                '.$other_util->get_bootstrap_js().'
                '.$menu_footer->get_menu().'
                <div class="container">
                    <form action="php/process_category.php" method="POST">
                        '.$category_id.'
                        '.$name.'<br />
                        '.$description.'<br />
                        '.$submit_button.'<br /> 
                    </form>
                    '.$menu_footer->get_footer('Pedro').'
                </div>
            </body>
        </html>';
?>