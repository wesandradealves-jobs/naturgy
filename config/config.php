<?php
    session_start();
    header('Content-Type: text/html; charset=UTF-8');  
    
    $ENV = 'dev';
    
    if($ENV != 'prod'){
        $servidor = "localhost";
        $usuario = "root";
        $senha = "";
        $dbname = "naturgy";
        $default_url = 'http://comview.com.br'; 
    } else {
        $servidor = "23.111.163.66";
        $usuario = "comview_2019";
        $senha = "comview_2019";
        $dbname = "comview_2019";
        $default_url = 'http://comview.aaminformatica.com.br';        
    }
  
    //Criar a conexao
    $conn = mysqli_connect($servidor, $usuario, $senha, $dbname);
    
    if(!$conn){
        die("Falha na conexao: " . mysqli_connect_error());
    }else{
        //echo "Conexao realizada com sucesso";
    }     
    mysqli_set_charset($conn,"utf8");
?>