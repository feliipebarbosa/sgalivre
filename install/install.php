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

define("PATH", "");

if (Config::SGA_INSTALLED) {
	Template::display_error('O SGA já está instalado.');
}
else {
	TInstall::display_header('SGA Livre');
	
	// prepara instalação
	$step[0] = new InstallStep(0, "Início", false, true, false, true); // install welcome
	$step[1] = new InstallStep(1, "Verificação de Requisitos", true, true, true, true); // install check
	$step[2] = new InstallStep(2, "Licença", true, true, true, false); // license
	$step[3] = new InstallStep(3, "Configurar Banco de Dados", false, false, false, false, 'Install.prevStep();', '', 'Install.carregarDadosDB();'); // DB
    $step[4] = new InstallStep(4, "Configurar Administrador", true, true, true, true, '', 'Install.checkAdmin();'); // Admin
	$step[5] = new InstallStep(5, "Aplicar", true, false, true, false); // Aplicar
	
	Session::getInstance()->setGlobal('SGA_INSTALL', $step);
	
	if (!Session::getInstance()->exists('SGA_INSTALL_STEP')) {
		$current_step = $step[0];
		Session::getInstance()->setGlobal('SGA_INSTALL_STEP', $current_step);
	}
	else {
		$current_step = Session::getInstance()->get('SGA_INSTALL_STEP');
	}
	
	TInstall::display_install_template();
	
	TInstall::display_footer();
}
?>