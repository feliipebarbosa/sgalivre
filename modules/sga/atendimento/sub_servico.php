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
 * Exibe uma lista de subserviço.
 */
try {
	
	if(empty($_POST["id_servico"]) ){
		throw new Exception("serviço não especificado");
	}
	$id_servico = $_POST["id_servico"];

	$id_uni = SGA::get_current_user()->get_unidade()->get_id();
    $sub_servicos = DB::getInstance()->get_servicos_sub($id_servico, array(Servico::SERVICO_ATIVO));

    // verifica se existem subserviços
    if ($sub_servicos == null || sizeof($sub_servicos) == 0) {
        // verifica se o serviço realmente existe
        $servico = DB::getInstance()->get_servico($id_servico);
        if ($servico == null) {
            throw new Exception("O serviço selecionado não existe no sistema.");
        }
        else {
            $sub_servicos = array($servico);
        }
    }
	TAtendimento::exibir_sub_servico($sub_servicos);
}
catch (Exception $e) {
	Template::display_exception($e);
}
?>