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
                            <label for="numero_processo">Número do processo</label>
                            <span>
                                <input name="search['numero_processo']" type="text">
                            </span>
                        </div>
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
                        </div>
                      <?php
                      endif;
                    ?>        
                    <div class="fieldset">
                      <button class="btn btn-1">Buscar</button>
                    </div>
                </div>
          </form> 
        <?php
        endif;
      ?>   