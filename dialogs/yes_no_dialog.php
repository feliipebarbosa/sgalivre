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

try {
	if (empty($_REQUEST['message']) || empty($_REQUEST['onclickok'])) {
		throw new Exception("Erro interno, mensagem ou evento onclick n&atilde;o especificado para dialog de confirma&ccedil;&atilde;o.");
	}
	
	if (empty($_REQUEST['title'])) {
		$title = 'Confirma&ccedil;&atilde;o';
	}
	else {
		$title = $_REQUEST['title'];
	}
	
	$message = $_REQUEST['message'];
	$onclickok = $_REQUEST['onclickok'];
	$onclickcancel = $_REQUEST['onclickcancel'];
	
	Template::display_yes_no_dialog($message, $title, "window.closePopup(this);".$onclickok,false, $onclickcancel);
}
catch (Exception $e) {
	Template::display_exception($e);
}
?>