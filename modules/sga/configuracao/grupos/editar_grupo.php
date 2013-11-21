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
 * Exibe a janela de edição de grupos
 */
try {

    if (empty($_POST['id_grupo'])) {
	    throw new Exception("O grupo a ser editado deve estar selecionado.");
    }

    $id_grupo = $_POST['id_grupo'];
    $grupo = DB::getInstance()->get_grupo_by_id($id_grupo);
    
    TConfiguracao::display_popup_header("EDITAR GRUPO");
	TConfiguracao::display_grupo($grupo);
	TConfiguracao::display_popup_footer();
} 
catch(Exception $e) {
	TConfiguracao::display_exception($e);
}
?>