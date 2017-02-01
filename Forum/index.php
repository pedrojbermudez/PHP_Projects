<?php
    declare(strict_types=1);
    
    session_start();

    include_once('util/menu.php');
    include_once('util/thread_util.php');
    include_once('util/other.php');

    function get_thread_list(): string {
        $html = '<div id="thread_list">';
        $thread_util = new ThreadUtil();
        $threads = $thread_util->get_30_threads_html();
        foreach ($threads as $thread) {
            $thread_id = $thread->get_thread_id();
            $thread_name = $thread->get_thread_name();
            $forum_id = $thread->get_forum_id();
            $forum_name =$thread->get_forum_name();
            $user_id =$thread->get_user_id();
            $user_name =$thread->get_user_name();
            $html .= '
                <div class="row">
                    <div class="row">
                        <div class="col-md-10">
                            <a href="forum.php?fid='.$forum_id.'">'.$forum_name.'</a>: 
                            <a href="thread.php?tid='.$thread_id.'">'.$thread_name.'</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <a href="user/view/'.$user_id.'">'.$user_name.'</a>
                        </div>
                        <hr />
                    </div>
                </div>';
        }
        return $html.'</div>';
    }
    
    $other_util = new Other();
    
    
    $threads = get_thread_list();
    $menu_footer = new MenuFooter();
    // $time_start = microtime(true)
    // $time_end = microtime(true);
    // echo 'Time execution: '.($time_end - $time_start).'<br />';
    echo '<!DOCTYPE html>
    <html lang="en">
        <head>
            <title>Home Page</title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            '.$other_util->get_bootstrap_css().'
        </head>
        <body style="padding-top: 70px;">
            '.$other_util->get_jquery().'
            '.$other_util->get_bootstrap_js().'
            <div class="container">
                '.$menu_footer->get_menu().'
                <div id="forum_list" style="display: none"></div>
                '.$threads.'
                '.$menu_footer->get_footer('Pedro').'    
            </div>
            <script type="text/javascript" src="js/ajax.js"></script>
            <script type="text/javascript" src="js/show_hide.js"></script>
        </body>
    </html>';
?>