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

define('MODULO', 'LOGIN');

if (!Session::getInstance()->exists(SGA::K_CURRENT_MODULE)) {
	// TODO módulo padrão deve ser configuravel
	header("Location:?mod=sga.inicio");
}

if (SGA::is_logged()) {
    header("Location:?mod=" . Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_chave());
}

// JS Login
$misc = '<script>
            SGA.addOnLoadListener( function() { $("#login_usu").get(0).focus(); } );
         </script>';

Template::display_header("SGA Livre", $misc);
Template::display_login_header();
if (Session::getInstance()->get(SGA::K_LOGIN_ERROR)) {
	Template::display_error(Session::getInstance()->get(SGA::K_LOGIN_ERROR));
	Session::getInstance()->del(SGA::K_LOGIN_ERROR);
}
Template::display_login_form('Enviar', '?reg_log');
Template::display_footer();
?>