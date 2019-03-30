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


            $insert_query = "INSERT INTO processos (uid,nome_processo,numero_processo,tipo_processo,comprador,numero_requisicao,subfamilia,grupo_de_compras,data_disp_compras,cod_material,adjudicacao_num_pa,adjudicacao_vencimento,dados_bravo_sav,dados_bravo_rqf,fornecedor,fornecedor_data_inicial,fornecedor_data_final,estrategia_data_inicial,estrategia_data_final,elaboracao_documento_data_inicial,elaboracao_documento_data_final,negociacao_direta_data_inicial,negociacao_direta_data_final,leilao_data_inicial,leilao_data_final,elaboracao_pa_data_inicial,elaboracao_pa_data_final,workflow_data_inicial,workflow_data_final,criacao_de_pedido_data_inicial,criacao_de_pedido_data_final,tramite_assinatura_interna_diretor_1,tramite_assinatura_interna_diretor_1_data_inicial,tramite_assinatura_interna_diretor_1_data_final,tramite_assinatura_interna_diretor_2,tramite_assinatura_interna_diretor_2_data_inicial,tramite_assinatura_interna_diretor_2_data_final,tramite_assinatura_externa_email,tramite_assinatura_externa_retirada,tramite_assinatura_externa_devolucao,disponivel_sap,status) VALUES ('".$_POST['uid']."','".$_POST['nome_processo']."','".$_POST['numero_processo']."','".$_POST['tipo_processo']."','".$_POST['comprador']."','".$_POST['numero_requisicao']."','".$_POST['subfamilia']."','".$_POST['grupo_de_compras']."','".$_POST['data_disp_compras']."','".$_POST['cod_material']."','".$_POST['adjudicacao_num_pa']."','".$_POST['adjudicacao_vencimento']."','".$_POST['dados_bravo_sav']."','".$_POST['dados_bravo_rqf']."','".$_POST['fornecedor']."','".$_POST['fornecedor_data_inicial']."','".$_POST['fornecedor_data_final']."','".$_POST['estrategia_data_inicial']."','".$_POST['estrategia_data_final']."','".$_POST['elaboracao_documento_data_inicial']."','".$_POST['elaboracao_documento_data_final']."','".$_POST['negociacao_direta_data_inicial']."','".$_POST['negociacao_direta_data_final']."','".$_POST['leilao_data_inicial']."','".$_POST['leilao_data_final']."','".$_POST['elaboracao_pa_data_inicial']."','".$_POST['elaboracao_pa_data_final']."','".$_POST['workflow_data_inicial']."','".$_POST['workflow_data_final']."','".$_POST['criacao_de_pedido_data_inicial']."','".$_POST['criacao_de_pedido_data_final']."','".$_POST['tramite_assinatura_interna_diretor_1']."','".$_POST['tramite_assinatura_interna_diretor_1_data_inicial']."','".$_POST['tramite_assinatura_interna_diretor_1_data_final']."','".$_POST['tramite_assinatura_interna_diretor_2']."','".$_POST['tramite_assinatura_interna_diretor_2_data_inicial']."','".$_POST['tramite_assinatura_interna_diretor_2_data_final']."','".$_POST['tramite_assinatura_externa_email']."','".$_POST['tramite_assinatura_externa_retirada']."','".$_POST['tramite_assinatura_externa_devolucao']."', '".$_POST['disponivel_sap']."', '".$_POST['status']."')";

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
                        $rodadaQuery = "INSERT INTO rodadas (uid, pid, position, tipo, nivel, data_inicial, data_final) VALUES (".$uid.",".$lastID.",'".$count_rodada."','".$rodada_tipo[$count_rodada]."','".$nivel_rodada[$count_rodada]."','".$data_inicial_rodada[$count_rodada]."','".$data_final_rodada[$count_rodada]."')";

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
                header("Location: ".$default_url."/processos/?registered=true"); 
            } else {
                header("Location: ".$default_url."/cadastro/processos/?error=true"); 
            }
        } else {
            header("Location: ".$default_url."/cadastro/processos/?exist=true"); 
        }
    } else {
		$update_query = "UPDATE processos SET uid = '".$_POST['uid']."', nome_processo = '".$_POST['nome_processo']."', numero_processo = '".$_POST['numero_processo']."', tipo_processo = '".$_POST['tipo_processo']."', comprador = '".$_POST['comprador']."', numero_requisicao = '".$_POST['numero_requisicao']."', subfamilia = '".$_POST['subfamilia']."', grupo_de_compras = '".$_POST['grupo_de_compras']."', data_disp_compras = '".$_POST['data_disp_compras']."', cod_material = '".$_POST['cod_material']."', adjudicacao_num_pa = '".$_POST['adjudicacao_num_pa']."', adjudicacao_vencimento = '".$_POST['adjudicacao_vencimento']."', dados_bravo_sav = '".$_POST['dados_bravo_sav']."', dados_bravo_rqf = '".$_POST['dados_bravo_rqf']."', fornecedor = '".$_POST['fornecedor']."', fornecedor_data_inicial = '".$_POST['fornecedor_data_inicial']."', fornecedor_data_final = '".$_POST['fornecedor_data_final']."', estrategia_data_inicial = '".$_POST['estrategia_data_inicial']."', estrategia_data_final = '".$_POST['estrategia_data_final']."', elaboracao_documento_data_inicial = '".$_POST['elaboracao_documento_data_inicial']."', elaboracao_documento_data_final = '".$_POST['elaboracao_documento_data_final']."', negociacao_direta_data_inicial = '".$_POST['negociacao_direta_data_inicial']."', negociacao_direta_data_final = '".$_POST['negociacao_direta_data_final']."', leilao_data_inicial = '".$_POST['leilao_data_inicial']."', leilao_data_final = '".$_POST['leilao_data_final']."', elaboracao_pa_data_inicial = '".$_POST['elaboracao_pa_data_inicial']."', elaboracao_pa_data_final = '".$_POST['elaboracao_pa_data_final']."', workflow_data_inicial = '".$_POST['workflow_data_inicial']."', workflow_data_final = '".$_POST['workflow_data_final']."', criacao_de_pedido_data_inicial = '".$_POST['criacao_de_pedido_data_inicial']."', criacao_de_pedido_data_final = '".$_POST['criacao_de_pedido_data_final']."', tramite_assinatura_interna_diretor_1 = '".$_POST['tramite_assinatura_interna_diretor_1']."', tramite_assinatura_interna_diretor_1_data_inicial = '".$_POST['tramite_assinatura_interna_diretor_1_data_inicial']."', tramite_assinatura_interna_diretor_1_data_final = '".$_POST['tramite_assinatura_interna_diretor_1_data_final']."', tramite_assinatura_interna_diretor_2 = '".$_POST['tramite_assinatura_interna_diretor_2']."', tramite_assinatura_interna_diretor_2_data_inicial = '".$_POST['tramite_assinatura_interna_diretor_2_data_inicial']."', tramite_assinatura_interna_diretor_2_data_final = '".$_POST['tramite_assinatura_interna_diretor_2_data_final']."', tramite_assinatura_externa_email = '".$_POST['tramite_assinatura_externa_email']."', tramite_assinatura_externa_retirada = '".$_POST['tramite_assinatura_externa_retirada']."', tramite_assinatura_externa_devolucao = '".$_POST['tramite_assinatura_externa_devolucao']."', disponivel_sap = '".$_POST['disponivel_sap']."', status = '".$_POST['status']."' WHERE `processos`.`id` = ".$_POST['id'];

        if(mysqli_query($conn, $update_query)) {
            $lastID = mysqli_insert_id($conn);

            for($count = 0; $count < sizeof($_POST['nivel-rodada']); $count++){
                $q = "SELECT * FROM rodadas WHERE position = ".$count;
                if(!mysqli_query($conn, $q)->num_rows){
                    $query_rodadas = "INSERT INTO rodadas (uid, pid, position, tipo, nivel, data_inicial, data_final) VALUES (".$_POST['uid'].",".$_POST['id'].",'".$count."','".$_POST['rodada-tipo'][$count]."','".$_POST['nivel-rodada'][$count]."','".$_POST['data-inicial-rodada'][$count]."','".$_POST['data-final-rodada'][$count]."')";
                } else {
                    $query_rodadas = "UPDATE rodadas SET tipo = '".$_POST['rodada-tipo'][$count]."', nivel = '".$_POST['nivel-rodada'][$count]."', data_inicial = '".$_POST['data-inicial-rodada'][$count]."', data_final = '".$_POST['data-final-rodada'][$count]."' WHERE pid = ".$_POST['id']." AND position = ".$count;
                }
                mysqli_query($conn, $query_rodadas);
            }

            if(isset($_POST['responsavel'])){
                for($count = 0; $count < sizeof($_POST['responsavel']); $count++){
                    $query_responsaveis_test = "SELECT * FROM responsavel_by_processos WHERE position = ".$count." AND responsavel = '".$_POST['responsavel'][$count]."'";
                    $query_responsaveis_new = "SELECT * FROM responsavel_by_processos WHERE position = ".$count." AND responsavel = '".$_POST['responsavel'][$count]."'";
                    
                    if(!empty($_POST['responsavel'][$count])){
                        if(!mysqli_query($conn, $query_responsaveis_test)->num_rows){
                            $query_responsaveis = "UPDATE responsavel_by_processos SET responsavel = '".$_POST['responsavel'][$count]."' WHERE pid = ".$_POST['id']." AND position = ".$count;

                            mysqli_query($conn, $query_responsaveis);                        
                        }

                        if(!mysqli_query($conn, $query_responsaveis_new)->num_rows){
                            $query_responsaveis = "INSERT INTO responsavel_by_processos (uid, pid, position, responsavel) VALUES (".$_POST['uid'].",".$_POST['id'].",".$count.",'".$_POST['responsavel'][$count]."')";

                            mysqli_query($conn, $query_responsaveis);                        
                        }     
                    }
                }   
            }

            $sociedadeQuery = 'TRUNCATE TABLE sociedades_by_processos';
            mysqli_query($conn, $sociedadeQuery);              

            if(!empty($_POST['sociedade'])){

                $sociedade = array();
                $valor = array();
                $moeda = array();

                foreach (array_filter($_POST['sociedade']) as $key => $value) {
                    array_push($sociedade, $value);
                } foreach (array_filter($_POST['valor']) as $key => $value) {
                    array_push($valor, $value);
                } foreach (array_filter($_POST['moeda']) as $key => $value) {
                    array_push($moeda, $value);
                }

                if(!empty($sociedade) && !empty($valor) && !empty($moeda)){
                    $count = -1;

                    foreach ($sociedade as $key => $value) {
                        $count++;
                        $query_sociedade_test = "SELECT * FROM sociedades_by_processos WHERE sociedade = '".$value."'";

                        if(!mysqli_query($conn, $query_sociedade_test)->num_rows){
                            $q = "INSERT INTO sociedades_by_processos (uid, pid, position, sociedade, valor, moeda) VALUES (".$_POST['uid'].",".$_POST['id'].",".(mysqli_query($conn, "SELECT * FROM sociedades_by_processos")->num_rows + 1).",'".$value."','".$valor[$count]."','".$moeda[$count]."')";
                            mysqli_query($conn, $q);
                        } elseif(mysqli_query($conn, $query_sociedade_test)->num_rows) {
                            if($value && $valor[$count] && $moeda[$count]){
                                $qup = "UPDATE sociedades_by_processos SET sociedade = '".$value."', valor = '".$valor[$count]."', moeda = '".$moeda[$count]."' WHERE sociedade = '".$value."'";

                                mysqli_query($conn, $qup);      
                            }
                        } 
                    } 
                }    
            }
            header("Location: ".$default_url."/processo/".$_POST['uid']."/".$_POST['id']."/?updated=true"); 
        } else {
        	header("Location: ".$default_url."/processo/".$_POST['uid']."/".$_POST['id']."/?error=true"); 
        }
    }