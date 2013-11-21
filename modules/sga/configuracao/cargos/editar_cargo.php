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

SGA::check_login('sga.configuracao');

/**
 * Exibe a janela para edição de cargos.
 */
try {
	if (empty($_POST['id_cargo'])) {
		throw new Exception("Cargo não especificado.");
	}
	
	$id_cargo = $_POST['id_cargo'];
	$cargo = DB::getInstance()->get_cargo($id_cargo);
	
	// get array permissoes do cargo
	$pcs = DB::getInstance()->get_permissoes_cargo($id_cargo);
	foreach ($pcs as $pc) {
		$cargo ->add_permissao($pc);
	}
	
	TConfiguracao::display_popup_header('EDITAR CARGO');
	TConfiguracao::display_view_cargo($cargo);
	TConfiguracao::display_popup_footer();
}
catch(Exception $e) {
	TConfiguracao::display_exception($e);
}
?>