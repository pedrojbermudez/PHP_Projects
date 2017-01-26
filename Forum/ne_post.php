<?php
    include_once('util/post_util.php');
    include_once('util/menu.php');
    include_once('util/other.php');

    $post_util = new PostUtil();
    $menu_footer = new MenuFooter();
    $other_util = new Other();
    
    $post;
    $submit_button;
    $thread_id;

    // Checking if post id exists
    if(isset($_GET['pid'])) {
        // Post id exists
        $post_object = $post_util->get_post($_GET['pid']);
        // checking if post id exists
        if(isset($post_object)) {
            // Thread exists
            $title = 'Edit Post';
            $thread_id = '<input type="hidden" name="thread_id" value="'.$post_object->get_thread_id().'">';
            $post_id = '<input type="hidden" name="post_id" value="'.$post_object->get_post_id().'">';
            $post = '<textarea name="post">'.$post_object->get_post().'</textarea>';
            $submit_button = '<input type="submit" value="Edit">';
        } else {
            // Wrong thread id
            $title = 'Wrong Thread';
            $thread_id = '';
            $post_id = '';
            $post = '<p>The thread you want to edit doesn\'t exists</p>';
            $submit_button = '<input type="button" onclick="history.back();" value="Back">';
        }
    } else if(isset($_GET['tid'])) {
        // New Category
        $title = 'New Thread';
        $post_id = '';
        $thread_id = '<input type="hidden" name="thread_id" value="'.$_GET['tid'].'">';
        $post = '<textarea name="post" required>Insert a post</textarea>';
        $submit_button = '<input type="submit" value="Create">';
    } else {
        $title = 'Wrong Forum';
        $thread_id = '';
        $post = '<p>You must select a thread.</p>';
        $submit_button = '<input type="button" onclick="history.back();" value="Back">';
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
                    <form action="php/process_post.php" method="POST">'.
                        $post_id.
                        $thread_id.
                        $post.'<br />'.
                        $submit_button.'<br /> 
                    </form>
                '.$menu_footer->get_footer('Pedro').'
                </div>
            </body>
        </html>';
?>