<?php
    include_once('util/thread_util.php');
    include_once('util/menu.php');

    $thread_util = new ThreadUtil();
    $menu_footer = new MenuFooter();

    $title;
    $name;
    $post;
    $submit_button;
    $forum_id;
    $thread_id;

    // Checking if someone wants to edit a thread
    if(isset($_GET['tid'])) {
        $thread = $thread_util->get_thread($_GET['tid']);
        // Checking if thread exists
        if(isset($thread)) {
            // Thread exists
            $title = 'Edit '.$thread->get_thread_name();
            $thread_id = '<input type="hidden" name="thread_id" value="'.$thread->get_thread_id().'">';
            $forum_id = '<input type="hidden" name="forum_id" value="'.$thread->get_forum_id().'">';
            $name = '<input type="text" name="name" value="'.$thread->get_thread_name().'" required>';
            $post = '';
            $submit_button = '<input type="submit" value="Edit">';
        } else {
            // Wrong thread id
            $title = 'Wrong Thread';
            $forum_id = '';
            $category_id = '';
            $name = '<p>The thread you want to edit doesn\'t exists</p>';
            $description = '';
            $submit_button = '<input type="button" onclick="history.back();" value="Back">';
        }
    } else if(isset($_GET['fid'])) {
        // New Category
        $title = 'New Thread';
        $thread_id = '';
        $forum_id = '<input type="hidden" name="forum_id" value="'.$_GET['fid'].'">';
        $name = '<input type="text" name="name" placeholder="Thread name" required>';
        $post = '<textarea name="post" required>Insert a post</textarea>';
        $submit_button = '<input type="submit" value="Create">';
    } else {
        $title = 'Wrong Forum';
        $thread_id = '';
        $forum_id = '';
        $name = '<p>You must select a forum.</p>';
        $post = '';
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
            </head>
            <body>'.
                $menu_footer->get_menu().'<br />
                <form action="php/process_thread.php" method="POST">'.
                    $thread_id.
                    $forum_id.
                    $name.'<br />'.
                    $post.'<br />'.
                    $submit_button.'<br /> 
                </form>'.
                $menu_footer->get_footer('Pedro').'
            </body>
        </html>';
?>