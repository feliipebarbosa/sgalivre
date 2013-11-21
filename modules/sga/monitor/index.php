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

SGA::check_access('sga.monitor');

/**
 * Monta template do monitor
 */

try {
	
	# verifica se o modulo esta devidamente instalado
	Session::getInstance()->get(SGA::K_CURRENT_MODULE)->verifica();
	
	$id_uni = SGA::get_current_user()->get_unidade()->get_id();
	TMonitor::display_header("Monitor");
	
	// pega o menu do monitor e os servicos da unidade
	$menus = DB::getInstance()->get_menu(Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_chave());
	$servicos = DB::getInstance()->get_servicos_unidade($id_uni, array(1));
	
	// guarda o objeto monitor na sessao
	$monitor = new Monitor($menus, $servicos, DB::getInstance()->get_total_fila($id_uni));
	Session::getInstance()->set('MONITOR', $monitor);
	
	$pagina = (Session::getInstance()->exists('MONITOR_PAGINA'))?Session::getInstance()->get('MONITOR_PAGINA'):0;
    Session::getInstance()->set('MONITOR_PAGINA', $pagina);

	TMonitor::display_monitor(Session::getInstance()->get('MONITOR'));
	TMonitor::display_footer();	

} catch (Exception $e) {
	Template::display_exception($e);
}


?>
