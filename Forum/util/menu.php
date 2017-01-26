<?php
    include_once('category_util.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/user.php');

    /**
     * Class to create a menu bar and the footer
     */
    class MenuFooter {
        private $category_util;

        public function __construct() {
            $this->category_util = new CategoryUtil();
        }

        private function get_categories(int $category_id_check): string {
            $categories = $this->category_util->get_categories();
            $html = '';
            foreach($categories as $category) {
                $category_id = $category->get_forum_id();
                $name = $category->get_name();
                if($category_id == $category_id_check) {
                    $html .= '
                        <li class="active">
                            <a href="category.php?cid='.$category_id.'">
                                '.$name.'
                            </a>
                        </li>';
                } else {
                    $html .= '
                        <li>
                            <a href="category.php?cid='.$category_id.'">
                                '.$name.'
                            </a>
                        </li>';
                }
            }
            return $html;
        }


        public function get_menu(int $category_id = -1): string {
            $menu = '
                <nav class="navbar navbar-default navbar-fixed-top">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" 
                                    class="navbar-toggle collapsed" 
                                    data-toggle="collapse" 
                                    data-target="#menu-navbar"
                                    aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a href="index.php" class="navbar-brand">Home</a>
                        </div>
                        <div class="collapse navbar-collapse" 
                                id="menu-navbar">
                            <ul class="nav navbar-nav">
                                '.$this->get_categories($category_id).'
                            </ul>
                            '.$this->get_login_html().'
                            '.$this->get_user_info().'
                            '.$this->get_new_category_forum().'
                        </div>
                    </div>
                </nav><br />';
                return $menu;
         }

        public function get_footer(string $name = 'Administrator'): string {
            return '<div class="row"><div class="col-md-12">Webpage create by '.$name.'</div></div>';
        }

        private function get_login_html():string {
            return isset($_SESSION['user_id']) && intval($_SESSION['user_id']) > -1 ? 
            '' : '<ul class="nav navbar-nav navbar-right">
                    <li>
                        <form class="navbar-form" action="php/process_login.php" 
                                method="POST">
                            <div class="form-group">
                                <input type="text" name="login_user_name" 
                                        placeholder="User name" required />
                                <input type="password" name="login_password" 
                                        placeholder="Password" required />
                                <button type="submit" class="btn btn-default">
                                        Login</button>
                                <a href="ne_user.php">New user</a>
                            </div>
                        </form>
                    </li>
                </ul>';
        }

        private function get_new_category_forum(): string {
            return isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1 ?
            '<ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" 
                            role="button" aria-haspopup="true" 
                            aria-expanded="false">
                        New
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="ne_category.php">Category</a>
                        </li>
                        <li>
                            <a href="ne_forum.php">Forum</a>
                        </li>
                        
                    </ul>
                </li>
            </ul>' : '';
        }

        // Get the user name as an anchor to see more details
        private function get_user_info(): string {
            return isset($_SESSION['user_id']) && intval($_SESSION['user_id']) > -1 
                        && isset($_SESSION['user_name']) ?
                '<ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" 
                                role="button" aria-haspopup="true" 
                                aria-expanded="false">
                            Welcome '.$_SESSION['user_name'].'
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="ne_user.php?uid='.$_SESSION['user_id'].'">
                                    Edit
                                </a>
                            </li>
                            <li>
                                <a href="logout.php">Logout</a>
                            </li>
                            
                        </ul>
                    </li>
                </ul>' : '';
        }
    }
?>