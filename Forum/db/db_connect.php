<?php
    declare(strict_types=1);
    
    class Connection {
        private $db_host='localhost';
        private $db_user='root';
        private $db_pass='toor';
        private $db_name='forum_db';

        protected function __constructor(){}
    
        // Connecting to the database
        function connect(): mysqli {
            
            $connect_db = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
            if($connect_db->connect_error) {
                printf("Connection failed: %s", $connect_db->connect_error);
                exit();
            }
            return $connect_db;
        }
    }
?>