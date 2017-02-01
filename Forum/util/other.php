<?php
    declare(strict_types=1);
    
    /**
     * This is other utilities
     */
    class Other {
        function __construct() { }

        function get_jquery(): string {
            return '<script src="http://code.jquery.com/jquery.js"></script>';
        }

        function get_bootstrap_js(int $level = 0): string {
            $js = '<script src="';
            for($i=0;$i<$level;$i++){
                $js .= '../';
            }
            return $js.'bootstrap/js/bootstrap.min.js"></script>';
        }

        function get_bootstrap_css(int $level = 0): string {
           $css = '<link href="';
           for($i=0;$i<$level;$i++){
                $css .= '../';
            }
            return $css.'bootstrap/css/bootstrap.min.css" 
                        rel="stylesheet" media="screen">';
        }
    } 
?>