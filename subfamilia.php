<?php 
	include('commons/header.php'); 
?>
<section>
	<div class="container">
		<div class="section-header">
			<h2 class="title"><span>&#187;</span> Subfamílias</h2>
		</div>
		<table class="tables tables-1" width="100%">
			<thead>
			  <tr>
			  	<th width="50">ID</th>
			    <th>Subfamilia</th>
			    <th>Nível de Risco</th>
			    <th width="20">Homologável</th>
			    <th>ARC</th>
			    <th width="100"></th>
			  </tr>
			</thead>
			<tbody>
				<?php 
					// Controle de Paginação

					if (isset($_GET['p'])) {
					    $page = $_GET['p'];
					} else {
					    $page = 1;
					}

					$no_of_records_per_page = 10;
					$offset = ($page-1) * $no_of_records_per_page; 

					$total_pages_sql = "SELECT COUNT(*) FROM subfamilia";

					$result = mysqli_query($conn,$total_pages_sql);
					$total_rows = mysqli_fetch_array($result)[0];
					$total_pages = ceil($total_rows / $no_of_records_per_page);

					// 

					$sql = "SELECT * FROM `subfamilia` ORDER BY id ASC LIMIT ".$offset.','.$no_of_records_per_page;
			        $res_data = mysqli_query($conn,$sql);
			        while($row = mysqli_fetch_array($res_data)) :
				?>
					<tr>
						<th><?php echo $row['id']; ?></th>
						<th><?php echo $row['subfamilia']; ?></th>
						<th><?php echo $row['nivel_de_risco']; ?></th>
						<th><?php echo ($row['homologavel']==1) ? 'Sim' : 'Não'; ?></th>
						<th><?php echo $row['arc']; ?></th>
						<th>
							<a class="ajax" title="Deletar" href="<?php echo $default_url.'/functions/delete.php?table=subfamilia&id='.$row['id']; ?>">
								<i class="fal fa-trash"></i>
							</a>
						</th>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	    <ul class="pagination">
	    	<li><a href="cadastro/subfamilia/?p=1">Primeira</a></li>
	        <li class="<?php if($page <= 1){ echo 'disabled'; } ?>">
	            <a href="<?php if($page <= 1){ echo '#'; } else { echo "cadastro/subfamilia/?p=".($page - 1); } ?>">Anterior</a>
	        </li>
	    	<?php 
	    		for ($i = 1; $i <= $total_pages; $i++) {
	    			echo '
						<li class="'.( ($page == $i) ? 'disabled current' : '' ).'">
						    <a href="cadastro/subfamilia/?p='.$i.'">'.$i.'</a>
						</li>
					';	    			
	    		}
	    	?>
	        <li class="<?php if($page >= $total_pages){ echo 'disabled'; } ?>">
	            <a href="<?php if($page >= $total_pages){ echo '#'; } else { echo "cadastro/subfamilia/?p=".($page + 1); } ?>">Próxima</a>
	        </li>
	    	<li><a href="cadastro/subfamilia/?p=<?php echo $total_pages; ?>">Última</a></li>
	    </ul> 
	</div>
</section>
<?php include('commons/footer.php'); ?>