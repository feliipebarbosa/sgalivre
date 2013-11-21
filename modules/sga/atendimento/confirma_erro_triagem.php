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
 * Confirma o erro de triagem e volta para a tela inicial do módulo Atendimento.
 */
try {
//	Template::display_confirm_dialog("confirma erro triagem","");
	$id_servico = $_POST["id_servico"];
	
	$usuario = Session::getInstance()->get(SGA::K_CURRENT_USER);
	$id_usuario = $usuario->get_id();
	$id_unidade = $usuario->get_unidade()->get_id();
	$atendimento = Session::getInstance()->get('ATENDIMENTO');
	//$dt_cheg = $atendimento->get_dt_cheg();
	$cliente = $atendimento->get_cliente();
	$num_senha = $cliente->get_senha()->get_numero();
	$id_prio = $cliente->get_senha()->get_prioridade()->get_id();
	$num_guiche = $usuario->get_num_guiche();
	$nm_cliente = $cliente->get_nome();
	
	//buscar o ident_cli do banco
	$ident_cliente = "";
	
	Session::getInstance()->del('ATENDIMENTO');
	DB::getInstance()->set_atendimento_status($atendimento->get_id(), Atendimento::ERRO_TRIAGEM);

    // O dt_cheg da nova senha deve ser o momento atual
	DB::getInstance()->erro_triagem($id_unidade, $id_servico, $num_senha, $id_prio, 0, Atendimento::SENHA_EMITIDA, $nm_cliente, $ident_cliente, SGA::get_date("Y-m-d H:m:i"));
	
	SGA::_include("modules/sga/atendimento/atender/index.php");
}
catch (Exception $e) {
	Template::display_exception($e);
}
?>