<?php include('commons/header.php'); ?>
<form class="login-form forms recover-form" method="POST" action="<?php echo $default_url.'/phpmailer/send.php'; ?>">
      <p class="login-form-text">Para recuperar sua senha, informe seu e-mail abaixo.</p>
      <div class="fieldset">
            <label for="email">E-mail</label>
            <span>
                  <input required="required" id="email" name="email" type="email">
            </span>
      </div>
      <div class="fieldset login-form-footer">
            <span>
                  <a href="login">&#187; Voltar para Login</a>
            </span>
            <span>
                  <button class="btn btn-1">Recuperar Senha</button>
            </span>
      </div>
</form>
<?php include('commons/footer.php'); ?>