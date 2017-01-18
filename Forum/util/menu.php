<?php
    include_once('category_util.php');
    /**
     * Class to create a menu bar and the footer
     */
    class MenuFooter {

        public function __construct() {}

        public function get_menu(): string {
            $category_util = new CategoryUtil();
            $menu = '<div><span><a href="index.php">Home</a></span>'.$category_util->get_categories_menu().'</div>';
            if(session_status() == PHP_SESSION_NONE) { session_start(); }
            $admin = isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1;
            if($admin)
                $menu .= '
                    <div id="div_menu_admin">
                        <a href="ne_category.php">New Category</a>
                        <a href="ne_forum.php">New Forum</a>
                    </div>';
            if(!session_id() || !isset($_SESSION['user_id']))
                $menu .= '<div id="div_menu_login">
                    <form action="php/process_login.php" method="POST">
                        <input type="text" name="login_user_name" placeholder="User name" required>
                        <input type="password" name="login_password" placeholder="Password" required>
                        <input type="submit" name="" value="Login">
                    </form>
                    <a href="ne_user.php">New user</a>
                    </div>';
            else
                $menu .='
                    <div id="div_user_control">
                        <span>
                            <a href="user.php?uid='
                            .$_SESSION['user_id'].'">Welcome '.$_SESSION['user_name'].'
                            </a>
                        </span>
                        <span><a href="ne_user.php?uid='.$_SESSION['user_id'].'">Edit</a></span>
                        <span><a href="logout.php">Logout</a></span>
                    </div>';
            return $menu;
        }

        public function get_footer(string $name = 'Administrator'): string {
            return '<div id="div_footer"><span id="span_footer_content">Webpage create by '.$name.'</span></div>';
        }
    }
?>