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
 * Determina status de senha como cancelada
 */
try {
	
	$id_atendimento = $_POST["id_atendimento"];
	
	if(empty($id_atendimento) ){
		throw new Exception("Atendimento não especificado");
	}
	$id_uni = SGA::get_current_user()->get_unidade()->get_id();
	$atendimento = DB::getInstance()->get_atendimento($id_atendimento, $id_uni);
	$atendimento->set_status(Atendimento::SENHA_CANCELADA);
	DB::getInstance()->set_atendimento_status($id_atendimento, Atendimento::SENHA_CANCELADA);
	Template::display_confirm_dialog("Senha cancelada com sucesso", "Sucesso");
}
catch (Exception $e) {
	Template::display_exception($e);
}
?>