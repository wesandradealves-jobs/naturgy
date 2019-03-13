<?php include('commons/header.php'); ?>
<form class="login-form forms" method="POST" action="<?php echo $default_url.'/functions/login.php'; ?>">
      <p class="login-form-text">Para acessar o sistema  COMVIEW insira seu login e senha nos campos abaixo.</p>
      <div class="fieldset">
            <label for="usuario">Login</label>
            <span>
                  <input name="usuario" type="text">
            </span>
      </div>
      <div class="fieldset">
            <label for="senha">Senha</label>
            <span>
                  <input name="senha" type="password">
            </span>
      </div>
      <div class="fieldset login-form-footer">
            <span>
                  <a href="reset"><i class="fal fa-question-circle"></i> Esqueci minha senha</a>
            </span>
            <span>
                  <button class="btn btn-1">Acessar</button>
            </span>
      </div>
      <input type="hidden" name="form" value="login" />
</form>
<?php include('commons/footer.php'); ?>