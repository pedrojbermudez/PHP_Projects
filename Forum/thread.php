<?php
    declare(strict_types=1);

    include_once('util/menu.php');
    include_once('util/thread_util.php');
    include_once('util/post_util.php');
    include_once('util/other.php');
    
    if(session_status() == PHP_SESSION_NONE) { session_start(); }

    function get_date_year(string $date): string {
        return ''.substr($date, 0, 4);
    }

    function get_date_month(string $date): string {
        return ''.substr($date, 5, 2);
    }

    function get_date_day(string $date): string {
        return ''.substr($date, 8, 2);
    }

    function get_date_time($date): string {
        return ''.substr($date, 10);
    }

    function get_full_date($date): string {
        return get_date_day($date).'/'.get_date_month($date).'/'
                    .get_date_year($date).' '.get_date_time($date);
    }

    function get_posts_html(array $posts, int $thread_id): string {
        if(isset($posts) && !empty($posts))
            $max = sizeof($posts);
        else
            $max = 0;
        $html = '<div class="row"><div class="col-md-12">'.get_new_post($thread_id).'</div><hr /></div>';
        for ($i=0; $i < $max; $i++) {
            $creation_date = 'Created on: '.get_full_date($posts[$i]->get_creation_date());
            $profile_picture = $posts[$i]->get_profile_picture();
            $user_id = $posts[$i]->get_user_id();
            $user_name = $posts[$i]->get_user_name();
            $post_id = $posts[$i]->get_post_id();
            $post = $posts[$i]->get_post();
            $thread_id = $posts[$i]->get_thread_id();
            $modification_date = empty($posts[$i]->get_modification_date()) 
                ? '' : 'Modificated on: '.get_full_date($posts[$i]->get_modification_date());
            $html .= '
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <ul class="list-inline">
                        <li><a href="user.php?uid='.$user_id.'">'.$user_name.'</a><br />
                        <img src="'.$profile_picture.'" width="90" /></li>
                            '.get_edit_post($post_id, $user_id).'
                            '.get_delete_post($post_id, $user_id).'    
                        <li><p class="normal">'.$creation_date.'<br />
                            '.$modification_date.'</p></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <pre style="white-space: pre-wrap; word-break: normal;">'.$post.'</pre>
                    </div>                       
                </div>';
        }
        return $html;
    }

    function get_new_post(int $thread_id): string {
        if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > -1)
            return '<a href="ne_post.php?tid='.$thread_id.'">New post</a>';
        else 
            return '';
    }

    function edit_thread_html(int $thread_id, int $user_id): string {
        return isset($_SESSION['user_id']) && ($_SESSION['user_id'] == 1  
                        || $_SESSION['user_id'] == $user_id )? 
            '<a href="ne_thread.php?tid='.$thread_id.'">Edit thread</a>' : '';
    }

    function get_delete_post(int $post_id, int $user_id): string {
        if(isset($_SESSION['user_id']) && ($_SESSION['user_id'] == 1 
                    || $_SESSION['user_id'] == $user_id))
            return '
                <li><form action="php/process_post.php" method="POST">
                    <input type="hidden" name="deleted" value="true">
                    <input type="hidden" name="post_id" value="'.$post_id.'">
                    <button type="submit" class="btn btn-default">Delete Post</button>
                </form></li>';
        else 
            return '';
    }

    function get_edit_post(int $post_id, int $user_id): string {
        if(isset($_SESSION['user_id']) && ($_SESSION['user_id'] == 1 
                    || $_SESSION['user_id'] == $user_id))
            return '
                <li><form action="ne_post.php" method="GET">
                    <input type="hidden" name="pid" value="'.$post_id.'" />
                    <button type="submit" class="btn btn-default">Edit post</button>        
                </form></li>';
        else 
            return '';
    }

    function get_delete_thread_form(int $thread_id, int $user_id, int $forum_id): string {
        // Next versino only admin and author can delete it
        if(isset($_SESSION['user_id']) && ($_SESSION['user_id'] == 1 
                    || $_SESSION['user_id'] == $user_id))
            return '<form action="php/process_thread.php" method="POST">
                        <input type="hidden" name="thread_id" value="'.$thread_id.'" />
                        <input type="hidden" name="forum_id" value="'.$forum_id.'" />
                        <input type="hidden" name="delete" value="true" />
                        <input type="submit" value="Delete" />
                    </form>';
        else return '';
    }

    $menu_footer = new MenuFooter();
    $thread_util = new ThreadUtil();
    $other_util = new Other();
    $html = '';
    
    if(isset($_GET['tid'])) {
        $thread = $thread_util->get_thread(intval($_GET['tid']));
        if(isset($thread) && $thread->get_thread_id() > -1 ) {
            $post_util = new PostUtil();
            $posts = $post_util->get_posts($thread->get_thread_id());
            $html .= get_posts_html($posts, intval($_GET['tid']));
        } else {
        $html .= '<p class="lead">Incorrect thread. Please select a correct thread.</p>';    
        }
    } else {
        $html .= '<p class="lead">Incorrect thread. Please select a correct thread.</p>';
    }

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