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

SGA::check_login('sga.monitor');
/**
 * Exibe todas as senhas canceladas de um serviço
 */
try {
	
	if(empty($_POST["id_servico"]) ){
		throw new Exception("serviço não especificado");
	}
	$id_servico = $_POST["id_servico"];
	
	$servicos = array();
	if ($id_servico == -1) {
		$ids_serv = array();
		$id_uni = SGA::get_current_user()->get_unidade()->get_id();
		$servicos_mestre = DB::getInstance()->get_servicos_unidade_reativar(Servico::SERVICO_ATIVO,$id_uni);
		foreach ($servicos_mestre as $s) {
			$servicos[] =  $s->get_id(); 
		}	
	}
	else {
		$servicos[] = $id_servico;
	}
	
	$unidade = SGA::get_current_user()->get_unidade();
	
	$fila = DB::getInstance()->get_fila($servicos, $unidade->get_id(), $ids_stat=array(Atendimento::SENHA_CANCELADA,Atendimento::NAO_COMPARECEU));
	
	TMonitor::exibir_senhas_servico($fila->get_atendimentos(),'Monitor.onPrioridadeSenha();');
}
catch (Exception $e) {
	Template::display_exception($e);
}
?>