<?php 
    include_once("../config/config.php");

    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']); 
    $senha = md5(mysqli_real_escape_string($conn, $_POST['senha']));
    $query = "SELECT * FROM users WHERE usuario = "."'".$usuario."'"." AND senha = '".$senha."'";
    $result = mysqli_fetch_assoc(mysqli_query($conn, $query));
    if($result){
        $_SESSION['usuario'] = $result['usuario'];
        $_SESSION['uid'] = $result['id'];
        $_SESSION['userType'] = $result['userType'];
        $_SESSION['timestamp'] = time();    
        header("Location: ".$default_url."/login");       
    } else {
        header("Location: ".$default_url."/login/?erro=true"); 
    }