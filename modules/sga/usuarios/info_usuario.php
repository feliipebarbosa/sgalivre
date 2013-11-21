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
 * Monta interface de edição do usuario
 */
try {
	if (empty($_POST['id_usu'])) {
		TUsuarios::display_popup_header('CRIAR USUÁRIO');
		TUsuarios::display_edit_user();
		TUsuarios::display_popup_footer();
	}
	else {
		$id_usu = $_POST['id_usu'];
        $id_grupo = $_POST['id_grupo'];
	
		$usuario = DB::getInstance()->get_usuario_by_id($id_usu);
		if ($usuario == null) {
			throw new Exception("Usuário não encontrado.");
		}
        
		TUsuarios::display_edit_user($usuario, $id_grupo);
	}
}
catch (Exception $e) {
	TUsuarios::display_exception($e);
}

?>