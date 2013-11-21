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

@SGA::check_login('sga.atendimento');

/**
 * Coloca a senha do cliente no painel
 */
 
try {
	/**
	 * 
	 * @var Usuario usuario
	 */
	$usuario = SGA::get_current_user();
	$unidade = $usuario->get_unidade();
	$servicos = $usuario->get_servicos();
	
	if (!Session::getInstance()->exists('ATENDIMENTO')) {
		// altera status na tabela atendimento e retorna o Atendimento
	    $atendimento = DB::getInstance()->chama_proximo_atendimento($usuario->get_id(), $unidade->get_id(), $servicos, $usuario->get_num_guiche());
	    //verirfica se têm fila
	    if ($atendimento == null) {
	    	throw new Exception("A fila está vazia.");
	    }
	    
	    // coloca o atendimento na Sessão do usuario
	    Session::getInstance()->set('ATENDIMENTO', $atendimento);
	}
	else {
		$atendimento = Session::getInstance()->get('ATENDIMENTO');
	}
		
	$msg_senha = $atendimento->get_cliente()->get_senha()->is_prioridade() ? "Prioridade" : "Atendimento";
    // insere na tabela do painel
	DB::getInstance()->chama_proximo($unidade->get_id(), $atendimento->get_servico(), $atendimento->get_cliente()->get_senha()->get_numero(), $atendimento->get_cliente()->get_senha()->get_sigla(), $msg_senha, "Guichê", $usuario->get_num_guiche());
    
	if ($atendimento->get_status() == Atendimento::CHAMADO_PELA_MESA) {
		SGA::_include("modules/sga/atendimento/atender/index.php");
	}
	else {
	    throw new Exception("Erro ao chamar proximo da fila. Status: ".$atendimento->get_status());
	}

} catch(Exception $e) {
	TAtendimento::display_exception($e);
	SGA::_include("modules/sga/atendimento/atender/index.php");
}
?>
