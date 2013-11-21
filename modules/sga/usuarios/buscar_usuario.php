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

SGA::check_login("sga.usuarios");
/**
 * Exibe o resultado da busca por usuarios
 */
try {
	if (empty($_POST['search_type'])) {
		throw new Exception("Selecione o modo da busca.");
	}
	
	$modo = $_POST['search_type']; // tipo de busca (login, nome)
	$termo = $_POST['search_input']; // valor da busca
    $id_grupo = $_POST['id_grupo']; // procurar dentro deste grupo

    $modulo = Session::getInstance()->get(SGA::K_CURRENT_MODULE);
    $admin = SGA::get_current_user();
	$lotacao = $admin->get_lotacao();
    
    $termo_login = "%"; // todos logins
    $termo_nome = "%"; // todos nomes

	if ($termo != "") {
		if ($modo == "login") {
			if (ctype_alnum($termo)) {
				$termo_login = '%'.$termo.'%';
			}
            else {
				throw new Exception("Digite apenas letras ou números.");
			}
		}
        else {
            $termo_nome = '%'.$termo.'%';
		}
	}

    $result = DB::getInstance()->get_usuarios_grupos_by_usuario($admin->get_id(), $modulo->get_id(), $id_grupo, $termo_login, $termo_nome);

    // nao passar null, passar um array vazio
    if ($result == null) {
        $result = array();
    }
	TUsuarios::display_resultado_users_interno($result, $id_grupo);
}
catch (Exception $e) {
	TUsuarios::display_exception($e);
}

?>