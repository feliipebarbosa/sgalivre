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

SGA::check_login('sga.atendimento');
 
/**
 * Carregador de conteudo
 * 
 * Carrega o conteudo do pagina atual na pagina a partir do link do menu
 * 
 */

try {
	if (!Session::getInstance()->exists('ATENDIMENTO_PAGINA')) {
        SGA::_include("modules/sga/atendimento/atender/index.php");
    }
    else {
    	$link = "modules/sga/atendimento/" . DB::getInstance()->get_menu_link(Session::getInstance()->get('ATENDIMENTO_PAGINA'));
        if (is_file($link)) {
        	SGA::_include($link);
        }
        else {
            throw new Exception("Pagina nao encontrada.");
        }
    }
}
catch(Exception $e) {
	TAtendimento::display_exception($e);
}


?>
