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

/**
 * Arquivo invocado no primeiro acesso ao modulo Atendimento nesta sessão, responsavel por restaurar
 * o atendimento do DB de volta para a Session
 */
$usuario = SGA::get_current_user();

$id_usu = $usuario->get_id();
$id_uni = $usuario->get_unidade()->get_id();
$status = array(Atendimento::CHAMADO_PELA_MESA, Atendimento::ATENDIMENTO_INICIADO, Atendimento::ATENDIMENTO_ENCERRADO);
$atendimentos = DB::getInstance()->get_atendimentos_by_usuario($id_usu, $id_uni, $status);

if (sizeof($atendimentos) > 0) {
	Session::getInstance()->set('ATENDIMENTO', $atendimentos[0]);
}

?>