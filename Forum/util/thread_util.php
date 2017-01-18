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
            $thread = $this->get_thread($thread_id);
            $html = '<div id="div_thread">';
            if(isset($thread)) {
                // Forum exists
                $tmp = '<hr />';
                $posts = $this->post_util->get_posts($thread->get_thread_id());
                if(isset($posts)) {
                    // Forum has some threads
                    $max = sizeof($posts);
                    for ($i=0; $i < $max; $i++) {
                        $modification_date = empty($posts[$i]->get_modification_date()) 
                            ? '' : 'Modificated on: '.$posts[$i]->get_modification_date();
                        // Next version only admin and the original author can edit
                        $tmp .= '<div class="div_post_list"><p><a href="ne_post.php?pid='
                            .$posts[$i]->get_post_id().'">Edit<br /></a>'
                            .$posts[$i]->get_post().'</p><div class="div_profile_picture"><img src="'
                            .$posts[$i]->get_profile_picture().'" width="250" />'
                            .'<a href="user.php?uid='.$posts[$i]->get_user_id().'">'
                            .$posts[$i]->get_user_name().'</a></div><p>Created on: '
                            .$posts[$i]->get_creation_date().'</p><p>'
                            .$modification_date.'</p></div><hr />';
                    }
                } else {
                    // No forum in that category
                    $tmp .= '<div class="div_post_list"><p>There is not any thread in this forum.</p></div>';
                }
                // Creating html block
                $html .= '
                        <h1>'.$thread->get_thread_name().'</h1>
                        <a href="ne_thread.php?tid='.$thread->get_thread_id().'">Edit thread</a>
                        '.$this->delete_thread_form($thread->get_thread_id()).'
                        <a href="ne_post.php?tid='.$thread->get_thread_id().'">New post</a>
                        '.$tmp;
            } else {
                // Wrong forum id
                $html = '<p>Wrong forum</p>';
            }
            $html .= '</div>';
            return $html;
        }

        private function delete_thread_form(int $thread_id): string {
            // Next versino only admin and author can delete it
            return '<form action="php/process_thread.php" method="POST">
                        <input type="hidden" name="thread_id" value="'.$thread_id.'" />
                        <input type="hidden" name="delete" value="true">
                        <input type="submit" value="Delete">
                    </form>';
        }
    }    
?>