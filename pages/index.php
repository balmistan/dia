<?php
error_reporting(E_ALL | E_STRICT); // all = 2147483647  

ini_set('display_error', 1);
ini_set('ignore_repeated_errors', 0);
ini_set('ignore_repeated_source', 0);

session_start();

include("../class/db.class.php");

$post_var = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

$err_msg = '';

if (isset($post_var["username"])) {

    $_SESSION["dbname"] = $post_var["dbname"];
    $_SESSION["hostname"] = $post_var["server"];
    $_SESSION["user"] = $post_var["username"];
    $_SESSION["password"] = $post_var["password"];
    $_SESSION["port"] = $post_var["port"];
    
    $db = new db(true);    // true active debug

 
    if (!$db->isLogged()) {
        $err_msg = "Die Anmeldung am MySQL-Server ist fehlgeschlagen";
    } else {
        header("Location: main.php");
    }
    
    
}
?>



<!DOCTYPE HTML>
<html>
    <head>
        <title>DIA</title>
        <meta charset="UTF-8" />
        <link rel="stylesheet" type="text/css" href="../css/reset.css">
        <link rel="stylesheet" type="text/css" href="../css/login.css">
    </head>

    <body>
        <form class="box login" action="?" method="post">
            <fieldset class="boxBody">
                <label>DB Name</label>
                <input type="text" name="dbname" />
                <label>Server</label>
                <input type="text" name="server" />
                <label>Port</label>
                <input type="text" name="port" />       
                <label>Benutzername</label>
                <input type="text" name="username" />
                <label>Passwort</label>
                <input type="Password" name="password" />
            </fieldset>
            <footer>
                <span class="error"><?php echo $err_msg; ?></span>
                <input type="submit" class="btnLogin" value="Ok" />
            </footer>
        </form>
    </body>
</html>




