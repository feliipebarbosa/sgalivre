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

SGA::check_access('sga.atendimento');

/**
 * Monta template do atendimento
 */

try {    

    # verifica se o modulo esta devidamente instalado   
    Session::getInstance()->get(SGA::K_CURRENT_MODULE)->verifica();
	
	TAtendimento::display_header("Atendimento");
	
	$usuario = Session::getInstance()->get(SGA::K_CURRENT_USER);
	
	# verifica se a unidade está definida
	if ($usuario->get_unidade() instanceof Unidade) {
		#verifica se o guichê de atendimento está definido
		if (!($usuario->get_num_guiche() > 0)) {
			$n = $_COOKIE['Atendimento_guiche'];
	        TAtendimento::display_input_dialog("Informe o número do seu guiche.", "Atendimento", "Atendimento.setGuiche", $n);
		}
	    else {
		    SGA::_include("modules/sga/atendimento/atend.php");
	    }
	}
	else {
		// TODO remover
	}
    
    TAtendimento::display_footer();
}
catch (Exception $e) {
	Template::display_exception($e);
}
?>
