<?php 
    require_once("../config/config.php");
    require_once("../functions/functions.php");

    $query = 'DELETE FROM '.$_GET['table'].' WHERE id = '.$_GET['id'];

    if(mysqli_query($conn, $query)) {
        if($_GET['table'] == 'users'){
            $query_processos = 'DELETE FROM processos WHERE uid = '.$_GET['id']; 
            $query_responsaveis = 'DELETE FROM responsavel_by_processos WHERE uid = '.$_GET['id'];
            $query_rodadas = 'DELETE FROM rodadas WHERE uid = '.$_GET['id'];
            $query_prorrogacao = 'DELETE FROM prorrogacao_by_rodadas WHERE uid = '.$_GET['id'];
            $query_sociedade = 'DELETE FROM sociedades_by_processos WHERE uid = '.$_GET['id'];                

            mysqli_query($conn, $query_responsaveis);
            mysqli_query($conn, $query_rodadas);
            mysqli_query($conn, $query_processos);
            mysqli_query($conn, $query_prorrogacao);
            mysqli_query($conn, $query_sociedade);

            header("Location: ".$default_url."/usuarios/?deleted=true");
        } elseif($_GET['table'] == 'processos'){
            $query_responsaveis = 'DELETE FROM responsavel_by_processos WHERE pid = '.$_GET['id'];
            $query_rodadas = 'DELETE FROM rodadas WHERE pid = '.$_GET['id'];
            $query_prorrogacao = 'DELETE FROM prorrogacao_by_rodadas WHERE pid = '.$_GET['id'];
            $query_sociedade = 'DELETE FROM sociedades_by_processos WHERE pid = '.$_GET['id'];
            mysqli_query($conn, $query_prorrogacao);
            mysqli_query($conn, $query_responsaveis);
            mysqli_query($conn, $query_rodadas);
            mysqli_query($conn, $query_sociedade);

            header("Location: ".$default_url."/processos/?deleted=true");
        } elseif($_GET['table'] == 'rodadas'){
            $query_rodadas = 'DELETE FROM rodadas WHERE pid = '.$_GET['pid'].' AND position = '.$_GET['position'];

            if(mysqli_query($conn, $query_rodadas)){
                header("Location: ".$default_url."/processo/".$_GET['uid']."/".$_GET['pid']."/?deleted=true"); 
            }
        } elseif($_GET['table'] == 'responsavel_by_processos'){
            $query_responsaveis = 'DELETE FROM responsavel_by_processos WHERE pid = '.$_GET['pid'].' AND responsavel = '.$_GET['id'];

            if(mysqli_query($conn, $query_responsaveis)){
                header("Location: ".$default_url."/processo/".$_GET['uid']."/".$_GET['pid']."/?deleted=true"); 
            }
        }
    } 
    
