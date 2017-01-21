<?php
    declare(strict_types=1);

    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/forum.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/thread.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/db/forum_db.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/util/thread_util.php');
    

    /**
     * Class to manage Forum object given an object directly or just a 
     * html code
     */
    class ForumUtil
    {
        private $forum_db;
        private $thread_util;

        function __construct() {
            $this->forum_db = new ForumDB();
            $this->thread_util = new ThreadUtil();
        }

        function new_forum(string $name, string $description, int $category_id): int {
            return $this->forum_db->new_forum($name, $description, $category_id); 
        }
        function edit_forum(int $forum_id, string $name, string $description, int $category_id) { 
            $this->forum_db->edit_forum($forum_id, $name, $description, $category_id); 
        }

        function delete_forum(int $forum_id) { $this->forum_db->delete_forum($forum_id); }
        function get_forum(int $forum_id): Forum { return $this->forum_db->get_forum($forum_id); }

        /*
            Getting an predeterminate html code to show a forum and its threads.
            The next version should be included a util to create a pagination over
            the threads.
        */
        function get_forum_html(int $forum_id): string {
            $forum = $this->get_forum($forum_id);
            $html = '<div id="div_forum">';
            if(isset($forum)) {
                // Forum exists
                $tmp = '<hr />';
                $threads = $this->thread_util->get_threads($forum->get_forum_id());
                if(isset($threads)) {
                    // Forum has some threads
                    $max = sizeof($threads);
                    for ($i=0; $i < $max; $i++) { 
                        echo $threads[$i]->get_thread_id();
                        $tmp .= '<div class="div_thread_list">'
                            .$this->delete_thread_form($threads[$i]->get_thread_id()).
                            '<p>
                                <a href="thread.php?tid='.$threads[$i]->get_thread_id().'">'
                                .$threads[$i]->get_thread_name().'</a>'
                                .$this->edit_thread_html($threads[$i]->get_thread_id(), 
                                    $threads[$i]->get_user_id())
                            .'<br /><q>Created by: <a href="user.php?uid='.$threads[$i]->get_user_id().'">'
                            .$threads[$i]->get_user_name().'</a></q></p></div><hr />';
                    }
                } else {
                    // No forum in that category
                    $tmp .= '<div class="div_thread_list"><p>There is not any thread in this forum.</p></div>';
                }
                // Creating html block
                $html .= '
                        <h1>'.$forum->get_name().'</h1>
                        <q>'.$forum->get_description().'</q><br />
                        <p>'.$this->delete_forum_form($forum->get_forum_id())
                        .$this->edit_forum_html($forum->get_forum_id())
                        .$this->new_thread($forum->get_forum_id()).'
                        '.$tmp;
            } else {
                // Wrong forum id
                $html = '<p>Wrong forum</p>';
            }
            $html .= '</div>';
            return $html;
        }

        private function new_thread($forum_id): string {
            return isset($_SESSION['user']) && $_SESSION['user']->get_user_id() > -1 ? 
                    '<p><a href="ne_thread.php?fid='.$forum_id.'">New thread</a></p>' : '';
        }

        private function edit_forum_html(int $forum_id): string {
            return isset($_SESSION['user']) && $_SESSION['user']->get_user_id() == 1 ? 
                    '<p><a href="ne_forum.php?fid='.$forum_id.'">Edit Forum</a></p>' : '';
        }

        private function edit_thread_html(int $thread_id, int $user_id): string {
            return isset($_SESSION['user']) && ($_SESSION['user']->get_user_id() == 1  
                || $_SESSION['user']->get_user_id() == $user_id )? 
                    '<p><a href="ne_thread.php?tid='.$thread_id.'">Edit thread</a></p>' : '';
        }

        private function delete_thread_form(int $thread_id): string {
            return isset($_SESSION['user']) && $_SESSION['user']->get_user_id() == 1 ?
                    '<form action="php/process_thread.php" method="POST">
                        <input type="hidden" name="thread_id" value="'.$thread_id.'" />
                        <input type="hidden" name="delete" value="true">
                        <input type="submit" value="Delete">
                    </form>' : '';
        }

        private function delete_forum_form(int $forum_id): string {
            return isset($_SESSION['user']) && $_SESSION['user']->get_user_id() == 1 ?
                    '<form action="php/process_forum.php" method="POST">
                        <input type="hidden" name="forum_id" value="'.$forum_id.'" />
                        <input type="hidden" name="delete" value="true">
                        <input type="submit" value="Delete Forum">
                    </form>' : '';
        }
    }    
?>