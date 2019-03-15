<?php 
	include('commons/header.php'); 
	if(isset($_GET['id'])) :
		$query_processos = "SELECT * FROM processos WHERE `processos`.`id` = '".$_GET['id']."'";
		$processo = mysqli_fetch_assoc(mysqli_query($conn, $query_processos));
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
					        		echo '
									<div class="fieldset '.( ($_SESSION['userType'] == 'responsavel') ? 'disabled' : '' ).' field_id_'.str_replace('-', '_', to_permalink($value)).'">
									    <label for="'.str_replace('-', '_', to_permalink($value)).'">'.$value.'</label>
									    <span>
									      <input tabindex="'.$i.'" value="'. ( (isset($_GET['id']) && isset($processo)) ? $processo[str_replace('-', '_', to_permalink($value))] : '' ) .'" name="'.str_replace('-', '_', to_permalink($value)).'" type="'.( (stripos( str_replace('-', '_', to_permalink($value)), 'data' )) ? 'date' : 'text' ).'">
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
													          <li>
													            <span class="fieldset">
													              <label for="nivel-rodada">Nível</label>
													              <span>
													                <input readonly="readonly" value="'.$row['nivel'].'" name="nivel-rodada[]" class="nivel_rodada" type="text">
													              </span>		              
													            </span> 
													            <span class="fieldset">
													            	<label for="rodada_tipo">Tipo</label>
																	<span class="custom-combobox">
															        <i class="fal fa-angle-down"></i>
															        <select name="rodada-tipo[]">
															          <option '.( ($row['tipo'] == 'Rodada Comercial') ? 'selected="selected"' : '' ).' value="Rodada Comercial">Rodada Comercial</option>
															          <option '.( ($row['tipo'] == 'Rodada Técnica') ? 'selected="selected"' : '' ).' value="Rodada Técnica">Rodada Técnica</option>
															        </select>
															      	</span>
													            </span>
													            <span class="fieldset">
													              <label for="data-inicial-rodada">Início</label>
													              <span>
													                <input value="'.$row['data_inicial'].'" name="data-inicial-rodada[]" type="date">
													              </span>
													            </span>  
													            <span class="fieldset">
													              <label for="data-final-rodada">Fim</label>
													              <span>
													                <input value="'.$row['data_final'].'" name="data-final-rodada[]" type="date">
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
														  //         <option selected="selected" value="Rodada Comercial">Rodada Comercial</option>
														  //         <option value="Rodada Técnica">Rodada Técnica</option>
														  //       </select>
														  //     	</span>
												    //         </span>
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
												    //         <!--
												    //         <span class="fieldset rodadas-footer">
												    //           <span> 
												    //             <a href="javascript:void(0)" onclick="removeRodada(this)" class="btn btn-2">Excluir</a>
												    //           </span>
												    //         </span>
												    //         -->
												    //       </li>
												    //       <li>
												    //         <span class="fieldset">
												    //           <label for="nivel-rodada">Nível</label>
												    //           <span>
												    //             <input value="1" readonly="readonly" name="nivel-rodada[]" class="nivel_rodada" type="text">
												    //           </span>		              
												    //         </span> 
												    //         <span class="fieldset">
												    //         	<label for="rodada_tipo">Tipo</label>
																// <span class="custom-combobox">
														  //       <i class="fal fa-angle-down"></i>
														  //       <select name="rodada-tipo[]">
														  //         <option value="Rodada Comercial">Rodada Comercial</option>
														  //         <option value="Rodada Técnica" selected="selected">Rodada Técnica</option>
														  //       </select>
														  //     	</span>
												    //         </span>
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
												    //         <!--
												    //         <span class="fieldset rodadas-footer">
												    //           <span> 
												    //             <a href="javascript:void(0)" onclick="removeRodada(this)" class="btn btn-2">Excluir</a>
												    //           </span>
												    //         </span>
												    //         -->
												    //       </li>
												    //      ';
										      		} else {	
										      			echo '<li>
												            <span class="fieldset">
												              <label for="nivel-rodada">Nível</label>
												              <span>
												                <input value="1"  readonly="readonly" name="nivel-rodada[]" class="nivel_rodada" type="text">
												              </span>		              
												            </span> 
												            <span class="fieldset">
												            	<label for="rodada_tipo">Tipo</label>
																<span class="custom-combobox">
														        <i class="fal fa-angle-down"></i>
														        <select name="rodada-tipo[]">
														          <option selected="selected" value="Rodada Comercial">Rodada Comercial</option>
														          <option value="Rodada Técnica">Rodada Técnica</option>
														        </select>
														      	</span>
												            </span>
												            <span class="fieldset">
												              <label for="data-inicial-rodada">Início</label>
												              <span>
												                <input name="data-inicial-rodada[]" type="date">
												              </span>
												            </span>  
												            <span class="fieldset">
												              <label for="data-final-rodada">Fim</label>
												              <span>
												                <input name="data-final-rodada[]" type="date">
												              </span>
												            </span> 
												            <!--
												            <span class="fieldset rodadas-footer">
												              <span> 
												                <a href="javascript:void(0)" onclick="removeRodada(this)" class="btn btn-2">Excluir</a>
												              </span>
												            </span>
												            -->
												          </li>
												          <li>
												            <span class="fieldset">
												              <label for="nivel-rodada">Nível</label>
												              <span>
												                <input value="1"  readonly="readonly" name="nivel-rodada[]" class="nivel_rodada" type="text">
												              </span>		              
												            </span> 
												            <span class="fieldset">
												            	<label for="rodada_tipo">Tipo</label>
																<span class="custom-combobox">
														        <i class="fal fa-angle-down"></i>
														        <select name="rodada-tipo[]">
														          <option value="Rodada Comercial">Rodada Comercial</option>
														          <option value="Rodada Técnica" selected="selected">Rodada Técnica</option>
														        </select>
														      	</span>
												            </span>
												            <span class="fieldset">
												              <label for="data-inicial-rodada">Início</label>
												              <span>
												                <input name="data-inicial-rodada[]" type="date">
												              </span>
												            </span>  
												            <span class="fieldset">
												              <label for="data-final-rodada">Fim</label>
												              <span>
												                <input name="data-final-rodada[]" type="date">
												              </span>
												            </span> 
												            <!--
												            <span class="fieldset rodadas-footer">
												              <span> 
												                <a href="javascript:void(0)" onclick="removeRodada(this)" class="btn btn-2">Excluir</a>
												              </span>
												            </span>
												            -->
												          </li>
												         ';
												    }
										          echo '
										        </ul>
										        <div class="rodadas-footer">'; 
									                	if(isset($res_sql_rodadas)){
									                		echo '<a class="btn btn-2 deletar_rodada">Excluir</a>';
									                	} else {
									                	echo '<a href="javascript:void(0)" onclick="removeRodada(this)" class="btn btn-2 disabled">Excluir</a>';		
									                	}
									                echo '
										        </div>
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
											    				<select name="'.str_replace('-', '_', to_permalink($value)).'[]">';
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


												$responsaveis = "SELECT * FROM `users` WHERE `userType` = 'responsavel' ORDER BY nome";
												if ($responsavel = $conn->query($responsaveis)) {
													echo '<span><span class="fieldset">
														<span class="custom-combobox">
													    	<i class="fal fa-angle-down"></i>
										    				<select name="'.str_replace('-', '_', to_permalink($value)).'[]">';
										    				echo '<option value="">Selecione uma opção</option>';
												    while($row = $responsavel->fetch_assoc()) :
														echo '<option value="'.$row['id'].'">'.$row['nome'].'</option>';
													endwhile;
													echo '</select>
														  	</span>		  
														</span></span>';
												}
 												$responsavel->free();	
								      		} else {
												$responsaveis = "SELECT * FROM `users` WHERE `userType` = 'responsavel' ORDER BY nome";
												if ($responsavel = $conn->query($responsaveis)) {
													echo '<span><span class="fieldset">
														<span class="custom-combobox">
													    	<i class="fal fa-angle-down"></i>
										    				<select name="'.str_replace('-', '_', to_permalink($value)).'[]">';
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
									    				<select name="'.str_replace('-', '_', to_permalink($value)).'">';
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
									      <select name="subfamilia">
									      	<option value="">Selecione</option>
									      	'; 
										        $subfamilias = "SELECT * FROM subfamilia";
										        $res_subfamilias = mysqli_query($conn, $subfamilias);
										        $subfamilia = mysqli_fetch_assoc($res_compradores);

										        while($row = mysqli_fetch_array($res_subfamilias)) :
										        	echo '<option '.( $processo['subfamilia'] == $row['subfamilia'] ? 'selected="selected"' : '' ).' value="'.$row['subfamilia'].'">'.$row['subfamilia'].'</option>';
										        endwhile;
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
					        		if(!isset($_GET['id']) && $_SESSION['userType'] == 'comprador' || isset($_GET['id']) && $_SESSION['userType'] == 'comprador' && $processo['comprador'] == $_SESSION['usuario']){
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
									    				<select name="'.str_replace('-', '_', to_permalink($value)).'">';
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
									        <strong>Valor Total</strong>
									      </div>
									      <div>
									        <strong>Moeda</strong>
									      </div>
									    </li>
										<?php 
											$sociedades = "SELECT * FROM sociedades";
									        $rsociedades = mysqli_query($conn,$sociedades);
									        $j = 0;
									        while($row = mysqli_fetch_array($rsociedades)) :
									        	$j++; 
									        	echo '
													<li id="'.to_permalink($row['sociedade']).'" class="row">
												      <div>
												      	<div class="custom-checkbox">
															<input type="checkbox" name="sociedade[]" value="'.$row['sociedade'].'">
															<label for="'.$row['sociedade'].'"></label>
												      	</div>
												        <label for="sociedade">'.$row['sociedade'].'</label>
												      </div>';
												      if($j <= 1){
												      echo '
													      <div>
													        <input class="money" value="'. ( (isset($_GET['id']) && isset($processo)) ? $processo['valor'] : '' ) .'" name="valor" type="text">
													      </div>
													      <div>
													        <span class="custom-combobox">
													          <i class="fal fa-angle-down"></i>
													          <select name="moeda" class="moeda">
													          	<option>Selecione uma moeda</option>
																<option value="USD">United States Dollars</option>
																<option value="EUR">Euro</option>
																<option value="GBP">United Kingdom Pounds</option>
																<option value="DZD">Algeria Dinars</option>
																<option value="ARP">Argentina Pesos</option>
																<option value="AUD">Australia Dollars</option>
																<option value="ATS">Austria Schillings</option>
																<option value="BSD">Bahamas Dollars</option>
																<option value="BBD">Barbados Dollars</option>
																<option value="BEF">Belgium Francs</option>
																<option value="BMD">Bermuda Dollars</option>
																<option value="BRR">Brazil Real</option>
																<option value="BGL">Bulgaria Lev</option>
																<option value="CAD">Canada Dollars</option>
																<option value="CLP">Chile Pesos</option>
																<option value="CNY">China Yuan Renmimbi</option>
																<option value="CYP">Cyprus Pounds</option>
																<option value="CSK">Czech Republic Koruna</option>
																<option value="DKK">Denmark Kroner</option>
																<option value="NLG">Dutch Guilders</option>
																<option value="XCD">Eastern Caribbean Dollars</option>
																<option value="EGP">Egypt Pounds</option>
																<option value="FJD">Fiji Dollars</option>
																<option value="FIM">Finland Markka</option>
																<option value="FRF">France Francs</option>
																<option value="DEM">Germany Deutsche Marks</option>
																<option value="XAU">Gold Ounces</option>
																<option value="GRD">Greece Drachmas</option>
																<option value="HKD">Hong Kong Dollars</option>
																<option value="HUF">Hungary Forint</option>
																<option value="ISK">Iceland Krona</option>
																<option value="INR">India Rupees</option>
																<option value="IDR">Indonesia Rupiah</option>
																<option value="IEP">Ireland Punt</option>
																<option value="ILS">Israel New Shekels</option>
																<option value="ITL">Italy Lira</option>
																<option value="JMD">Jamaica Dollars</option>
																<option value="JPY">Japan Yen</option>
																<option value="JOD">Jordan Dinar</option>
																<option value="KRW">Korea (South) Won</option>
																<option value="LBP">Lebanon Pounds</option>
																<option value="LUF">Luxembourg Francs</option>
																<option value="MYR">Malaysia Ringgit</option>
																<option value="MXP">Mexico Pesos</option>
																<option value="NLG">Netherlands Guilders</option>
																<option value="NZD">New Zealand Dollars</option>
																<option value="NOK">Norway Kroner</option>
																<option value="PKR">Pakistan Rupees</option>
																<option value="XPD">Palladium Ounces</option>
																<option value="PHP">Philippines Pesos</option>
																<option value="XPT">Platinum Ounces</option>
																<option value="PLZ">Poland Zloty</option>
																<option value="PTE">Portugal Escudo</option>
																<option value="ROL">Romania Leu</option>
																<option value="RUR">Russia Rubles</option>
																<option value="SAR">Saudi Arabia Riyal</option>
																<option value="XAG">Silver Ounces</option>
																<option value="SGD">Singapore Dollars</option>
																<option value="SKK">Slovakia Koruna</option>
																<option value="ZAR">South Africa Rand</option>
																<option value="KRW">South Korea Won</option>
																<option value="ESP">Spain Pesetas</option>
																<option value="XDR">Special Drawing Right (IMF)</option>
																<option value="SDD">Sudan Dinar</option>
																<option value="SEK">Sweden Krona</option>
																<option value="CHF">Switzerland Francs</option>
																<option value="TWD">Taiwan Dollars</option>
																<option value="THB">Thailand Baht</option>
																<option value="TTD">Trinidad and Tobago Dollars</option>
																<option value="TRL">Turkey Lira</option>
																<option value="VEB">Venezuela Bolivar</option>
																<option value="ZMK">Zambia Kwacha</option>
																<option value="EUR">Euro</option>
																<option value="XCD">Eastern Caribbean Dollars</option>
																<option value="XDR">Special Drawing Right (IMF)</option>
																<option value="XAG">Silver Ounces</option>
																<option value="XAU">Gold Ounces</option>
																<option value="XPD">Palladium Ounces</option>
																<option value="XPT">Platinum Ounces</option>
													          </select>
													        </span>
													      </div>';   	
												      }
											        	if(isset($_GET['id'])){
										        			foreach ($sociedades_by_processos as $key => $value) {
									        					if($value['sociedade'] == $row['sociedade']){
									        						echo '
									        							<script>
									        								document.querySelector("#'.to_permalink($value['sociedade']).' input").checked = true;

									        								document.querySelector(".moeda").setAttribute("value", "'.$processo['moeda'].'");

																		    for(var i = 0, j = document.querySelector(".moeda").options.length; i < j; ++i) {
																		        if(document.querySelector(".moeda").options[i].value === "'.$processo['moeda'].'") {
																		           document.querySelector(".moeda").selectedIndex = i;
																		           break;
																		        }
																		    }
																		</script>
									        						';
									        					}
										        			}
											        	}												      
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