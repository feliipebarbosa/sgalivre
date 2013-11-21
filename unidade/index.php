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

define('MODULO', 'LOGIN_UNIDADE');

try {
	if (!SGA::is_logged()) {
		SGA::force_login();
	}
	$usuario = Session::getInstance()->get(SGA::K_CURRENT_USER);
	
	$trocar = $_GET['trocar'];
	if ($trocar) {
		$modulo = Session::getInstance()->get(SGA::K_CURRENT_MODULE);
		
		Session::getInstance()->reset();
		$usuario->set_unidade();
		
		Session::getInstance()->setGlobal(SGA::K_CURRENT_MODULE, $modulo);
		Session::getInstance()->setGlobal(SGA::K_CURRENT_USER, $usuario);
        header("Location:?mod=sga.inicio");
	}
	
	if (SGA::has_unidade()) {
	    header("Location:?mod=" . Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_chave());
	}
	
	$unidades = DB::getInstance()->get_unidades_by_usuario($usuario->get_id());
	$total_unidades = sizeof($unidades);
	if ($total_unidades == 0) {
		Template::display_header("SGA");
		Template::display_error('Seu usuário não está associado a nenhuma unidade.','ERRO',0,"location.href='?".SGA::K_LOGOUT."'");
		Template::display_footer();
	}
	else if ($total_unidades == 1) {
		$unidade = array_shift($unidades);
		$usuario->set_unidade($unidade);
		
		header("Location:?mod=" . Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_chave());
		//Template::display_confirm_dialog('Logado automaticamente na unidade: '.$unidade->get_codigo()." - ".$unidade->get_nome(), "Atendimento");
	} else {
		// ajusta array de unidades para mapeamento key => value
		$aux = array();
		foreach ($unidades as $unidade) {
			if($unidade->get_stat_uni() == 1){
				$aux[$unidade->get_id()] = $unidade->get_codigo()." - ".$unidade->get_nome();
			}
		}
		$content = Template::display_jump_menu($aux, "id_uni", "", null, "", 1);
		
		Template::display_header("SGA");
		echo Template::display_select_unidade($content, 'Defina sua unidade:', "SGA.setUnidade");
        //echo $content;
		Template::display_footer();
	}
}
catch (Exception $e) {
	Template::display_header("SGA");
	Template::display_exception($e, 'Erro', "window.redir('?logout')");
	Template::display_footer();
}
?>