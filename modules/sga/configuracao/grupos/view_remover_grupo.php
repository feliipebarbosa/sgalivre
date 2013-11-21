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
 * Exibe uma janela para a confirmação de remoção de grupo.
 */
try {

    if (empty($_POST['id_grupo'])) {
	    throw new Exception("O grupo a ser removido deve estar selecionado.");
    }

    $id_grupo = $_POST['id_grupo'];
    $grupo = DB::getInstance()->get_grupo_by_id($id_grupo);
    if ($grupo->is_raiz()) {
    	throw new Exception('Não é possível remover o grupo raíz.');
    }
    $grupos = DB::getInstance()->get_sub_grupos($id_grupo);
    $aux = array();
    $aux[]= $grupo->get_nome();
    foreach ($grupos as $g) {
    	$aux[]= $g->get_nome();
    }
	TConfiguracao::display_popup_header("CONFIRMAR A REMOÇÃO DO GRUPO");
    TConfiguracao::display_confirm_remover_grupo($aux);
    TConfiguracao::display_popup_footer();

}
catch(Exception $e) {
	TConfiguracao::display_exception($e);
}
?>