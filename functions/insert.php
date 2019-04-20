<?php 
    require_once("../config/config.php");
    require_once("../functions/functions.php");

    if($_POST){
        foreach ($_POST as $key => $value) {
            $arr = explode('&', $value);
            foreach ($arr as $k => $v) {
                $vals = explode('=', $v);
                ${$vals[0]} = $vals[1];
            }
        }

        if($table == 'subfamilia'){
            $query = "SELECT * FROM `subfamilia` WHERE subfamilia LIKE '%".$subfamilia."%'";
        } elseif($table == 'processos_tipos'){
            $query = "SELECT * FROM `processos_tipos` WHERE tipo LIKE '%".$tipo."%'";
        }

        $result = mysqli_fetch_assoc(mysqli_query($conn, $query));

        if(!isset($result)) {
            if($table == 'subfamilia'){
                $hom = (isset($homologavel)) ? 1 : 0;

                $ins = "INSERT INTO subfamilia (subfamilia, nivel_de_risco, homologavel, arc) VALUES ('".$subfamilia."','".$nivel_de_risco."',".$hom.",'".$arc."')";
            } elseif($table == 'processos_tipos'){
                $ins = "INSERT INTO processos_tipos (tipo) VALUES ('".$tipo."')";
            }

            if(mysqli_query($conn, $ins)){
                print_r(json_encode( array('SQL'=>true )));
            } else {
                print_r(json_encode( array('SQL'=>false )));
            }         
        }
    }

