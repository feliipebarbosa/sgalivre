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
SGA::check_login('sga.agenda');

/**
 * Redireciona para a tela inicial de atendimento
 */
try {

	$usuario = SGA::get_current_user();
	$dia = $_POST['day'];
	$hora_ini_manha = $_POST['hour_start_morning'];
	$hora_fim_manha = $_POST['hour_end_morning'];
	$hora_ini_tarde = $_POST['hour_start_afternoon'];
	$hora_fim_tarde = $_POST['hour_end_afternoon'];
    $id_usu = $usuario->get_id();
    $id_uni = SGA::get_current_user()->get_unidade()->get_id();
    
    $agenda = DB::getInstance()->criar_agenda($dia, $hora_ini_manha, $hora_fim_manha, $hora_ini_tarde, $hora_fim_tarde, $id_usu, $id_uni);
    
    SGA::_include("modules/sga/agenda/index.php");
}
catch (Exception $e) {
	TAgenda::display_exception($e);
}

?>