<?php     
    $idletime=900; //15 minutos
    $idleMsgs=10; //10s
    if (time()-$_SESSION['timestamp']>$idletime){
        unset(
            $_SESSION['usuario'],
            $_SESSION['uid'],
            $_SESSION['timestamp'],
            $_SESSION['userType']
        ); 
        header("Location: ".$default_url."/login/?logout=true");
    } else {
        $_SESSION['timestamp']=time();
    }
?>