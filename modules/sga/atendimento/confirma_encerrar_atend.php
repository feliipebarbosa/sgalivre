<?php

/**
 * 
 * Copyright (C) 2009 DATAPREV - Empresa de Tecnologia e Informações da Previdência Social - Brasil
 *
 * Este arquivo é parte do programa SGA Livre - Sistema de Gerenciamento do Atendimento - Versão Livre
 *
 * O SGA é um software livre; você pode redistribuí­-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como 
 * publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.
 *
 * Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer
 * MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a 
 * Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA.
 *
**/

SGA::check_login('sga.atendimento');
/**
 * Encerra o atendimento e volta para a tela inicial do módulo Atendimento.
 */
try {
	
	if(empty($_POST["list_servico_atendido"]) ){
		throw new Exception("Nenhum serviço atendido.");
	}
	$servicos = $_POST["list_servico_atendido"];

    $atendimento = Session::getInstance()->get("ATENDIMENTO");
	$id_uni = SGA::get_current_user()->get_unidade()->get_id();
	$id_atend = $atendimento->get_id();

	

    DB::getInstance()->set_atendimento_status($id_atend, Atendimento::ATENDIMENTO_ENCERRADO_CODIFICADO);
    DB::getInstance()->encerra_atendimentos($id_atend, $id_uni, $servicos);

    $atendimento->set_status(Atendimento::ATENDIMENTO_ENCERRADO_CODIFICADO);

    $redirecionado = false;
    // redirecionar, se for o caso
    if (!empty($_POST["servico_erro_triagem"]) && !empty($_POST['check_redirecionar'])) {
        $check_redirecionar = $_POST['check_redirecionar'];
        $id_servico_redir = $_POST["servico_erro_triagem"];

        if ($check_redirecionar == "true") {
            
            $num_senha = $atendimento->get_cliente()->get_senha()->get_numero();
            $id_prio = $atendimento->get_cliente()->get_senha()->get_prioridade()->get_id();
            $nm_cliente = $atendimento->get_cliente()->get_nome();
            $ident_cliente = $atendimento->get_cliente()->get_ident();

            // O dt_cheg da nova senha deve ser o momento atual
            DB::getInstance()->erro_triagem($id_uni, $id_servico_redir, $num_senha, $id_prio, 0, Atendimento::SENHA_EMITIDA, $nm_cliente, $ident_cliente, SGA::get_date("Y-m-d H:m:i"));
            $redirecionado = true;
        }
    }

    Session::getInstance()->del('ATENDIMENTO');
    Session::getInstance()->del("redirecionar");

    if ($redirecionado) {
        Template::display_confirm_dialog("Atendimento encerrado e redirecionado.","Sucesso",true);
    }
	else {
        Template::display_confirm_dialog("Atendimento encerrado","Sucesso",true);
    }
    
	SGA::_include("modules/sga/atendimento/atender/index.php");
}
catch (Exception $e) {
	Template::display_exception($e);
}
?>
