<?php
    declare(strict_types=1);

    include_once('data/category.php');
    include_once('data/forum.php');
    include_once('util/category_util.php');
    include_once('util/menu.php');
    include_once('util/other.php');

    $menu_footer = new MenuFooter();
    $cat_util = new CategoryUtil();
    $other_util = new Other();
    $html = '';
    
    if(session_status() == PHP_SESSION_NONE) { session_start(); }

    function get_edit_category(int $category_id): string {
        return isset($_SESSION['user_id']) && intval($_SESSION['user_id']) == 1 ?
            '<form action="ne_category.php" method="GET">
                <input type="hidden" name="cid" value="'.$category_id.'" />
                <button type="submit" class="btn btn-default">
                    Edit category
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

    function get_delete_category(int $category_id): string {
        return isset($_SESSION['user_id']) && intval($_SESSION['user_id']) == 1 ?
            '<form action="php/process_category.php" method="POST">
                <input type="hidden" name="category_id" value="'.$category_id.'" />
                <button type="submit" class="btn btn-default">
                    Delete category
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

    $category_id = isset($_GET['cid']) ? intval($_GET['cid']) : -1;
    if($category_id > -1) {
        $category = $cat_util->get_category($category_id);
        if(isset($category) && $category->get_forum_id() > -1) {
            $html .= '
                <h1>'.$category->get_name().'</h1>
                <q>'.$category->get_description().'</q>
                <ul class="list-inline">
                    <li>'.get_edit_category($category->get_forum_id()).'</li>
                    <li>'.get_delete_category($category->get_forum_id()).'</li>
                </ul><hr />';
            foreach($category->get_forums() as $forum) {
                $html .= '
                <div class="row">
                    <div class="col-md-2">
                        <a href="forum.php?fid='.$forum->get_forum_id().'">
                            '.$forum->get_name().'
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <q>'.$forum->get_description().'</q>
                    </div>
                </div>
                <ul class="list-inline">
                    <li>'.get_edit_forum($forum->get_forum_id()).'</li>
                    <li>'.get_delete_forum($forum->get_forum_id()).'</li>
                </ul><hr />';
            }
        } else {
            $html .= '<p>Incorrect category. Please select a correct category</p>';  
        }
    }
    else {
        $html .= '<p>You must select a category</p>';
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
                '.$menu_footer->get_menu($category_id).'
                '.$html.'
                '.$menu_footer->get_footer().'
            </div>
        </body>
    </html>';
?>
