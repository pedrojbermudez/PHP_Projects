<?php
    session_start();
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], 
            $params["domain"], $params["secure"], $params["httponly"]);
        
    }
    session_destroy();
    echo '<script type="text/javascript">
        window.alert("You are off right now. Please come back soon, thank you.");
        window.location.href="../index.php"</script>';
?>