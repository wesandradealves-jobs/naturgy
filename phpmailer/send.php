<?php
    require_once("../config/config.php");
    require_once("../functions/functions.php");
    require_once("PHPMailerAutoload.php");

    $enviarPara = $_POST['email'];
    $assunto = 'Recuperação de Senha';
    $senha = randHash(20);
    $message = '
        <div style="background-color: lightblue; display: block; padding: 30px;">
            <div style="background-color: white; display: block; padding: 30px; text-align: center;">
                <font style="font-style: italic;">Este e-mail contém sua nova senha: <br/>Sua nova senha é:<br/></font>
                <font style="font-size: 2.5rem; font-weight: bold;">'.$senha.'</font>
            </div>
        </div>
    ';

    $query = "SELECT * FROM users WHERE email = '".$_POST['email']."' ";
    $result = mysqli_fetch_assoc(mysqli_query($conn, $query));

    if(!$result) :
        // $result = array("error"=>true, 'message'=>'Esta conta não existe.'); 
        header("Location: ".$default_url."/reset/?error=true");  
    else :
        $stmt = $conn->prepare("UPDATE `users` SET `senha` = ?, `senha_desc` = ? WHERE `users`.`id` = ".$result['id']);
        if(isset($stmt) && $stmt !== FALSE) {
            // $result = array("error"=>false, 'message'=>'Password atualizado com sucesso.', 'senha' => $senha); 
            header("Location: ".$default_url."/reset/?sent=true");  

            $stmt->bind_param("ss", md5($senha), $senha);
            $stmt->execute();
            $stmt->close();
        } else {
            die();
            header("Location: ".$default_url."/reset/?error=true");  
            // $result = array("error"=>true, 'message'=>'Erro ao atualizar o password.'); 
        }
    endif;

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


?>