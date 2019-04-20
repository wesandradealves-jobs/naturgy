        <?php if(isset($_SESSION['uid'])) : ?>
          <nav class="navigation">
        		<div class="container">
        			<ul>
                <li>
                      <a href="processos">Processos</a>
                </li> 
                <?php if(isset($_SESSION['userType']) && $_SESSION['userType']!='responsavel') : ?>
                <li>
                      <a href="cadastro/processos">Cadastro de Processos</a>
                      <?php if($_SESSION['userType']=='administrador') : ?>
                      <ul>
                        <li><a href="cadastro/subfamilia">Subfamília(s)</a></li>
                        <!-- <li><a href="cadastro/tipos-de-processos">Tipos de Processos</a></li> -->
                      </ul>
                      <?php endif; ?>
                </li>  
                <?php endif; ?>  
                <?php if(isset($_SESSION['userType']) && $_SESSION['userType']=='administrador') : ?>
                  <li>
                        <a href="usuarios">Usuários</a>
                  </li>  
                  <li>
                        <a href="cadastro/usuarios">Cadastro de Usuários</a>
                  </li>
                <?php endif; ?>
        			</ul>
        		</div>
        	</nav>
          <?php if($basename != 'tipos-de-processos' && $basename != 'subfamilia'): ?>
            <form method="GET" action="busca/<?php echo ($basename == 'usuarios' || $basename == 'usuario') ? 'usuarios' : 'processos'; ?>/" class="searchbar forms">
                <div class="container">
                      <div class="fieldset">
                        <i class="fa fa-search"></i>
                      </div>
                      <?php 
                        if($basename == 'usuarios' || $basename == 'usuario') :
                          ?>
                          <div class="fieldset">
                                <label for="nome">Nome de usuário</label>
                                <span>
                                    <input name="search['nome']" type="text">
                                </span>
                          </div>
                          <div class="fieldset">
                                <label for="sap">Número da matrícula (SAP)</label>
                                <span>
                                    <input name="search['sap']" type="text">
                                </span>
                          </div>        
                        <?php
                          elseif($basename == 'processos' || $basename == 'processo') :
                        ?>
                          <div class="fieldset">
                              <label for="search[filtro]">Filtro</label>
                              <span class="custom-combobox">
                                  <i class="fal fa-angle-down"></i>
                                  <select required="required" name="search[filtro]">
                                    <option value="">Selecione uma opção</option>
                                <!--     <?php 
                                      $tipo_processo = $conn->query("SELECT * FROM `processos_tipos` ORDER BY tipo");
                                      while($row = $tipo_processo->fetch_assoc()) :
                                        echo '<option value="'.$row['tipo'].'">'.$row['tipo'].'</option>';
                                      endwhile;
                                    ?> -->
                                    <?php 
                                      $labelsSQL = 'SELECT * FROM processos_labels ORDER BY label ASC';
                                      $label = mysqli_query($conn,$labelsSQL);

                                      while($rowl = mysqli_fetch_array($label)) :
                                        if(str_replace('-','_',to_permalink($rowl['label'])) != "uid" && str_replace('-','_',to_permalink($rowl['label'])) != "valor" && str_replace('-','_',to_permalink($rowl['label'])) != "id"  && str_replace('-','_',to_permalink($rowl['label'])) != "subfamilia"  && str_replace('-','_',to_permalink($rowl['label'])) != "moeda"  && str_replace('-','_',to_permalink($rowl['label'])) != "sociedade"  && str_replace('-','_',to_permalink($rowl['label'])) != "rodadas" && str_replace('-','_',to_permalink($rowl['label'])) != "responsavel" && str_replace('-','_',to_permalink($rowl['label'])) != "fornecedor" && str_replace('-','_',to_permalink($rowl['label'])) != "comprador"){
                                          echo '<option value="'.str_replace('-','_',to_permalink($rowl['label'])).'">'.$rowl['label'].'  </option>';
                                        }
                                      endwhile;
                                    ?>                                    
                                  </select>
                              </span>
                          </div>                      
                          <div class="fieldset">
                              <label for="search[keyword]">Palavra-chave</label>
                              <span>
                                  <input required="required" name="search[keyword]" type="text">
                              </span>
                          </div><!-- 
                          <div class="fieldset">
                              <label for="nome_processo">Nome do processo</label>
                              <span>
                                  <input name="search['nome_processo']" type="text">
                              </span>
                          </div>
                          <div class="fieldset">
                              <label for="tipo_processo">Tipo do processo</label>
                              <span class="custom-combobox">
                                  <i class="fal fa-angle-down"></i>
                                  <select name="search['tipo_processo']">
                                    <option value="">Selecione uma opção</option>
                                    <?php 
                                      $tipo_processo = $conn->query("SELECT * FROM `processos_tipos` ORDER BY tipo");
                                      while($row = $tipo_processo->fetch_assoc()) :
                                        echo '<option value="'.$row['tipo'].'">'.$row['tipo'].'</option>';
                                      endwhile;
                                    ?>
                                  </select>
                              </span>
                          </div> -->
                        <?php
                        endif;
                      ?>        
                      <div class="fieldset">
                        <button class="btn btn-1">Buscar</button>
                      </div>
                  </div>
            </form> 
            <?php else : ?>
            <form class="searchbar forms assets">
                <div class="container">
                      <?php 
                        if($basename == 'subfamilia') :
                          ?>
                          <div class="fieldset checkbox">
                            <label for="homologavel">Hom.</label>
                            <span>
                              <span class="custom-checkbox">
                                <input name="homologavel" type="checkbox">
                                <label></label>
                              </span>  
                            </span>
                          </div>                          
                          <div class="fieldset">
                            <label for="subfamilia">Subfamilia</label>
                            <span>
                                <input required="required" name="subfamilia" type="text">
                            </span>
                          </div>
                          <div class="fieldset">
                            <label for="nivel_de_risco">Nível de Risco</label>
                            <span>
                                <input required="required" name="nivel_de_risco" type="text">
                            </span>
                          </div>  
                          <div class="fieldset">
                            <label for="arc">ARC</label>
                            <span>
                                <input required="required" name="arc" type="text">
                            </span>
                          </div>  
                          <input type="hidden" name="table" value="subfamilia">
                      <?php else : ?>
                          <div class="fieldset">
                            <label for="tipo">Tipo</label>
                            <span>
                                <input required="required" name="tipo" type="text">
                            </span>
                          </div>      
                          <input type="hidden" name="table" value="processos_tipos">                  
                      <?php endif; ?>
                      <div class="fieldset">
                        <button class="btn btn-1">Inserir</button>
                      </div>
                  </div>
            </form> 
          <?php endif; ?>
        <?php
        endif;
      ?>   