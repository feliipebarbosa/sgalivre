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

SGA::check_login("sga.configuracao");

try {
	if (empty($_POST['id_uni'])) {
		TConfiguracao::display_popup_header();
		TConfiguracao::display_nova_unidade();
		TConfiguracao::display_popup_footer();
	}
	else {
		$id_uni = $_POST['id_uni'];
		$unidade = DB::getInstance()->get_unidade($id_uni);
		
		if ($unidade == null) {
			throw new Exception("Unidade n&atilde;o encontrada.");
		}
		TConfiguracao::display_edit_uni($unidade);
	}
}
catch (Exception $e) {
	TConfiguracao::display_exception($e);
}

?>