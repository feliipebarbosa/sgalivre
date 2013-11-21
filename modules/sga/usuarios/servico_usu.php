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

SGA::check_login('sga.usuarios');
/**
 * 
 * Monta interface da popup dos serviços por unidade que o usuario editado ainda não tem acesso
 */
try {
    $id_uni = $_POST['id_uni'];
    if (empty($id_uni)) {
		throw new Exception("Unidade não especificada.");
	}
    
	$id_servicos = $_POST["id_servicos"];
	if (sizeof($id_servicos) == 0) {
		$id_servicos[0] = null;
	}
    
    Template::display_popup_header("ADICIONAR SERVIÇO");
    TUsuarios::display_new_serv_user($id_servicos, $id_uni);
    Template::display_popup_footer();
}
catch (Exception $e) {
	Template::display_exception($e);
}
?>