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
 * Define a unidade do usuário
 */ 
try {
    
    if (!isset($_POST['id_uni'])) {
    	throw new Exception("Selecione uma unidade.");
    }
	$id_uni = (int) $_POST['id_uni'];
	
	$usuario = Session::getInstance()->get(SGA::K_CURRENT_USER);
	$unidades = DB::getInstance()->get_unidades_by_usuario($usuario->get_id());
	
	$tmp = array();
	foreach ($unidades as $u) {
		$tmp[$u->get_id()] = $u;
	}
	
	// Verificação de segurança
	// Usuario tentou definir uma unidade ao qual não tem acesso
	$unidade = $tmp[$id_uni];
	if ($unidade != null) {
		$usuario->set_unidade($unidade);
		header("Location:?mod=" . Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_chave());
	}
	else {	
		throw new Exception('Acesso negado a unidade selecionada.');
	}
	

}
catch (Exception $e) {
	Template::display_exception($e, 'Erro', "window.redir('?unidade')");
}


?>