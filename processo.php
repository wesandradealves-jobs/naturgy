<?php 
	include('commons/header.php'); 
	if(isset($_GET['id'])) :
		$query_processos = "SELECT * FROM processos WHERE `processos`.`id` = '".$_GET['id']."'";
		$processo = mysqli_fetch_assoc(mysqli_query($conn, $query_processos));
	else: 
		$ids = array();
        $res_data = mysqli_query($conn, "SELECT * FROM processos ORDER BY id ASC");
		$last_register = (int)$res_data->num_rows;		
	endif;
?>
<section>
	<div class="container">
		<div class="progressBar">
			<ul>
			  <li>Status do processo</li>
			  <li class="progressStatus"><span>0</span>%</li>
			  <li class="progressLoader">
			  	<span></span>
			  </li>
			<li class="progressCaption">
			    <img width="100%" src="<?php echo $default_url.'/assets/imgs/timeline.png'; ?>" />
			  </li>
			</ul>
		</div>
		<div class="section-header">
			<h2 class="title"><span>&#187;</span> 
				<?php echo (isset($_GET['id'])) ? 'Edite' : 'Cadastre'; ?> um processo
			</h2>
			<form id="processo-form" method="POST" class="forms register processo <?php echo (isset($_GET['id'])) ? '-edit' : ''; ?>" action="<?php echo $default_url.'/functions/processos.php'; ?>">
				<p class="forms-header-text">
					<?php echo (isset($_GET['id'])) ? 'Edite' : 'Cadastre'; ?> um processo; preenchendo os campos abaixo.
				</p>
				<?php 
					$i = -1;
					$label = array();
			        $labels = mysqli_query($conn, 'SELECT * FROM processos_labels');
			        while($row = mysqli_fetch_array($labels)) :
			        	array_push($label, $row['label']);
			        endwhile;	

	        		$sociedades_by_processos = array();
	        		if(isset($_GET['id'])){
						$sqlsociedades = 'SELECT * FROM sociedades_by_processos WHERE pid ='.$_GET['id'];
						$ressociedades = mysqli_query($conn,$sqlsociedades);

						while($row = mysqli_fetch_array($ressociedades)) :
							array_push($sociedades_by_processos, array('pid'=>$row['pid'],'sociedade'=>$row['sociedade'],'moeda'=>$row['moeda'],'valor'=>$row['valor'],'position'=>$row['position']));
						endwhile;
	        		}
			        foreach ($label as $value) {
			        	$i++;
			        	if(str_replace('-', '_', to_permalink($value)) == 'id' || str_replace('-', '_', to_permalink($value)) == 'uid'){
			        		if(str_replace('-', '_', to_permalink($value)) == 'id'){
								echo '<input tabindex="'.$i.'" value="'.( isset($_GET['id']) ? $_GET['id'] : '').'" type="hidden" name="'.str_replace('-', '_', to_permalink($value)).'">';
			        		} else {
			        			echo '<input tabindex="'.$i.'" value="'.( (str_replace('-', '_', to_permalink($value)) == 'uid') ? $_SESSION['uid'] : '' ).'" type="hidden" name="'.str_replace('-', '_', to_permalink($value)).'">';
			        		}
			        	} else {
			        		if(str_replace('-', '_', to_permalink($value)) != 'sociedade' && str_replace('-', '_', to_permalink($value)) != 'valor' && str_replace('-', '_', to_permalink($value)) != 'moeda'){

			        			if(str_replace('-', '_', to_permalink($value)) != 'status' && str_replace('-', '_', to_permalink($value)) != 'responsavel' && str_replace('-', '_', to_permalink($value)) != 'subfamilia' && str_replace('-', '_', to_permalink($value)) != 'comprador' && str_replace('-', '_', to_permalink($value)) != 'rodadas' && str_replace('-', '_', to_permalink($value)) != 'tipo_processo'){
					        		
									switch ($value) {
										case 'Fornecedor':
											echo '<h3><span>'.$value.'</span></h3>';	
											break;
										case 'Estratégia Data Inicial':
											echo '<h3><span>Estratégia</span></h3>';	
											break;											
										case 'Elaboração Documento Data Inicial':
											echo '<h3><span>Elaboração Documentação de Lançamento</span></h3>';	
											break;											
										case 'Negociação Direta Data Inicial':
											echo '<h3><span>Negociação Direta</span></h3>';	
											break;											
										case 'Leilão Data Inicial':
											echo '<h3><span>Leilão</span></h3>';	
											break;											
										case 'Elaboração PA Data Inicial':
											echo '<h3><span>Elaboração PA</span></h3>';	
											break;											
										case 'Workflow Data Inicial':
											echo '<h3><span>Workflow</span></h3>';	
											break;											
										case 'Criação de Pedido Data Inicial':
											echo '<h3><span>Criação de Pedido</span></h3>';	
											break;											
										case 'Trâmite Assinatura Interna Diretor 1':
											echo '<h3><span>Trâmite 1º Diretor</span></h3>';	
											break;											
										case 'Trâmite Assinatura Interna Diretor 2':
											echo '<h3><span>Trâmite 2º Diretor</span></h3>';	
											break;
										case 'Trãmite Assinatura Externa Email':
											echo '<hr/>';
											break;
										default:
											break;
									}

					        		echo '
									<div class="fieldset '.( ($_SESSION['userType'] == 'responsavel') ? 'disabled' : '' ).' field_id_'.str_replace('-', '_', to_permalink($value)).'">
										'; 

										echo '
									    <label for="'.str_replace('-', '_', to_permalink($value)).'">'; 
											switch ($value) {
												case 'Adjudicação Vencimento':
													echo 'Vencimento Adjudicação Anterior';
													break;
												case 'Dados Bravo RQF':
													echo 'Dados Bravo RFQ';
													break;
												case 'Trâmite Assinatura Interna Diretor 1':
													echo '1º Diretor';
													break;
												case 'Trâmite Assinatura Interna Diretor 2':
													echo '2º Diretor';
													break;			
												case 'Número Pedido':
													echo 'Nº pedido/contrato SAP';
													break;
												default:
													if(strpos( $value, 'Inicial' ) !== false){
														echo 'Início';
													} elseif(strpos( $value, 'Final' ) !== false){
														echo 'Fim';
													} else {
														echo $value;
													}
													break;
											}
											echo '</label>
									    <span>'; 
									    echo '
<input '.( (str_replace('-', '_', to_permalink($value)) == 'numero_processo') ? 'readonly="readonly"' : '' ).' tabindex="'.$i.'" value="'. ( (isset($_GET['id']) && isset($processo)) ? $processo[str_replace('-', '_', to_permalink($value))] : (str_replace('-', '_', to_permalink($value)) == 'numero_processo' && isset($last_register) ? $last_register + 1 : '' ) ) .'" name="'.str_replace('-', '_', to_permalink($value)).'" type="'.( (to_permalink($value) == 'tramite-assinatura-externa-retirada' || to_permalink($value) == 'tramite-assinatura-externa-devolucao' || to_permalink($value) == 'adjudicacao-vencimento' || to_permalink($value) == 'disponivel-sap' || to_permalink($value) == 'data-disp-compras' || stripos( str_replace('-', '_', to_permalink($value)), 'data' )) ? 'date' : 'text' ).'">
									    </span>
									  </div>';		
								} elseif(str_replace('-', '_', to_permalink($value)) == 'rodadas') { 
									echo '
										<div class="fieldset field_id_'.str_replace('-', '_', to_permalink($value)).' '.( ($_SESSION['userType'] == 'responsavel') ? 'disabled' : '' ).'">
											<div class="rodadas">
										        <div class="rodadas-header">
										          <strong>Rodadas</strong>
										          <a class="add-more" href="javascript:void(0)" onclick="addMultipleInput(this)">
										            <i class="fal fa-plus"></i>
										          </a> 
										        </div>
										        <ul class="columns">';
										      		if(isset($_GET['id'])){
														$sql_rodadas = "SELECT * FROM rodadas WHERE pid = ".$_GET['id'];
												        $res_sql_rodadas = mysqli_query($conn,$sql_rodadas);
												        while($row = mysqli_fetch_array($res_sql_rodadas)) :
											      			echo '
													          <li data-origin="database">
													            <span class="fieldset">
													              <label for="nivel-rodada">Nível</label>
													              <span>
													                <input  readonly="readonly" value="'.$row['nivel'].'" name="nivel-rodada[]" class="nivel_rodada" type="text">
													              </span>		              
													            </span> 
													            <span class="fieldset">
													            	<label for="rodada_tipo">Tipo</label>
																	<span class="custom-combobox">
															        <i class="fal fa-angle-down"></i>
															        <select  name="rodada-tipo[]">
															          <option '.( ($row['tipo'] == 'Rodada Comercial') ? 'selected="selected"' : '' ).' value="Rodada Comercial">Rodada Comercial</option>
															          <option '.( ($row['tipo'] == 'Rodada Técnica') ? 'selected="selected"' : '' ).' value="Rodada Técnica">Rodada Técnica</option>
															        </select>
															      	</span>
													            </span>
															    <!--<span class="fieldset">
															      <label for="rodada-tipo">Tipo</label>
															      <span>
															        <input value="'.$row['tipo'].'" readonly="readonly" name="rodada-tipo[]" type="text">
															      </span>		              
															    </span>-->
													            <span class="fieldset">
													              <label for="data-inicial-rodada">Início</label>
													              <span>
													                <input  value="'.$row['data_inicial'].'" name="data-inicial-rodada[]" type="date">
													              </span>
													            </span>  
													            <span class="fieldset">
													              <label for="data-final-rodada">Fim</label>
													              <span>
													                <input  value="'.$row['data_final'].'" name="data-final-rodada[]" type="date">
													              </span>
													            </span>
															    <span class="fieldset rodadas-footer">
															      <!--<span>
															      <a class="collapse" onclick="collapse(this)" href="javascript:void(0)">
																	<i class="fa fa-angle-up"></i>
															      </a>
															      </span>	-->				    
															      <span> 
															      <a href="javascript:void(0)" onclick="removeRodada(this)" class="remove-rodada btn btn-2">Excluir</a>
															      </span>
															    </span>													            
													            <!--
													            <span class="fieldset rodadas-footer">
													              <div> 
													                <a href="'.$default_url.'/functions/delete.php?table=rodadas&uid='.$_GET['uid'].'&pid='.$_GET['id'].'&position='.$row['position'].'&id='.$row['id'].'" class="btn btn-2">Excluir</a>
													              </div>
													            </span>-->';
													            echo '
													          </li>
											      			';
												        endwhile;
												   //      if(!$res_sql_rodadas->num_rows){
															// echo '<li>
															//     <span class="fieldset">
															//       <label for="nivel-rodada">Nível</label>
															//       <span>
															//         <input value="1"  readonly="readonly" name="nivel-rodada[]" class="nivel_rodada" type="text">
															//       </span>		              
															//     </span>
															//     <!--<span class="fieldset">
															//       <label for="rodada-tipo">Tipo</label>
															//       <span>
															//         <input value=""  readonly="readonly" name="rodada-tipo[]" type="text">
															//       </span>		              
															//     </span>-->
													  //           <span class="fieldset">
													  //           	<label for="rodada_tipo">Tipo</label>
															// 		<span class="custom-combobox">
															//         <i class="fal fa-angle-down"></i>
															//         <select name="rodada-tipo[]">
															//           <option value="Rodada Comercial">Rodada Comercial</option>
															//           <option value="Rodada Técnica">Rodada Técnica</option>
															//         </select>
															//       	</span>
													  //           </span>				      
															//     <span class="fieldset">
															//       <label for="data-inicial-rodada">Início</label>
															//       <span>
															//         <input name="data-inicial-rodada[]" type="date">
															//       </span>
															//     </span>  
															//     <span class="fieldset">
															//       <label for="data-final-rodada">Fim</label>
															//       <span>
															//         <input name="data-final-rodada[]" type="date">
															//       </span>
															//     </span> 
															//     <span class="fieldset rodadas-footer">
															//       <span> 
															//         <a href="javascript:void(0)" onclick="removeRodada(this)" class="remove-rodada btn btn-2">Excluir</a>
															//       </span>
															//     </span>
															//   </li>';	
												   //      }
										      		} else {	
										      // 			echo '<li>
												    //         <span class="fieldset">
												    //           <label for="nivel-rodada">Nível</label>
												    //           <span>
												    //             <input value="1"  readonly="readonly" name="nivel-rodada[]" class="nivel_rodada" type="text">
												    //           </span>		              
												    //         </span>
												    //         <span class="fieldset">
												    //         	<label for="rodada_tipo">Tipo</label>
																// <span class="custom-combobox">
														  //       <i class="fal fa-angle-down"></i>
														  //       <select name="rodada-tipo[]">
														  //         <option value="Rodada Comercial">Rodada Comercial</option>
														  //         <option value="Rodada Técnica">Rodada Técnica</option>
														  //       </select>
														  //     	</span>
												    //         </span>	
												    //         <!--
														  //   <span class="fieldset">
														  //     <label for="rodada-tipo">Tipo</label>
														  //     <span>
														  //       <input value=""  readonly="readonly" name="rodada-tipo[]" type="text">
														  //     </span>		              
														  //   </span>-->
												    //         <span class="fieldset">
												    //           <label for="data-inicial-rodada">Início</label>
												    //           <span>
												    //             <input name="data-inicial-rodada[]" type="date">
												    //           </span>
												    //         </span>  
												    //         <span class="fieldset">
												    //           <label for="data-final-rodada">Fim</label>
												    //           <span>
												    //             <input name="data-final-rodada[]" type="date">
												    //           </span>
												    //         </span> 
												    //         <span class="fieldset rodadas-footer">
												    //           <span> 
												    //             <a href="javascript:void(0)" onclick="removeRodada(this)" class="remove-rodada btn btn-2">Excluir</a>
												    //           </span>
												    //         </span>
												    //       </li>';
												    }
										          echo '
										        </ul>
										        <!--
										        <div class="rodadas-footer">'; 
									                	if(isset($res_sql_rodadas)){
									                		echo '<a class="btn btn-2 deletar_rodada">Excluir</a>';
									                	} else {
									                		echo '<a href="javascript:void(0)" onclick="removeRodada(this)" class="remove-rodada btn btn-2 disabled">Excluir</a>';		
									                	}
									                echo '
										        </div>
										        -->
											</div>
										</div>
									';
								} elseif(str_replace('-', '_', to_permalink($value)) == 'responsavel') {
					        		echo '
									<div class="fieldset '.( ($_SESSION['userType'] == 'responsavel') ? 'disabled' : '' ).' field_id_'.str_replace('-', '_', to_permalink($value)).'">
									    <label for="'.str_replace('-', '_', to_permalink($value)).'">'.$value.'</label>
									    <span>';
								      		if(isset($_GET['id'])){
												$sql_responsaveis = "SELECT * FROM responsavel_by_processos WHERE pid = ".$_GET['id'];
										        
										        $res_sql_responsaveis = mysqli_query($conn,$sql_responsaveis);

										        while($row = mysqli_fetch_array($res_sql_responsaveis)) :
													$responsaveis = "SELECT * FROM `users` WHERE `userType` = 'responsavel' ORDER BY nome";
													$rid = $row['responsavel'];
													if ($responsavel = $conn->query($responsaveis)) {
														echo '<span><span class="fieldset">
															<span class="custom-combobox">
														    	<i class="fal fa-angle-down"></i>
											    				<select   name="'.str_replace('-', '_', to_permalink($value)).'[]">';
											    				echo '<option value="">Selecione uma opção</option>';
													    while($row = $responsavel->fetch_assoc()) :
															echo '<option '.(isset($_GET['id']) && $rid == $row['id'] ? 'selected="selected"' : '' ).' value="'.$row['id'].'">'.$row['nome'].'</option>';
														endwhile;
														echo '</select>
															  	</span>		  
															</span><a href="'.$default_url.'/functions/delete.php?table=responsavel_by_processos&uid='.$_GET['uid'].'&pid='.$_GET['id'].'&id='.$rid.'" class="removeElement"><i class="fal fa-close"></i></a></span>';
													}
	 												$responsavel->free();	
										        endwhile;

										        if(!$res_sql_responsaveis->num_rows){
													$responsaveis = "SELECT * FROM `users` WHERE `userType` = 'responsavel' ORDER BY nome";
													if ($responsavel = $conn->query($responsaveis)) {
														echo '<span><span class="fieldset">
															<span class="custom-combobox">
														    	<i class="fal fa-angle-down"></i>
											    				<select     name="'.str_replace('-', '_', to_permalink($value)).'[]">';
											    				echo '<option value="">Selecione uma opção</option>';
													    while($row = $responsavel->fetch_assoc()) :
															echo '<option value="'.$row['id'].'">'.$row['nome'].'</option>';
														endwhile;
														echo '</select>
															  	</span>		  
															</span></span>';
													}
	 												$responsavel->free();
	 											}										        	
								      		} else {
												$responsaveis = "SELECT * FROM `users` WHERE `userType` = 'responsavel' ORDER BY nome";
												if ($responsavel = $conn->query($responsaveis)) {
													echo '<span><span class="fieldset">
														<span class="custom-combobox">
													    	<i class="fal fa-angle-down"></i>
										    				<select     name="'.str_replace('-', '_', to_permalink($value)).'[]">';
										    				echo '<option value="">Selecione uma opção</option>';
												    while($row = $responsavel->fetch_assoc()) :
														echo '<option value="'.$row['id'].'">'.$row['nome'].'</option>';
													endwhile;
													echo '</select>
														  	</span>		  
														</span></span>';
												}
 												$responsavel->free();	
								      		}
									    echo '</span><div>
										      <a class="add-more" href="javascript:void(0)" onclick="addSimpleInput(this)">
										        <i class="fal fa-plus"></i>
										      </a>
										    </div>
										</div>';	



								} elseif(str_replace('-', '_', to_permalink($value)) == 'tipo_processo') {
						        		echo '
										<div class="fieldset '.( ($_SESSION['userType'] == 'responsavel') ? 'disabled' : '' ).' field_id_'.str_replace('-', '_', to_permalink($value)).'">
										    <label for="'.str_replace('-', '_', to_permalink($value)).'">'.$value.'</label>';

											if ($tipo_processo = $conn->query("SELECT * FROM `processos_tipos` ORDER BY tipo")) {
												echo '<span><span class="fieldset">
													<span class="custom-combobox">
												    	<i class="fal fa-angle-down"></i>
									    				<select  name="'.str_replace('-', '_', to_permalink($value)).'">';
									    				echo '<option value="">Selecione uma opção</option>';
											    while($row = $tipo_processo->fetch_assoc()) :
													echo '<option '.( (isset($processo) && $processo['tipo_processo'] == $row['tipo']) ? 'selected="selected"' : '' ).' value="'.$row['tipo'].'">'.$row['tipo'].'</option>';
												endwhile;
												echo '</select>
													  	</span>		  
													</span></span>';
											}
										    echo '
										  </div>';	
								} elseif(str_replace('-', '_', to_permalink($value)) == 'subfamilia') {
					        		echo '
									<div id="subfamilia" class="fieldset '.( ($_SESSION['userType'] == 'responsavel') ? 'disabled' : '' ).' field_id_'.str_replace('-', '_', to_permalink($value)).'">
									    <label for="'.str_replace('-', '_', to_permalink($value)).'">'.$value.'</label>
									    <span class="custom-combobox">
									      <i class="fal fa-angle-down"></i>
									      <select  name="subfamilia">
									      	<option value="">Selecione</option>
									      	'; 
										        $subfamilias = "SELECT * FROM subfamilia";
										        $res_subfamilias = mysqli_query($conn, $subfamilias);
										        $subfamilia = mysqli_fetch_assoc($res_compradores);

										        while($row = mysqli_fetch_array($res_subfamilias)) :
										        	echo '<option '.( $processo['subfamilia'] == $row['subfamilia'].' | Homologável: '.$row['homologavel'].' | Nível de Risco: '.$row['nivel_de_risco'].' | ARC: '.$row['arc'] ? 'selected="selected"' : '' ).' value="'.$row['subfamilia'].' | Homologável: '.$row['homologavel'].' | Nível de Risco: '.$row['nivel_de_risco'].' | ARC: '.$row['arc'].'">'.$row['subfamilia'].' | Homologável: '.$row['homologavel'].' | Nível de Risco: '.$row['nivel_de_risco'].' | ARC: '.$row['arc'].'</option>';
										        endwhile;

										        // '.$row['subfamilia'].' | Homologável: '.$row['homologavel'].' | Nível de Risco: '.$row['nivel_de_risco'].' | ARC: '.$row['arc']'
									      	echo '
									      </select>
									    </span>
									    '; 
									    if(isset($_GET['id']) && isset($processo) && $processo['subfamilia']){
											echo '<div class="subfamilia_result">'.$processo['subfamilia'].'</div>';
									    }
									    echo'
									  </div>';
								} elseif(str_replace('-', '_', to_permalink($value)) == 'comprador') {
					        		if(!isset($_GET['id']) && $_SESSION['userType'] == 'comprador' || isset($_GET['id']) && $_SESSION['userType'] == 'comprador' && $processo['comprador'] == $_SESSION['uid']){
										echo '<input tabindex="'.$i.'" value="'.$_SESSION['usuario'].'" name="'.str_replace('-', '_', to_permalink($value)).'" type="hidden">';
					        		} else {
						        		echo '
										<div class="fieldset '.( ($_SESSION['userType'] == 'responsavel') ? 'disabled' : '' ).' field_id_'.str_replace('-', '_', to_permalink($value)).'">
										    <label for="'.str_replace('-', '_', to_permalink($value)).'">'.$value.'</label>';

											$compradores = "SELECT * FROM `users` WHERE `userType` = 'comprador' ORDER BY nome";
											if ($comprador = $conn->query($compradores)) {
												echo '<span><span class="fieldset">
													<span class="custom-combobox">
												    	<i class="fal fa-angle-down"></i>
									    				<select  name="'.str_replace('-', '_', to_permalink($value)).'">';
									    				echo '<option value="">Selecione uma opção</option>';
											    while($row = $comprador->fetch_assoc()) :
													echo '<option '.( (isset($processo) && $processo['comprador'] == $row['id']) ? 'selected="selected"' : '' ).' value="'.$row['id'].'">'.$row['nome'].'</option>';
												endwhile;
												echo '</select>
													  	</span>		  
													</span></span>';
											}
											$comprador->free();											    
										    echo '
										  </div>';	
									
					        		}
								} else {
									echo '<input tabindex="'.$i.'" value="'. ( (isset($_GET['id']) && isset($processo)) ? $processo[str_replace('-', '_', to_permalink($value))] : '' ) .'" name="'.str_replace('-', '_', to_permalink($value)).'" type="hidden">';
								}
			        		} else {	        			
			        			if(str_replace('-', '_', to_permalink($value)) == 'sociedade'){
			        				?>
			        				  <div class="fieldset field_id_<?php echo str_replace('-', '_', to_permalink($value)); ?> <?php echo ($_SESSION['userType'] == 'responsavel') ? 'disabled' : ''; ?>">
									  <ul id="sociedades" class="rows">
									    <li class="row">
									      <div>
									        <strong>Sociedades</strong>
									      </div>
									      <div>
									        <strong>Valor</strong>
									      </div>
									      <div>
									        <strong>Moeda</strong>
									      </div>
									    </li>
										<?php
											$moedas = array(
												array('slug'=>'USD', 'title'=>'United States Dollars'),array('slug'=>'EUR', 'title'=>'Euro'),array('slug'=>'GBP', 'title'=>'United Kingdom Pounds'),array('slug'=>'DZD', 'title'=>'Algeria Dinars'),array('slug'=>'ARP', 'title'=>'Argentina Pesos'),array('slug'=>'AUD', 'title'=>'Australia Dollars'),array('slug'=>'ATS', 'title'=>'Austria Schillings'),array('slug'=>'BSD', 'title'=>'Bahamas Dollars'),array('slug'=>'BBD', 'title'=>'Barbados Dollars'),array('slug'=>'BEF', 'title'=>'Belgium Francs'),array('slug'=>'BMD', 'title'=>'Bermuda Dollars'),array('slug'=>'BRR', 'title'=>'Brazil Real'),array('slug'=>'BGL', 'title'=>'Bulgaria Lev'),array('slug'=>'CAD', 'title'=>'Canada Dollars'),array('slug'=>'CLP', 'title'=>'Chile Pesos'),array('slug'=>'CNY', 'title'=>'China Yuan Renmimbi'),array('slug'=>'CYP', 'title'=>'Cyprus Pounds'),array('slug'=>'CSK', 'title'=>'Czech Republic Koruna'),array('slug'=>'DKK', 'title'=>'Denmark Kroner'),array('slug'=>'NLG', 'title'=>'Dutch Guilders'),array('slug'=>'XCD', 'title'=>'Eastern Caribbean Dollars'),array('slug'=>'EGP', 'title'=>'Egypt Pounds'),array('slug'=>'FJD', 'title'=>'Fiji Dollars'),array('slug'=>'FIM', 'title'=>'Finland Markka'),array('slug'=>'FRF', 'title'=>'France Francs'),array('slug'=>'DEM', 'title'=>'Germany Deutsche Marks'),array('slug'=>'XAU', 'title'=>'Gold Ounces'),array('slug'=>'GRD', 'title'=>'Greece Drachmas'),array('slug'=>'HKD', 'title'=>'Hong Kong Dollars'),array('slug'=>'HUF', 'title'=>'Hungary Forint'),array('slug'=>'ISK', 'title'=>'Iceland Krona'),array('slug'=>'INR', 'title'=>'India Rupees'),array('slug'=>'IDR', 'title'=>'Indonesia Rupiah'),array('slug'=>'IEP', 'title'=>'Ireland Punt'),array('slug'=>'ILS', 'title'=>'Israel New Shekels'),array('slug'=>'ITL', 'title'=>'Italy Lira'),array('slug'=>'JMD', 'title'=>'Jamaica Dollars'),array('slug'=>'JPY', 'title'=>'Japan Yen'),array('slug'=>'JOD', 'title'=>'Jordan Dinar'),array('slug'=>'KRW', 'title'=>'Korea (South) Won'),array('slug'=>'LBP', 'title'=>'Lebanon Pounds'),array('slug'=>'LUF', 'title'=>'Luxembourg Francs'),array('slug'=>'MYR', 'title'=>'Malaysia Ringgit'),array('slug'=>'MXP', 'title'=>'Mexico Pesos'),array('slug'=>'NLG', 'title'=>'Netherlands Guilders'),array('slug'=>'NZD', 'title'=>'New Zealand Dollars'),array('slug'=>'NOK', 'title'=>'Norway Kroner'),array('slug'=>'PKR', 'title'=>'Pakistan Rupees'),array('slug'=>'XPD', 'title'=>'Palladium Ounces'),array('slug'=>'PHP', 'title'=>'Philippines Pesos'),array('slug'=>'XPT', 'title'=>'Platinum Ounces')
											);

											$chosen_sociedades = array();
											$chosen_valores = array();
											$chosen_moedas = array();

											$sociedades = "SELECT * FROM sociedades";
									        $rsociedades = mysqli_query($conn,$sociedades);
									        $j = -1;

									        while($row = mysqli_fetch_array($rsociedades)) :
									        	$j++; 

									        	if(isset($_GET['id'])){
									        		$count = -1;
								        			foreach ($sociedades_by_processos as $key => $value) {
								        				$count++;

							        					if($value['sociedade'] == $row['sociedade']){
							        						array_push($chosen_sociedades, $value['sociedade']);
							        						array_push($chosen_valores, $value['valor']);
							        						array_push($chosen_moedas, $value['moeda']);
							        					}
							        				}									        		
									        	}

									        	echo '
													<li id="'.to_permalink($row['sociedade']).'" class="row">
												      <div>
												      	<div class="custom-checkbox">
															<input '.( (in_array($row['sociedade'], $chosen_sociedades) ) ? 'checked="checked"' : '' ).' type="checkbox" name="sociedade[]" value="'.$row['sociedade'].'">
															<label for="'.$row['sociedade'].'"></label>
												      	</div>
												        <label for="sociedade">'.$row['sociedade'].'</label>
												      </div>
												      <div>';
															if(isset($_GET['id'])){
																$count = -1;
																$val = false;
																foreach ($sociedades_by_processos as $key => $value) {
																	$count++;
																	if($value['sociedade'] == $row['sociedade']){
																		echo '<input class="money" value="'.$chosen_valores[$count].'" name="valor[]" type="text">';
																		$val = true;
																	}
																}	
																if(!$val){
																	echo '<input class="money" name="valor[]" type="text">';
																}
															} else {
																echo '<input class="money" name="valor[]" type="text">';
															}
												        echo '
												      </div>
												      <div>';
												      echo '
												        <span class="custom-combobox">
												          <i class="fal fa-angle-down"></i>
												          <select name="moeda[]" class="moeda">
												          	<option value="">Selecione uma moeda</option>';
															if(isset($_GET['id'])){
																$count = -1;
																$val = false;
																foreach ($sociedades_by_processos as $key => $value) {
																	$count++;
																	$currency = 0;
																	if($value['sociedade'] == $row['sociedade']){
																		$moeda = $chosen_moedas[$count];

															          	foreach ($moedas as $key => $value) {
															          		echo '<option '.( ($value['slug'] == $moeda) ? 'selected' : '' ).' value="'.$value['slug'].'">'.$value['title'].'</option>';
															          	}																		

																		$val = true;
																	}
																}	
																if(!$val){
														          	foreach ($moedas as $key => $value) {
														          		echo '<option value="'.$value['slug'].'">'.$value['title'].'</option>';
														          	}
																}
															} else {
													          	foreach ($moedas as $key => $value) {
													          		echo '<option value="'.$value['slug'].'">'.$value['title'].'</option>';
													          	}
															}
												          	echo '
												          </select>
												        </span>
												      </div>
												      ';		
												      echo '
												    </li>
									        	';
									        endwhile;
										?>
									  </ul>
									  </div>
			        				<?php
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
				<div class="forms-footer">
					<button class="<?php echo ($_SESSION['userType'] == 'responsavel') ? 'disabled' : ''; ?> btn btn-1">Salvar</button>
					<input type="hidden" name="action" value="<?php echo (isset($_GET['id'])) ? 'atualizar' : 'salvar'; ?>" />
				</div>
			</form>
		</div>
	</div>
</section>
<?php include('commons/footer.php'); ?>