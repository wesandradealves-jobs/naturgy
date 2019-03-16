<?php
		include_once("../config/config.php");
		
        unset(
            $_SESSION['usuario'],
            $_SESSION['uid'],
            $_SESSION['timestamp'],
            $_SESSION['processos'],
            $_SESSION['refresh'],
            $_SESSION['userType']
        ); 
        header("Location: ".$default_url."/login/?logout=true");