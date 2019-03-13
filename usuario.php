<?php 
	include('commons/header.php'); 
	$user = mysqli_fetch_assoc(mysqli_query($conn, (isset($_GET['id'])) ? "SELECT * FROM users WHERE `users`.`id` = '".$_GET['id']."'" : "SELECT * FROM users WHERE `users`.`id` = '".$_SESSION['uid']."'" ));
?>
<section>
	<div class="container">
		<div class="section-header">
			<h2 class="title"><span>&#187;</span> 
				<?php
					if((isset($_GET['id']) && $_GET['id'] == $_SESSION['uid'])) :
						echo 'Minha Conta';
					elseif(isset($_GET['id']) && $_GET['id'] != $_SESSION['uid']) :
						echo 'Editar Conta';
					elseif(!isset($_GET['id'])) :
						echo 'Cadastro de novo usuário';
					endif;
				?>
			</h2>
		</div>
		<form id="form" method="POST" class="forms register user <?php echo (isset($_GET['id'])) ? '-edit' : ''; ?>" action="<?php echo $default_url.'/functions/users.php'; ?>">
			<p class="forms-header-text">
				<?php
					if( (isset($_GET['id']) && $_GET['id'] == $_SESSION['uid']) || (isset($_GET['id']) && $_GET['id'] != $_SESSION['uid']) ) :
						echo 'Edite um';
					elseif(!isset($_GET['id'])) :
						echo 'Cadastre um novo';
					endif;
				?>
				usuário preenchendo os campos abaixo.
				<?php if(!isset($_GET['id'])) : ?>
				<br/>[ * ] Sua senha de acesso será enviada por e-mail.
				<?php endif; ?>
			</p>
			<?php 
				$i = -1;
				$label = array();
		        $labels = mysqli_query($conn, 'SELECT * FROM users_labels');
		        while($row = mysqli_fetch_array($labels)) :
		        	array_push($label, $row['label']);
		        endwhile;

		        foreach ($user as $key => $value) {
		        	$i++;
		        	if($key == 'enabled' || $key == 'senha_desc'){
		        		echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
		        	} elseif(($key == 'usuario' && !isset($_GET['id'])) || ($key == 'senha' && !isset($_GET['id'])) || ($key == 'id' && !isset($_GET['id']))){
		        		// -
		        	} elseif($key == 'userType') {
		        		if(isset($_SESSION['userType']) && $_SESSION['userType']=='administrador') : 
	    					$roles = "SELECT * FROM roles ORDER BY slug";
						    if ($role = $conn->query($roles)) {
								echo '<div class="fieldset">
								  <label for="'.$key.'">'.$label[$i].'</label>
									<span class="custom-combobox">
								    	<i class="fal fa-angle-down"></i>
					    				<select required="required" name="'.$key.'">';
							    while ($row = $role->fetch_assoc()) {
									echo '<option '.(isset($_GET['id']) && $value == $row['slug'] ? 'selected="selected"' : '' ).' value="'.$row['slug'].'">'.$row['role'].'</option>';
							    }  
								echo '</select>
									  	</span>		  
									</div>'; 
							} 		
							$role->free();	
						else :
							echo '
								<div class="fieldset field_id_'.$key.'">
								  <label for="'.$key.'">'.$label[$i].'</label>
								  <span>
								  	<input readonly="readonly" type="text" value="'.strtoupper($value).'">
								    <input type="hidden" name="'.$key.'" value="'.$value.'">
								  </span>
								</div>	
							';
						endif;
		        	} elseif($key == 'gerencia') {
		        		if(
		        			(isset($_SESSION['userType']) && ($_SESSION['userType']=='administrador' || $_SESSION['userType']=='comprador') && (isset($_GET['id']) && $_GET['id'] == $_SESSION['uid']))
		        			|| isset($_SESSION['userType']) && ($_SESSION['userType']!='administrador' && $_GET['id'] == $_SESSION['uid'])
		        		) : 
							echo '
								<div class="fieldset field_id_'.$key.'">
								  <label for="'.$key.'">'.$label[$i].'</label>
								  <span>
								  	<input readonly="readonly" type="text" value="'.strtoupper($value).'">
								    <input type="hidden" name="'.$key.'" value="'.$value.'">
								  </span>
								</div>	
							';
						else :
			       	 		$res_data = mysqli_query($conn,"SELECT * FROM `users` WHERE `userType` = 'responsavel'");

							echo '<div class="fieldset">
							  <label for="'.$key.'">'.$label[$i].'</label>
								<span class="custom-combobox">
							    	<i class="fal fa-angle-down"></i>
				    				<select name="'.$key.'">';
				    				echo '<option value="">Selecione uma opção</option>';
						    while($row = mysqli_fetch_array($res_data)) :
								echo '<option '.(isset($_GET['id']) && $value == $row['nome'] ? 'selected="selected"' : '' ).' value="'.$row['nome'].'">'.$row['nome'].'</option>';
							endwhile;
							echo '</select>
								  	</span>		  
								</div>';
						endif;
		        	} else {
		        		if($key == 'setor' || $key == 'classificacao'){
			        		if(
			        			(isset($_SESSION['userType']) && ($_SESSION['userType']=='administrador' || $_SESSION['userType']=='comprador') && (isset($_GET['id']) && $_GET['id'] == $_SESSION['uid']))
			        			|| isset($_SESSION['userType']) && ($_SESSION['userType']!='administrador' && $_GET['id'] == $_SESSION['uid'])
			        		) : 
								echo '
									<div class="fieldset field_id_'.$key.'">
									  <label for="'.$key.'">'.$label[$i].'</label>
									  <span>
									  	<input readonly="readonly" type="text" value="'.strtoupper($value).'">
									    <input type="hidden" name="'.$key.'" value="'.$value.'">
									  </span>
									</div>	
								';
							else :
								echo '
									<div class="fieldset field_id_'.$key.'">
									  <label for="'.$key.'">'.$label[$i].'</label>
									  <span>
									    <input class="'.$key.'" '.( ( $key == 'email' ) ? 'required="required"' : '' ).' '.( !isset($_GET['id']) ? '' : (($key == 'id') ? 'readonly="readonly"' : '' ).'  value="'.( (isset($_GET['id']) && $value) ? $value :'') ).'" name="'.$key.'" type="'.( ($key == 'senha') ? 'password' : 'text').'">
									  </span>
									</div>	
								';
							endif;
		        		} else {
		        			if(
		        				$_SESSION['userType'] == 'administrador'
		        				&&
		        				$key == 'sap'
		        				&& (isset($_GET['id']) && $_GET['id'] == $_SESSION['uid'])
		        			){
								echo '
									<div class="fieldset field_id_'.$key.'">
									  <label for="'.$key.'">'.$label[$i].'</label>
									  <span>
									  	<input readonly="readonly" type="text" value="'.strtoupper($value).'">
									    <input type="hidden" name="'.$key.'" value="'.$value.'">
									  </span>
									</div>	
								';		        				
		        			} else {
								echo '
									<div class="fieldset field_id_'.$key.'">
									  <label for="'.$key.'">'.$label[$i].'</label>
									  <span>
									    <input class="'.$key.'" '.( ( $key == 'email' ) ? 'required="required"' : '' ).' '.( (!isset($_GET['id']) ? '' : ($key == 'id') ? 'readonly="readonly"' : '' ).'  value="'.( (isset($_GET['id']) && $value) ? $value :'') ).'" name="'.$key.'" type="'.( ($key == 'senha') ? 'password' : 'text').'">
									  </span>
									</div>	
								';
		        			}
		        		}
		        	}
		        }
			?>
			<style>
				[readonly]{
					background-color: whitesmoke;
					pointer-events: none;
				}
			</style>
			<div class="fieldset forms-footer">
				<button class="btn btn-1">Salvar</button>
				<input type="hidden" name="action" value="<?php echo (isset($_GET['id'])) ? 'atualizar' : 'salvar'; ?>" />
				<?php if(isset($_GET['id'])) : ?> 
					<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
				<?php endif; ?>
			</div>	
		</form>    
	</div>
</section>
<?php include('commons/footer.php'); ?>