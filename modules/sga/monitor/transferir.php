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

SGA::check_login('sga.monitor');

/**
 * Monta template do transfere senha
 */

if (empty($_GET['id_atend']) || empty($_GET['senha']) || empty($_GET['servico']) || !isset($_GET['prioridade'])) {
	throw new Exception("Erro transferindo senha.");
}

$id_atend = $_GET['id_atend'];
$senha = $_GET['senha'];
$servico = $_GET['servico'];
$prioridade = $_GET['prioridade'];

TMonitor::display_popup_header("Transferir Senha");
TMonitor::display_transfere_senha($id_atend, $senha, $servico, $prioridade);
TMonitor::display_popup_footer();

?>