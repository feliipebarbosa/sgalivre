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

SGA::check_login("sga.usuarios");
/**
 * Exibe interface para edição ou adicao de lotacao de um usuario
 */
try {
	
	TUsuarios::display_popup_header($titulo = (empty($_POST['id_grupo'])?"Criar Lotação":"Editar Lotação"));
	
	// adicionando
	if (empty($_POST['id_grupo'])) {
		if(!empty($_POST['id_usu'])){
			$id_usu = $_POST['id_usu'];
		}
		$id_grupo_selecionado = $_POST['id_grupo_selecionado'] ;
		TUsuarios::display_user_group(SGA::get_current_user(),null,null,$id_usu, $id_grupo_selecionado);
	}
	else { // editando
		if (empty($_POST['id_cargo'])) {
			throw new Exception("Cargo não especificado.");
		}
		$id_usu = $_POST['id_usu'];
		$id_grupo = $_POST['id_grupo'];
		$id_cargo = $_POST['id_cargo'];
		$grupo = DB::getInstance()->get_grupo_by_id($id_grupo);
		$cargo = DB::getInstance()->get_cargo($id_cargo);
		TUsuarios::display_user_group(SGA::get_current_user(), $grupo, $cargo);
	}
	
	TUsuarios::display_popup_footer();
}
catch (Exception $e) {
	TUsuarios::display_exception($e);
}

?>