<?php 
	include('commons/header.php'); 
?>
<section>
	<div class="container">
		<div class="section-header">
			<h2 class="title"><span>&#187;</span> Tipos de Processo</h2>
		</div>
		<table class="tables tables-1" width="100%">
			<thead>
			  <tr>
			  	<th width="50">ID</th>
			    <th>Tipo de Processo</th>
			    <th width="100"></th>
			  </tr>
			</thead>
			<tbody>
				<?php 
					$sql = "SELECT * FROM `processos_tipos` ORDER BY id ASC";
			        $res_data = mysqli_query($conn,$sql);
			        while($row = mysqli_fetch_array($res_data)) :
				?>
					<tr>
						<th><?php echo $row['id']; ?></th>
						<th><?php echo $row['tipo']; ?></th>
						<th>
							<a class="ajax" title="Deletar" href="<?php echo $default_url.'/functions/delete.php?table=processos_tipos&id='.$row['id']; ?>">
								<i class="fal fa-trash"></i>
							</a>
						</th>						
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</section>
<?php include('commons/footer.php'); ?>