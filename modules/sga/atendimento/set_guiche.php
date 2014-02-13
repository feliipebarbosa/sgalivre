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
 * Define o numero do guiche do atendente
 */
 
try {
    
    if (!isset($_GET['guiche'])) {
    	throw new Exception("Guiche não especificado.");
    }
    
    $n = (int) $_GET['guiche'];
    if ($n < 1 || $n > 255) {
    	throw new Exception("O guiche deve estar entre 1 e 255.");
    }
    
    setcookie('Atendimento_guiche',$n,time()+60*60*24*30*48 );
    Session::getInstance()->get(SGA::K_CURRENT_USER)->set_num_guiche($n);
    SGA::_include("modules/sga/atendimento/atend.php");
	

}
catch (Exception $e) {
	TAtendimento::display_exception($e,'',"window.redir('?mod=sga.atendimento')");
}


?>
