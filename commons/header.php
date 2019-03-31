<?php 
  require_once('config/config.php');
  require_once('functions/functions.php');

  date_default_timezone_set('America/Sao_Paulo');

  $basename = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF']))-4);

  if($basename != 'index' && $basename != 'reset') {
    require_once('functions/expire.php');

    if(!isset($_SESSION['usuario'])){
        header("Location: ".$default_url."/login");
    }
  } else {
    if(isset($_SESSION['usuario'])){
        header("Location: ".$default_url."/processos");
    }
  }

  if($basename == 'usuarios' && $_SESSION['userType'] != 'administrador'){
        header("Location: ".$default_url);
  }   
?>
<!DOCTYPE html>
<html lang="pt-br" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Naturgy Comview <?php echo (isset($_SESSION['usuario'])) ? ' - Bem vindo(a) '.$_SESSION['usuario'] : ''; ?></title>
    <meta charset="UTF-8">
    <base href="<?php echo $default_url; ?>/" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="Wesley Andrade - github.com/wesandradealves">
    <!-- <meta name="description" content=""> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!--     <meta name="keywords" content="">
    <meta property="og:description" content="">
-->
    <meta property="og:title" content="Naturgy Comview">
    <meta property="og:url" content="<?php echo $default_url; ?>">
    <meta property="og:site_name" content="Naturgy Comview">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?php echo $default_url.'/screenshot.png'; ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="true">
    <link rel="canonical" href="<?php echo $default_url; ?>">
    <link rel="apple-touch-icon" href="<?php echo $default_url.'/favico.png'; ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo $default_url.'/favico.png'; ?>"> 
  </head>
  <body class="pg-login <?php echo ($basename != 'index') ? 'pg-dashboard pg-'.$basename : ''; ?>"> 
    <div id="wrap">
      <?php 
        if(strpos( $_SERVER['REQUEST_URI'], '?' ) !== false){
          ?>
          <div class="messageBar">
            <span>            
          <?php
            switch (explode('=',substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], "?") + 1))[0]) {
              case 'updated':
                echo 'Atualizado com sucesso.';
                break;
              case 'exist':
                echo 'Já existe cadastro semelhante.';
                break;
              case 'sent':
                echo 'E-mail enviado com sucesso.';
                break;
              case 'error':
              case 'erro':
                echo 'Ocorreram erros ou dados inválidos.';
                break;
              case 'logout':
                echo 'Você foi deslogado do sistema.';
                break;
              case 'deleted':
                echo 'Deletado com sucesso.';
                break;
              case 'registered':
                echo 'Cadastrado com sucesso.';
                break;
              case 'enabled':
                echo 'Ativo com sucesso.';
                break;
              case 'disabled':
                echo 'Desativado com sucesso.';
                break;                                                                                              
              default:
                // code...
                break;
            }
            ?>
                </span>
                <a href="javascript:void(0)" onclick="closeMessage(this)" class="fal fa-close"></a>
              </div>            
              <?php
        }
      ?>
      <header id="header">
        <?php 
      if(isset($_SESSION['usuario'])) :
        ?>
        <div class="topbar">
          <div class="container">
            <?php 
              echo '<p>Bem vindo(a) <a href="usuario/'.$_SESSION['uid'].'">'.$_SESSION['usuario'].'</a> (<a class="logout"  href="'.$default_url.'/functions/logout.php">Logout</a>)</p>';
            ?>
          </div>
        </div>
        <?php
      endif;
        ?>
        <div class="header">
          <div class="container">
            <h1 class="logo">
              <a href="<?php echo ($_SESSION['usuario']) ? 'usuario/'.$_SESSION['uid'] : $default_url; ?>"><img height="67" src="assets/imgs/logo.png" alt=""> <span>comview</span></a>
            </h1>
          </div>          
        </div>
        <?php 
            include('commons/tools.php');
        ?>
      </header>
      <main class="main">