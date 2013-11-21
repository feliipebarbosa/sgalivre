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
try {

    $erro = 0;
    Session::getInstance()->set(SGA::K_LOGIN_ERROR, null);

    $messages = array(
        'Usu&aacute;rio Inv&aacute;lido. Por favor, tente novamente.',
        'M&oacute;dulo Inv&aacute;lido. Por favor, entre em contato com o administrador do sistema.',
        'Senha Inv&aacute;lida. Por favor, tente novamente.',
        'Acesso Negado ao M&oacute;dulo. Por favor, entre em contato com seu gerente.',
        'Usu&aacute;rio desativado. Por favor, entre em contato com seu gerente'     
    );
	
    if (!empty($_POST['user']) && !empty($_POST['pass'])) {
		
		$user = $_POST['user'];
		$pass = $_POST['pass'];
		if (Session::getInstance()->exists(SGA::K_CURRENT_MODULE)) {
			$mod = Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_chave();

			$erro = Login::has_acesso($user, $pass, $mod);
			//
			if ($erro == -1) {
				define('MODULO', $mod);
				$usuario = DB::getInstance()->get_usuario($user);
				
			    Session::getInstance()->setGlobal(SGA::K_CURRENT_USER, $usuario);
			    DB::getInstance()->salvar_session_id($usuario->get_id());
                DB::getInstance()->set_session_status($usuario->get_id(), Session::SESSION_ATIVA);
                
                header("Location:./?mod=$mod");
			}
            else {
			    Session::getInstance()->set(SGA::K_LOGIN_ERROR, $messages[$erro-1]);
			    header("Location:./?" . SGA::K_NEED_LOGIN);
			}			
		} else {
	        Session::getInstance()->set(SGA::K_LOGIN_ERROR, "Nenhum m&oacute;dulo carregado.");
		    header("Location:./?" . SGA::K_NEED_LOGIN);
		}
    } else {
        header("Location:./?" . SGA::K_NEED_LOGIN);
    }


} catch (Exception $e) {
	Template::display_exception($e);
}


?>
