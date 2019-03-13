<?php 
    require_once("../config/config.php");
    require_once("../functions/functions.php");

   	$query_user = "SELECT * FROM users WHERE `users`.`id` = '".$_GET['id']."'";
    
    $user = mysqli_fetch_assoc(mysqli_query($conn, $query_user));
    
    $query_enable = "UPDATE users SET enabled = ".(($user['enabled']=='1') ? 0 : 1)."  WHERE `users`.`id` = ".$_GET['id'];
    
    if(mysqli_query($conn, $query_enable)) :
        header("Location: ".$default_url."/usuarios/?".( ($user['enabled']=='1') ? 'disabled' : 'enabled' )."=true");
    endif;
