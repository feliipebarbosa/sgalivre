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

/**
 * arquivo que chama a funçao para montar a interface
 * de cancelar senha por serviço 
 */
SGA::check_login('sga.monitor');

try {
	
	
	$id_uni = SGA::get_current_user()->get_unidade()->get_id();
	$servicos = DB::getInstance()->get_servicos_unidade($id_uni, array(Servico::SERVICO_ATIVO));
	

	$tmp = array();
	$tmp[-1] = 'Serviços';
	/** 
	* array passado como parametro onde a chave é o id do serviço 
	* e o valor é uma string com sigla e nome do serviço 
	*/
	foreach ($servicos as $s) {
		$tmp[$s->get_id()] = $s->get_sigla().'-'.$s->get_nome(); 
	}
	
	TMonitor::exibir_cancelar_senha_por_servico($tmp);
	
}
catch (Exception $e) {
	Template::display_exception($e);
}
?>