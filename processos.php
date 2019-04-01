<?php 
	include('commons/header.php'); 

	$url = NULL;

	if(isset($_SERVER['QUERY_STRING'])){
		$url = 'http://'.$_SERVER['HTTP_HOST'].explode('&orderBy', $_SERVER['REQUEST_URI'])[0];
	}
?>
<section>
	<div class="container">
		<div class="section-header">
			<h2 class="title"><span>&#187;</span> Todos os Processos</h2>
		</div>
          <table class="tables tables-1" width="100%">m
           <thead>
              <tr>
              	<th><a title="ID" href="<?php echo ($_SERVER['QUERY_STRING']) ? explode('orderBy',$url)[0].'&orderBy=id' : 'processos/ordem/?orderBy=id';?>">ID</a></th>
                <th width="20">Responsável(eis)<br/>(Cliente)</th>
                <th width="100">Comprador</th>
                <th width="100"><a title="Solped" href="<?php echo ($_SERVER['QUERY_STRING']) ? explode('orderBy',$url)[0].'&orderBy=numero_processo' : 'processos/ordem/?orderBy=numero_processo';?>">Solped</a></th>
                <th width="100"><a title="Objeto" href="<?php echo ($_SERVER['QUERY_STRING']) ? explode('orderBy',$url)[0].'&orderBy=nome_processo' : 'processos/ordem/?orderBy=nome_processo';?>">Objeto</a></th>
                <th width="100">Sociedade(s)</th>
                <th width="150">Valor</th>
                <th width="100">Moeda(s)</th>
                <th width="100"><a title="Tratamento" href="<?php echo ($_SERVER['QUERY_STRING']) ? explode('orderBy',$url)[0].'&orderBy=tipo_processo' : 'processos/ordem/?orderBy=tipo_processo';?>">Tratamento</a></th>
                <th width="250">Tempo em curso<br/>(Dias úteis)</th>
                <th width="150"><a title="Status" href="<?php echo ($_SERVER['QUERY_STRING']) ? explode('orderBy',$url)[0].'&orderBy=status' : 'processos/ordem/?orderBy=status';?>">Status</a></th>
                <th width="10%"></th>
              </tr>
            </thead>	
            <tbody>
			<?php
				$orderby = " ORDER BY ". ( (isset($_GET['orderBy'])) ? (($_GET['orderBy'] == 'status' || $_GET['orderBy'] == 'numero_processo') ? 'ABS('.$_GET['orderBy'].')' : "'".$_GET['orderBy']."'") : 'id' ) ." DESC"; 

				$queryCondition = NULL;
				$userCondition = NULL;
				$filter = false;
				$arr = array();
				$current = array();
				$pids = array();

				// Responsaveis

				$sResponsaveis = 'SELECT * FROM responsavel_by_processos ORDER BY id ASC';
				$rsResponsaveis = mysqli_query($conn,$sResponsaveis);

				while($rowResponsaveis = mysqli_fetch_array($rsResponsaveis)) :
					array_push($arr, array(
						'pid'=>$rowResponsaveis['pid'], 
						'rid'=>$rowResponsaveis['responsavel']
					));
				endwhile;			

				// Controle de Paginação

				if (isset($_GET['p'])) {
				    $page = $_GET['p'];
				} else {
				    $page = 1;
				}

				$no_of_records_per_page = 10;
				$offset = ($page-1) * $no_of_records_per_page; 

				$total_pages_sql = "SELECT COUNT(*) FROM processos";

				$result = mysqli_query($conn,$total_pages_sql);
				$total_rows = mysqli_fetch_array($result)[0];
				$total_pages = ceil($total_rows / $no_of_records_per_page);

				// Processos

				$sProcessos = 'SELECT * FROM processos ORDER BY id ASC';
				$rsProcessos = mysqli_query($conn,$sProcessos);

				while($rowProcessos = mysqli_fetch_array($rsProcessos)) :
					array_push($current, array(
						'pid'=>$rowProcessos['id'], 
						'rid'=>$_SESSION['uid']
					));
				endwhile;	

				// Pega os processos por responsavel logado

				$mapping = array_map(function($current, $arr) {
				    if($_SESSION['uid'] === $arr['rid']){
				    	return $arr['pid'];
				    }
				}, $current, $arr);

				foreach ($mapping as $key => $value) {
					if($value){
						array_push($pids, $value);
					}
				}	

				// Motor de Busca

				if(!empty($_GET["search"])) {
					$j = 0;
					// Pega dados da busca
					foreach($_GET["search"] as $k=>$v){
						if(!empty($v)) {
							$j++;

							// Define a condição do usuário
							switch ($_SESSION['userType']) {
								case 'responsavel':
									if(!empty($pids)){
										$userCondition = ' AND id IN ('.implode(',', $pids).')'; 
									}
									break;		
								case 'comprador':
									$userCondition = ' AND `processos`.uid = '.$_SESSION['uid']; 
									break;			
								default:
									
									break;
							}

							if(!empty($queryCondition)) {
								// $queryCondition .= " AND ".str_replace("'", "", $k)." = '".$v."'";
							} else {
								$queryCondition .= " WHERE LOWER(processos.".str_replace("'", "", $v).") LIKE '%".$_GET['search']['keyword']."%'";
								// $queryCondition .= " WHERE ".str_replace("'", "", $k)." = '".$v."'";
							}
						}
					}
					// Se vier de busca e não tiver valores
					if(!$j){
						// Define a condição do usuário
						switch ($_SESSION['userType']) {
							case 'responsavel':
								if(!empty($pids)){
									$userCondition = ' WHERE id IN ('.implode(',', $pids).')'; 
								}
								break;		
							case 'comprador':
								$userCondition = ' WHERE `processos`.uid = '.$_SESSION['uid']; 
								break;			
							default:
								
								break;
						}
					}
				} else {
					// Define a condição do usuário
					switch ($_SESSION['userType']) {
						case 'responsavel':
							if(!empty($pids)){
								$userCondition = ' WHERE id IN ('.implode(',', $pids).')';
							} 
							break;		
						case 'comprador':
							$userCondition = ' WHERE `processos`.comprador = '.$_SESSION['uid']; 
							break;			
						default:
							
							break;
					}
				}

				// Default loop

				$sql = "SELECT * FROM processos " . ((isset($queryCondition)) ? $queryCondition : '') . $userCondition . $orderby . " LIMIT ".$offset.','.$no_of_records_per_page;

				// print_r($sql);

		        $res_data = mysqli_query($conn,$sql);
		        while($row = mysqli_fetch_array($res_data)) :
		    ?>
			<tr>
				<th>
					<?php 
						echo $row['id'];
					?>
				</th>
				<th>
					<?php 
						// Pega o repsonsavel

						if(!empty($arr)){
							$responsaveis = array();

							foreach ($arr as $key => $value) {
								if(in_array($row['id'], $value, false)){
									array_push($responsaveis, $value['rid']);
								}
							}

	    			        $stblresponsavel = "SELECT * FROM users WHERE id IN (".implode(',', $responsaveis).")";

		    			    $qtblresponsavel = mysqli_fetch_array(mysqli_query($conn, $stblresponsavel));

					        $rqtblresponsavel = mysqli_query($conn,$stblresponsavel);
					        while($rwqtblresponsavel = mysqli_fetch_array($rqtblresponsavel)) :
					        	print_r($rwqtblresponsavel['nome'].'<br>');
					        endwhile;	
						}
					?>
				</th>
				<th><?php 
					if(isset($row['comprador']) && $row['comprador'] != ''){
					    if(strlen($row['comprador']) <= 2){
	    			        $scomprador = "SELECT * FROM users WHERE id = ".$row['comprador'];
	    			        $qcomprador = mysqli_fetch_assoc(mysqli_query($conn, $scomprador));
	    			        
	    			        print_r($qcomprador['nome']);   
					    } else {
					        echo $row['comprador'];
					    }						
					}
				?></th>
				<th><?php echo $row['numero_processo'] ?></th>
				<th><?php echo $row['nome_processo'] ?></th>
				<th style="text-align: left">
					<?php 
						$sociedades_by_processos = array();
						$sqlsociedades = 'SELECT * FROM sociedades_by_processos WHERE pid ='.$row['id'];
						$ressociedades = mysqli_query($conn,$sqlsociedades);

						while($rw = mysqli_fetch_array($ressociedades)) :
							array_push($sociedades_by_processos, array('pid'=>$rw['pid'],'sociedade'=>$rw['sociedade'],'moeda'=>$rw['moeda'],'valor'=>$rw['valor'],'position'=>$rw['position']));
						endwhile;

						foreach ($sociedades_by_processos as $value) {
							echo '&#9679; '.$value['sociedade'].'<br>';
							// echo $value['sociedade'].",";
						}
					?>
				</th>
				<th>
					<?php 
						$valores = array();
						$moedas = array();
						foreach ($sociedades_by_processos as $key => $value) {
							array_push($valores, $value['valor']);
							if(!in_array($value['moeda'], $moedas)){
							    array_push($moedas, $value['moeda']);
							}
						}
						print_r(array_sum($valores));
					?>
				</th>
				<th>
					<?php 
						foreach ($moedas as $value) {
							echo '●'.$value.'<br>';
						}
					?>
				</th>
				<th><?php echo $row['tipo_processo'] ?></th>
				<th>
					<?php 
						$created = new DateTime(date($row['timestamp']));
						$now = new DateTime(date('m/d/Y h:i:s a', time()));
						echo $created->diff($now)->format('%R%a dias');
					?>
				</th>
				<th><?php echo $row['status']."%" ?></th>
				<th>
					<?php if($_SESSION['userType'] && $_SESSION['userType'] != 'responsavel') : ?>
					<a title="Editar" href="processo/<?php echo $row['uid'] ?>/<?php echo $row['id'] ?>">
						<i class="fal fa-edit"></i>
					</a>		
					<a title="Deletar" href="<?php echo $default_url.'/functions/delete.php?table=processos&id='.$row['id']; ?>">
						<i class="fal fa-trash"></i>
					</a>	
					<?php else : ?>
					<a title="Visualizar" href="processo/<?php echo $row['uid'] ?>/<?php echo $row['id'] ?>">
						<i class="fal fa-eye"></i>
					</a>	
					<?php endif; ?>	
				</th>
			</tr>		            
	        <?php 
				endwhile;
            ?>
			</tbody>
		</table>
	    <ul class="pagination">
	    	<li><a href="processos/?p=1">Primeira</a></li>
	        <li class="<?php if($page <= 1){ echo 'disabled'; } ?>">
	            <a href="<?php if($page <= 1){ echo '#'; } else { echo "processos/?p=".($page - 1); } ?>">Anterior</a>
	        </li>
	    	<?php 
	    		for ($i = 1; $i <= $total_pages; $i++) {
	    			echo '
						<li class="'.( ($page == $i) ? 'disabled current' : '' ).'">
						    <a href="processos/?p='.$i.'">'.$i.'</a>
						</li>
					';	    			
	    		}
	    	?>
	        <li class="<?php if($page >= $total_pages){ echo 'disabled'; } ?>">
	            <a href="<?php if($page >= $total_pages){ echo '#'; } else { echo "processos/?p=".($page + 1); } ?>">Próxima</a>
	        </li>
	    	<li><a href="processos/?p=<?php echo $total_pages; ?>">Última</a></li>
	    </ul>                
	</div>
</section>
<?php include('commons/footer.php'); ?>