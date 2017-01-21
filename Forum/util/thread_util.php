<?php
    declare(strict_types=1);

    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/post.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/thread.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/util/post_util.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/db/thread_db.php');

    /**
     * Class to manage Thread object given an object directly or just a 
     * html code
     */
    class ThreadUtil
    {
        private $post_util;
        private $thread_db;

        function __construct() {
            $this->post_util = new PostUtil();
            $this->thread_db = new ThreadDB();
        }

        function new_thread(string $name, string $post, int $forum_id, int $user_id): int {
            $thread_id = $this->thread_db->new_thread($name, $forum_id, $user_id);
            $this->post_util->new_post($post, $thread_id, $user_id);
            return $thread_id;
        }    

        function edit_thread(int $thread_id, string $name, string $forum_id) {
            $this->thread_db->edit_thread($thread_id, $name, $forum_id);
        }

        function delete_thread(int $thread_id){ $this->thread_db->delete_thread($thread_id); }
        function get_thread(int $thread_id): Thread { return $this->thread_db->get_thread($thread_id); }
        function get_threads(int $forum_id): array { return $this->thread_db->get_threads($forum_id); }
        
        /*
            Getting an predeterminate html code to show a thread and its posts.
            The next version should be included a util to create a pagination over
            the posts.
        */
        function get_thread_html(int $thread_id): string {
            if(session_status() == PHP_SESSION_NONE) { session_start(); }
            $thread = $this->get_thread($thread_id);
            $html = '<div id="div_thread">';
            if(isset($thread)) {
                // Thread exists
                $tmp = '';
                $posts = $this->post_util->get_posts($thread->get_thread_id());
                if(isset($posts)) {
                    // Thread has some posts
                    $tmp .= $this->get_posts_html($posts); 
                } else {
                    // No post in the thread
                    $tmp .= '<div class="div_post_list"><p>There is not any post in this thread.</p></div>';
                }
                // Creating html block
                $html .= '
                        <h1>'.$thread->get_thread_name().'</h1>'
                        .$this->edit_thread_html($thread->get_thread_id(), $thread->get_user_id())
                        .$this->get_delete_thread_form($thread->get_thread_id(), $thread->get_user_id(), 
                            $thread->get_forum_id())
                        .$this->get_new_post($thread->get_thread_id())
                        .$tmp;
            } else {
                // Wrong thread
                $html = '<p>Wrong thread</p>';
            }
            $html .= '</div>';
            return $html;
        }

        private function get_posts_html(array $posts): string {
            $max = sizeof($posts);
            $html = '<hr />';
            for ($i=0; $i < $max; $i++) {
                $modification_date = empty($posts[$i]->get_modification_date()) 
                    ? '' : 'Modificated on: '.$posts[$i]->get_modification_date();
                // Next version only admin and the original author can edit
                $html .= '<div class="div_post_list"><p>
                    <div class="div_profile_picture"><img src="'.$posts[$i]->get_profile_picture().'" width="75" />'
                    .'<br /><a href="user.php?uid='.$posts[$i]->get_user_id().'">'.$posts[$i]->get_user_name().'</a></div>'
                    .$this->get_edit_post($posts[$i]->get_post_id(), $posts[$i]->get_user_id())
                    .$this->get_delete_post($posts[$i]->get_post_id(), $posts[$i]->get_user_id())
                    .$posts[$i]->get_post().'</p>
                    <p>Created on: '.$posts[$i]->get_creation_date().'</p>
                    <p>'.$modification_date.'</p></div><hr />';
            }
            return $html;
        }

        private function get_post_functions(int $post_id, int $user_id): string {
            return $this->get_edit_post($post_id, $user_id).$this->get_delete_post($post_id, $user_id);
        }

        private function get_new_post(int $thread_id): string {
            if(isset($_SESSION['user']) && $_SESSION['user']->get_user_id() > -1)
                return '<a href="ne_post.php?tid='.$thread_id.'">New post</a>';
            else 
                return '';
        }

        private function get_edit_post(int $post_id, int $user_id): string {
            if(isset($_SESSION['user']) && ($_SESSION['user']->get_user_id() == 1 
                || $_SESSION['user']->get_user_id() == $user_id))
                return '<p><a href="ne_post.php?pid='.$post_id.'">Edit post</a></p>';
            else 
                return '';
        }

        private function edit_thread_html(int $thread_id, int $user_id): string {
            return isset($_SESSION['user']) && ($_SESSION['user']->get_user_id() == 1  
                || $_SESSION['user']->get_user_id() == $user_id )? 
                    '<p><a href="ne_thread.php?tid='.$thread_id.'">Edit thread</a></p>' : '';
        }

        private function get_delete_post(int $post_id, int $user_id): string {
            if(isset($_SESSION['user']) && ($_SESSION['user']->get_user_id() == 1 
                || $_SESSION['user']->get_user_id() == $user_id))
                return '<p><form action="php/process_post.php" method="POST">
                            <input type="hidden" name="deleted" value="true">
                            <input type="hidden" name="post_id" value="'.$post_id.'">
                            <input type="submit" value="Delete post">
                        </form></p>';
            else 
                return '';
        }

        private function get_delete_thread_form(int $thread_id, int $user_id, int $forum_id): string {
            // Next versino only admin and author can delete it
            if(isset($_SESSION['user']) && ($_SESSION['user']->get_user_id() == 1 
               || $_SESSION['user']->get_user_id() == $user_id))
                return '<form action="php/process_thread.php" method="POST">
                            <input type="hidden" name="thread_id" value="'.$thread_id.'" />
                            <input type="hidden" name="forum_id" value="'.$forum_id.'" />
                            <input type="hidden" name="delete" value="true" />
                            <input type="submit" value="Delete" />
                        </form>';
            else return '';
        }
    }    
?>