<?php 
    require_once("../config/config.php");
    require_once("../functions/functions.php");

	if($_POST['action']=='salvar') {

        if($_POST['nome_processo'] && $_POST['numero_processo']){
            $query = "SELECT * FROM processos WHERE numero_processo = '".$_POST['numero_processo']."' OR nome_processo = "."'".$_POST['nome_processo']."'";
        } elseif($_POST['nome_processo'] && $_POST['numero_processo'] == ''){
            $query = "SELECT * FROM processos WHERE nome_processo = "."'".$_POST['nome_processo']."'";
        } elseif($_POST['nome_processo'] == '' && $_POST['numero_processo']){
            $query = "SELECT * FROM processos WHERE numero_processo = "."'".$_POST['numero_processo']."'";
        }

        $result = mysqli_fetch_assoc(mysqli_query($conn, $query));

        if(!isset($result)) {
            $insert_query = "INSERT INTO processos (uid,nome_processo,numero_processo,tipo_processo,comprador,numero_requisicao,subfamilia,grupo_de_compras,data_disp_compras,cod_material,adjudicacao_num_pa,adjudicacao_vencimento,dados_bravo_sav,dados_bravo_rqf,fornecedor,fornecedor_data_inicial,fornecedor_data_final,estrategia_data_inicial,estrategia_data_final,elaboracao_documento_data_inicial,elaboracao_documento_data_final,negociacao_direta_data_inicial,negociacao_direta_data_final,leilao_data_inicial,leilao_data_final,elaboracao_pa_data_inicial,elaboracao_pa_data_final,workflow_data_inicial,workflow_data_final,numero_pedido,criacao_de_pedido_data_inicial,criacao_de_pedido_data_final,tramite_assinatura_interna_diretor_1,tramite_assinatura_interna_diretor_1_data_inicial,tramite_assinatura_interna_diretor_1_data_final,tramite_assinatura_interna_diretor_2,tramite_assinatura_interna_diretor_2_data_inicial,tramite_assinatura_interna_diretor_2_data_final,tramite_assinatura_externa_email,tramite_assinatura_externa_retirada,tramite_assinatura_externa_devolucao,disponivel_sap,status) VALUES ('".$_POST['uid']."','".$_POST['nome_processo']."','".$_POST['numero_processo']."','".$_POST['tipo_processo']."','".$_POST['comprador']."','".$_POST['numero_requisicao']."','".$_POST['subfamilia']."','".$_POST['grupo_de_compras']."','".$_POST['data_disp_compras']."','".$_POST['cod_material']."','".$_POST['adjudicacao_num_pa']."','".$_POST['adjudicacao_vencimento']."','".$_POST['dados_bravo_sav']."','".$_POST['dados_bravo_rqf']."','".$_POST['fornecedor']."','".$_POST['fornecedor_data_inicial']."','".$_POST['fornecedor_data_final']."','".$_POST['estrategia_data_inicial']."','".$_POST['estrategia_data_final']."','".$_POST['elaboracao_documento_data_inicial']."','".$_POST['elaboracao_documento_data_final']."','".$_POST['negociacao_direta_data_inicial']."','".$_POST['negociacao_direta_data_final']."','".$_POST['leilao_data_inicial']."','".$_POST['leilao_data_final']."','".$_POST['elaboracao_pa_data_inicial']."','".$_POST['elaboracao_pa_data_final']."','".$_POST['workflow_data_inicial']."','".$_POST['workflow_data_final']."','".$_POST['numero_pedido']."','".$_POST['criacao_de_pedido_data_inicial']."','".$_POST['criacao_de_pedido_data_final']."','".$_POST['tramite_assinatura_interna_diretor_1']."','".$_POST['tramite_assinatura_interna_diretor_1_data_inicial']."','".$_POST['tramite_assinatura_interna_diretor_1_data_final']."','".$_POST['tramite_assinatura_interna_diretor_2']."','".$_POST['tramite_assinatura_interna_diretor_2_data_inicial']."','".$_POST['tramite_assinatura_interna_diretor_2_data_final']."','".$_POST['tramite_assinatura_externa_email']."','".$_POST['tramite_assinatura_externa_retirada']."','".$_POST['tramite_assinatura_externa_devolucao']."', '".$_POST['disponivel_sap']."', '".$_POST['status']."')";

            if(mysqli_query($conn, $insert_query)) {
                $pid = mysqli_insert_id($conn);

                // Rodadas
                $nivel_rodada = $_POST['nivel-rodada'];
                $data_inicial_rodada = $_POST['data-inicial-rodada'];
                $data_final_rodada = $_POST['data-final-rodada'];
                $rodada_tipo = $_POST['rodada-tipo'];
                $rodadas = array();

                for($count_rodada = 0; $count_rodada < sizeof($nivel_rodada); $count_rodada++){
                    if(!empty($nivel_rodada[$count_rodada]))
                    {
                        $rodadaQuery = "INSERT INTO rodadas (uid, pid, position, tipo, nivel, data_inicial, data_final) VALUES (".$_POST['uid'].",".$pid.",'".$count_rodada."','".$rodada_tipo[$count_rodada]."','".$nivel_rodada[$count_rodada]."','".$data_inicial_rodada[$count_rodada]."','".$data_final_rodada[$count_rodada]."')";

                        mysqli_query($conn, $rodadaQuery);
                    }
                    $lastRodadaID = mysqli_insert_id($conn);
                    array_push($rodadas, $lastRodadaID);
                }                  


                // Responsáveis
                if(isset($_POST['responsavel'])){
                    for($count = 0; $count < sizeof($_POST['responsavel']); $count++){
                        if(!empty($_POST['responsavel'][$count]))
                        {
                            $responsavelQuery = "INSERT INTO responsavel_by_processos (uid, pid, position, responsavel) VALUES (".$_POST['uid'].",".$pid.",".$count.",'".$_POST['responsavel'][$count]."')";

                            mysqli_query($conn, $responsavelQuery);
                        }
                    } 
                }

                // Sociedade
                $count = -1;

                $sociedade = array();
                $valor = array();
                $moeda = array();

                if(isset($_POST['sociedade'])){
                    foreach ($_POST['valor'] as $key => $value) {
                        $count++;

                        if(!empty($value)){
                            array_push($valor, $value);
                        }
                    }                    
                    foreach ($_POST['sociedade'] as $key => $value) {
                        $count++;

                        if(!empty($value)){
                            array_push($sociedade, $value);
                        }
                    }   
                    foreach ($_POST['moeda'] as $key => $value) {
                        $count++;

                        if(!empty($value)){
                            array_push($moeda, $value);
                        }
                    }   
                    for ($i = 0; $i < sizeof($sociedade); $i++) {
                        $sociedadeQuery = "INSERT INTO sociedades_by_processos (uid, pid, position, sociedade, moeda, valor) VALUES (".$_POST['uid'].",".$pid.",".$i.",'".$sociedade[$i]."', '".$moeda[$i]."', '".$valor[$i]."')";

                        mysqli_query($conn, $sociedadeQuery);                    
                    }                                       
                }    
            }
            $post = array(
                'uid'=>$_POST['uid'],
                'id'=>$pid
            );    

            print_r(json_encode($post, JSON_PRETTY_PRINT));             
        } else {
			print_r(json_encode($result, JSON_PRETTY_PRINT));  
        }
	}