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

        function get_bootstrap_js(): string {
            return '<script src="bootstrap/js/bootstrap.min.js"></script>';
        }

        function get_bootstrap_css(): string {
            return '<link href="bootstrap/css/bootstrap.min.css" 
                        rel="stylesheet" media="screen">';
        }
    } 
?>