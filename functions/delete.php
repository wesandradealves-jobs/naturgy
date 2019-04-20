<?php 
    require_once("../config/config.php");
    require_once("../functions/functions.php");

    if($_GET){
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
            } elseif($_GET['table'] == 'responsavel_by_processos'){
                $query_responsaveis = 'DELETE FROM responsavel_by_processos WHERE pid = '.$_GET['pid'].' AND responsavel = '.$_GET['id'];

                if(mysqli_query($conn, $query_responsaveis)){
                    header("Location: ".$default_url."/processo/".$_GET['uid']."/".$_GET['pid']."/?deleted=true"); 
                }
            }
        } 
    } elseif($_POST){
        if($_POST['table'] != 'rodadas'){
            $query = 'DELETE FROM '.$_POST['table'].' WHERE id = '.$_POST['id'];
            
            $check = "SELECT * FROM `".$_POST['table']."` WHERE id = ".$_POST['id'];
            $res_data = mysqli_query($conn,$check);

            if($_POST['table'] == 'subfamilia'){
                while($row = mysqli_fetch_array($res_data)) :
                    $res = $row['subfamilia'].' | Homologável: '.$row['homologavel'].' | Nível de Risco: '.$row['nivel_de_risco'].' | ARC: '.$row['arc'];
                endwhile;     

                $check_processo = "SELECT * FROM processos WHERE subfamilia = '".$res."'"; 
                $res_data_processo = mysqli_query($conn,$check_processo); 

                while($r = mysqli_fetch_array($res_data_processo)) :
                    $pid = $r['id'];
                endwhile;                   

                $update = "UPDATE processos SET subfamilia = '' WHERE `processos`.`subfamilia` = '".$res."'";  

                mysqli_query($conn, $update); 
            } elseif($_POST['table'] == 'processos_tipos'){
                while($row = mysqli_fetch_array($res_data)) :
                    $res = $row['tipo'];
                endwhile;     

                $check_processo = "SELECT * FROM processos WHERE tipo_processo = '".$res."'"; 
                $res_data_processo = mysqli_query($conn,$check_processo); 

                while($r = mysqli_fetch_array($res_data_processo)) :
                    $pid = $r['id'];
                endwhile;                   

                $update = "UPDATE processos SET tipo_processo = '' WHERE `processos`.`tipo_processo` = '".$res."'";  

                mysqli_query($conn, $update); 
            }
        } else {
            $query = 'DELETE FROM '.$_POST['table'].' WHERE pid = '.$_POST['pid'].' AND position = '.$_POST['position'];
        }
        print_r(json_encode( array('SQL'=>mysqli_query($conn, $query) ) ));     
    }


