<?php 
    require_once("../config/config.php");
    require_once("../functions/functions.php");
    require_once("../phpmailer/PHPMailerAutoload.php");

    $usuario = to_permalink($_POST['nome']);
    $query_user = "SELECT * FROM users WHERE usuario = '".$usuario."'";
    $result_query_user = mysqli_fetch_assoc(mysqli_query($conn, $query_user));

    if($_POST['action']=='salvar') :
        $enviarPara = $_POST['email'];
        $assunto = 'Sua nova senha de acesso';    
        $senha = randHash(20);

        if(!$result_query_user['id']){
          $message = '
                <div style="background-color: lightblue; display: block; padding: 30px;">
                    <div style="background-color: white; display: block; padding: 30px; text-align: center;">
                        <font style="font-style: italic;">Este e-mail contém seu nome de usuário e senha de acesso: <br/>Seu nome de usuário é <strong>'.$usuario.'</strong> e Sua senha é:<br/></font>
                        <font style="font-size: 2.5rem; font-weight: bold;">'.$senha.'</font>
                    </div>
                </div>
            ';        

            $query_user = "INSERT INTO users (usuario,senha,senha_desc,nome,sap,telefone,email,classificacao,setor,gerencia,userType) VALUES ('".$usuario."', '".md5($senha)."','".$senha."','".$_POST['nome']."', '".$_POST['sap']."', '".$_POST['telefone']."', '".$_POST['email']."', '".$_POST['classificacao']."', '".$_POST['setor']."', '".$_POST['gerencia']."', '".$_POST['userType']."')";
            if(mysqli_query($conn, $query_user)) {
               // $result_query_user = array("error"=>false, 'message'=>'Novo registro com sucesso. Confira sua senha no seu e-mail de cadastro.');
                header("Location: ".$default_url."/usuarios/?sent=true");  
            } else {
               // $result_query_user = array("error"=>true, 'message'=>mysqli_error($conn));
                header("Location: ".$default_url."/usuarios/?error=true");  
            }   
            
            $mail = new PHPMailer;

            if($ENV!='prod'){
                $mail->SMTPDebug = 0;
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = 'tls';
                $mail->SMTPAuth = true;
                $mail->Username = "wesandradealves@gmail.com";
                $mail->Password = "Wes@03122530";
                if($enviarPara != 'wesandradealves@gmail.com') :
                    $mail->AddCC('wesandradealves@gmail.com', 'Wesley Andrade');
                endif;
                // $mail->AddBCC('', '');
                $mail->setFrom($mail->Username, 'Naturgy Comview');
                $mail->addAddress($enviarPara, $_POST['nome']);
                $mail->Subject = $assunto;
                $mail->Body    = $message;
                $mail->CharSet = 'UTF-8';
                $mail->AltBody = 'This is a plain-text message body';
                if(!$result['error']) :
                    $mail->send();
                endif;  
                function save_mail($mail)
                {
                    $path = "{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail";
                    $imapStream = imap_open($path, $mail->Username, $mail->Password);
                    $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
                    imap_close($imapStream);
                    return $result;
                }
            } else {
                $mail = new PHPMailer;
                $mail->setFrom('noreply@comview.aaminformatica.com.br', 'Naturgy Comview');
                $mail->addAddress($enviarPara, $_POST['nome']);
                if($enviarPara != 'wesandradealves@gmail.com') :
                    $mail->AddBCC('wesandradealves@gmail.com', 'Wesley Andrade');
                endif;
                $mail->Subject = $assunto;
                $mail->isHTML(true);
                $mail->Body    = $message;
                $mail->AltBody = 'This is a plain-text message body';
                $mail->CharSet = 'UTF-8';
                if(!$result_query_user['error']) :
                    $mail->send();
                endif;         
            }

            header("Location: ".$default_url."/usuarios/?registered=true");  
        } else {
            header("Location: ".$default_url."/cadastro/usuarios/?exist=true"); 
        }
    else : 
        if($_POST['senha']==$result_query_user['senha']){
            $senha = $_POST['senha'];
            $senha_desc = $_POST['senha_desc'];
        } elseif(md5($_POST['senha'])!=$result_query_user['senha']){
            $senha = md5($_POST['senha']);
            $senha_desc = $_POST['senha'];
        }
        
        $query = "UPDATE users SET usuario = '".$_POST['usuario']."', senha = '".$senha."', senha_desc = '".$senha_desc."',nome = '".$_POST['nome']."', sap = '".$_POST['sap']."', telefone = '".$_POST['telefone']."', email = '".$_POST['email']."', classificacao = '".(isset($_POST['classificacao']) ? $_POST['classificacao'] : '')."', setor = '".(isset($_POST['setor']) ? $_POST['setor'] : '')."', gerencia = '".(isset($_POST['gerencia']) ? $_POST['gerencia'] : '')."', userType = '".$_POST['userType']."'  WHERE `users`.`id` = ".$_POST['id'];

        if(mysqli_query($conn, $query)) {
            if($_POST['senha'] != $result_query_user['senha']){
                if($_POST['id'] == $_SESSION['uid']){
                    unset(
                        $_SESSION['usuario'],
                        $_SESSION['uid'],
                        $_SESSION['timestamp'],
                        $_SESSION['userType']
                    ); 
                    header("Location: ".$default_url."/login/?logout=true");
                } else {
                    header("Location: ".$default_url."/usuario/".$_POST['id'].'#form'); 
                }  
            } else {
                header("Location: ".$default_url."/usuario/".$_POST['id'].'#form');  
            }
        } else {
        	header("Location: ".$default_url."/usuario/?error=true"); 
        }  
    endif;