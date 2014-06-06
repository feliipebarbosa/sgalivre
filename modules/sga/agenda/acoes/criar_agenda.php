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
	echo "<script>alert('test');</script>";
	$dia_semana = $_POST['dia_semana'];

	#$dias = split (",",$dia_semana);

	echo "<script>alert($dia_semana);</script>";

	$dia_semana = 'segunda'
	switch ( $numero ){
  		case 'segunda':
    		$dia = '07/04/2014';
  		case 'terca':
    		$dia = '08/04/2014';
  		case 'quarta':
    		$dia = '09/04/2014';
  		case 'quinta':
    		$dia = '10/04/2014';
  		case 'sexta':
    		$dia = '11/04/2014';
    	case 'sabado':
    		$dia = '12/04/2014';	
	}

    $id_usu = $usuario->get_id();
    $id_uni = SGA::get_current_user()->get_unidade()->get_id();
    
    $agenda = DB::getInstance()->criar_agenda($dia, $dia_semana);
    
    SGA::_include("modules/sga/agenda/index.php");
}
catch (Exception $e) {
	TAgenda::display_exception($e);
}

?>