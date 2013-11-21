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

$current_step = Session::getInstance()->get('SGA_INSTALL_STEP');		
if ($current_step->has_next_step() && $current_step->get_next_enabled()) {
	$steps = Session::getInstance()->get('SGA_INSTALL');
	if (isset($steps[$current_step->get_numero() + 1])) {
		Session::getInstance()->setGlobal('SGA_INSTALL_STEP', $steps[$current_step->get_numero() + 1]);
	}
	else {
		throw new Exception('Próximo passo da instalação deveria existir, mas não foi encontrado!');
	}
}
SGA::_include('install/install_content.php');
?>