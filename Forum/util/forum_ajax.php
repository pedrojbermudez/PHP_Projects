<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/category.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/forum.php');
    include_once('category_util.php');
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

    $html = '';
    if(isset($_REQUEST) && isset($_REQUEST["cid"]) && !empty($_REQUEST["cid"])){
        $category_id = $_REQUEST["cid"];
        $cat_util = new CategoryUtil();
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
                    <div class="col-md-10">
                        <a href="forum.php?fid='.$forum->get_forum_id().'">
                            '.$forum->get_name().'
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10">
                        <q>'.$forum->get_description().'</q>
                    </div>
                </div>
                <ul class="list-inline">
                    <li>'.get_edit_forum($forum->get_forum_id()).'</li>
                    <li>'.get_delete_forum($forum->get_forum_id()).'</li>
                </ul><hr />';
            }
            
        } 
    } else {
        $html .= '<p>No category here.</p>';
    }
    echo $html;
?>