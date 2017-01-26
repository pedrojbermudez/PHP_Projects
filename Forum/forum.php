<?php
    declare(strict_types=1);

    include_once('util/menu.php');
    include_once('util/other.php');
    include_once('util/forum_util.php');
    include_once('util/thread_util.php');
    
    if(session_status() == PHP_SESSION_NONE) { session_start(); }

    $menu_footer = new MenuFooter();
    $forum_util = new ForumUtil();
    $other_util = new Other();
    $html = '';
    
    function new_thread($forum_id): string {
        return isset($_SESSION['user_id']) && $_SESSION['user_id'] > -1 ? 
            '<form action="ne_thread.php" method="GET">
                <input type="hidden" name="fid" value="'.$forum_id.'" />
                <button type="submit" class="btn btn-default">
                    New Thread
                </button>
            </form>' : '';
    }

    function get_edit_forum(int $forum_id): string {
        return isset($_SESSION['user_id']) && intval($_SESSION['user_id']) == 1 ?
            '<form action="ne_forum.php" method="GET">
                <input type="hidden" name="fid" value="'.$forum_id.'" />
                <button type="submit" class="btn btn-default">
                    Edit forum
                </button>
            </form>' : '';
    }

    function get_edit_thread(int $thread_id, int $user_id): string {
        return isset($_SESSION['user_id']) && ($_SESSION['user_id'] == 1  
            || $_SESSION['user_id'] == $user_id ) ?
                '<form action="ne_thread.php" method="GET">
                    <input type="hidden" name="tid" value="'.$thread_id.'" />
                    <button type="submit" class="btn btn-default">
                        Edit thread
                    </button>
                </form>' : '';
    }

    function get_delete_forum(int $forum_id): string {
        return isset($_SESSION['user_id']) && intval($_SESSION['user_id']) == 1 ?
            '<form action="php/process_forum.php" method="POST">
                <input type="hidden" name="forum_id" value="'.$forum_id.'" />
                <button type="submit" class="btn btn-default">
                    Delete forum
                </button>
            </form>' : '';
    }

    function get_delete_thread(int $thread_id): string {
        return isset($_SESSION['user_id']) && ($_SESSION['user_id'] == 1  
            || $_SESSION['user_id'] == $user_id ) ?
                '<form action="php/process_thread.php" method="POST">
                    <input type="hidden" name="thread_id" value="'.$thread_id.'" />
                    <input type="hidden" name="delete" value="true">
                    <button type="submit" class="btn btn-default">
                        Delete thread
                    </button>
                </form>' : '';
    }

    function get_threads(int $forum_id): string {
        $thread_util = new ThreadUtil();        
        $threads = $thread_util->get_threads($forum_id);
        $code = '';
        if(isset($threads)) {
            // Forum has some threads
            foreach ($threads as $thread) {
                $code .= '
                    <ul class="list-inline">
                        <li>
                            <a href="thread.php?tid='.$thread->get_thread_id().'">   
                                '.$thread->get_thread_name().' 
                            </a><br />
                            <span class="small">
                                Created by: 
                                <a href="user.php?uid='.$thread->get_user_id().'">
                                    '.$thread->get_user_name().'
                                </a>
                                <hr />
                            </span>
                        </li>
                        <li>
                            '.get_edit_thread($thread->get_thread_id(), $thread->get_user_id()).'
                        </li>
                        <li>
                            '.get_delete_thread($thread->get_thread_id()).'
                        </li>
                    </ul>';
            }    
        } else {
            // No thread in that forum
            $code .= '<p>There is not any thread in this forum.</p>';
        }
        return $code;
    }

    if($_GET['fid']) {
        $forum = $forum_util->get_forum(intval($_GET['fid']));
        if($forum->get_forum_id() > -1) {
            $html .= 
                '<h1>'.$forum->get_name().'</h1>
                <q>'.$forum->get_description().'</q>
                <ul class="list-inline">
                    <li>'.get_edit_forum($forum->get_forum_id()).'</li>
                    <li>'.get_delete_forum($forum->get_forum_id()).'</li>
                </ul><hr />'
                .get_threads($forum->get_forum_id());
        } else {
            $html .= '<p>You must select a correct forum</p>';
        }
    } else {
        $html .= '<p>You must select a forum</p>';
    }

    echo '<!DOCTYPE html>
    <html lang="en">
        <head>
            <title></title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link href="css/style.css" rel="stylesheet">
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