<?php 
	include('commons/header.php'); 
?>
<section>
	<div class="container">
		<div class="section-header">
			<h2 class="title"><span>&#187;</span> Todos os Usuários</h2>
		</div>
          <table class="tables tables-1" width="100%">
            <thead>
              <tr>
                <th width="20">ID</th>
                <th width="100">Usuário</th>
                <th width="100">Nome</th>
                <th width="150">SAP</th>
                <th width="350">Telefone</th>
                <th width="350">E-mail</th>
                <th width="100">Classificação</th>
                <th width="100">Setor</th>
                <th width="100">Gerência</th>
                <th width="150">Tipo de Usuário</th>
                <?php if($_SESSION['userType']=='administrador') : ?>
                <th width="10%"></th>
            	<?php endif; ?>
              </tr>
            </thead>	
            <tbody>
			<?php
				$orderby = " ORDER BY id ASC"; 
				$queryCondition = NULL;
				$userCondition = NULL;
				$filter = false;

				// Controle de Paginação

				if (isset($_GET['p'])) {
				    $page = $_GET['p'];
				} else {
				    $page = 1;
				}

				$no_of_records_per_page = 10;
				$offset = ($page-1) * $no_of_records_per_page; 

				$total_pages_sql = "SELECT COUNT(*) FROM users";
				
				$result = mysqli_query($conn,$total_pages_sql);
				$total_rows = mysqli_fetch_array($result)[0];
				$total_pages = ceil($total_rows / $no_of_records_per_page);

				if(!empty($_GET["search"])) {
					// Pega dados da busca
					foreach($_GET["search"] as $k=>$v){
						if(!empty($v)) {
							if(!empty($queryCondition)) {
								$queryCondition .= " AND ".str_replace("'", "", $k)." = '".$v."'";
							} else {
								$queryCondition .= " WHERE ".str_replace("'", "", $k)." = '".$v."'";
							}
						}
					}
				}

				// Default loop

				$sql = "SELECT * FROM users " . ((isset($queryCondition)) ? $queryCondition : '') . $userCondition . $orderby . " LIMIT ".$offset.','.$no_of_records_per_page;

		        $res_data = mysqli_query($conn,$sql);
		        while($row = mysqli_fetch_array($res_data)) :
		    ?>
			<tr>
				<th><?php echo $row['id'] ?></th>
				<th><?php echo $row['usuario'] ?></th>
				<th><?php echo $row['nome'] ?></th>
				<th><?php echo $row['sap'] ?></th>
				<th><?php echo $row['telefone'] ?></th>
				<th><?php echo $row['email'] ?></th>
				<th><?php echo $row['classificacao'] ?></th>
				<th><?php echo $row['setor'] ?></th>
				<th><?php echo $row['gerencia'] ?></th>
				<th><?php echo $row['userType'] ?></th>
                <?php if($_SESSION['userType']=='administrador') : ?>
				<th>
					<?php if($_SESSION['userType']=='administrador' || ($_SESSION['userType']=='comprador' && $row['userType'] != 'comprador')) : ?>	
					<a title="Editar" class="editar" href="usuario/<?php echo $row['id'] ?>">
						<i class="fal fa-edit"></i>
					</a>	
					<?php endif; ?>	
					<?php if($_SESSION['userType']=='administrador') : ?>	
						<?php if($_SESSION['uid'] != $row['id']) : ?>
						<a title="Deletar" class="editar" href="<?php echo $default_url.'/functions/delete.php?table=users&id='.$row['id']; ?>">
							<i class="fal fa-trash"></i>
						</a>	
						<a title="Habilitar" class="enable" href="<?php echo $default_url.'/functions/enable.php?table=users&id='.$row['id']; ?>">
							<i class="fal fa-toggle-off <?php echo ($row['enabled']==1) ? 'fa-toggle-off' : 'fa-toggle-on'; ?>"></i>
						</a>
						<?php endif; ?>	
					<?php endif; ?>
				</th>
            	<?php endif; ?>
			</tr>		            
	        <?php 
				endwhile;
				$conn->close();
				unset($conn);
            ?>
			</tbody>
		</table>
	    <ul class="pagination">
	    	<li><a href="usuarios/?p=1">Primeira</a></li>
	        <li class="<?php if($page <= 1){ echo 'disabled'; } ?>">
	            <a href="<?php if($page <= 1){ echo '#'; } else { echo "usuarios/?p=".($page - 1); } ?>">Anterior</a>
	        </li>
	    	<?php 
	    		for ($i = 1; $i <= $total_pages; $i++) {
	    			echo '
						<li class="'.( ($page == $i) ? 'disabled current' : '' ).'">
						    <a href="usuarios/?p='.$i.'">'.$i.'</a>
						</li>
					';	    			
	    		}
	    	?>
	        <li class="<?php if($page >= $total_pages){ echo 'disabled'; } ?>">
	            <a href="<?php if($page >= $total_pages){ echo '#'; } else { echo "usuarios/?p=".($page + 1); } ?>">Próxima</a>
	        </li>
	    	<li><a href="usuarios/?p=<?php echo $total_pages; ?>">Última</a></li>
	    </ul>                
	</div>
</section>
<?php include('commons/footer.php'); ?>