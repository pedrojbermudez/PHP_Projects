<?php
    declare(strict_types=1);

    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/data/user.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Forum/db/user_db.php');

    /**
     * Class to manage Thread object given an object directly or just a 
     * html code
     */
    class UserUtil
    {
        private $user_db;

        function __construct() {
            $this->user_db = new UserDB();
        }

        function new_user(string $json) {
            $this->user_db->new_user($json);
        }

        function edit_user(int $user_id, string $name, string $surname, string $country, string $state, 
            string $city, string $profile_picture = 'images/default.jpg') {
            $this->user_db->edit_user($user_id, $name, $surname, $country, $state, 
                $city, $profile_picture);
        }

        function delete_user(int $user_id) { $this->user_db->delete_user($user_id); }
        
        function get_user(int $user_id, int $level = 2) {
            $content_type = $_SERVER['HTTP_ACCEPT'];
            $status_code;
            $status_message;
            $level_url = '';
            for($i=0; $i < $level; $i++) {
                $level_url .= '../';
            }
            $user = $this->user_db->get_user($user_id);
            if($user['user_id'] > -1) {
                $status_code = 200;
                $status_message = 'OK';
                header('HTTP/1.1 '.$status_code.' '.$status_message); 
                if(strpos($content_type,'application/json') !== false){
                    header('Content-Type:application/json');
                    return json_encode($user);
                } else if(strpos($content_type,'text/html') !== false){
                    header('Content-Type:text/html');
                    // Bootstrap
                    return '
                        <div class="row">
                            <div class="col-md-12">
                                <h1>'.$user['user_name'].'</h1><br />
                                <img src="'.$level_url.$user['profile_picture'].'" width="250" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p>'.$user['name'].'</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p>'.$user['surname'].'</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p>'.$user['country'].'</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p>'.$user['state'].'</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p>'.$user['city'].'</p>
                            </div>
                        </div>
                        ';
                } else if(strpos($content_type,'application/xml') !== false) {
                    header('Content-Type:application/xml');
                    $xml = new SimpleXMLElement('<?xml version="1.0"?><user></user>');
                    foreach($user as $key=>$value) {
                        $xml->addChild(strval($key), strval($value));
                    }
                    return $xml->asXML();
                }
            } else {
                $status_code = 404;
                $status_message = 'Not found';
            header('HTTP/1.1 '.$status_code.' '.$status_message);
            header('Content-Type:'.$content_type); 
                $user['error'] = 'No user found.';
            }
            
        }

        // Check if user name exists in database
        function user_exists(string $user_name): bool { return $this->user_db->user_exists($user_name); }
        
        // Check if email exists in database
        function email_exists(string $email): bool { return $this->user_db->email_exists($email); }
        
        // Return an User object with data filled.
        function get_user_login(string $user_name, string $password): User { 
            return $this->user_db->get_user_login($user_name, $password); 
        }

        // Check the password given in the database
        function check_passowrd(string $user_name, string $password): bool { 
            return $this->user_db->check_passowrd($user_name, $password);
        }
    } 
?>