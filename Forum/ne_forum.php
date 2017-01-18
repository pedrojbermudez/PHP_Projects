<?php
    include_once('util/category_util.php');
    include_once('util/forum_util.php');
    include_once('util/menu.php');

    $category_util = new CategoryUtil();
    $forum_util = new ForumUtil();
    $menu_footer = new MenuFooter();

    $title;
    $name;
    $description;
    $submit_button;
    $forum_id;
    $category_id;
    $categories = $category_util->get_categories();
    // Checking if someone wants to edit a forum
    if(isset($_GET['fid'])) {
        $forum = $forum_util->get_forum($_GET['fid']);
        // Checking if forum exists
        if(isset($forum)) {
            // Forum exists
            $title = 'Edit '.$forum->get_name();
            $forum_id = '<input type="hidden" name="forum_id" value="'.$forum->get_forum_id().'">';
            $name = '<input type="text" name="name" value="'.$forum->get_name().'" required>';
            $description = '<textarea name="description">'.$forum->get_description().'</textarea>';
            $submit_button = '<input type="submit" value="Edit">';
            $category_id = 'Select a category: <select name="category_id">';
            foreach($categories as $category) {
                $selected = $category->get_forum_id() == $forum->get_category_id() ? 'selected' : '';
                $category_id .= '<option value="'.
                    $category->get_forum_id().'" '.$selected.'>'.$category->get_name().'</option>';
            }
            $category_id .= '</select>';
        } else {
            // Wrong forum id
            $title = 'Wrong Forum';
            $forum_id = '';
            $category_id = '';
            $name = '<p>The forum you want to edit doesn\'t exists</p>';
            $description = '';
            $submit_button = '<input type="button" onclick="history.back();" value="Back">';
        }
    } else {
        // New Category
        $title = 'New Forum';
        $name = '<input type="text" name="name" placeholder="Forum name" required>';
        $description = '<textarea name="description">Insert a description for the new forum</textarea>';
        $submit_button = '<input type="submit" value="Create">';
        $category_id = 'Select a category: <select name="category_id">';
        $forum_id = '';
        foreach($categories as $category) {
            $category_id .= '<option value="'.
                $category->get_forum_id().'">'.$category->get_name().'</option>';
        }
        $category_id .= '</select>';
    }

    echo '
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <title>'.$title.'</title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <link href="css/style.css" rel="stylesheet">
            </head>
            <body>'.
                $menu_footer->get_menu().'<br />
                <form action="php/process_forum.php" method="POST">'.
                    $forum_id.
                    $category_id.'<br />'.
                    $name.'<br />'.
                    $description.'<br />'.
                    $submit_button.'<br /> 
                </form>'.
                $menu_footer->get_footer('Pedro').'
            </body>
        </html>';
?>